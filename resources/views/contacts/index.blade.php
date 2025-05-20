@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">My Contacts</h5>
            <div class="float-end">
                <a href="{{ route('contacts.create') }}" class="btn btn-sm btn-primary">Add Contact</a>
                <a href="{{ route('contacts.import') }}" class="btn btn-sm btn-secondary">Import</a>
                <button class="btn btn-sm btn-secondary" id="exportContactsBtn">Export</button>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @if(session('errors') && is_array(session('errors')) && count(session('errors')) > 0)
                <div class="alert alert-danger">
                    <p><strong>The following errors occurred:</strong></p>
                    <ul>
                        @foreach(session('errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div id="exportOptions" class="mb-4" style="display: none;">
                <div class="card">
                    <div class="card-body bg-light">
                        <h6 class="mb-3">Export Options</h6>
                        <form action="{{ route('contacts.export') }}" method="GET">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="export_type" id="exportAll" value="all" checked>
                                        <label class="form-check-label" for="exportAll">
                                            Export all contacts
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="export_type" id="exportSelected" value="selected">
                                        <label class="form-check-label" for="exportSelected">
                                            Export selected contacts
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="groupFilter" class="form-label">Filter by group (optional)</label>
                                    <select class="form-select form-select-sm" name="group_id" id="groupFilter">
                                        <option value="">All groups</option>
                                        @foreach(Auth::user()->contactGroups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary mt-4">Export Contacts</button>
                                </div>
                            </div>
                            <input type="hidden" name="selected_contacts" id="selectedContactIds" value="">
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllContacts">
                                </div>
                            </th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Company</th>
                            <th>Groups</th>
                            <th>Actions</th>
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
                                <td><a href="{{ route('contacts.show', $contact) }}">{{ $contact->full_name }}</a></td>
                                <td>{{ $contact->phone_number }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ $contact->company }}</td>
                                <td>{{ $contact->groups->pluck('name')->join(', ') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-sm btn-secondary">Edit</a>
                                        <form action="{{ route('contacts.destroy', $contact) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this contact?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                        <a href="{{ route('sms.compose') }}?contact_id={{ $contact->id }}" class="btn btn-sm btn-primary">SMS</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">No contacts found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{ $contacts->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle export options panel
        const exportBtn = document.getElementById('exportContactsBtn');
        const exportOptions = document.getElementById('exportOptions');
        
        exportBtn.addEventListener('click', function() {
            exportOptions.style.display = exportOptions.style.display === 'none' ? 'block' : 'none';
        });
        
        // Select all checkbox functionality
        const selectAllCheckbox = document.getElementById('selectAllContacts');
        const contactCheckboxes = document.querySelectorAll('.contact-checkbox');
        
        selectAllCheckbox.addEventListener('change', function() {
            contactCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateSelectedContactsInput();
        });
        
        contactCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSelectedContactsInput();
            });
        });
        
        // Update hidden input with selected contact IDs
        function updateSelectedContactsInput() {
            const selectedIds = Array.from(contactCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);
            
            document.getElementById('selectedContactIds').value = selectedIds.join(',');
            
            // Auto-select "Export selected" when contacts are checked
            if (selectedIds.length > 0) {
                document.getElementById('exportSelected').checked = true;
            } else {
                document.getElementById('exportAll').checked = true;
            }
        }
    });
</script>
@endpush