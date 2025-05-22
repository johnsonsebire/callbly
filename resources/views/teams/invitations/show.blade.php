@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Team Invitation</div>

                <div class="card-body">
                    <h4>Join {{ $invitation->team->name }}</h4>
                    <p>You've been invited to join the team by {{ $invitation->team->owner->name }}.</p>
                    
                    <div class="alert alert-info">
                        <p>To join this team, you'll need to:</p>
                        <ol>
                            <li>Create an account with {{ $invitation->email }}</li>
                            <li>Or log in if you already have an account</li>
                        </ol>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('register', ['email' => $invitation->email]) }}" class="btn btn-primary">
                            Create Account
                        </a>
                        <a href="{{ route('login', ['email' => $invitation->email]) }}" class="btn btn-outline-primary">
                            Log In
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection