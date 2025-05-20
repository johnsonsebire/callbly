@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Create Contact Group</h5>
            <a href="{{ route('contact-groups.index') }}" class="btn btn-sm btn-secondary float-end">Back to Groups</a>
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

            <form action="{{ route('contact-groups.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Group Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description (Optional)</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Create Group</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection