@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Import Preview</h5>
            <a href="{{ route('contacts.import') }}" class="btn btn-sm btn-secondary float-end">Back</a>
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
                <p><strong>Total Records:</strong> {{ $total_records }}</p>
                <p class="mb-0">Please map the columns in your CSV file to the appropriate contact fields below.</p>
            </div>

            <form action="{{ route('contacts.process-import') }}" method="POST">
                @csrf
                <input type="hidden" name="path" value="{{ $path }}">
                <input type="hidden" name="group_id" value="{{ $group_id }}">
                
                <div class="mb-4">
                    <h6 class="mb-3">Sample Data Preview:</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    @foreach($headers as $header)
                                        <th>{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($records as $record)
                                    <tr>
                                        @foreach($headers as $header)
                                            <td>{{ $record[$header] }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="name_column" class="form-label">Name Column</label>
                        <select class="form-select" id="name_column" name="name_column" required>
                            <option value="" selected disabled>Select column</option>
                            @foreach($headers as $header)
                                <option value="{{ $header }}" {{ strtolower($header) == 'name' || strtolower($header) == 'full name' ? 'selected' : '' }}>{{ $header }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="phone_column" class="form-label">Phone Column</label>
                        <select class="form-select" id="phone_column" name="phone_column" required>
                            <option value="" selected disabled>Select column</option>
                            @foreach($headers as $header)
                                <option value="{{ $header }}" {{ strtolower($header) == 'phone' || strtolower($header) == 'phone number' || strtolower($header) == 'contact' ? 'selected' : '' }}>{{ $header }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="email_column" class="form-label">Email Column (Optional)</label>
                        <select class="form-select" id="email_column" name="email_column">
                            <option value="">-- None --</option>
                            @foreach($headers as $header)
                                <option value="{{ $header }}" {{ strtolower($header) == 'email' || strtolower($header) == 'email address' ? 'selected' : '' }}>{{ $header }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="company_column" class="form-label">Company Column (Optional)</label>
                        <select class="form-select" id="company_column" name="company_column">
                            <option value="">-- None --</option>
                            @foreach($headers as $header)
                                <option value="{{ $header }}" {{ strtolower($header) == 'company' || strtolower($header) == 'organization' ? 'selected' : '' }}>{{ $header }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Import Contacts</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection