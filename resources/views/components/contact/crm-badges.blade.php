@props(['contact'])

<div class="d-flex flex-wrap gap-2 align-items-center">
    {{-- Lead Status Badge --}}
    <span class="badge bg-{{ $contact->lead_status_color }}">
        {{ ucfirst(str_replace('_', ' ', $contact->lead_status)) }}
    </span>
    
    {{-- Priority Badge --}}
    @if($contact->priority !== 'medium')
        <span class="badge bg-{{ $contact->priority_color }}">
            @if($contact->priority === 'urgent')
                <i class="fas fa-exclamation-triangle me-1"></i>
            @elseif($contact->priority === 'high')
                <i class="fas fa-arrow-up me-1"></i>
            @endif
            {{ ucfirst($contact->priority) }}
        </span>
    @endif
    
    {{-- Follow-up Status --}}
    @if($contact->next_follow_up_date)
        @if($contact->isOverdueForFollowUp())
            <span class="badge bg-danger">
                <i class="fas fa-clock me-1"></i>
                Overdue
            </span>
        @elseif($contact->needsFollowUp())
            <span class="badge bg-warning">
                <i class="fas fa-calendar-check me-1"></i>
                Due Today
            </span>
        @else
            <span class="badge bg-info">
                <i class="fas fa-calendar me-1"></i>
                Follow-up: {{ $contact->next_follow_up_date->format('M j') }}
            </span>
        @endif
    @endif
    
    {{-- WhatsApp Indicator --}}
    @if($contact->has_whatsapp)
        <span class="badge bg-success">
            <i class="fab fa-whatsapp me-1"></i>
            WhatsApp
        </span>
    @endif
    
    {{-- Potential Value --}}
    @if($contact->potential_value)
        <span class="badge bg-secondary">
            <i class="fas fa-dollar-sign me-1"></i>
            GHS {{ number_format($contact->potential_value, 2) }}
        </span>
    @endif
    
    {{-- Tags --}}
    @if($contact->tags)
        @foreach($contact->tags as $tag)
            <span class="badge bg-light text-dark border">
                <i class="fas fa-tag me-1"></i>
                {{ $tag }}
            </span>
        @endforeach
    @endif
</div>