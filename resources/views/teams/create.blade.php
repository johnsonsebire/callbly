@extends('layouts.master')

@section('title', 'Create Team Workspace')

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
                                        <i class="ki-outline ki-plus-circle fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">Create New Team Workspace</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Setup</span>
                                    </div>
                                    <p class="text-gray-400 fs-7 mb-0">Initialize a collaborative workspace to manage team members, Sender IDs, and shared credits.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('teams.index') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-arrow-left me-1 fs-5"></i> Back to Teams
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-5 g-xl-10 justify-content-center">
                    <div class="col-lg-8 col-xl-7">
                        <div class="card border-0 shadow-sm overflow-hidden" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                            <div class="card-header pt-6 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-3">Workspace Setup</span>
                                    <span class="text-gray-400 mt-1 fs-7">Enter the details for your new organization team workspace</span>
                                </h3>
                            </div>
                            <div class="card-body pt-4">
                                @if ($errors->any())
                                    <div class="alert alert-danger d-flex align-items-center p-4 mb-6 rounded-3">
                                        <i class="ki-outline ki-cross-circle fs-2x text-danger me-3"></i>
                                        <div>
                                            <span class="font-bold text-gray-900 fs-6">Please resolve the following errors:</span>
                                            <ul class="mb-0 mt-1 text-gray-700 fs-7 ps-4">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif

                                <form action="{{ route('teams.store') }}" method="POST">
                                    @csrf

                                    <!-- Team Name -->
                                    <div class="mb-6">
                                        <label for="name" class="form-label font-bold text-gray-800 fs-6 mb-2">
                                            Team Workspace Name <span class="text-danger">*</span>
                                        </label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control form-control-solid ps-12 @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name') }}" 
                                                   placeholder="e.g. Acme Marketing & Operations" required autofocus>
                                            <i class="ki-outline ki-people position-absolute top-50 translate-middle-y ms-4 fs-3 text-gray-400"></i>
                                        </div>
                                        @error('name')
                                            <div class="invalid-feedback d-block mt-1 font-semibold">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text text-gray-400 fs-7 mt-1.5">This name will identify your shared workspace across the Callbly console.</div>
                                    </div>

                                    <!-- Description -->
                                    <div class="mb-6">
                                        <label for="description" class="form-label font-bold text-gray-800 fs-6 mb-2">
                                            Workspace Description <span class="text-gray-400 font-normal fs-7">(Optional)</span>
                                        </label>
                                        <textarea class="form-control form-control-solid @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="4" 
                                                  placeholder="Describe the department, project, or operational scope of this team workspace...">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback d-block mt-1 font-semibold">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Feature Highlights Callout -->
                                    <div class="rounded-3 p-5 mb-8 bg-light-primary border border-primary/20">
                                        <div class="d-flex align-items-start gap-3">
                                            <i class="ki-outline ki-shield-check text-primary fs-2hx mt-1"></i>
                                            <div>
                                                <h4 class="text-gray-900 font-bold fs-6 mb-1">What happens when you create a team workspace?</h4>
                                                <ul class="text-gray-600 fs-7 mb-0 ps-4">
                                                    <li>You will automatically be assigned as the <strong>Workspace Owner</strong>.</li>
                                                    <li>You can invite colleagues, developers, and managers with role-based permissions.</li>
                                                    <li>Members can dispatch SMS & USSD dispatches using the shared team wallet balance.</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="separator separator-dashed my-6"></div>

                                    <!-- Actions -->
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="{{ route('teams.index') }}" class="btn btn-light font-bold px-5">
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary font-bold px-6 shadow-sm">
                                            <i class="ki-outline ki-check-circle me-1.5 fs-4"></i> Create Workspace
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection