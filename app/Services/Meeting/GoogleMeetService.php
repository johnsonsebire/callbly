<?php

namespace App\Services\Meeting;

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Google\Service\Calendar\ConferenceData;
use Google\Service\Calendar\CreateConferenceRequest;
use Google\Service\Calendar\ConferenceSolutionKey;
use App\Models\MeetingBooking;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class GoogleMeetService
{
    protected Client $client;
    protected Calendar $calendar;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect_uri'));
        $this->client->addScope(Calendar::CALENDAR);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
        
        $this->calendar = new Calendar($this->client);
    }

    /**
     * Get Google OAuth authorization URL
     */
    public function getAuthUrl(User $user): string
    {
        $this->client->setState(json_encode(['user_id' => $user->id]));
        return $this->client->createAuthUrl();
    }

    /**
     * Handle OAuth callback and store tokens
     */
    public function handleCallback(string $code, User $user): bool
    {
        try {
            $token = $this->client->fetchAccessTokenWithAuthCode($code);
            
            if (isset($token['error'])) {
                Log::error('Google OAuth error', ['error' => $token['error']]);
                return false;
            }

            // Store the tokens (you might want to create a separate table for this)
            $user->update([
                'google_access_token' => json_encode($token),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Google OAuth callback error', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            return false;
        }
    }

    /**
     * Set access token for authenticated requests
     */
    protected function setAccessToken(User $user): bool
    {
        $tokens = json_decode($user->google_access_token ?? '{}', true);
        
        if (empty($tokens)) {
            return false;
        }

        $this->client->setAccessToken($tokens);

        // Refresh token if expired
        if ($this->client->isAccessTokenExpired()) {
            if (isset($tokens['refresh_token'])) {
                $newTokens = $this->client->fetchAccessTokenWithRefreshToken($tokens['refresh_token']);
                $user->update([
                    'google_access_token' => json_encode($newTokens),
                ]);
                $this->client->setAccessToken($newTokens);
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * Create a Google Calendar event with Meet link
     */
    public function createMeetingEvent(MeetingBooking $booking): ?array
    {
        try {
            $user = $booking->user;
            
            if (!$this->setAccessToken($user)) {
                Log::warning('Google Calendar not connected for user', ['user_id' => $user->id]);
                return $this->createFallbackMeetLink($booking);
            }

            $event = new Event([
                'summary' => $booking->eventType->name . ' with ' . $booking->booker_name,
                'description' => $this->buildEventDescription($booking),
                'start' => new EventDateTime([
                    'dateTime' => $booking->scheduled_at->toRfc3339String(),
                    'timeZone' => $booking->timezone,
                ]),
                'end' => new EventDateTime([
                    'dateTime' => $booking->scheduled_end_at->toRfc3339String(),
                    'timeZone' => $booking->timezone,
                ]),
                'attendees' => [
                    ['email' => $booking->booker_email, 'displayName' => $booking->booker_name],
                    ['email' => $user->email, 'displayName' => $user->name],
                ],
                'conferenceData' => new ConferenceData([
                    'createRequest' => new CreateConferenceRequest([
                        'requestId' => 'meet-' . $booking->booking_reference,
                        'conferenceSolutionKey' => new ConferenceSolutionKey([
                            'type' => 'hangoutsMeet'
                        ])
                    ])
                ]),
                'reminders' => [
                    'useDefault' => false,
                    'overrides' => [
                        ['method' => 'email', 'minutes' => 60],
                        ['method' => 'popup', 'minutes' => 15],
                    ],
                ],
            ]);

            $createdEvent = $this->calendar->events->insert('primary', $event, [
                'conferenceDataVersion' => 1,
                'sendUpdates' => 'all'
            ]);

            $meetLink = $createdEvent->getConferenceData()?->getEntryPoints()[0]?->getUri();

            return [
                'success' => true,
                'event_id' => $createdEvent->getId(),
                'meet_link' => $meetLink,
                'event_data' => $createdEvent->toArray(),
            ];

        } catch (\Exception $e) {
            Log::error('Failed to create Google Calendar event', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);

            // Fallback to basic Google Meet link
            return $this->createFallbackMeetLink($booking);
        }
    }

    /**
     * Create a fallback Google Meet link when Calendar API fails
     */
    protected function createFallbackMeetLink(MeetingBooking $booking): array
    {
        // Generate a unique meet link identifier
        $meetId = 'callbly-' . strtolower($booking->booking_reference);
        $meetLink = "https://meet.google.com/new";

        return [
            'success' => true,
            'event_id' => null,
            'meet_link' => $meetLink,
            'event_data' => null,
            'fallback' => true,
        ];
    }

    /**
     * Build event description with meeting details
     */
    protected function buildEventDescription(MeetingBooking $booking): string
    {
        $description = "Meeting scheduled through Callbly\n\n";
        $description .= "Event: {$booking->eventType->name}\n";
        $description .= "Host: {$booking->user->name}\n";
        $description .= "Participant: {$booking->booker_name}\n";
        $description .= "Email: {$booking->booker_email}\n";
        
        if ($booking->booker_phone) {
            $description .= "Phone: {$booking->booker_phone}\n";
        }

        if (!empty($booking->custom_responses)) {
            $description .= "\nAdditional Information:\n";
            foreach ($booking->custom_responses as $question => $response) {
                $description .= "- {$question}: {$response}\n";
            }
        }

        $description .= "\nBooking Reference: {$booking->booking_reference}\n";
        $description .= "Manage Booking: {$booking->public_url}";

        return $description;
    }

    /**
     * Update an existing Google Calendar event
     */
    public function updateMeetingEvent(MeetingBooking $booking): bool
    {
        try {
            $user = $booking->user;
            
            if (!$this->setAccessToken($user) || !$booking->google_event_id) {
                return false;
            }

            $event = $this->calendar->events->get('primary', $booking->google_event_id);
            
            $event->setStart(new EventDateTime([
                'dateTime' => $booking->scheduled_at->toRfc3339String(),
                'timeZone' => $booking->timezone,
            ]));
            
            $event->setEnd(new EventDateTime([
                'dateTime' => $booking->scheduled_end_at->toRfc3339String(),
                'timeZone' => $booking->timezone,
            ]));

            $this->calendar->events->update('primary', $booking->google_event_id, $event, [
                'sendUpdates' => 'all'
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to update Google Calendar event', [
                'booking_id' => $booking->id,
                'event_id' => $booking->google_event_id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Cancel a Google Calendar event
     */
    public function cancelMeetingEvent(MeetingBooking $booking): bool
    {
        try {
            $user = $booking->user;
            
            if (!$this->setAccessToken($user) || !$booking->google_event_id) {
                return false;
            }

            $this->calendar->events->delete('primary', $booking->google_event_id, [
                'sendUpdates' => 'all'
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to cancel Google Calendar event', [
                'booking_id' => $booking->id,
                'event_id' => $booking->google_event_id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}