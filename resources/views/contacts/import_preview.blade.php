@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Import Preview Card -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Import Preview</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Map your CSV columns to contact fields</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('contacts.import') }}" class="btn btn-sm btn-light-primary">
                                <i class="ki-outline ki-arrow-left fs-2 me-2"></i>Back
                            </a>
                        </div>
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

                        <div class="alert alert-info d-flex align-items-center p-5 mb-6">
                            <i class="ki-outline ki-information-5 fs-2 me-4 text-info"></i>
                            <div class="d-flex flex-column">
                                <h5 class="mb-1 text-dark">Import Information</h5>
                                <div><strong>Total Records:</strong> {{ $total_records }}</div>
                                <div>Please map the columns in your CSV file to the appropriate contact fields below.</div>
                            </div>
                        </div>

                        <form action="{{ route('contacts.process-import') }}" method="POST">
                            @csrf
                            <input type="hidden" name="path" value="{{ $path }}">
                            <input type="hidden" name="group_id" value="{{ $group_id }}">
                            
                            <!-- Data Preview Section -->
                            <div class="mb-8">
                                <h4 class="fw-bold text-gray-800 mb-5">Sample Data Preview</h4>
                                <div class="table-responsive">
                                    <table class="table table-row-bordered table-row-gray-300 gy-2">
                                        <thead>
                                            <tr class="fw-bold text-muted bg-light">
                                                @foreach($headers as $header)
                                                    <th class="min-w-125px">{{ $header }}</th>
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
                            
                            <!-- Column Mapping Section -->
                            <div class="card card-bordered mb-8">
                                <div class="card-header">
                                    <h3 class="card-title fw-bold text-dark">
                                        Column Mapping
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-5">
                                            <label for="name_column" class="form-label fw-semibold required">Name Column</label>
                                            <select class="form-select form-select-solid" id="name_column" name="name_column" required>
                                                <option value="" selected disabled>Select column</option>
                                                @foreach($headers as $header)
                                                    <option value="{{ $header }}" {{ strtolower($header) == 'name' || strtolower($header) == 'full name' ? 'selected' : '' }}>{{ $header }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-3 mb-5">
                                            <label for="phone_column" class="form-label fw-semibold required">Phone Column</label>
                                            <select class="form-select form-select-solid" id="phone_column" name="phone_column" required>
                                                <option value="" selected disabled>Select column</option>
                                                @foreach($headers as $header)
                                                    <option value="{{ $header }}" {{ strtolower($header) == 'phone' || strtolower($header) == 'phone number' || strtolower($header) == 'contact' ? 'selected' : '' }}>{{ $header }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-3 mb-5">
                                            <label for="email_column" class="form-label fw-semibold">Email Column (Optional)</label>
                                            <select class="form-select form-select-solid" id="email_column" name="email_column">
                                                <option value="">-- None --</option>
                                                @foreach($headers as $header)
                                                    <option value="{{ $header }}" {{ strtolower($header) == 'email' || strtolower($header) == 'email address' ? 'selected' : '' }}>{{ $header }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-3 mb-5">
                                            <label for="company_column" class="form-label fw-semibold">Company Column (Optional)</label>
                                            <select class="form-select form-select-solid" id="company_column" name="company_column">
                                                <option value="">-- None --</option>
                                                @foreach($headers as $header)
                                                    <option value="{{ $header }}" {{ strtolower($header) == 'company' || strtolower($header) == 'organization' ? 'selected' : '' }}>{{ $header }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('contacts.import') }}" class="btn btn-light me-3">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ki-outline ki-check fs-2 me-2"></i>Import Contacts
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
</div>
@endsection