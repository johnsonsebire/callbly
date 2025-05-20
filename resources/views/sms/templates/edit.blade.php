<div class="card-title">@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('sms.templates') }}" class="btn btn-sm btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left me-2"></i>Back to Templates
            </a>
            <h2>Edit Template</h2>
            <p class="text-muted">Modify your SMS template</p>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Template Details</h5>
                </div><div class="card-title"></div><div class="card-title"></div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div><div class="card-title"></div><div class="card-title"></div>
                    @endif

                    <form method="POST" action="{{ route('sms.templates.update', $template->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Template Name</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $template->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div><div class="card-title"></div><div class="card-title"></div>
                            @enderror
                            <small class="text-muted">Give your template a descriptive name</small>
                        </div><div class="card-title"></div><div class="card-title"></div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Message Content</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" 
                                      name="content" 
                                      rows="6" 
                                      required>{{ old('content', $template->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div><div class="card-title"></div><div class="card-title"></div>
                            @enderror
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted" id="characterCount">0 characters</small>
                                <small class="text-muted" id="messageCount">0 message(s)</small>
                            </div><div class="card-title"></div><div class="card-title"></div>
                        </div><div class="card-title"></div><div class="card-title"></div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3">{{ old('description', $template->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div><div class="card-title"></div><div class="card-title"></div>
                            @enderror
                            <small class="text-muted">Add notes about when to use this template</small>
                        </div><div class="card-title"></div><div class="card-title"></div>

                        <button type="submit" class="btn btn-primary">Update Template</button>
                    </form>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Template Variables</h5>
                </div><div class="card-title"></div><div class="card-title"></div>
                <div class="card-body">
                    <p class="text-muted mb-3">You can use these variables in your templates:</p>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <code>{name}</code> - Recipient's name
                        </li>
                        <li class="mb-2">
                            <code>{company}</code> - Your company name
                        </li>
                        <li class="mb-2">
                            <code>{date}</code> - Current date
                        </li>
                    </ul>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>
</div><div class="card-title"></div></div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contentField = document.getElementById('content');
        const characterCount = document.getElementById('characterCount');
        const messageCount = document.getElementById('messageCount');

        function updateCounts() {
            const content = contentField.value;
            const charCount = content.length;
            const msgCount = Math.ceil(charCount / 160);

            characterCount.textContent = charCount + ' characters';
            messageCount.textContent = msgCount + ' message(s)';
        }

        contentField.addEventListener('input', updateCounts);
        updateCounts(); // Initial count
    });
</script>
@endpush

@endsection