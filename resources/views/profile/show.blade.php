<div class="card-title">@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Profile Information</h5>
                </div><div class="card-title"></div><div class="card-title"></div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div><div class="card-title"></div><div class="card-title"></div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <p class="form-control-static">{{ $user->name }}</p>
                    </div><div class="card-title"></div><div class="card-title"></div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <p class="form-control-static">{{ $user->email }}</p>
                    </div><div class="card-title"></div><div class="card-title"></div>

                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <p class="form-control-static">{{ $user->phone ?? 'Not set' }}</p>
                    </div><div class="card-title"></div><div class="card-title"></div>

                    <div class="mb-3">
                        <label class="form-label">Company Name</label>
                        <p class="form-control-static">{{ $user->company_name ?? 'Not set' }}</p>
                    </div><div class="card-title"></div><div class="card-title"></div>

                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>
</div><div class="card-title"></div></div>
@endsection