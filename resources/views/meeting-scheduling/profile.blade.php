@extends('layouts.master')

@section('title', 'Company Profile')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Page Header -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Company Profile</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Manage your public company profile and branding</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('meeting-scheduling.dashboard') }}" class="btn btn-sm btn-light">
                                <i class="ki-outline ki-arrow-left fs-2 me-2"></i>Back to Dashboard
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                                <i class="ki-outline ki-shield-tick fs-2hx text-success me-4"></i>
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-success">Success</h4>
                                    <span>{{ session('success') }}</span>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('meeting-scheduling.profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-lg-8">
                                    <!-- Company Information -->
                                    <div class="card card-flush mb-8">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Company Information</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="mb-10 fv-row">
                                                <label class="required form-label">Company Name</label>
                                                <input type="text" name="company_name" class="form-control mb-2" 
                                                       placeholder="Enter your company name" 
                                                       value="{{ old('company_name', $profile->company_name ?? '') }}" required />
                                                @error('company_name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-10 fv-row">
                                                <label class="required form-label">Brand Name/URL Slug</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">{{ url('/') }}/</span>
                                                    <input type="text" name="brand_name" class="form-control" 
                                                           placeholder="your-brand" 
                                                           value="{{ old('brand_name', $profile->brand_name ?? '') }}" required />
                                                </div>
                                                @error('brand_name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="text-muted fs-7">This will be your public scheduling URL</div>
                                            </div>

                                            <div class="mb-10 fv-row">
                                                <label class="form-label">Company Description</label>
                                                <textarea name="description" class="form-control mb-2" rows="4" 
                                                          placeholder="Brief description of your company">{{ old('description', $profile->description ?? '') }}</textarea>
                                                @error('description')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-10 fv-row">
                                                        <label class="form-label">Email</label>
                                                        <input type="email" name="email" class="form-control mb-2" 
                                                               placeholder="company@example.com" 
                                                               value="{{ old('email', $profile->email ?? auth()->user()->email) }}" />
                                                        @error('email')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-10 fv-row">
                                                        <label class="form-label">Phone Number</label>
                                                        <input type="text" name="phone" class="form-control mb-2" 
                                                               placeholder="+233 XX XXX XXXX" 
                                                               value="{{ old('phone', $profile->phone ?? '') }}" />
                                                        @error('phone')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-10 fv-row">
                                                <label class="form-label">Website</label>
                                                <input type="url" name="website" class="form-control mb-2" 
                                                       placeholder="https://yourwebsite.com" 
                                                       value="{{ old('website', $profile->website ?? '') }}" />
                                                @error('website')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-10 fv-row">
                                                <label class="form-label">Address</label>
                                                <textarea name="address" class="form-control mb-2" rows="3" 
                                                          placeholder="Company address">{{ old('address', $profile->address ?? '') }}</textarea>
                                                @error('address')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Social Media Links -->
                                    <div class="card card-flush mb-8">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Social Media Links</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-10 fv-row">
                                                        <label class="form-label">LinkedIn</label>
                                                        <input type="url" name="linkedin_url" class="form-control mb-2" 
                                                               placeholder="https://linkedin.com/company/yourcompany" 
                                                               value="{{ old('linkedin_url', $profile->linkedin_url ?? '') }}" />
                                                        @error('linkedin_url')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-10 fv-row">
                                                        <label class="form-label">Twitter</label>
                                                        <input type="url" name="twitter_url" class="form-control mb-2" 
                                                               placeholder="https://twitter.com/yourcompany" 
                                                               value="{{ old('twitter_url', $profile->twitter_url ?? '') }}" />
                                                        @error('twitter_url')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-10 fv-row">
                                                        <label class="form-label">Facebook</label>
                                                        <input type="url" name="facebook_url" class="form-control mb-2" 
                                                               placeholder="https://facebook.com/yourcompany" 
                                                               value="{{ old('facebook_url', $profile->facebook_url ?? '') }}" />
                                                        @error('facebook_url')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-10 fv-row">
                                                        <label class="form-label">Instagram</label>
                                                        <input type="url" name="instagram_url" class="form-control mb-2" 
                                                               placeholder="https://instagram.com/yourcompany" 
                                                               value="{{ old('instagram_url', $profile->instagram_url ?? '') }}" />
                                                        @error('instagram_url')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <!-- Logo Upload -->
                                    <div class="card card-flush mb-8">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Company Logo</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="text-center">
                                                <div class="image-input image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                                                    <div class="image-input-wrapper w-125px h-125px" 
                                                         style="background-image: url('{{ $profile && $profile->logo ? Storage::url($profile->logo) : asset('img/blank-image.svg') }}')"></div>
                                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" 
                                                           data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change logo">
                                                        <i class="ki-outline ki-pencil fs-7"></i>
                                                        <input type="file" name="logo" accept=".png,.jpg,.jpeg" />
                                                        <input type="hidden" name="logo_remove" />
                                                    </label>
                                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" 
                                                          data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel logo">
                                                        <i class="ki-outline ki-cross fs-2"></i>
                                                    </span>
                                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" 
                                                          data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove logo">
                                                        <i class="ki-outline ki-cross fs-2"></i>
                                                    </span>
                                                </div>
                                                <div class="text-muted fs-7">Set the company logo that will appear on your public scheduling pages. Only *.png, *.jpg and *.jpeg image files are accepted</div>
                                                @error('logo')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Branding Settings -->
                                    <div class="card card-flush mb-8">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Branding</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="mb-10 fv-row">
                                                <label class="form-label">Primary Color</label>
                                                <input type="color" name="primary_color" class="form-control form-control-color" 
                                                       value="{{ old('primary_color', $profile->primary_color ?? '#3B82F6') }}" />
                                                <div class="text-muted fs-7">Choose your brand's primary color</div>
                                            </div>

                                            <div class="mb-10 fv-row">
                                                <label class="form-label">Time Zone</label>
                                                <select name="timezone" class="form-select mb-2">
                                                    <option value="Africa/Accra" {{ old('timezone', $profile->timezone ?? 'Africa/Accra') == 'Africa/Accra' ? 'selected' : '' }}>Africa/Accra (GMT)</option>
                                                    <option value="UTC" {{ old('timezone', $profile->timezone ?? '') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                                    <option value="America/New_York" {{ old('timezone', $profile->timezone ?? '') == 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                                                    <option value="Europe/London" {{ old('timezone', $profile->timezone ?? '') == 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                                                    <option value="Asia/Dubai" {{ old('timezone', $profile->timezone ?? '') == 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (GST)</option>
                                                </select>
                                                @error('timezone')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Preview Link -->
                                    @if($profile && $profile->brand_name)
                                    <div class="card card-flush mb-8">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Public Profile</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="mb-5">
                                                <label class="form-label">Your Public URL</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" 
                                                           value="{{ route('public.profile.show', $profile->brand_name) }}" readonly>
                                                    <button class="btn btn-outline-secondary copy-link" type="button">
                                                        <i class="ki-outline ki-copy fs-2"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <a href="{{ route('public.profile.show', $profile->brand_name) }}" 
                                               target="_blank" class="btn btn-light-primary btn-sm w-100">
                                                <i class="ki-outline ki-eye fs-2 me-2"></i>Preview Profile
                                            </a>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Save Button -->
                                    <div class="card card-flush">
                                        <div class="card-body pt-0">
                                            <div class="d-flex flex-stack">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <span class="indicator-label">Save Profile</span>
                                                    <span class="indicator-progress">Please wait...
                                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate brand name from company name
    document.querySelector('input[name="company_name"]').addEventListener('input', function() {
        const brandInput = document.querySelector('input[name="brand_name"]');
        if (!brandInput.value || brandInput.dataset.manual !== 'true') {
            const brand = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            brandInput.value = brand;
        }
    });

    // Mark brand name as manually edited
    document.querySelector('input[name="brand_name"]').addEventListener('input', function() {
        this.dataset.manual = 'true';
    });

    // Copy link functionality
    document.querySelectorAll('.copy-link').forEach(function(button) {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            input.select();
            input.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(input.value).then(function() {
                // Show success message
                const originalIcon = button.innerHTML;
                button.innerHTML = '<i class="ki-outline ki-check fs-2"></i>';
                setTimeout(function() {
                    button.innerHTML = originalIcon;
                }, 2000);
            });
        });
    });

    // Image input functionality
    const imageInputs = document.querySelectorAll('[data-kt-image-input]');
    imageInputs.forEach(function(element) {
        KTImageInput.createInstances(element);
    });
});
</script>
@endpush
@endsection