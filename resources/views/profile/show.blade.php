@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Profile Settings</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success mb-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" class="form-control" value="{{ $user->email }}" disabled readonly>
                            <small class="text-muted">Email cannot be changed. Please contact support if you need to update your email address.</small>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" name="company_name" id="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $user->company_name) }}">
                            @error('company_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Account Settings</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('settings.currency') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Currency Preferences</h6>
                                <p class="small text-muted mb-0">Select your preferred currency for displaying prices</p>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $user->currency->code }}</span>
                        </a>
                        
                        <a href="{{ route('sms.billing-tier') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">SMS Billing Tier</h6>
                                <p class="small text-muted mb-0">View your current SMS pricing tier</p>
                            </div>
                            <span class="badge bg-success rounded-pill">{{ $user->billingTier->name }}</span>
                        </a>
                        
                        <a href="#" class="list-group-item list-group-item-action">
                            <div>
                                <h6 class="mb-1">Change Password</h6>
                                <p class="small text-muted mb-0">Update your account password</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Account Balance</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light text-dark mb-3">
                                <div class="card-body">
                                    <h6>Available Balance</h6>
                                    <h3>{{ $user->formatAmount($user->account_balance) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light text-dark mb-3">
                                <div class="card-body">
                                    <h6>SMS Credits Used</h6>
                                    <h3>{{ number_format($user->sms_credits ?? 0) }}</h3>
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