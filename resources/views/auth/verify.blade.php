<div class="card-title">@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Verify Your Email Address</h5>
                </div><div class="card-title"></div><div class="card-title"></div>
                <div class="card-body p-4">
                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success" role="alert">
                            A new verification link has been sent to your email address.
                        </div><div class="card-title"></div><div class="card-title"></div>
                    @endif

                    <p>Before proceeding, please check your email for a verification link. If you did not receive the email,</p>
                    
                    <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">click here to request another</button>.
                    </form>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>
</div><div class="card-title"></div></div>
@endsection