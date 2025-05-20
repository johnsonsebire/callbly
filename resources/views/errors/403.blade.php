@extends('layouts.error')
@php
use Illuminate\Support\Facades\Auth;
@endphp
@section('content')
    <!--begin::Title-->
    <h1 class="fw-bolder fs-2hx text-gray-900 mb-4">Oops!</h1>
    <!--end::Title-->
    <!--begin::Text-->
    <div class="fw-semibold fs-6 text-gray-500 mb-7">You don't have the permission to view this page.</div>
    <!--end::Text-->
    <!--begin::Illustration-->
    <div class="mb-3">
        <img src="assets/media/auth/404-error.png" class="mw-100 mh-300px theme-light-show" alt="" />
        <img src="assets/media/auth/404-error-dark.png" class="mw-100 mh-300px theme-dark-show" alt="" />
    </div>
    <!--end::Illustration-->
    <!--begin::Link-->
    <div class="mb-0">
    @if(Auth::check())
        <a href="{{route('dashboard')}}" class="btn btn-sm btn-primary">Return Home</a>
        @else 
        <a href="{{route('home')}}" class="btn btn-sm btn-primary">Login</a>
        @endif

    </div>
    <!--end::Link-->
@endsection
