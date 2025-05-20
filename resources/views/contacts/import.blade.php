@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Import Contacts</h5>
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

            <div class="alert alert-info">
                <h6 class="alert-heading">CSV Format Guidelines</h6>
                <p>Your CSV file should include the following columns:</p>
                <ul>
                    <li>A column for names (first name, last name or both)</li>
                    <li>A column for phone numbers</li>
                    <li>Optional columns: email, company, notes</li>
                </ul>
                <p class="mb-0">The first row should contain column headers.</p>
            </div>

            <form action="{{ route('contacts.upload-import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="csv_file" class="form-label">Choose CSV File</label>
                    <input type="file" class="form-control" id="csv_file" name="csv_file" required accept=".csv">
                </div>
                
                @if(count($groups) > 0)
                <div class="mb-3">
                    <label for="group_id" class="form-label">Add to Group (Optional)</label>
                    <select class="form-select" id="group_id" name="group_id">
                        <option value="">-- Select a group --</option>
                        @foreach($groups as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Upload and Preview</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection