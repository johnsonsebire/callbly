<div class="card-title">@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Create USSD Service</h5>
                </div><div class="card-title"></div><div class="card-title"></div>
                <div class="card-body">
                    <form action="{{ route('ussd.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="form-label">Service Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div><div class="card-title"></div><div class="card-title"></div>
                            @enderror
                        </div><div class="card-title"></div><div class="card-title"></div>

                        <div class="mb-4">
                            <label for="shortcode" class="form-label">Shortcode</label>
                            <input type="text" class="form-control @error('shortcode') is-invalid @enderror" 
                                   id="shortcode" name="shortcode" value="{{ old('shortcode') }}" required>
                            <div class="form-text">The shortcode users will dial to access your service (e.g., *123#)</div><div class="card-title"></div><div class="card-title"></div>
                            @error('shortcode')
                                <div class="invalid-feedback">{{ $message }}</div><div class="card-title"></div><div class="card-title"></div>
                            @enderror
                        </div><div class="card-title"></div><div class="card-title"></div>

                        <div class="mb-4">
                            <label for="menu_structure" class="form-label">Menu Structure</label>
                            <textarea class="form-control @error('menu_structure') is-invalid @enderror" 
                                      id="menu_structure" name="menu_structure" rows="10" required>{{ old('menu_structure') }}</textarea>
                            <div class="form-text">Enter your menu structure in JSON format. See documentation for structure guidelines.</div><div class="card-title"></div><div class="card-title"></div>
                            @error('menu_structure')
                                <div class="invalid-feedback">{{ $message }}</div><div class="card-title"></div><div class="card-title"></div>
                            @enderror
                        </div><div class="card-title"></div><div class="card-title"></div>

                        <div class="mb-4">
                            <label for="callback_url" class="form-label">Callback URL (Optional)</label>
                            <input type="url" class="form-control @error('callback_url') is-invalid @enderror" 
                                   id="callback_url" name="callback_url" value="{{ old('callback_url') }}">
                            <div class="form-text">URL to receive session updates and user inputs</div><div class="card-title"></div><div class="card-title"></div>
                            @error('callback_url')
                                <div class="invalid-feedback">{{ $message }}</div><div class="card-title"></div><div class="card-title"></div>
                            @enderror
                        </div><div class="card-title"></div><div class="card-title"></div>

                        <div class="text-end">
                            <a href="{{ route('ussd.services') }}" class="btn btn-light me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Service</button>
                        </div><div class="card-title"></div><div class="card-title"></div>
                    </form>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>
</div><div class="card-title"></div></div>
@endsection