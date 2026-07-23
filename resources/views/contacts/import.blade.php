@extends('layouts.master')

@section('title', 'Batch Import Audience Contacts')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                
                <!-- Welcome Banner Hero Card -->
                <div class="card mb-5 mb-xl-8 border-0 shadow-lg overflow-hidden" style="background: linear-gradient(135deg, #090d16 0%, #0f172a 50%, #1e293b 100%);">
                    <div class="card-body p-6 sm:p-8 text-white relative">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-6">
                            <div class="d-flex align-items-center gap-4">
                                <div class="symbol symbol-60px symbol-lg-75px symbol-circle border border-2 border-cyan-400/40 p-1 shadow-inner" style="background: rgba(14, 165, 233, 0.15);">
                                    <div class="symbol-label font-extrabold text-cyan-400 fs-1 rounded-circle" style="background: #0ea5e9; color: #ffffff;">
                                        <i class="ki-outline ki-cloud-add fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">Batch Import Contacts</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">CSV & Excel</span>
                                    </div>
                                    <p class="text-gray-400 fs-7 mb-0">Bulk import contact lists into your audience database with automatic field mapping.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('contacts.index') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-arrow-left me-1 fs-5"></i> Back to Directory
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-center p-4 mb-5 rounded-3 shadow-xs">
                        <i class="ki-outline ki-cross-circle fs-2x text-danger me-3"></i>
                        <div>
                            <span class="font-bold text-gray-900 fs-6">Upload Error</span>
                            <ul class="mb-0 fs-7 text-gray-700 ps-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="row g-5 g-xl-8">
                    <!-- Upload Form Column -->
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                            <div class="card-header pt-6 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-3">Upload Audience Spreadsheet</span>
                                    <span class="text-gray-400 mt-1 fs-7">Select an Excel (.xlsx, .xls) or CSV (.csv) document</span>
                                </h3>
                            </div>
                            <div class="card-body pt-4">
                                <form action="{{ route('contacts.upload-import') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <div class="mb-6">
                                        <label for="excel_file" class="form-label font-bold text-gray-800 fs-6 mb-2">Select Spreadsheet File <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <input type="file" class="form-control form-control-solid ps-12 @error('excel_file') is-invalid @enderror" id="excel_file" name="excel_file" required accept=".xlsx,.xls,.csv">
                                            <i class="ki-outline ki-file-up position-absolute top-50 translate-middle-y ms-4 fs-3 text-gray-400"></i>
                                        </div>
                                        <div class="form-text text-gray-400 fs-7 mt-1.5">
                                            Supported file types: Microsoft Excel (.xlsx, .xls) and Comma-Separated Values (.csv).
                                        </div>
                                        @error('excel_file')<div class="invalid-feedback d-block mt-1 font-semibold">{{ $message }}</div>@enderror
                                    </div>
                                    
                                    @if(count($groups) > 0)
                                    <div class="mb-6">
                                        <label for="group_id" class="form-label font-bold text-gray-800 fs-6 mb-2">Target Contact Group <span class="text-gray-400 font-normal fs-7">(Optional)</span></label>
                                        <div class="position-relative">
                                            <select class="form-select form-select-solid ps-12" id="group_id" name="group_id">
                                                <option value="">-- Do not assign to group --</option>
                                                @foreach($groups as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            <i class="ki-outline ki-category position-absolute top-50 translate-middle-y ms-4 fs-3 text-gray-400"></i>
                                        </div>
                                        <div class="form-text text-gray-400 fs-7 mt-1.5">
                                            Imported contacts will be automatically assigned to this audience group segment.
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="d-flex justify-content-end gap-3 mt-6">
                                        <a href="{{ route('contacts.index') }}" class="btn btn-light font-bold px-5">Cancel</a>
                                        <button type="submit" class="btn btn-primary font-bold px-6 py-3 shadow-sm">
                                            <i class="ki-outline ki-upload fs-4 me-2"></i>Upload & Preview Mapping
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Format Guidelines Column -->
                    <div class="col-lg-4">
                        <!-- Guidelines Card -->
                        <div class="card border-0 shadow-sm overflow-hidden mb-5 bg-light-soft" style="border-radius: 1.25rem;">
                            <div class="card-header pt-5 pb-2 border-0 bg-transparent">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-5">File Structure Rules</span>
                                    <span class="text-gray-400 mt-1 fs-8">Header row requirements</span>
                                </h3>
                                <div class="card-toolbar">
                                    <i class="ki-outline ki-information-5 fs-2 text-primary"></i>
                                </div>
                            </div>
                            <div class="card-body pt-2 fs-7 text-gray-700">
                                <ul class="ps-4 mb-4">
                                    <li class="mb-2"><strong class="text-gray-900">Required:</strong> Names (first name / last name) and valid phone numbers.</li>
                                    <li class="mb-2"><strong class="text-gray-900">Optional:</strong> email, company, date_of_birth, gender, country, city, notes.</li>
                                    <li class="mb-2"><strong class="text-gray-900">Header Row:</strong> Row #1 must contain column titles.</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Sample Format Card -->
                        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 1.25rem;">
                            <div class="card-header pt-5 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-6">Sample Header Format</span>
                                </h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-row-bordered table-sm gy-2 gs-3 mb-0">
                                        <thead>
                                            <tr class="fs-8 font-bold text-gray-500 bg-light-soft">
                                                <th>first_name</th>
                                                <th>last_name</th>
                                                <th>phone</th>
                                                <th>email</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fs-8 font-mono text-gray-700">
                                            <tr>
                                                <td>John</td>
                                                <td>Doe</td>
                                                <td>233501234567</td>
                                                <td>john@ex.com</td>
                                            </tr>
                                            <tr>
                                                <td>Jane</td>
                                                <td>Smith</td>
                                                <td>233502345678</td>
                                                <td>jane@ex.com</td>
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
</div>
@endsection