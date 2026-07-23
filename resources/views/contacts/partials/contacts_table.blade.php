<!-- Results Summary -->
@if($contacts->count() > 0)
    <div class="d-flex justify-content-between align-items-center mb-4 px-1">
        <div class="text-gray-500 font-medium fs-7">
            <i class="ki-outline ki-information-5 fs-6 me-1 text-primary"></i>
            Showing <strong>{{ $contacts->firstItem() }}</strong> to <strong>{{ $contacts->lastItem() }}</strong> of <strong>{{ number_format($contacts->total()) }}</strong> contacts
        </div>
        @if(request('search') || request('group'))
            <div class="badge badge-light-primary font-bold fs-8 px-2.5 py-1">
                <i class="ki-outline ki-filter fs-7 me-1"></i>
                Filtered results
                @if(request('search'))
                    | Search: "{{ request('search') }}"
                @endif
            </div>
        @endif
    </div>
@endif

<div class="table-responsive">
    <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
        <thead>
            <tr class="fw-bold text-gray-400 fs-7 uppercase">
                <th class="min-w-25px">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAllContacts">
                    </div>
                </th>
                <th class="min-w-180px">Contact Name</th>
                <th class="min-w-130px">Phone Number</th>
                <th class="min-w-150px">Email Address</th>
                <th class="min-w-120px">Company</th>
                <th class="min-w-140px">Groups</th>
                <th class="min-w-130px text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contacts as $contact)
                <tr>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input contact-checkbox" type="checkbox" value="{{ $contact->id }}">
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40px me-3">
                                <span class="symbol-label bg-light-primary font-bold text-primary rounded-circle">
                                    {{ strtoupper(substr($contact->full_name, 0, 1)) }}
                                </span>
                            </div>
                            <div class="d-flex flex-column">
                                <a href="{{ route('contacts.show', $contact) }}" class="text-gray-900 font-bold text-hover-primary fs-6 mb-0">{{ $contact->full_name }}</a>
                                @if($contact->country || $contact->city)
                                    <span class="text-gray-400 font-medium fs-8">
                                        @if($contact->city){{ $contact->city }}@endif
                                        @if($contact->city && $contact->country), @endif
                                        @if($contact->country){{ $contact->country }}@endif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="font-mono text-gray-900 font-bold fs-7 d-block">{{ $contact->phone_number }}</span>
                        @if($contact->gender)
                            <span class="text-gray-400 font-medium fs-8">{{ ucfirst($contact->gender) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($contact->email)
                            <a href="mailto:{{ $contact->email }}" class="text-gray-700 text-hover-primary font-medium fs-7">{{ $contact->email }}</a>
                        @else
                            <span class="text-gray-400 fs-8">—</span>
                        @endif
                    </td>
                    <td>
                        @if($contact->company)
                            <span class="text-gray-800 font-semibold fs-7">{{ $contact->company }}</span>
                        @else
                            <span class="text-gray-400 fs-8">—</span>
                        @endif
                    </td>
                    <td>
                        @foreach($contact->groups as $group)
                            <span class="badge badge-light-info font-bold fs-8 px-2 py-0.5 me-1 mb-1">{{ $group->name }}</span>
                        @endforeach
                        @if($contact->groups->isEmpty())
                            <span class="text-gray-400 fs-8 font-medium">Ungrouped</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end align-items-center gap-1">
                            <a href="{{ route('sms.compose') }}?contact_id={{ $contact->id }}" 
                               class="btn btn-sm btn-icon btn-light-success rounded-circle" 
                               data-bs-toggle="tooltip" 
                               data-bs-placement="top" 
                               title="Send SMS to {{ $contact->full_name }}">
                                <i class="ki-outline ki-paper-plane fs-4"></i>
                            </a>
                            <a href="{{ route('contacts.edit', $contact) }}" 
                               class="btn btn-sm btn-icon btn-light-primary rounded-circle" 
                               data-bs-toggle="tooltip" 
                               data-bs-placement="top" 
                               title="Edit {{ $contact->full_name }}">
                                <i class="ki-outline ki-pencil fs-4"></i>
                            </a>
                            <form action="{{ route('contacts.destroy', $contact) }}" 
                                  method="POST" 
                                  class="d-inline-block m-0" 
                                  onsubmit="return confirm('Are you sure you want to delete {{ $contact->full_name }}? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-icon btn-light-danger rounded-circle" 
                                        type="submit"
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        title="Delete {{ $contact->full_name }}">
                                    <i class="ki-outline ki-trash fs-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-12">
                        <div class="d-flex flex-column align-items-center">
                            <div class="symbol symbol-70px symbol-circle bg-light-primary mb-4 p-3">
                                <i class="ki-outline ki-people fs-3hx text-primary"></i>
                            </div>
                            <h4 class="fs-3 font-extrabold text-gray-900 mb-2">
                                @if(request('search'))
                                    No contacts found for "{{ request('search') }}"
                                @elseif(request('group'))
                                    No contacts found in this group
                                @else
                                    No contacts in database
                                @endif
                            </h4>
                            <p class="text-gray-400 fs-6 max-w-500px mb-5">
                                @if(request('search'))
                                    Try adjusting your search query or clear the filter.
                                @elseif(request('group'))
                                    This contact group segment does not contain any contacts yet.
                                @else
                                    Get started by building your customer audience directory.
                                @endif
                            </p>
                            <div class="d-flex flex-center gap-3">
                                @if(request('search') || request('group'))
                                    <button type="button" class="btn btn-light-primary font-bold shadow-xs" onclick="clearFilters()">
                                        <i class="ki-outline ki-cross fs-4 me-2"></i>Clear Filters
                                    </button>
                                @else
                                    <a href="{{ route('contacts.create') }}" class="btn btn-primary font-bold shadow-sm">
                                        <i class="ki-outline ki-plus fs-4 me-2"></i>Add First Contact
                                    </a>
                                    <a href="{{ route('contacts.import') }}" class="btn btn-light-primary font-bold">
                                        <i class="ki-outline ki-cloud-add fs-4 me-2"></i>Import Contacts
                                    </a>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-6">
    {{ $contacts->links() }}
</div>
