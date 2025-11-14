@extends('layouts.admin')

@section('title', 'Sync Preview - ' . $location->location_name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="bi bi-arrow-repeat"></i> Sync Preview: {{ $location->location_name }}
        </h2>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Users
        </a>
    </div>

    <!-- Summary Card -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-plus-circle"></i> New Users
                    </h5>
                    <h2 class="mb-0">{{ $summary['new'] }}</h2>
                    <small>Will be added</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-dash-circle"></i> Deleted Users
                    </h5>
                    <h2 class="mb-0">{{ $summary['deleted'] }}</h2>
                    <small>Will be deactivated</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-check-circle"></i> Unchanged
                    </h5>
                    <h2 class="mb-0">{{ $summary['unchanged'] }}</h2>
                    <small>No changes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-server"></i> Total External
                    </h5>
                    <h2 class="mb-0">{{ $summary['total_external'] }}</h2>
                    <small>From API</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Form -->
    @if($summary['new'] > 0 || $summary['deleted'] > 0)
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i>
            <strong>Warning:</strong> This action will modify {{ $summary['new'] + $summary['deleted'] }} user records.
            Please review the changes below before confirming.
        </div>

        <form action="{{ route('admin.users.sync.execute', $location->id) }}" method="POST" id="syncForm">
            @csrf
            <input type="hidden" name="preview_data" value="{{ json_encode(['summary' => $summary, 'details' => $details]) }}">
            
            <div class="d-flex justify-content-end mb-3">
                <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
                <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to execute this sync? This action cannot be undone.')">
                    <i class="bi bi-check-circle"></i> Confirm & Execute Sync
                </button>
            </div>
        </form>
    @else
        <div class="alert alert-success">
            <i class="bi bi-check-circle"></i>
            <strong>All synced!</strong> No changes needed. All users are already up to date.
        </div>
    @endif

    <!-- New Users Table -->
    @if(count($details['new']) > 0)
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle"></i> New Users ({{ count($details['new']) }})
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted">These users exist in the external server but not in your database. They will be added as active users.</p>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-sm table-striped">
                        <thead class="sticky-top bg-white">
                            <tr>
                                <th>#</th>
                                <th>Email</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($details['new'] as $index => $email)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $email }}</td>
                                    <td><span class="badge bg-success">Will be added</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Deleted Users Table -->
    @if(count($details['deleted']) > 0)
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="bi bi-dash-circle"></i> Deleted Users ({{ count($details['deleted']) }})
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted">These users exist in your database but not in the external server. They will be deactivated (status set to inactive).</p>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-sm table-striped">
                        <thead class="sticky-top bg-white">
                            <tr>
                                <th>#</th>
                                <th>Email</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($details['deleted'] as $index => $email)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $email }}</td>
                                    <td><span class="badge bg-danger">Will be deactivated</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Unchanged Users (Collapsed by default) -->
    @if(count($details['unchanged']) > 0)
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <button class="btn btn-link text-white text-decoration-none p-0" type="button" data-bs-toggle="collapse" data-bs-target="#unchangedUsers">
                        <i class="bi bi-check-circle"></i> Unchanged Users ({{ count($details['unchanged']) }})
                        <i class="bi bi-chevron-down"></i>
                    </button>
                </h5>
            </div>
            <div class="collapse" id="unchangedUsers">
                <div class="card-body">
                    <p class="text-muted">These users exist in both systems and will remain unchanged.</p>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-striped">
                            <thead class="sticky-top bg-white">
                                <tr>
                                    <th>#</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($details['unchanged'] as $index => $email)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $email }}</td>
                                        <td><span class="badge bg-info">No change</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
