@extends('layouts.master')

@section('title', 'Team Workspaces')

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
                                        <i class="ki-outline ki-people fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">Team Workspaces</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Collaboration</span>
                                    </div>
                                    <p class="text-gray-400 fs-7 mb-0">Manage shared organization environments, team members, and permissions.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('teams.create') }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                    <i class="ki-outline ki-plus me-1 fs-5"></i> Create New Team
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

@push('styles')
<style>
    .team-card {
        background: #ffffff;
        border-radius: 1.25rem !important;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        position: relative;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04) !important;
        border: 1px solid rgba(0, 0, 0, 0.04) !important;
    }
    
    [data-bs-theme="dark"] .team-card {
        background: #1e1e2d !important;
        border-color: rgba(255, 255, 255, 0.06) !important;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.25) !important;
    }
    
    .team-card:hover {
        transform: translateY(-4px) !important;
        box-shadow: 0 16px 32px -8px rgba(0, 0, 0, 0.08) !important;
    }

    [data-bs-theme="dark"] .team-card:hover {
        box-shadow: 0 16px 32px -8px rgba(0, 0, 0, 0.4) !important;
    }

    .team-avatar {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.25rem;
    }
</style>
@endpush

                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center p-4 mb-6 rounded-3 shadow-xs">
                        <i class="ki-outline ki-check-circle fs-2x text-success me-3"></i>
                        <span class="font-bold text-gray-800 fs-6">{{ session('success') }}</span>
                    </div>
                @endif

                @if($teams->isEmpty())
                    <div class="card card-flush border-0 shadow-sm">
                        <div class="card-body text-center py-12">
                            <div class="symbol symbol-70px symbol-circle bg-light-primary mb-4 p-3">
                                <i class="ki-outline ki-people fs-3hx text-primary"></i>
                            </div>
                            <h3 class="fs-2 font-extrabold text-gray-900 mb-2">No Team Workspaces Found</h3>
                            <p class="text-gray-400 fs-6 max-w-500px mx-auto mb-6">You haven't created or joined any team workspaces yet. Collaborate with team members by initializing your first workspace.</p>
                            <a href="{{ route('teams.create') }}" class="btn btn-primary font-bold px-6 shadow-sm">
                                <i class="ki-outline ki-plus fs-4 me-2"></i>Create Your First Team
                            </a>
                        </div>
                    </div>
                @else
                    <div class="row g-5 g-xl-6 mb-5">
                        @foreach($teams as $team)
                            @php
                                $isCurrent = ($user->current_team_id === $team->id);
                                $roleName = ($user->teamRole($team) === 'owner') ? 'Owner' : ucfirst($user->teamRole($team));
                            @endphp
                            <div class="col-md-6 col-lg-4">
                                <div class="card team-card h-100 overflow-hidden" style="border-top: 4px solid {{ $isCurrent ? '#0284c7' : '#94a3b8' }} !important;">
                                    <div class="card-body p-6 d-flex flex-column justify-content-between">
                                        <div>
                                            <!-- Top Header Info -->
                                            <div class="d-flex align-items-center justify-content-between mb-4">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="team-avatar text-white shadow-xs" style="background: {{ $isCurrent ? 'linear-gradient(135deg, #0284c7 0%, #0369a1 100%)' : 'linear-gradient(135deg, #475569 0%, #334155 100%)' }};">
                                                        {{ strtoupper(substr($team->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <h3 class="text-gray-900 font-extrabold fs-5 mb-0.5">{{ $team->name }}</h3>
                                                        <span class="badge badge-light-secondary fs-8 font-bold px-2 py-0.5 text-gray-600">{{ $roleName }}</span>
                                                    </div>
                                                </div>
                                                
                                                @if($isCurrent)
                                                    <span class="badge badge-light-primary font-bold fs-8 px-2.5 py-1 rounded-pill">
                                                        <i class="bullet bullet-dot bg-primary me-1.5"></i>Active
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Description -->
                                            <p class="text-gray-500 fs-7 font-medium mb-5">
                                                {{ $team->description ?? 'No detailed description provided for this team workspace.' }}
                                            </p>
                                        </div>

                                        <div>
                                            <!-- Metadata Pills -->
                                            <div class="d-flex align-items-center gap-2 mb-6">
                                                <div class="bg-light-soft border border-gray-200 rounded-2 px-3 py-1.5 d-flex align-items-center">
                                                    <i class="ki-outline ki-profile-user fs-5 text-primary me-2"></i>
                                                    <span class="text-gray-700 font-bold fs-7">{{ $team->users->count() }} {{ Str::plural('Member', $team->users->count()) }}</span>
                                                </div>
                                                @if($team->personal_team)
                                                    <div class="bg-light-soft border border-gray-200 rounded-2 px-3 py-1.5 d-flex align-items-center">
                                                        <i class="ki-outline ki-user fs-5 text-info me-2"></i>
                                                        <span class="text-gray-700 font-bold fs-7">Personal</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="d-flex flex-wrap align-items-center gap-2 pt-4 border-top border-gray-100">
                                                @if(!$isCurrent)
                                                    <form method="POST" action="{{ route('teams.switch', $team) }}" class="m-0">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary btn-sm font-bold shadow-xs px-3">
                                                            Switch To
                                                        </button>
                                                    </form>
                                                @endif

                                                <a href="{{ route('teams.show', $team) }}" class="btn btn-light-primary btn-sm font-bold px-3">
                                                    View Details
                                                </a>

                                                @if(!$team->personal_team && $user->teamRole($team) !== 'owner')
                                                    <form method="POST" action="{{ route('teams.leave', $team) }}" class="m-0 ms-auto" 
                                                          onsubmit="return confirm('Are you sure you want to leave this team?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-light-danger btn-sm font-bold px-3">
                                                            Leave
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection