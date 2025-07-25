@extends('layouts.master')

@section('title', $team->name)

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                @if(session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="card mb-5">
                    <div class="card-body py-5">
                        <div class="d-flex align-items-center flex-wrap mb-5">
                            <div class="symbol symbol-60px me-5 mb-2">
                                <div class="symbol-label bg-light-primary">
                                    <span class="fs-1 text-primary">{{ strtoupper(substr($team->name, 0, 1)) }}</span>
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-grow-1 mb-2">
                                <div class="d-flex align-items-center mb-1">
                                    <h2 class="text-gray-900 fw-bold me-2">{{ $team->name }}</h2>
                                    @if($team->personal_team)
                                        <span class="badge badge-light-primary">Personal Team</span>
                                    @endif
                                </div>
                                <span class="text-muted fw-semibold d-block">{{ $team->description }}</span>
                            </div>
                            <div class="d-flex flex-wrap mt-3 mt-md-0 mb-2">
                                @if(Auth::user()->can('update-team', $team))
                                    <a href="{{ route('teams.edit', $team) }}" class="btn btn-light-primary me-2 mb-2">
                                        <i class="ki-outline ki-edit fs-2 me-1"></i>Edit Team
                                    </a>
                                @endif
                                
                                @if(Auth::user()->can('invite-to-team', $team))
                                    <a href="{{ route('teams.invitations.create', $team) }}" class="btn btn-light-info me-2 mb-2">
                                        <i class="ki-outline ki-people fs-2 me-1"></i>Invite Members
                                    </a>
                                @endif
                                
                                @if(Auth::user()->can('delete-team', $team) && !$team->personal_team)
                                    <form method="POST" action="{{ route('teams.destroy', $team) }}" class="mb-2"
                                          onsubmit="return confirm('Are you sure you want to delete this team? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-light-danger">
                                            <i class="ki-outline ki-trash fs-2 me-1"></i>Delete Team
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <div class="separator separator-dashed my-5"></div>

                        <div class="d-flex flex-wrap">
                            <div class="border border-dashed border-gray-300 rounded py-3 px-4 me-6 mb-3">
                                <div class="fs-6 text-gray-800 fw-bold">{{ $team->users->count() }}</div>
                                <div class="fw-semibold text-gray-500">Team Members</div>
                            </div>
                            
                            <div class="border border-dashed border-gray-300 rounded py-3 px-4 me-6 mb-3">
                                <div class="fs-6 text-gray-800 fw-bold">{{ $invitations->count() }}</div>
                                <div class="fw-semibold text-gray-500">Pending Invitations</div>
                            </div>

                            <div class="border border-dashed border-gray-300 rounded py-3 px-4 me-6 mb-3">
                                <div class="fs-6 text-gray-800 fw-bold">{{ $availableSenderNames->count() }}</div>
                                <div class="fw-semibold text-gray-500">Sender Names</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team Settings -->
                <div class="card mb-5">
                    <div class="card-header border-0 pt-6">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800">Team Settings</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">Resource sharing configuration</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(Auth::user()->can('update-team', $team))
                            <x-teams.settings-form :team="$team" />
                        @else
                            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6">
                                <i class="ki-outline ki-information-5 fs-2tx text-primary me-4"></i>
                                <div class="d-flex flex-stack flex-grow-1">
                                    <div class="fw-semibold">
                                        <h4 class="text-gray-900 fw-bold">Team Resource Sharing</h4>
                                        <div class="fs-6 text-gray-700">
                                            <p class="mb-2">This team has the following resource sharing settings:</p>
                                            <ul class="mb-0">
                                                <li>SMS Credits: {{ $team->share_sms_credits ? 'Shared' : 'Not shared' }}</li>
                                                <li>Contacts: {{ $team->share_contacts ? 'Shared' : 'Not shared' }}</li>
                                                <li>Sender Names: {{ $team->share_sender_names ? 'Shared' : 'Not shared' }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Team Members -->
                <div class="card mb-5">
                    <div class="card-header border-0 pt-6">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800">Team Members</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">People with access to this team</span>
                        </h3>
                        @if(Auth::user()->can('invite-to-team', $team))
                            <div class="card-toolbar">
                                <a href="{{ route('teams.invitations.create', $team) }}" class="btn btn-primary">
                                    <i class="ki-outline ki-plus fs-2"></i>Invite Member
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="card-body pt-0">
                        <!-- Search and Filter for Team Members -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="d-flex align-items-center">
                                <div class="input-group input-group-sm me-3" style="max-width: 300px;">
                                    <span class="input-group-text">
                                        <i class="ki-outline ki-magnifier fs-5"></i>
                                    </span>
                                    <input type="text" id="membersSearchInput" class="form-control" 
                                           placeholder="Search members by name or email...">
                                    <button class="btn btn-outline-secondary" type="button" id="clearMembersSearch">
                                        <i class="ki-outline ki-cross-circle fs-6"></i>
                                    </button>
                                </div>
                                <select id="membersRoleFilter" class="form-select form-select-sm" style="max-width: 150px;">
                                    <option value="">All Roles</option>
                                    <option value="owner">Owner</option>
                                    <option value="admin">Admin</option>
                                    <option value="member">Member</option>
                                </select>
                            </div>
                            <div>
                                <span id="membersResultCount" class="badge bg-light text-dark">{{ $team->users->count() }} member(s)</span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="membersTable">
                                <thead>
                                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-125px">User</th>
                                        <th class="min-w-125px">Role</th>
                                        <th class="min-w-125px">Joined</th>
                                        <th class="text-end min-w-100px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-semibold">
                                    <!-- Team Owner -->
                                    <tr class="member-row" data-member-name="{{ strtolower($team->owner->name) }}" 
                                        data-member-email="{{ strtolower($team->owner->email) }}" 
                                        data-member-role="owner">
                                        <td class="d-flex align-items-center">
                                            <div class="symbol symbol-circle symbol-40px me-3">
                                                <div class="symbol-label bg-light-primary">
                                                    <span class="text-primary">{{ strtoupper(substr($team->owner->name, 0, 1)) }}</span>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span>{{ $team->owner->name }}</span>
                                                <span class="text-muted">{{ $team->owner->email }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-primary">Owner</span>
                                        </td>
                                        <td>{{ $team->created_at->format('M d, Y') }}</td>
                                        <td class="text-end">
                                            <span class="text-muted">Team Owner</span>
                                        </td>
                                    </tr>
                                    
                                    <!-- Team Members -->
                                    @foreach($members as $member)
                                        @if($member->id !== $team->owner_id)
                                            <tr class="member-row" data-member-name="{{ strtolower($member->name) }}" 
                                                data-member-email="{{ strtolower($member->email) }}" 
                                                data-member-role="{{ $member->pivot->role }}">
                                                <td class="d-flex align-items-center">
                                                    <div class="symbol symbol-circle symbol-40px me-3">
                                                        <div class="symbol-label bg-light-primary">
                                                            <span class="text-primary">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span>{{ $member->name }}</span>
                                                        <span class="text-muted">{{ $member->email }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if(Auth::user()->can('update-team-member', $team))
                                                        <form method="POST" action="{{ route('teams.members.update', [$team, $member]) }}" id="role-form-{{ $member->id }}">
                                                            @csrf
                                                            <select name="role" class="form-select form-select-sm form-select-solid role-select" 
                                                                    data-member-id="{{ $member->id }}">
                                                                <option value="member" {{ $member->pivot->role === 'member' ? 'selected' : '' }}>Member</option>
                                                                <option value="admin" {{ $member->pivot->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                            </select>
                                                        </form>
                                                    @else
                                                        <span class="badge badge-light-{{ $member->pivot->role === 'admin' ? 'success' : 'info' }}">
                                                            {{ ucfirst($member->pivot->role) }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{ $member->pivot->created_at->format('M d, Y') }}</td>
                                                <td class="text-end">
                                                    @if(Auth::user()->can('remove-team-member', $team))
                                                        <form method="POST" action="{{ route('teams.members.destroy', [$team, $member]) }}" 
                                                              onsubmit="return confirm('Are you sure you want to remove this member from the team?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-icon btn-sm btn-light-danger">
                                                                <i class="ki-outline ki-trash fs-2"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- No results message for members -->
                            <div id="noMembersResults" class="text-center py-4" style="display: none;">
                                <i class="ki-outline ki-information-5 fs-3x text-muted mb-3"></i>
                                <p class="text-muted">No team members found matching your search criteria.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Invitations -->
                @if($invitations->count() > 0 && Auth::user()->can('invite-to-team', $team))
                    <div class="card mb-5">
                        <div class="card-header border-0 pt-6">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-800">Pending Invitations</span>
                                <span class="text-gray-500 mt-1 fw-semibold fs-6">People invited to join this team</span>
                            </h3>
                        </div>
                        <div class="card-body pt-0">
                            <!-- Search and Filter for Pending Invitations -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="input-group input-group-sm me-3" style="max-width: 300px;">
                                        <span class="input-group-text">
                                            <i class="ki-outline ki-magnifier fs-5"></i>
                                        </span>
                                        <input type="text" id="invitationsSearchInput" class="form-control" 
                                               placeholder="Search invitations by email...">
                                        <button class="btn btn-outline-secondary" type="button" id="clearInvitationsSearch">
                                            <i class="ki-outline ki-cross-circle fs-6"></i>
                                        </button>
                                    </div>
                                    <select id="invitationsRoleFilter" class="form-select form-select-sm" style="max-width: 150px;">
                                        <option value="">All Roles</option>
                                        <option value="admin">Admin</option>
                                        <option value="member">Member</option>
                                    </select>
                                </div>
                                <div>
                                    <span id="invitationsResultCount" class="badge bg-light text-dark">{{ $invitations->count() }} invitation(s)</span>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="invitationsTable">
                                    <thead>
                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                            <th class="min-w-125px">Email</th>
                                            <th class="min-w-125px">Role</th>
                                            <th class="min-w-125px">Invited On</th>
                                            <th class="min-w-125px">Expires</th>
                                            <th class="text-end min-w-100px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600 fw-semibold">
                                        @foreach($invitations as $invitation)
                                            <tr class="invitation-row" data-invitation-email="{{ strtolower($invitation->email) }}" 
                                                data-invitation-role="{{ $invitation->role }}">
                                                <td>{{ $invitation->email }}</td>
                                                <td>
                                                    <span class="badge badge-light-{{ $invitation->role === 'admin' ? 'success' : 'info' }}">
                                                        {{ ucfirst($invitation->role) }}
                                                    </span>
                                                </td>
                                                <td>{{ $invitation->created_at->format('M d, Y') }}</td>
                                                <td>{{ $invitation->expires_at->format('M d, Y') }}</td>
                                                <td class="text-end">
                                                    <form method="POST" action="{{ route('teams.invitations.destroy', [$team, $invitation]) }}" 
                                                          onsubmit="return confirm('Are you sure you want to cancel this invitation?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-icon btn-sm btn-light-danger">
                                                            <i class="ki-outline ki-trash fs-2"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- No results message for invitations -->
                                <div id="noInvitationsResults" class="text-center py-4" style="display: none;">
                                    <i class="ki-outline ki-information-5 fs-3x text-muted mb-3"></i>
                                    <p class="text-muted">No pending invitations found matching your search criteria.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit role change forms
        const roleSelects = document.querySelectorAll('.role-select');
        roleSelects.forEach(select => {
            select.addEventListener('change', function() {
                const memberId = this.dataset.memberId;
                document.getElementById(`role-form-${memberId}`).submit();
            });
        });

        // Team Members Search and Filter
        const membersSearchInput = document.getElementById('membersSearchInput');
        const membersRoleFilter = document.getElementById('membersRoleFilter');
        const clearMembersSearch = document.getElementById('clearMembersSearch');
        const membersResultCount = document.getElementById('membersResultCount');
        const noMembersResults = document.getElementById('noMembersResults');
        const membersTable = document.getElementById('membersTable');

        // Invitations Search and Filter
        const invitationsSearchInput = document.getElementById('invitationsSearchInput');
        const invitationsRoleFilter = document.getElementById('invitationsRoleFilter');
        const clearInvitationsSearch = document.getElementById('clearInvitationsSearch');
        const invitationsResultCount = document.getElementById('invitationsResultCount');
        const noInvitationsResults = document.getElementById('noInvitationsResults');
        const invitationsTable = document.getElementById('invitationsTable');

        // Members search and filter functionality
        function filterMembers() {
            const searchTerm = membersSearchInput ? membersSearchInput.value.toLowerCase() : '';
            const roleFilter = membersRoleFilter ? membersRoleFilter.value.toLowerCase() : '';
            const memberRows = document.querySelectorAll('.member-row');
            let visibleCount = 0;

            memberRows.forEach(row => {
                const memberName = row.dataset.memberName || '';
                const memberEmail = row.dataset.memberEmail || '';
                const memberRole = row.dataset.memberRole || '';

                const matchesSearch = !searchTerm || 
                    memberName.includes(searchTerm) || 
                    memberEmail.includes(searchTerm);
                
                const matchesRole = !roleFilter || memberRole === roleFilter;

                if (matchesSearch && matchesRole) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update count and show/hide no results message
            if (membersResultCount) {
                membersResultCount.textContent = `${visibleCount} member(s)`;
            }

            if (noMembersResults && membersTable) {
                if (visibleCount === 0) {
                    noMembersResults.style.display = 'block';
                    membersTable.style.display = 'none';
                } else {
                    noMembersResults.style.display = 'none';
                    membersTable.style.display = '';
                }
            }
        }

        // Invitations search and filter functionality
        function filterInvitations() {
            const searchTerm = invitationsSearchInput ? invitationsSearchInput.value.toLowerCase() : '';
            const roleFilter = invitationsRoleFilter ? invitationsRoleFilter.value.toLowerCase() : '';
            const invitationRows = document.querySelectorAll('.invitation-row');
            let visibleCount = 0;

            invitationRows.forEach(row => {
                const invitationEmail = row.dataset.invitationEmail || '';
                const invitationRole = row.dataset.invitationRole || '';

                const matchesSearch = !searchTerm || invitationEmail.includes(searchTerm);
                const matchesRole = !roleFilter || invitationRole === roleFilter;

                if (matchesSearch && matchesRole) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update count and show/hide no results message
            if (invitationsResultCount) {
                invitationsResultCount.textContent = `${visibleCount} invitation(s)`;
            }

            if (noInvitationsResults && invitationsTable) {
                if (visibleCount === 0) {
                    noInvitationsResults.style.display = 'block';
                    invitationsTable.style.display = 'none';
                } else {
                    noInvitationsResults.style.display = 'none';
                    invitationsTable.style.display = '';
                }
            }
        }

        // Event listeners for members
        if (membersSearchInput) {
            membersSearchInput.addEventListener('input', filterMembers);
        }

        if (membersRoleFilter) {
            membersRoleFilter.addEventListener('change', filterMembers);
        }

        if (clearMembersSearch) {
            clearMembersSearch.addEventListener('click', function() {
                if (membersSearchInput) {
                    membersSearchInput.value = '';
                }
                if (membersRoleFilter) {
                    membersRoleFilter.value = '';
                }
                filterMembers();
            });
        }

        // Event listeners for invitations
        if (invitationsSearchInput) {
            invitationsSearchInput.addEventListener('input', filterInvitations);
        }

        if (invitationsRoleFilter) {
            invitationsRoleFilter.addEventListener('change', filterInvitations);
        }

        if (clearInvitationsSearch) {
            clearInvitationsSearch.addEventListener('click', function() {
                if (invitationsSearchInput) {
                    invitationsSearchInput.value = '';
                }
                if (invitationsRoleFilter) {
                    invitationsRoleFilter.value = '';
                }
                filterInvitations();
            });
        }

        // Initial filter application
        filterMembers();
        filterInvitations();
    });
</script>
@endpush