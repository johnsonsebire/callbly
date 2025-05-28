@props(['contact'])

<div class="d-flex flex-wrap">
    {{-- Lead Stage Badge --}}
    @if($contact->lead_stage)
        @php
            $leadStageColors = [
                'prospect' => 'badge-light-info',
                'qualified' => 'badge-light-warning',
                'proposal' => 'badge-light-primary',
                'negotiation' => 'badge-light-success',
                'closed_won' => 'badge-success',
                'closed_lost' => 'badge-danger'
            ];
            $leadStageLabels = [
                'prospect' => 'Prospect',
                'qualified' => 'Qualified',
                'proposal' => 'Proposal',
                'negotiation' => 'Negotiation',
                'closed_won' => 'Closed Won',
                'closed_lost' => 'Closed Lost'
            ];
        @endphp
        <span class="badge {{ $leadStageColors[$contact->lead_stage] ?? 'badge-light' }} me-1 mb-1">
            {{ $leadStageLabels[$contact->lead_stage] ?? ucfirst(str_replace('_', ' ', $contact->lead_stage)) }}
        </span>
    @endif

    {{-- Contact Status Badge --}}
    @if($contact->contact_status)
        @php
            $statusColors = [
                'active' => 'badge-light-success',
                'inactive' => 'badge-light-secondary',
                'blocked' => 'badge-light-danger',
                'pending' => 'badge-light-warning'
            ];
        @endphp
        <span class="badge {{ $statusColors[$contact->contact_status] ?? 'badge-light' }} me-1 mb-1">
            {{ ucfirst($contact->contact_status) }}
        </span>
    @endif

    {{-- Priority Badge --}}
    @if($contact->priority)
        @php
            $priorityColors = [
                'high' => 'badge-danger',
                'medium' => 'badge-warning',
                'low' => 'badge-light'
            ];
        @endphp
        <span class="badge {{ $priorityColors[$contact->priority] ?? 'badge-light' }} me-1 mb-1">
            <i class="ki-outline ki-arrow-up fs-7 me-1"></i>{{ ucfirst($contact->priority) }} Priority
        </span>
    @endif

    {{-- Preferred Contact Method Badge --}}
    @if($contact->preferred_contact_method)
        @php
            $methodIcons = [
                'email' => 'ki-outline ki-sms',
                'phone' => 'ki-outline ki-phone',
                'whatsapp' => 'fab fa-whatsapp',
                'sms' => 'ki-outline ki-message-text-2'
            ];
        @endphp
        <span class="badge badge-light-primary me-1 mb-1" title="Preferred contact method">
            <i class="{{ $methodIcons[$contact->preferred_contact_method] ?? 'ki-outline ki-message' }} fs-7 me-1"></i>{{ ucfirst($contact->preferred_contact_method) }}
        </span>
    @endif
</div>