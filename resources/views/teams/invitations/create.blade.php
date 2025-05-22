@extends('layouts.master')

@section('title', 'Invite Team Members')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-md-8 col-lg-6 mx-auto">
                        <div class="card card-flush">
                            <div class="card-header">
                                <h3 class="card-title">Invite Team Members</h3>
                                <div class="card-toolbar">
                                    <a href="{{ route('teams.show', $team) }}" class="btn btn-light">
                                        <i class="ki-outline ki-arrow-left fs-2 me-1"></i>Back to Team
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success mb-4">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger mb-4">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger mb-4">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('teams.invitations.store', $team) }}" method="POST">
                                    @csrf

                                    <div class="mb-5">
                                        <label for="email" class="form-label fw-semibold">Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Enter the email address of the person you'd like to invite to your team.</div>
                                    </div>

                                    <div class="mb-5">
                                        <label for="role" class="form-label fw-semibold">Role</label>
                                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                            <option value="member" {{ old('role') == 'member' ? 'selected' : '' }}>Member</option>
                                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <strong>Member:</strong> Can use team resources based on sharing settings.<br>
                                            <strong>Admin:</strong> Can manage team members and team resources.
                                        </div>
                                    </div>

                                    <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6 mb-8">
                                        <i class="ki-outline ki-information-5 fs-2tx text-primary me-4"></i>
                                        <div class="d-flex flex-stack flex-grow-1">
                                            <div class="fw-semibold">
                                                <h4 class="text-gray-900 fw-bold">Invitation Information</h4>
                                                <div class="fs-6 text-gray-700">
                                                    <ul class="mb-0">
                                                        <li>An email invitation will be sent to the provided address.</li>
                                                        <li>The invitation will expire in 7 days.</li>
                                                        <li>You can cancel the invitation anytime before it's accepted.</li>
                                                        <li>If the user already has an account, they'll be added to your team upon acceptance.</li>
                                                        <li>If not, they'll be prompted to create an account first.</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('teams.show', $team) }}" class="btn btn-light me-3">Cancel</a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ki-outline ki-send fs-2 me-1"></i>Send Invitation
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