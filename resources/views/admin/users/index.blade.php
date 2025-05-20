@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h5 class="mb-0">Manage Users</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($users->isEmpty())
                            <p class="text-center">No users found.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role(s)</th>
                                            <th>Joined</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->getRoleNames()->join(', ') }}</td>
                                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                                <td class="text-end">
                                                    <a href="#" class="btn btn-sm btn-outline-primary">View</a>
                                                    <a href="#" class="btn btn-sm btn-outline-secondary">Edit</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $users->links() }}
                            </div>
                        @endif
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