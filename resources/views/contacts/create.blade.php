@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Create New Contact</h5>
            <a href="{{ route('contacts.index') }}" class="btn btn-sm btn-secondary float-end">Back to Contacts</a>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('contacts.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                </div>
                
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                    <div class="form-text">Phone numbers will automatically be formatted with the country code (e.g. 233).</div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                </div>

                <div class="mb-3">
                    <label for="company" class="form-label">Company</label>
                    <input type="text" class="form-control" id="company" name="company" value="{{ old('company') }}">
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                </div>

                @if(count($groups) > 0)
                <div class="mb-3">
                    <label class="form-label">Contact Groups</label>
                    <div>
                        @foreach($groups as $id => $name)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="group_{{ $id }}" name="groups[]" value="{{ $id }}">
                                <label class="form-check-label" for="group_{{ $id }}">{{ $name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Create Contact</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection