@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Edit Contact Card -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Edit Contact</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Update contact information</span>
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

                        <form action="{{ route('contacts.update', $contact->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label required">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $contact->first_name) }}" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label required">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $contact->last_name) }}" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="phone_number" class="form-label required">Phone Number</label>
                                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $contact->phone_number) }}" required>
                                        <div class="form-text">Phone numbers will automatically be formatted with the country code (e.g. 233).</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $contact->email) }}">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $contact->date_of_birth ? $contact->date_of_birth->format('Y-m-d') : '') }}">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="company" class="form-label">Company</label>
                                        <input type="text" class="form-control" id="company" name="company" value="{{ old('company', $contact->company) }}">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Notes</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $contact->notes) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            
                            @if(count($groups) > 0)
                            <div class="mb-5">
                                <label class="form-label d-block">Contact Groups</label>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @foreach($groups as $id => $name)
                                        <div class="form-check form-check-custom form-check-solid me-5">
                                            <input class="form-check-input" type="checkbox" 
                                                id="group_{{ $id }}" 
                                                name="groups[]" 
                                                value="{{ $id }}"
                                                {{ in_array($id, $selectedGroups) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="group_{{ $id }}">{{ $name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('contacts.index') }}" class="btn btn-light me-3">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ki-outline ki-check fs-2 me-2"></i>Update Contact
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