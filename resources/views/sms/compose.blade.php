@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Compose SMS</h2>
            <p class="text-muted">Create and send a new SMS campaign</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Message Details</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('sms.send') }}" id="smsForm">
                        @csrf

                        <div class="form-group">
                            <label for="sender_id">Sender ID</label>
                            <select name="sender_id" id="sender_id" class="form-control @error('sender_id') is-invalid @enderror" required>
                                <option value="">Select a sender ID</option>
                                @foreach($senderNames as $senderName)
                                    <option value="{{ $senderName->name }}" {{ old('sender_id') == $senderName->name ? 'selected' : '' }}>
                                        {{ $senderName->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sender_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            @if($senderNames->isEmpty())
                                <small class="text-danger">
                                    You need to <a href="{{ route('sms.sender-names') }}">register a sender ID</a> before sending SMS.
                                </small>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea name="message" id="message" rows="6" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted" id="characterCount">0 characters</small>
                                <small class="text-muted" id="messageCount">0 message(s)</small>
                            </div>
                            @error('message')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="recipients">Recipients</label>
                            <textarea name="recipients" id="recipients" rows="6" class="form-control @error('recipients') is-invalid @enderror" placeholder="Enter phone numbers separated by commas, new lines, or spaces" required>{{ old('recipients') }}</textarea>
                            <small class="text-muted">
                                Example formats: +233244123456, +233244123457 or +233244123456, +233244123457
                            </small>
                            <div class="mt-1" id="recipientCount">0 recipient(s)</div>
                            @error('recipients')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary" id="sendButton" {{ $senderNames->isEmpty() ? 'disabled' : '' }}>
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Message Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Credits Required:</strong>
                        <span id="creditsNeeded">0</span>
                    </div>
                    <div class="mb-3">
                        <strong>Recipient Count:</strong>
                        <span id="recipientsCount">0</span>
                    </div>
                    <div>
                        <strong>Characters:</strong>
                        <span id="charsCount">0</span>
                        <small class="text-muted">
                            (160 chars = 1 message)
                        </small>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="pl-3">
                        <li>Keep your message concise to reduce costs.</li>
                        <li>Avoid using special characters that might increase message parts.</li>
                        <li>Make sure all recipient numbers include country code.</li>
                        <li>Test your message with a small group first.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messageField = document.getElementById('message');
        const recipientsField = document.getElementById('recipients');
        const characterCount = document.getElementById('characterCount');
        const messageCount = document.getElementById('messageCount');
        const recipientCount = document.getElementById('recipientCount');
        const charsCount = document.getElementById('charsCount');
        const recipientsCount = document.getElementById('recipientsCount');
        const creditsNeeded = document.getElementById('creditsNeeded');

        // Character counter
        messageField.addEventListener('input', function() {
            const length = this.value.length;
            characterCount.textContent = length + ' characters';
            charsCount.textContent = length;
            
            // Calculate message count (160 chars for single SMS, 153 for multi-part)
            let parts = 1;
            if (length <= 160) {
                parts = 1;
            } else {
                parts = Math.ceil((length - 160) / 153) + 1;
            }
            messageCount.textContent = parts + ' message(s)';
            
            calculateCredits();
        });
        
        // Recipient counter
        recipientsField.addEventListener('input', function() {
            const recipients = getRecipientCount(this.value);
            recipientCount.textContent = recipients + ' recipient(s)';
            recipientsCount.textContent = recipients;
            
            calculateCredits();
        });
        
        // Initial calculation
        const initialRecipients = getRecipientCount(recipientsField.value);
        recipientCount.textContent = initialRecipients + ' recipient(s)';
        recipientsCount.textContent = initialRecipients;
        
        const initialLength = messageField.value.length;
        characterCount.textContent = initialLength + ' characters';
        charsCount.textContent = initialLength;
        
        let initialParts = 1;
        if (initialLength <= 160) {
            initialParts = 1;
        } else {
            initialParts = Math.ceil((initialLength - 160) / 153) + 1;
        }
        messageCount.textContent = initialParts + ' message(s)';
        
        // Function to count recipients
        function getRecipientCount(text) {
            if (!text.trim()) return 0;
            // Split by commas, newlines, or spaces and filter empty entries
            const recipients = text.split(/[\s,\n]+/).filter(item => item.trim() !== '');
            return recipients.length;
        }
        
        // Function to calculate credits
        function calculateCredits() {
            const message = messageField.value;
            const recipientsText = recipientsField.value;
            
            if (!message || !recipientsText) {
                creditsNeeded.textContent = '0';
                return;
            }
            
            // Calculate locally first for immediate feedback
            let parts = 1;
            const length = message.length;
            if (length <= 160) {
                parts = 1;
            } else {
                parts = Math.ceil((length - 160) / 153) + 1;
            }
            
            const recipients = getRecipientCount(recipientsText);
            const estimatedCredits = parts * recipients;
            creditsNeeded.textContent = estimatedCredits;
            
            // Then get accurate calculation from server if both fields have value
            if (message && recipientsText) {
                // Debounce to prevent too many requests
                clearTimeout(window.calculateTimeout);
                window.calculateTimeout = setTimeout(function() {
                    const formData = new FormData();
                    formData.append('message', message);
                    formData.append('recipients', recipientsText);
                    formData.append('_token', '{{ csrf_token() }}');
                    
                    fetch('{{ route('sms.calculate-credits') }}', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        creditsNeeded.textContent = data.credits_needed;
                    })
                    .catch(error => console.error('Error calculating credits:', error));
                }, 500);
            }
        }
        
        // Initial calculation
        calculateCredits();
    });
</script>
@endsection