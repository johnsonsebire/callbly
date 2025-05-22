<?php

namespace App\Notifications;

use App\Models\TeamInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeamInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The team invitation instance.
     */
    protected TeamInvitation $invitation;

    /**
     * Create a new notification instance.
     */
    public function __construct(TeamInvitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/team-invitations/' . $this->invitation->token);
        $expiresAt = $this->invitation->expires_at->format('F j, Y');
        
        return (new MailMessage)
            ->subject('You\'ve Been Invited to Join a Team on ' . config('app.name'))
            ->greeting('Hello!')
            ->line('You have been invited to join the **' . $this->invitation->team->name . '** team on ' . config('app.name') . '.')
            ->line('This invitation was sent by **' . $this->invitation->team->owner->name . '**.')
            ->line('As a team member, you\'ll be able to collaborate and share resources with other team members.')
            ->action('Accept Invitation', $url)
            ->line('This invitation will expire on ' . $expiresAt . '.')
            ->line('If you did not expect to receive this invitation, you may discard this email.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'team_id' => $this->invitation->team_id,
            'team_name' => $this->invitation->team->name,
            'inviter' => $this->invitation->team->owner->name,
            'role' => $this->invitation->role,
            'expires_at' => $this->invitation->expires_at->toIso8601String(),
        ];
    }
}
