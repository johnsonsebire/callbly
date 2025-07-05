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

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $contact->email) }}">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $contact->date_of_birth ? $contact->date_of_birth->format('Y-m-d') : '') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-select" id="gender" name="gender">
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ old('gender', $contact->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender', $contact->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender', $contact->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="company" class="form-label">Company</label>
                                        <input type="text" class="form-control" id="company" name="company" value="{{ old('company', $contact->company) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="country" class="form-label">Country</label>
                                        <input type="text" class="form-control" id="country" name="country" value="{{ old('country', $contact->country) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="region" class="form-label">Region/State</label>
                                        <input type="text" class="form-control" id="region" name="region" value="{{ old('region', $contact->region) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $contact->city) }}">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Notes</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $contact->notes) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Custom Fields Section -->
                            @if($customFields->count() > 0)
                            <div class="separator border-2 my-10"></div>
                            <div class="mb-10">
                                <h3 class="text-dark fw-bold mb-5">Additional Information</h3>
                                <div class="row">
                                    @foreach($customFields as $customField)
                                        <div class="col-md-6 mb-5">
                                            <label for="custom_{{ $customField->name }}" class="form-label {{ $customField->is_required ? 'required' : '' }}">
                                                {{ $customField->label }}
                                            </label>
                                            
                                            @php
                                                $currentValue = old('custom_fields.'.$customField->name, 
                                                    $contact->custom_fields[$customField->name] ?? '');
                                            @endphp
                                            
                                            @if($customField->type === 'text')
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="custom_{{ $customField->name }}" 
                                                       name="custom_fields[{{ $customField->name }}]" 
                                                       value="{{ $currentValue }}"
                                                       placeholder="{{ $customField->placeholder }}"
                                                       {{ $customField->is_required ? 'required' : '' }}>
                                            @elseif($customField->type === 'email')
                                                <input type="email" 
                                                       class="form-control" 
                                                       id="custom_{{ $customField->name }}" 
                                                       name="custom_fields[{{ $customField->name }}]" 
                                                       value="{{ $currentValue }}"
                                                       placeholder="{{ $customField->placeholder }}"
                                                       {{ $customField->is_required ? 'required' : '' }}>
                                            @elseif($customField->type === 'phone')
                                                <input type="tel" 
                                                       class="form-control" 
                                                       id="custom_{{ $customField->name }}" 
                                                       name="custom_fields[{{ $customField->name }}]" 
                                                       value="{{ $currentValue }}"
                                                       placeholder="{{ $customField->placeholder }}"
                                                       {{ $customField->is_required ? 'required' : '' }}>
                                            @elseif($customField->type === 'number')
                                                <input type="number" 
                                                       class="form-control" 
                                                       id="custom_{{ $customField->name }}" 
                                                       name="custom_fields[{{ $customField->name }}]" 
                                                       value="{{ $currentValue }}"
                                                       placeholder="{{ $customField->placeholder }}"
                                                       {{ $customField->is_required ? 'required' : '' }}>
                                            @elseif($customField->type === 'date')
                                                <input type="date" 
                                                       class="form-control" 
                                                       id="custom_{{ $customField->name }}" 
                                                       name="custom_fields[{{ $customField->name }}]" 
                                                       value="{{ $currentValue }}"
                                                       {{ $customField->is_required ? 'required' : '' }}>
                                            @elseif($customField->type === 'url')
                                                <input type="url" 
                                                       class="form-control" 
                                                       id="custom_{{ $customField->name }}" 
                                                       name="custom_fields[{{ $customField->name }}]" 
                                                       value="{{ $currentValue }}"
                                                       placeholder="{{ $customField->placeholder }}"
                                                       {{ $customField->is_required ? 'required' : '' }}>
                                            @elseif($customField->type === 'select')
                                                <select class="form-select" 
                                                        id="custom_{{ $customField->name }}" 
                                                        name="custom_fields[{{ $customField->name }}]"
                                                        {{ $customField->is_required ? 'required' : '' }}>
                                                    <option value="">Select option</option>
                                                    @if($customField->options)
                                                        @foreach($customField->options as $option)
                                                            <option value="{{ $option }}" {{ $currentValue == $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            @elseif($customField->type === 'textarea')
                                                <textarea class="form-control" 
                                                          id="custom_{{ $customField->name }}" 
                                                          name="custom_fields[{{ $customField->name }}]" 
                                                          rows="3"
                                                          placeholder="{{ $customField->placeholder }}"
                                                          {{ $customField->is_required ? 'required' : '' }}>{{ $currentValue }}</textarea>
                                            @elseif($customField->type === 'checkbox')
                                                <div class="form-check form-check-custom form-check-solid">
                                                    <input class="form-check-input" 
                                                           type="checkbox" 
                                                           id="custom_{{ $customField->name }}" 
                                                           name="custom_fields[{{ $customField->name }}]" 
                                                           value="1"
                                                           {{ $currentValue ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="custom_{{ $customField->name }}">
                                                        {{ $customField->placeholder ?: 'Yes' }}
                                                    </label>
                                                </div>
                                            @endif
                                            
                                            @if($customField->description)
                                                <div class="form-text">{{ $customField->description }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
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