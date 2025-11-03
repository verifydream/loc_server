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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
