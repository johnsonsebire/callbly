@props(['contact', 'showLabel' => true, 'phoneType' => 'primary'])

@php
    $phoneNumber = match($phoneType) {
        'alternative' => $contact->alternative_phone,
        'whatsapp' => $contact->whatsapp_number ?: $contact->phone_number,
        default => $contact->phone_number
    };
    
    $hasWhatsApp = $contact->has_whatsapp && ($phoneType !== 'alternative' || $contact->whatsapp_number === $contact->alternative_phone);
    $needsCheck = $contact->isWhatsappCheckStale() && $phoneNumber;
@endphp

@if($phoneNumber)
<div class="d-flex align-items-center gap-2">
    @if($showLabel)
        <span class="text-muted small">
            {{ match($phoneType) {
                'alternative' => 'Alt Phone:',
                'whatsapp' => 'WhatsApp:',
                default => 'Phone:'
            } }}
        </span>
    @endif
    
    <span class="fw-bold">{{ $phoneNumber }}</span>
    
    @if($hasWhatsApp)
        <a href="#" 
           class="text-success" 
           data-bs-toggle="tooltip" 
           title="Send WhatsApp message"
           onclick="openWhatsApp('{{ $contact->id }}', '{{ $phoneNumber }}')">
            <i class="fab fa-whatsapp fs-5"></i>
        </a>
    @elseif($needsCheck)
        <button class="btn btn-sm btn-outline-secondary" 
                onclick="checkWhatsApp({{ $contact->id }})"
                data-bs-toggle="tooltip" 
                title="Check WhatsApp availability">
            <i class="fas fa-sync-alt"></i>
        </button>
    @endif
    
    <a href="tel:{{ $phoneNumber }}" 
       class="text-primary"
       data-bs-toggle="tooltip" 
       title="Call {{ $phoneNumber }}">
        <i class="fas fa-phone"></i>
    </a>
</div>
@endif

@push('scripts')
<script>
function openWhatsApp(contactId, phoneNumber, message = '') {
    // Get WhatsApp URL from API
    fetch(`/api/contacts/${contactId}/whatsapp-url?message=${encodeURIComponent(message)}`)
        .then(response => response.json())
        .then(data => {
            if (data.whatsapp_url) {
                window.open(data.whatsapp_url, '_blank');
            }
        })
        .catch(error => {
            console.error('Error getting WhatsApp URL:', error);
            // Fallback to direct WhatsApp URL
            window.open(`https://wa.me/${phoneNumber.replace(/[^0-9]/g, '')}`, '_blank');
        });
}

function checkWhatsApp(contactId) {
    const button = event.target.closest('button');
    const icon = button.querySelector('i');
    
    // Show loading state
    icon.className = 'fas fa-spinner fa-spin';
    button.disabled = true;
    
    fetch(`/api/contacts/${contactId}/check-whatsapp`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.has_whatsapp) {
            // Replace button with WhatsApp icon
            button.outerHTML = `
                <a href="#" 
                   class="text-success" 
                   data-bs-toggle="tooltip" 
                   title="Send WhatsApp message"
                   onclick="openWhatsApp('${contactId}', '${button.dataset.phone}')">
                    <i class="fab fa-whatsapp fs-5"></i>
                </a>
            `;
        } else {
            // Reset button
            icon.className = 'fas fa-sync-alt';
            button.disabled = false;
            button.title = 'WhatsApp not available';
            button.classList.add('text-muted');
        }
    })
    .catch(error => {
        console.error('Error checking WhatsApp:', error);
        // Reset button
        icon.className = 'fas fa-sync-alt';
        button.disabled = false;
    });
}
</script>
@endpush