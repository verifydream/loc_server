@extends('layouts.admin')

@section('title', 'Manajemen Versi APK')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manajemen Versi APK</h2>
        <a href="{{ route('admin.app-versions.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Upload Versi Baru
        </a>
    </div>

    <!-- Versions Table -->
    <div class="card">
        <div class="card-body">
            @if($versions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Version Name</th>
                                <th>Version Code</th>
                                <th>Release Notes</th>
                                <th>Upload Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($versions as $version)
                                <tr>
                                    <td><strong>{{ $version->version_name }}</strong></td>
                                    <td><span class="badge bg-secondary">{{ $version->version_code }}</span></td>
                                    <td>
                                        @if($version->release_notes)
                                            <small>{{ Str::limit($version->release_notes, 50) }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>{{ $version->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.app-versions.show', $version->id) }}" 
                                               class="btn btn-sm btn-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('apk.download', $version->id) }}" 
                                               class="btn btn-sm btn-success" title="Download APK" target="_blank">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            <a href="{{ route('admin.app-versions.edit', $version->id) }}" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal{{ $version->id }}"
                                                    title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade" id="deleteModal{{ $version->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirm Delete</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete version <strong>{{ $version->version_name }}</strong> (Code: {{ $version->version_code }})?
                                                        <br><br>
                                                        <small class="text-muted">This will also delete the APK file from storage.</small>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('admin.app-versions.destroy', $version->id) }}" method="POST" class="d-inline">
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
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $versions->firstItem() ?? 0 }} to {{ $versions->lastItem() ?? 0 }} of {{ $versions->total() }} entries
                    </div>
                    <div>
                        {{ $versions->links() }}
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> No APK versions found. 
                    <a href="{{ route('admin.app-versions.create') }}" class="alert-link">Upload your first version</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
