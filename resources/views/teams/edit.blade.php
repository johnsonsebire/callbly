@extends('layouts.master')

@section('title', 'Edit Team')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-md-8 col-lg-6 mx-auto">
                        <div class="card card-flush">
                            <div class="card-header">
                                <h3 class="card-title">Edit Team</h3>
                                <div class="card-toolbar">
                                    <a href="{{ route('teams.show', $team) }}" class="btn btn-light">
                                        <i class="ki-outline ki-arrow-left fs-2 me-1"></i>Back to Team
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger mb-4">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('teams.update', $team) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-5">
                                        <label for="name" class="form-label fw-semibold">Team Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $team->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-5">
                                        <label for="description" class="form-label fw-semibold">Description (Optional)</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="4">{{ old('description', $team->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Provide a brief description of your team and its purpose.</div>
                                    </div>

                                    <div class="separator separator-dashed my-8"></div>
                                    
                                    <h4 class="fs-4 fw-semibold mb-3">Resource Sharing Settings</h4>
                                    
                                    <div class="mb-5">
                                        <div class="form-check form-switch form-check-custom form-check-solid mb-3">
                                            <input class="form-check-input" type="checkbox" name="share_sms_credits" id="share_sms_credits" 
                                                {{ old('share_sms_credits', $team->share_sms_credits) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-gray-800" for="share_sms_credits">
                                                Share SMS Credits
                                            </label>
                                        </div>
                                        <div class="text-muted fs-7 ms-9">Allow team members to use the owner's SMS credits</div>
                                    </div>
                                    
                                    <div class="mb-5">
                                        <div class="form-check form-switch form-check-custom form-check-solid mb-3">
                                            <input class="form-check-input" type="checkbox" name="share_contacts" id="share_contacts" 
                                                {{ old('share_contacts', $team->share_contacts) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-gray-800" for="share_contacts">
                                                Share Contacts
                                            </label>
                                        </div>
                                        <div class="text-muted fs-7 ms-9">Allow team members to access and use the team's contact lists</div>
                                    </div>
                                    
                                    <div class="mb-5">
                                        <div class="form-check form-switch form-check-custom form-check-solid mb-3">
                                            <input class="form-check-input" type="checkbox" name="share_sender_names" id="share_sender_names" 
                                                {{ old('share_sender_names', $team->share_sender_names) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-gray-800" for="share_sender_names">
                                                Share Sender Names
                                            </label>
                                        </div>
                                        <div class="text-muted fs-7 ms-9">Allow team members to use the team's approved sender names</div>
                                    </div>

                                    <div class="separator separator-dashed my-8"></div>

                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('teams.show', $team) }}" class="btn btn-light">Cancel</a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ki-outline ki-check fs-2 me-1"></i>Save Changes
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