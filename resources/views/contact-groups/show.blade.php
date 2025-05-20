@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ $group->name }}</h5>
            <div class="float-end">
                <a href="{{ route('contact-groups.edit', $group) }}" class="btn btn-sm btn-primary">Edit Group</a>
                <a href="{{ route('contact-groups.index') }}" class="btn btn-sm btn-secondary">Back to Groups</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <dl>
                        <dt>Description</dt>
                        <dd class="mb-3">{{ $group->description ?: 'No description' }}</dd>
                        
                        <dt>Total Contacts</dt>
                        <dd class="mb-3">{{ $contacts->total() }}</dd>
                        
                        <dt>Created</dt>
                        <dd class="mb-3">{{ $group->created_at->format('M d, Y') }}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-2 mb-3">
                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addContactsModal">Add Contacts</a>
                        <a href="{{ route('sms.compose') }}?group_id={{ $group->id }}" class="btn btn-sm btn-success">
                            <i class="bi bi-chat-dots"></i> Send SMS to Group
                        </a>
                        <form action="{{ route('contact-groups.destroy', $group) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this group? This will not delete the contacts.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete Group</button>
                        </form>
                    </div>
                    <a href="{{ route('contacts.export') }}?group_id={{ $group->id }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-download"></i> Export Contacts
                    </a>
                </div>
            </div>

            <h6 class="mb-3">Contacts in this group</h6>
            
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contacts as $contact)
                            <tr>
                                <td><a href="{{ route('contacts.show', $contact) }}">{{ $contact->full_name }}</a></td>
                                <td>{{ $contact->phone_number }}</td>
                                <td>{{ $contact->email ?: 'N/A' }}</td>
                                <td>
                                    <form action="{{ route('contact-groups.remove-contact', [$group->id, $contact->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove this contact from the group?')">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No contacts in this group yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{ $contacts->links() }}
        </div>
    </div>
</div>

<!-- Add Contacts Modal -->
<div class="modal fade" id="addContactsModal" tabindex="-1" aria-labelledby="addContactsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addContactsModalLabel">Add Contacts to Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('contact-groups.store-contacts', $group->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Select contacts to add to <strong>{{ $group->name }}</strong>.</p>
                    
                    <!-- To be implemented: a livewire or AJAX component that loads contacts not in the group yet -->
                    <div class="alert alert-info">
                        To add contacts to this group, you need to select them from your contact list first.
                        <a href="{{ route('contacts.index') }}" class="alert-link">Go to Contacts</a>
                    </div>
                    
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" id="select-all">
                        <label class="form-check-label" for="select-all">
                            Select All
                        </label>
                    </div>
                    
                    <div class="contact-list mt-3" style="max-height: 300px; overflow-y: auto;">
                        <!-- This would be populated via AJAX/Livewire -->
                        <div class="alert alert-warning">
                            Contact selection feature will be implemented with AJAX soon.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Selected Contacts</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Select all checkbox functionality
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                document.querySelectorAll('.contact-checkbox').forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });
        }
    });
</script>
@endpush