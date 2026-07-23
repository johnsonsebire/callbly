@extends('layouts.master')

@section('title', 'Create SMS Template')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                
                <!-- Welcome Banner Hero Card -->
                <div class="card mb-5 mb-xl-8 border-0 shadow-lg overflow-hidden" style="background: linear-gradient(135deg, #090d16 0%, #0f172a 50%, #1e293b 100%);">
                    <div class="card-body p-6 sm:p-8 text-white relative">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-6">
                            <div class="d-flex align-items-center gap-4">
                                <div class="symbol symbol-60px symbol-lg-75px symbol-circle border border-2 border-cyan-400/40 p-1 shadow-inner" style="background: rgba(14, 165, 233, 0.15);">
                                    <div class="symbol-label font-extrabold text-cyan-400 fs-1 rounded-circle" style="background: #0ea5e9; color: #ffffff;">
                                        <i class="ki-outline ki-document fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">Create SMS Template</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Reusable Copy</span>
                                    </div>
                                    <p class="text-gray-400 fs-7 mb-0">Create standardized message copy with dynamic personalization variables.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('sms.templates') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-arrow-left me-1 fs-5"></i> Back to Templates
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-5 g-xl-8">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                            <div class="card-header pt-6 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-3">Template Form</span>
                                    <span class="text-gray-400 mt-1 fs-7">Define template name, message body, and optional description</span>
                                </h3>
                            </div>
                            <div class="card-body pt-4">
                                @if($errors->any())
                                    <div class="alert alert-danger d-flex align-items-center p-4 mb-5 rounded-3 shadow-xs">
                                        <i class="ki-outline ki-cross-circle fs-2x text-danger me-3"></i>
                                        <div>
                                            <span class="font-bold text-gray-900 fs-6">Validation Errors</span>
                                            <ul class="mb-0 fs-7 text-gray-700 ps-4">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('sms.templates.store') }}">
                                    @csrf
                                    <div class="mb-5">
                                        <label for="name" class="required form-label font-bold text-gray-800 fs-7 mb-2">Template Name</label>
                                        <input type="text" 
                                               class="form-control form-control-solid rounded-3 @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name') }}" 
                                               placeholder="e.g. Welcome Message, Appointment Reminder"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-5">
                                        <label for="content" class="required form-label font-bold text-gray-800 fs-7 mb-2">Message Content</label>
                                        <div class="position-relative">
                                            <textarea class="form-control form-control-solid rounded-3 font-mono @error('content') is-invalid @enderror" 
                                                    id="content" 
                                                    name="content" 
                                                    rows="6" 
                                                    placeholder="Type your message here. You can use variables like {name}."
                                                    required>{{ old('content') }}</textarea>
                                        </div>
                                        @error('content')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <div class="fs-8 text-gray-500 font-medium">
                                                <span id="characterCount">0 characters</span>
                                                <span class="mx-2">|</span>
                                                <span id="messageCount">0 page(s)</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-6">
                                        <label for="description" class="form-label font-bold text-gray-800 fs-7 mb-2">Description <span class="text-gray-400 font-normal">(Optional)</span></label>
                                        <textarea class="form-control form-control-solid rounded-3 @error('description') is-invalid @enderror" 
                                                  id="description" 
                                                  name="description" 
                                                  rows="3" 
                                                  placeholder="Internal note about when to use this template">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="d-flex justify-content-end gap-3 mt-8">
                                        <a href="{{ route('sms.templates') }}" class="btn btn-light font-bold px-5">Cancel</a>
                                        <button type="submit" class="btn btn-primary font-bold px-6 py-3 shadow-sm">
                                            <i class="ki-outline ki-check me-1 fs-4"></i> Save Template
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Available Variables Sidebar -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm overflow-hidden mb-5" style="border-top: 4px solid #10b981 !important; border-radius: 1.25rem;">
                            <div class="card-header pt-6 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-5">Dynamic Variables</span>
                                    <span class="text-gray-400 mt-1 fs-8">Personalize messages automatically</span>
                                </h3>
                            </div>
                            <div class="card-body pt-2 fs-7 text-gray-700">
                                <p class="text-gray-500 mb-3">Click any variable to insert it into your message content:</p>
                                <div class="d-flex flex-column gap-2">
                                    <div class="variable-item p-2.5 bg-light-soft border border-gray-200 rounded-3 cursor-pointer hover:bg-light-primary transition-all" data-tag="{name}">
                                        <code class="text-primary font-bold">{name}</code>
                                        <span class="text-gray-600 fs-8 d-block">Recipient's full name</span>
                                    </div>
                                    <div class="variable-item p-2.5 bg-light-soft border border-gray-200 rounded-3 cursor-pointer hover:bg-light-primary transition-all" data-tag="{first_name}">
                                        <code class="text-primary font-bold">{first_name}</code>
                                        <span class="text-gray-600 fs-8 d-block">Recipient's first name</span>
                                    </div>
                                    <div class="variable-item p-2.5 bg-light-soft border border-gray-200 rounded-3 cursor-pointer hover:bg-light-primary transition-all" data-tag="{last_name}">
                                        <code class="text-primary font-bold">{last_name}</code>
                                        <span class="text-gray-600 fs-8 d-block">Recipient's last name</span>
                                    </div>
                                    <div class="variable-item p-2.5 bg-light-soft border border-gray-200 rounded-3 cursor-pointer hover:bg-light-primary transition-all" data-tag="{email}">
                                        <code class="text-primary font-bold">{email}</code>
                                        <span class="text-gray-600 fs-8 d-block">Recipient's email address</span>
                                    </div>
                                    <div class="variable-item p-2.5 bg-light-soft border border-gray-200 rounded-3 cursor-pointer hover:bg-light-primary transition-all" data-tag="{phone}">
                                        <code class="text-primary font-bold">{phone}</code>
                                        <span class="text-gray-600 fs-8 d-block">Recipient's phone number</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contentArea = document.getElementById('content');
        const characterCount = document.getElementById('characterCount');
        const messageCount = document.getElementById('messageCount');
        const variableItems = document.querySelectorAll('.variable-item');

        function updateCounts() {
            if (!contentArea) return;
            const length = contentArea.value.length;
            characterCount.textContent = length + ' characters';
            const pages = Math.ceil(length / 160) || 1;
            messageCount.textContent = pages + ' SMS part' + (pages > 1 ? 's' : '');
        }

        if (contentArea) {
            contentArea.addEventListener('input', updateCounts);
            updateCounts();
        }

        variableItems.forEach(item => {
            item.addEventListener('click', function() {
                const tag = this.getAttribute('data-tag');
                if (contentArea && tag) {
                    const start = contentArea.selectionStart;
                    const end = contentArea.selectionEnd;
                    contentArea.value = contentArea.value.substring(0, start) + tag + contentArea.value.substring(end);
                    contentArea.selectionStart = contentArea.selectionEnd = start + tag.length;
                    contentArea.focus();
                    updateCounts();
                }
            });
        });
    });
</script>
@endpush