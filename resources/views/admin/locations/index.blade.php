@extends('layouts.admin')

@section('title', 'Location Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Location Management</h2>
        <a href="{{ route('admin.locations.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Location
        </a>
    </div>

    <!-- Locations Table -->
    <div class="card">
        <div class="card-body">
            @if($locations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th>Location Code</th>
                                <th>Location Name</th>
                                <th>Online URL</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($locations as $location)
                                <tr>
                                    <td>
                                        @if($location->logo)
                                            <img src="{{ url('public/storage/location-logos/' . $location->logo) }}" 
                                                 alt="{{ $location->location_name }}" 
                                                 style="max-width: 60px; max-height: 40px; object-fit: contain;">
                                        @else
                                            <span class="text-muted small">No logo</span>
                                        @endif
                                    </td>
                                    <td>{{ $location->location_code }}</td>
                                    <td>{{ $location->location_name }}</td>
                                    <td>{{ $location->online_url }}</td>
                                    <td>
                                        <a href="{{ route('admin.locations.edit', $location->id) }}" 
                                           class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $location->id }}">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>

                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade" id="deleteModal{{ $location->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirm Delete</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete location <strong>{{ $location->location_name }}</strong>?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('admin.locations.destroy', $location->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                    <div class="text-muted mb-2 mb-sm-0">
                        Showing {{ $locations->firstItem() ?? 0 }} to {{ $locations->lastItem() ?? 0 }} of {{ $locations->total() }} entries
                    </div>
                    <div>
                        {{ $locations->links() }}
                    </div>
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> No locations found
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
