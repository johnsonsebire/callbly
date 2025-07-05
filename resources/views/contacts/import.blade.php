@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Import Contacts Card -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Import Contacts</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Upload an Excel or CSV file to import multiple contacts</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('contacts.index') }}" class="btn btn-sm btn-light-primary">
                                <i class="ki-outline ki-arrow-left fs-2 me-2"></i>Back to Contacts
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

                        <div class="row">
                            <div class="col-lg-8">
                                <form action="{{ route('contacts.upload-import') }}" method="POST" enctype="multipart/form-data" class="mb-5">
                                    @csrf
                                    <div class="mb-5">
                                        <label for="excel_file" class="form-label fs-6 fw-semibold">Choose File</label>
                                        <input type="file" class="form-control" id="excel_file" name="excel_file" required accept=".xlsx,.xls,.csv">
                                        <div class="form-text">Supported formats: Excel (.xlsx, .xls), CSV (.csv)</div>
                                    </div>
                                    
                                    @if(count($groups) > 0)
                                    <div class="mb-5">
                                        <label for="group_id" class="form-label fs-6 fw-semibold">Add to Group (Optional)</label>
                                        <select class="form-select form-select-solid" id="group_id" name="group_id">
                                            <option value="">-- Select a group --</option>
                                            @foreach($groups as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                    
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('contacts.index') }}" class="btn btn-light me-3">Cancel</a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ki-outline ki-upload fs-2 me-2"></i>Upload and Preview
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="col-lg-4">
                                <div class="card card-bordered bg-light-info">
                                    <div class="card-header">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-dark">File Format Guidelines</span>
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-5">
                                            <p class="text-gray-800 fs-6 fw-semibold mb-2">Your file should include these columns:</p>
                                            <ul class="ps-4">
                                                <li><strong>Required:</strong> Names (first name, last name or both) and phone numbers</li>
                                                <li><strong>Optional:</strong> email, company, date_of_birth, gender, country, region, city, notes</li>
                                                <li><strong>Custom Fields:</strong> Any custom fields you've created will be available for mapping</li>
                                            </ul>
                                        </div>
                                        <div class="separator my-5"></div>
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-information-5 fs-2 text-info me-3"></i>
                                            <div class="text-gray-800 fs-6 fw-semibold">The first row should contain column headers</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card card-bordered mt-5">
                                    <div class="card-header">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-dark">Sample Format</span>
                                        </h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-row-bordered table-sm gy-2 gs-2">
                                                <thead class="border-bottom border-gray-300">
                                                    <tr class="fs-7 fw-bold text-gray-500">
                                                        <th>first_name</th>
                                                        <th>last_name</th>
                                                        <th>phone</th>
                                                        <th>email</th>
                                                        <th>company</th>
                                                        <th>gender</th>
                                                        <th>country</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fs-7">
                                                    <tr>
                                                        <td>John</td>
                                                        <td>Doe</td>
                                                        <td>233501234567</td>
                                                        <td>john@example.com</td>
                                                        <td>Tech Corp</td>
                                                        <td>male</td>
                                                        <td>Ghana</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Jane</td>
                                                        <td>Smith</td>
                                                        <td>233502345678</td>
                                                        <td>jane@example.com</td>
                                                        <td>Design Ltd</td>
                                                        <td>female</td>
                                                        <td>Nigeria</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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