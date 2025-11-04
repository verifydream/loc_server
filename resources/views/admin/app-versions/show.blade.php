@extends('layouts.admin')

@section('title', 'Detail Versi APK')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail Versi APK</h2>
        <div>
            <a href="{{ route('admin.app-versions.edit', $appVersion->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('admin.app-versions.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Version Details -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Version Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Version Name:</th>
                            <td><strong class="fs-5">{{ $appVersion->version_name }}</strong></td>
                        </tr>
                        <tr>
                            <th>Version Code:</th>
                            <td><span class="badge bg-secondary fs-6">{{ $appVersion->version_code }}</span></td>
                        </tr>
                        <tr>
                            <th>Upload Date:</th>
                            <td>{{ $appVersion->created_at->format('d F Y, H:i') }} WIB</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $appVersion->updated_at->format('d F Y, H:i') }} WIB</td>
                        </tr>
                        <tr>
                            <th>File Size:</th>
                            <td>
                                @if(Storage::exists($appVersion->file_path))
                                    {{ number_format(Storage::size($appVersion->file_path) / 1024 / 1024, 2) }} MB
                                @else
                                    <span class="text-danger">File not found</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Release Notes</h5>
                </div>
                <div class="card-body">
                    @if($appVersion->release_notes)
                        <div class="release-notes">
                            {!! nl2br(e($appVersion->release_notes)) !!}
                        </div>
                    @else
                        <p class="text-muted">No release notes available.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('apk.download', $appVersion->id) }}" 
                           class="btn btn-success" target="_blank">
                            <i class="bi bi-download"></i> Download APK
                        </a>
                        <a href="{{ route('admin.app-versions.edit', $appVersion->id) }}" 
                           class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit Version
                        </a>
                        <button type="button" class="btn btn-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal">
                            <i class="bi bi-trash"></i> Delete Version
                        </button>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">API Endpoint</h5>
                </div>
                <div class="card-body">
                    <p class="small mb-2">Check for updates:</p>
                    <code class="d-block p-2 bg-light rounded small">
                        GET {{ url('/api/latest-version') }}
                    </code>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete version <strong>{{ $appVersion->version_name }}</strong> (Code: {{ $appVersion->version_code }})?
                    <br><br>
                    <small class="text-muted">This will also delete the APK file from storage.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.app-versions.destroy', $appVersion->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
