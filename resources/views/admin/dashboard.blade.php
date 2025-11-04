@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-speedometer2"></i> Dashboard</h1>
</div>

<div class="row">
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Users</h6>
                        <h2 class="mb-0">{{ $totalUsers ?? 0 }}</h2>
                    </div>
                    <div class="text-primary" style="font-size: 3rem;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Active Users</h6>
                        <h2 class="mb-0">{{ $activeUsers ?? 0 }}</h2>
                    </div>
                    <div class="text-success" style="font-size: 3rem;">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Inactive Users</h6>
                        <h2 class="mb-0">{{ $inactiveUsers ?? 0 }}</h2>
                    </div>
                    <div class="text-warning" style="font-size: 3rem;">
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Locations</h6>
                        <h2 class="mb-0">{{ $totalLocations ?? 0 }}</h2>
                    </div>
                    <div class="text-info" style="font-size: 3rem;">
                        <i class="bi bi-pin-map-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">App Versions</h6>
                        <h2 class="mb-0">{{ $totalVersions ?? 0 }}</h2>
                    </div>
                    <div class="text-danger" style="font-size: 3rem;">
                        <i class="bi bi-phone-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- App Version Widget -->
<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-phone-fill"></i> Latest App Version
                </div>
                <a href="{{ route('admin.app-versions.index') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-gear"></i> Manage Versions
                </a>
            </div>
            <div class="card-body">
                @if($latestVersion)
                    <div class="row">
                        <div class="col-md-8">
                            <h4>Version {{ $latestVersion->version_name }} <span class="badge bg-secondary">Code: {{ $latestVersion->version_code }}</span></h4>
                            <p class="text-muted mb-2">
                                <i class="bi bi-calendar"></i> Uploaded: {{ $latestVersion->created_at->format('d F Y, H:i') }} WIB
                            </p>
                            @if($latestVersion->release_notes)
                                <div class="mt-3">
                                    <strong>Release Notes:</strong>
                                    <div class="mt-2 p-3 bg-light rounded">
                                        {!! nl2br(e($latestVersion->release_notes)) !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.app-versions.show', $latestVersion->id) }}" class="btn btn-info">
                                    <i class="bi bi-eye"></i> View Details
                                </a>
                                <a href="{{ route('admin.app-versions.download', $latestVersion->id) }}" class="btn btn-success">
                                    <i class="bi bi-download"></i> Download APK
                                </a>
                                <a href="{{ route('admin.app-versions.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Upload New Version
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-phone-fill text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">No app versions uploaded yet.</p>
                        <a href="{{ route('admin.app-versions.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Upload First Version
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history"></i> Recent Activity
            </div>
            <div class="card-body">
                <p class="text-muted">Welcome to Location Server Admin Dashboard</p>
                <p>Use the navigation menu to manage users and locations.</p>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle"></i> Quick Links
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-people-fill"></i> Manage Users
                    </a>
                    <a href="{{ route('admin.locations.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-pin-map-fill"></i> Manage Locations
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-person-plus-fill"></i> Add New User
                    </a>
                    <a href="{{ route('admin.locations.create') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-plus-circle-fill"></i> Add New Location
                    </a>
                    <a href="{{ route('admin.app-versions.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-phone-fill"></i> Manage App Versions
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
