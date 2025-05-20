@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('sms.templates') }}" class="btn btn-sm btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left me-2"></i>Back to Templates
            </a>
            <div class="card-title">
                <h1 class="fw-bold text-dark">Create SMS Template</h1>
            <p class="text-muted">Create a new SMS template for frequently sent messages</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h5 class="mb-0">Create New Template</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('sms.templates.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Template Name</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="e.g. Welcome Message, Appointment Reminder"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Give your template a descriptive name</small>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Message Content</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" 
                                      name="content" 
                                      rows="6" 
                                      placeholder="Type your message here. You can use variables like {name}."
                                      required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted" id="characterCount">0 characters</small>
                                <small class="text-muted" id="messageCount">0 message(s)</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Add notes about when to use this template">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Add notes about when and how to use this template</small>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Template
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <div class="card-title">
                    <h5 class="mb-0">Template Variables</h5>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">You can use these variables in your templates:</p>
                    <ul class="list-group">
                        <li class="list-group-item d-flex align-items-center">
                            <span class="badge bg-primary me-2">Code</span>
                            <div>
                                <strong>{name}</strong>
                                <div class="small text-muted">Recipient's name</div>
                            </div>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <span class="badge bg-primary me-2">Code</span>
                            <div>
                                <strong>{company}</strong>
                                <div class="small text-muted">Your company name</div>
                            </div>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <span class="badge bg-primary me-2">Code</span>
                            <div>
                                <strong>{date}</strong>
                                <div class="small text-muted">Current date</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-light">
                    <div class="card-title">
                    <h5 class="mb-0">SMS Formatting Tips</h5>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info me-2"></i> Keep messages concise
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info me-2"></i> Avoid special characters
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info me-2"></i> Include call to action
                        </li>
                        <li>
                            <i class="fas fa-info-circle text-info me-2"></i> Each SMS segment is 160 characters
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contentField = document.getElementById('content');
        const characterCount = document.getElementById('characterCount');
        const messageCount = document.getElementById('messageCount');

        function updateCounts() {
            const content = contentField.value;
            const charCount = content.length;
            const msgCount = Math.ceil(charCount / 160) || 1;

            characterCount.textContent = charCount + ' characters';
            messageCount.textContent = msgCount + ' message(s)';
            
            // Change color if approaching or exceeding single message
            if (charCount > 140 && charCount <= 160) {
                characterCount.className = 'text-warning';
            } else if (charCount > 160) {
                characterCount.className = 'text-danger';
            } else {
                characterCount.className = 'text-muted';
            }
        }

        contentField.addEventListener('input', updateCounts);
        updateCounts(); // Initial count
    });
</script>
@endpush

@endsection