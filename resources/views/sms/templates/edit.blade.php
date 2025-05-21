@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Edit Template Card -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <a href="{{ route('sms.templates') }}" class="btn btn-sm btn-outline-secondary mb-3">
                            <i class="fas fa-arrow-left me-2"></i>Back to Templates
                        </a>
                        <div class="card-title">
                            <h1 class="fw-bold text-dark">Edit SMS Template</h1>
                            <p class="text-muted">Update your existing SMS template</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    <h5 class="mb-0">Edit Template</h5>
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
                                               placeholder="e.g. Welcome Message, Appointment Reminder"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Give your template a descriptive name</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="content" class="form-label">Message Content</label>
                                        <div class="position-relative">
                                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                                    id="content" 
                                                    name="content" 
                                                    rows="6" 
                                                    placeholder="Type your message here. You can use variables like {name}."
                                                    required>{{ old('content', $template->content) }}</textarea>
                                            <div id="template-tags-dropdown" class="template-tags-dropdown d-none">
                                                <div class="template-tags-container">
                                                    <div class="template-tag" data-tag="{name}">Insert recipient's name</div>
                                                    <div class="template-tag" data-tag="{company}">Insert your company name</div>
                                                    <div class="template-tag" data-tag="{date}">Insert current date</div>
                                                </div>
                                            </div>
                                        </div>
                                        @error('content')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <div>
                                                <small class="text-muted" id="characterCount">0 characters</small>
                                                <small class="text-muted mx-2">|</small>
                                                <small class="text-muted" id="messageCount">0 message(s)</small>
                                            </div>
                                            <button type="button" id="insert-template-tag" class="btn btn-sm btn-light-primary">
                                                <i class="fas fa-tag me-1"></i> Insert Variable
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description (Optional)</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" 
                                                  name="description" 
                                                  rows="3"
                                                  placeholder="Add notes about when to use this template">{{ old('description', $template->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Add notes about when and how to use this template</small>
                                    </div>

                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="fas fa-save me-2"></i>Update Template
                                        </button>
                                        <a href="{{ route('sms.templates') }}" class="btn btn-outline-secondary">
                                            Cancel
                                        </a>
                                    </div>
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
                                        <button class="btn btn-sm btn-light ms-auto template-tag-btn" data-tag="{name}">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <span class="badge bg-primary me-2">Code</span>
                                        <div>
                                            <strong>{company}</strong>
                                            <div class="small text-muted">Your company name</div>
                                        </div>
                                        <button class="btn btn-sm btn-light ms-auto template-tag-btn" data-tag="{company}">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <span class="badge bg-primary me-2">Code</span>
                                        <div>
                                            <strong>{date}</strong>
                                            <div class="small text-muted">Current date</div>
                                        </div>
                                        <button class="btn btn-sm btn-light ms-auto template-tag-btn" data-tag="{date}">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header bg-light">
                                <div class="card-title">
                                    <h5 class="mb-0">Template Preview</h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="px-2 py-3 bg-light rounded border mb-3">
                                    <p class="mb-0" id="preview-content">{{ $template->content }}</p>
                                </div>
                                <p class="text-muted small">This is how your template looks with variables shown as codes. When sending, variables will be replaced with actual values.</p>
                                <div class="d-grid">
                                    <a href="{{ route('sms.compose') }}?template={{ $template->id }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-paper-plane me-1"></i> Use This Template
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
</div>
@endsection

@push('styles')
<style>
    .template-tags-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        z-index: 1000;
    }
    
    .template-tags-container {
        padding: 0.5rem;
    }
    
    .template-tag {
        padding: 0.5rem;
        cursor: pointer;
        border-bottom: 1px solid #f5f5f5;
    }
    
    .template-tag:hover {
        background-color: #f8f9fa;
    }
    
    .template-tag:last-child {
        border-bottom: none;
    }
    
    #preview-content {
        font-family: 'Courier New', monospace;
        white-space: pre-wrap;
    }
    
    .badge.bg-primary {
        background-color: #009ef7 !important;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contentField = document.getElementById('content');
        const characterCount = document.getElementById('characterCount');
        const messageCount = document.getElementById('messageCount');
        const insertTagBtn = document.getElementById('insert-template-tag');
        const templateTagsDropdown = document.getElementById('template-tags-dropdown');
        const templateTagBtns = document.querySelectorAll('.template-tag-btn');
        const previewContent = document.getElementById('preview-content');
        
        // Character count and SMS parts calculation
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
            
            // Update preview
            previewContent.textContent = content;
        }

        // Insert template tag functionality
        function insertTemplateTag(tag) {
            const selectionStart = contentField.selectionStart;
            const selectionEnd = contentField.selectionEnd;
            const beforeSelection = contentField.value.substring(0, selectionStart);
            const afterSelection = contentField.value.substring(selectionEnd);
            
            contentField.value = beforeSelection + tag + afterSelection;
            
            // Put cursor after inserted tag
            const newCursorPosition = selectionStart + tag.length;
            contentField.focus();
            contentField.setSelectionRange(newCursorPosition, newCursorPosition);
            
            // Update character count and preview
            updateCounts();
            
            // Hide dropdown
            templateTagsDropdown.classList.add('d-none');
        }
        
        // Toggle template tags dropdown
        insertTagBtn.addEventListener('click', function() {
            templateTagsDropdown.classList.toggle('d-none');
            contentField.focus();
        });
        
        // Handle template tag selection from dropdown
        document.querySelectorAll('.template-tag').forEach(tag => {
            tag.addEventListener('click', function() {
                insertTemplateTag(this.getAttribute('data-tag'));
            });
        });
        
        // Handle template tag buttons in sidebar
        templateTagBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                insertTemplateTag(this.getAttribute('data-tag'));
                contentField.focus();
            });
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('#insert-template-tag') && 
                !event.target.closest('#template-tags-dropdown')) {
                templateTagsDropdown.classList.add('d-none');
            }
        });
        
        // Listen for input to update character count and preview
        contentField.addEventListener('input', updateCounts);
        
        // Initial count and preview
        updateCounts();
    });
</script>
@endpush