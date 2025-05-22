@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Wrong Account</div>

                <div class="card-body">
                    <div class="alert alert-warning">
                        <h5>This invitation was sent to a different email address</h5>
                        <p>You are currently logged in as <strong>{{ auth()->user()->email }}</strong>, but this invitation was sent to <strong>{{ $invitation->email }}</strong>.</p>
                    </div>

                    <p>To accept this invitation, you need to:</p>
                    <ol>
                        <li>Log out of your current account</li>
                        <li>Log in with {{ $invitation->email }} or create a new account with this email</li>
                    </ol>

                    <div class="d-flex justify-content-between mt-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">Log Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection