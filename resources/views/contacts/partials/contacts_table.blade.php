<!-- Results Summary -->
@if($contacts->count() > 0)
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="text-muted">
            <i class="ki-outline ki-information-5 fs-6 me-1"></i>
            Showing {{ $contacts->firstItem() }} to {{ $contacts->lastItem() }} of {{ $contacts->total() }} contacts
        </div>
        @if(request('search') || request('group'))
            <div class="text-muted fs-7">
                <i class="ki-outline ki-filter fs-6 me-1"></i>
                Filtered results
                @if(request('search'))
                    | Search: "{{ request('search') }}"
                @endif
            </div>
        @endif
    </div>
@endif

<div class="table-responsive">
    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
        <thead>
            <tr class="fw-bold text-muted">
                <th class="min-w-25px">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAllContacts">
                    </div>
                </th>
                <th class="min-w-150px">Name</th>
                <th class="min-w-120px">Phone</th>
                <th class="min-w-150px">Email</th>
                <th class="min-w-120px">Company</th>
                <th class="min-w-150px">Groups</th>
                <th class="min-w-150px text-end">Actions</th>
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
                            <div class="symbol symbol-45px me-3">
                                <span class="symbol-label bg-light-primary text-primary">
                                    {{ substr($contact->full_name, 0, 1) }}
                                </span>
                            </div>
                            <div class="d-flex flex-column">
                                <a href="{{ route('contacts.show', $contact) }}" class="text-dark text-hover-primary fw-bold">{{ $contact->full_name }}</a>
                                @if($contact->country || $contact->city)
                                    <span class="text-muted fs-7">
                                        @if($contact->city){{ $contact->city }}@endif
                                        @if($contact->city && $contact->country), @endif
                                        @if($contact->country){{ $contact->country }}@endif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="text-dark fw-bold d-block">{{ $contact->phone_number }}</span>
                        @if($contact->gender)
                            <span class="text-muted fs-7">{{ ucfirst($contact->gender) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($contact->email)
                            <a href="mailto:{{ $contact->email }}" class="text-dark text-hover-primary">{{ $contact->email }}</a>
                        @else
                            <span class="text-muted">No email</span>
                        @endif
                    </td>
                    <td>
                        @if($contact->company)
                            <span class="text-dark">{{ $contact->company }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @foreach($contact->groups as $group)
                            <span class="badge badge-light-primary fs-7 fw-semibold me-1">{{ $group->name }}</span>
                        @endforeach
                        @if($contact->groups->isEmpty())
                            <span class="text-muted fs-7">No groups</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end align-items-center">
                            <a href="{{ route('contacts.edit', $contact) }}" 
                               class="btn btn-sm btn-light-primary me-1" 
                               data-bs-toggle="tooltip" 
                               data-bs-placement="top" 
                               title="Edit {{ $contact->full_name }}">
                                <i class="ki-outline ki-pencil fs-2"></i>
                            </a>
                            <form action="{{ route('contacts.destroy', $contact) }}" 
                                  method="POST" 
                                  class="d-inline-block" 
                                  onsubmit="return confirm('Are you sure you want to delete {{ $contact->full_name }}? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-light-danger me-1" 
                                        type="submit"
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        title="Delete {{ $contact->full_name }}">
                                    <i class="ki-outline ki-trash fs-2"></i>
                                </button>
                            </form>
                            <a href="{{ route('sms.compose') }}?contact_id={{ $contact->id }}" 
                               class="btn btn-sm btn-light-success" 
                               data-bs-toggle="tooltip" 
                               data-bs-placement="top" 
                               title="Send SMS to {{ $contact->full_name }}">
                                <i class="ki-outline ki-message-text-2 fs-2"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-15">
                        <div class="d-flex flex-column align-items-center">
                            <div class="symbol symbol-100px symbol-circle mb-7">
                                <div class="symbol-label fs-2x fw-semibold text-gray-400 bg-light-primary">
                                    <i class="ki-outline ki-people fs-2tx text-primary"></i>
                                </div>
                            </div>
                            <div class="text-center">
                                <h4 class="fw-semibold text-gray-800 mb-3">
                                    @if(request('search'))
                                        No contacts found for "{{ request('search') }}"
                                    @elseif(request('group'))
                                        No contacts in this group
                                    @else
                                        No contacts found
                                    @endif
                                </h4>
                                <p class="text-gray-400 fs-6 mb-5">
                                    @if(request('search'))
                                        Try adjusting your search criteria or clear the search to see all contacts.
                                    @elseif(request('group'))
                                        This group doesn't have any contacts yet. Try selecting a different group or add contacts to this group.
                                    @else
                                        Get started by adding your first contact or importing contacts from a file.
                                    @endif
                                </p>
                                <div class="d-flex flex-center gap-3">
                                    @if(request('search') || request('group'))
                                        <button type="button" class="btn btn-light-primary" onclick="clearFilters()">
                                            <i class="ki-outline ki-cross fs-2 me-2"></i>Clear Filters
                                        </button>
                                    @else
                                        <a href="{{ route('contacts.create') }}" class="btn btn-primary">
                                            <i class="ki-outline ki-plus-square fs-2 me-2"></i>Add First Contact
                                        </a>
                                        <a href="{{ route('contacts.import') }}" class="btn btn-light-primary">
                                            <i class="ki-outline ki-cloud-add fs-2 me-2"></i>Import Contacts
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $contacts->links() }}
</div>
