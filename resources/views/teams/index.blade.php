@extends('layouts.master')

@section('title', 'Teams')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-12">
                        <div class="card card-flush">
                            <div class="card-header border-0 pt-6">
                                <div class="card-title">
                                    <h2 class="fw-bold">Teams</h2>
                                </div>
                                <div class="card-toolbar">
                                    <a href="{{ route('teams.create') }}" class="btn btn-primary">
                                        <i class="ki-outline ki-plus fs-2"></i>Create Team
                                    </a>
                                </div>
                            </div>
                            <div class="card-body py-4">
                                @if(session('success'))
                                    <div class="alert alert-success mb-4">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if($teams->isEmpty())
                                    <div class="text-center py-10">
                                        <div class="mb-3">
                                            <i class="ki-outline ki-abstract-26 fs-3tx text-muted"></i>
                                        </div>
                                        <h3 class="fs-2 fw-bold mb-3">No Teams Found</h3>
                                        <div class="text-muted mb-5">You haven't created or joined any teams yet.</div>
                                        <a href="{{ route('teams.create') }}" class="btn btn-primary">Create Your First Team</a>
                                    </div>
                                @else
                                    <div class="row g-5">
                                        @foreach($teams as $team)
                                            <div class="col-md-6 col-lg-4">
                                                <div class="card card-bordered h-100">
                                                    <div class="card-body d-flex flex-column">
                                                        <div class="d-flex align-items-center mb-5">
                                                            <div class="symbol symbol-50px me-5">
                                                                <div class="symbol-label bg-light-primary">
                                                                    <span class="fs-1 text-primary">{{ strtoupper(substr($team->name, 0, 1)) }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex flex-column">
                                                                <h3 class="text-gray-900 mb-1">{{ $team->name }}</h3>
                                                                <div class="text-muted">
                                                                    {{ $user->teamRole($team) === 'owner' ? 'Owner' : ucfirst($user->teamRole($team)) }}
                                                                </div>
                                                            </div>
                                                            @if($user->current_team_id === $team->id)
                                                                <span class="badge badge-light-success ms-auto">Current</span>
                                                            @endif
                                                        </div>
                                                        
                                                        <div class="mb-5">
                                                            <div class="text-gray-600">
                                                                {{ $team->description ?? 'No description available' }}
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="d-flex align-items-center mb-5">
                                                            <div class="border border-dashed border-gray-300 rounded px-3 py-2 d-flex align-items-center me-3">
                                                                <i class="ki-outline ki-profile-user fs-2 text-gray-500 me-2"></i>
                                                                <span class="text-gray-700">{{ $team->users->count() + 1 }} Members</span>
                                                            </div>
                                                            @if($team->personal_team)
                                                                <div class="border border-dashed border-gray-300 rounded px-3 py-2 d-flex align-items-center">
                                                                    <i class="ki-outline ki-user fs-2 text-gray-500 me-2"></i>
                                                                    <span class="text-gray-700">Personal</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        
                                                        <div class="d-flex flex-wrap mt-auto">
                                                            @if($user->current_team_id !== $team->id)
                                                                <form method="POST" action="{{ route('teams.switch', $team) }}" class="me-2 mb-2">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                                        Switch To
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            
                                                            <a href="{{ route('teams.show', $team) }}" class="btn btn-light-primary btn-sm me-2 mb-2">
                                                                View Details
                                                            </a>
                                                            
                                                            @if(!$team->personal_team && $user->teamRole($team) !== 'owner')
                                                                <form method="POST" action="{{ route('teams.leave', $team) }}" class="mb-2" 
                                                                      onsubmit="return confirm('Are you sure you want to leave this team?');">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-light-danger btn-sm">
                                                                        Leave Team
                                                                    </button>
                                                                </form>
                                                            @endif
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
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
</div>
@endsection