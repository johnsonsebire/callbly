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
                        <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-sm btn-light-primary me-1" title="Edit">
                            <i class="ki-outline ki-pencil fs-2"></i>
                        </a>
                        <form action="{{ route('contacts.destroy', $contact) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this contact?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-light-danger me-1" title="Delete">
                                <i class="ki-outline ki-trash fs-2"></i>
                            </button>
                        </form>
                        <a href="{{ route('sms.compose') }}?contact_id={{ $contact->id }}" class="btn btn-sm btn-light-success" title="Send SMS">
                            <i class="ki-outline ki-message-text-2 fs-2"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-10">
                        <div class="d-flex flex-column align-items-center">
                            <i class="ki-outline ki-people fs-2tx text-gray-300 mb-5"></i>
                            <span class="text-gray-600 fs-5 fw-semibold">No contacts found</span>
                            <span class="text-gray-400 fs-7">
                                @if(request('search'))
                                    Try adjusting your search criteria
                                @else
                                    Add contacts to get started
                                @endif
                            </span>
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
