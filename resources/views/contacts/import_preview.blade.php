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
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Review and map your data before importing</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('contacts.import') }}" class="btn btn-sm btn-light-primary">
                                <i class="ki-outline ki-arrow-left fs-2 me-2"></i>Back to Import
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

                        <div class="alert alert-info d-flex align-items-center p-5 mb-10">
                            <i class="ki-outline ki-information-5 fs-2hx text-info me-4"></i>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-info">Import Information</h4>
                                <span>Found {{ $total_records }} contacts to import. Please map the columns below.</span>
                            </div>
                        </div>

                        <form action="{{ route('contacts.process-import') }}" method="POST" class="mb-5">
                            @csrf
                            <input type="hidden" name="path" value="{{ $path }}">
                            @if(isset($group_id) && $group_id)
                                <input type="hidden" name="group_id" value="{{ $group_id }}">
                            @endif

                            <div class="table-responsive mb-8">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted bg-light">
                                            @foreach($headers as $header)
                                                <th class="min-w-150px p-4">{{ $header }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($records as $record)
                                            <tr>
                                                @foreach($record as $field)
                                                    <td>{{ $field }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row gy-5">
                                <div class="col-xl-6">
                                    <div class="card card-bordered">
                                        <div class="card-header">
                                            <h3 class="card-title fw-bold text-dark">Required Fields</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-5">
                                                <label class="form-label required">First Name Column</label>
                                                <select name="first_name_column" class="form-select form-select-solid" required>
                                                    <option value="">Select column</option>
                                                    @foreach($headers as $index => $header)
                                                        <option value="{{ $header }}" 
                                                            {{ strtolower($header) == 'name' || strtolower($header) == 'first_name' ? 'selected' : '' }}>
                                                            Column {{ $index + 1 }}: {{ $header }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="form-text text-muted">Select which column contains the first name</div>
                                            </div>

                                            <div class="mb-5">
                                                <label class="form-label">Last Name Column (Optional)</label>
                                                <select name="last_name_column" class="form-select form-select-solid">
                                                    <option value="">Select column</option>
                                                    @foreach($headers as $index => $header)
                                                        <option value="{{ $header }}" 
                                                            {{ strtolower($header) == 'last_name' || strtolower($header) == 'surname' ? 'selected' : '' }}>
                                                            Column {{ $index + 1 }}: {{ $header }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="form-text text-muted">Select which column contains the last name (optional)</div>
                                            </div>

                                            <div class="mb-5">
                                                <label class="form-label required">Phone Column</label>
                                                <select name="phone_column" class="form-select form-select-solid" required>
                                                    <option value="">Select column</option>
                                                    @foreach($headers as $index => $header)
                                                        <option value="{{ $header }}" 
                                                            {{ strtolower($header) == 'phone' || strtolower($header) == 'phone_number' || strtolower($header) == 'mobile' ? 'selected' : '' }}>
                                                            Column {{ $index + 1 }}: {{ $header }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="form-text text-muted">Select which column contains the phone number</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-6">
                                    <div class="card card-bordered">
                                        <div class="card-header">
                                            <h3 class="card-title fw-bold text-dark">Optional Fields</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-5">
                                                <label class="form-label">Email Column</label>
                                                <select name="email_column" class="form-select form-select-solid">
                                                    <option value="">Select column</option>
                                                    @foreach($headers as $index => $header)
                                                        <option value="{{ $header }}" 
                                                            {{ strtolower($header) == 'email' || strtolower($header) == 'email_address' ? 'selected' : '' }}>
                                                            Column {{ $index + 1 }}: {{ $header }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-5">
                                                <label class="form-label">Company Column</label>
                                                <select name="company_column" class="form-select form-select-solid">
                                                    <option value="">Select column</option>
                                                    @foreach($headers as $index => $header)
                                                        <option value="{{ $header }}" 
                                                            {{ strtolower($header) == 'company' || strtolower($header) == 'organization' ? 'selected' : '' }}>
                                                            Column {{ $index + 1 }}: {{ $header }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="mb-5">
                                                <label class="form-label">Date of Birth Column</label>
                                                <select name="date_of_birth_column" class="form-select form-select-solid">
                                                    <option value="">Select column</option>
                                                    @foreach($headers as $index => $header)
                                                        <option value="{{ $header }}" 
                                                            {{ strtolower($header) == 'date_of_birth' || strtolower($header) == 'birth_date' || strtolower($header) == 'dob' ? 'selected' : '' }}>
                                                            Column {{ $index + 1 }}: {{ $header }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="form-text text-muted">Date format should be YYYY-MM-DD</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row gy-5 mt-5">
                                <div class="col-12">
                                    <div class="card card-bordered">
                                        <div class="card-header">
                                            <h3 class="card-title fw-bold text-dark">Additional Contact Information</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-5">
                                                        <label class="form-label">Gender Column</label>
                                                        <select name="gender_column" class="form-select form-select-solid">
                                                            <option value="">Select column</option>
                                                            @foreach($headers as $index => $header)
                                                                <option value="{{ $header }}" 
                                                                    {{ strtolower($header) == 'gender' || strtolower($header) == 'sex' ? 'selected' : '' }}>
                                                                    Column {{ $index + 1 }}: {{ $header }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-5">
                                                        <label class="form-label">Country Column</label>
                                                        <select name="country_column" class="form-select form-select-solid">
                                                            <option value="">Select column</option>
                                                            @foreach($headers as $index => $header)
                                                                <option value="{{ $header }}" 
                                                                    {{ strtolower($header) == 'country' || strtolower($header) == 'nation' ? 'selected' : '' }}>
                                                                    Column {{ $index + 1 }}: {{ $header }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="mb-5">
                                                        <label class="form-label">Region/State Column</label>
                                                        <select name="region_column" class="form-select form-select-solid">
                                                            <option value="">Select column</option>
                                                            @foreach($headers as $index => $header)
                                                                <option value="{{ $header }}" 
                                                                    {{ strtolower($header) == 'region' || strtolower($header) == 'state' ? 'selected' : '' }}>
                                                                    Column {{ $index + 1 }}: {{ $header }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-5">
                                                        <label class="form-label">City Column</label>
                                                        <select name="city_column" class="form-select form-select-solid">
                                                            <option value="">Select column</option>
                                                            @foreach($headers as $index => $header)
                                                                <option value="{{ $header }}" 
                                                                    {{ strtolower($header) == 'city' || strtolower($header) == 'town' ? 'selected' : '' }}>
                                                                    Column {{ $index + 1 }}: {{ $header }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Custom Fields Section -->
                            @if(isset($customFields) && $customFields->count() > 0)
                            <div class="row gy-5 mt-5">
                                <div class="col-12">
                                    <div class="card card-bordered">
                                        <div class="card-header">
                                            <h3 class="card-title fw-bold text-dark">Custom Fields</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach($customFields as $customField)
                                                <div class="col-md-6">
                                                    <div class="mb-5">
                                                        <label class="form-label">{{ $customField->label }} Column</label>
                                                        <select name="custom_field_{{ $customField->name }}_column" class="form-select form-select-solid">
                                                            <option value="">Select column</option>
                                                            @foreach($headers as $index => $header)
                                                                <option value="{{ $header }}" 
                                                                    {{ strtolower($header) == strtolower($customField->name) || strtolower($header) == strtolower($customField->label) ? 'selected' : '' }}>
                                                                    Column {{ $index + 1 }}: {{ $header }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @if($customField->description)
                                                            <div class="form-text text-muted">{{ $customField->description }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="d-flex justify-content-end mt-5">
                                <a href="{{ route('contacts.import') }}" class="btn btn-light me-3">Cancel</a>
                                <button type="submit" class="btn btn-primary">Import Contacts</button>
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