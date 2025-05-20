@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Contact Details</h5>
            <div class="float-end">
                <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-sm btn-primary">Edit</a>
                <a href="{{ route('contacts.index') }}" class="btn btn-sm btn-secondary">Back to Contacts</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <div class="row">
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">Full Name</dt>
                        <dd class="col-sm-8">{{ $contact->full_name }}</dd>
                        
                        <dt class="col-sm-4">Phone Number</dt>
                        <dd class="col-sm-8">{{ $contact->phone_number }}</dd>
                        
                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ $contact->email ?: 'Not specified' }}</dd>
                        
                        <dt class="col-sm-4">Company</dt>
                        <dd class="col-sm-8">{{ $contact->company ?: 'Not specified' }}</dd>
                        
                        <dt class="col-sm-4">Created</dt>
                        <dd class="col-sm-8">{{ $contact->created_at->format('M d, Y') }}</dd>
                        
                        <dt class="col-sm-4">Last Updated</dt>
                        <dd class="col-sm-8">{{ $contact->updated_at->format('M d, Y') }}</dd>
                    </dl>
                </div>
                
                <div class="col-md-6">
                    <h6 class="mb-3">Groups</h6>
                    @if($contact->groups->count() > 0)
                        <ul class="list-group">
                            @foreach($contact->groups as $group)
                                <li class="list-group-item">
                                    <a href="{{ route('contact-groups.show', $group) }}">{{ $group->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">This contact is not in any groups.</p>
                    @endif
                    
                    @if($contact->notes)
                        <h6 class="mb-2 mt-4">Notes</h6>
                        <div class="card">
                            <div class="card-body bg-light">
                                {{ $contact->notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="mt-4">
                <h6 class="mb-3">Actions</h6>
                <div class="d-flex gap-2">
                    <form action="{{ route('contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this contact?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Contact</button>
                    </form>
                    
                    <a href="{{ route('sms.compose') }}?contact_id={{ $contact->id }}" class="btn btn-primary">
                        <i class="bi bi-chat-dots"></i> Send SMS
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection