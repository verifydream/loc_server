@extends('layouts.admin')

@section('title', 'Edit Versi APK')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Versi APK</h2>
        <a href="{{ route('admin.app-versions.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <!-- Edit Form -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.app-versions.update', $appVersion->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Version Name -->
                        <div class="mb-3">
                            <label for="version_name" class="form-label">Version Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('version_name') is-invalid @enderror" 
                                   id="version_name" 
                                   name="version_name" 
                                   value="{{ old('version_name', $appVersion->version_name) }}" 
                                   placeholder="e.g., 1.0.0" 
                                   required>
                            @error('version_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Format: Major.Minor.Patch (e.g., 1.0.0)</small>
                        </div>

                        <!-- Version Code -->
                        <div class="mb-3">
                            <label for="version_code" class="form-label">Version Code <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('version_code') is-invalid @enderror" 
                                   id="version_code" 
                                   name="version_code" 
                                   value="{{ old('version_code', $appVersion->version_code) }}" 
                                   placeholder="e.g., 1" 
                                   required>
                            @error('version_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Integer value that increases with each version</small>
                        </div>

                        <!-- Release Notes -->
                        <div class="mb-3">
                            <label for="release_notes" class="form-label">Release Notes</label>
                            <textarea class="form-control @error('release_notes') is-invalid @enderror" 
                                      id="release_notes" 
                                      name="release_notes" 
                                      rows="6" 
                                      placeholder="What's new in this version?">{{ old('release_notes', $appVersion->release_notes) }}</textarea>
                            @error('release_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- APK File -->
                        <div class="mb-3">
                            <label for="apk_file" class="form-label">APK File (Optional)</label>
                            <input type="file" 
                                   class="form-control @error('apk_file') is-invalid @enderror" 
                                   id="apk_file" 
                                   name="apk_file" 
                                   accept=".apk">
                            @error('apk_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Leave empty to keep current file. Current file: 
                                @if(Storage::exists($appVersion->file_path))
                                    {{ number_format(Storage::size($appVersion->file_path) / 1024 / 1024, 2) }} MB
                                @else
                                    <span class="text-danger">Not found</span>
                                @endif
                            </small>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Version
                            </button>
                            <a href="{{ route('admin.app-versions.show', $appVersion->id) }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Current Version Info</h5>
                </div>
                <div class="card-body">
                    <p><strong>Version:</strong> {{ $appVersion->version_name }}</p>
                    <p><strong>Code:</strong> {{ $appVersion->version_code }}</p>
                    <p><strong>Uploaded:</strong> {{ $appVersion->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li>Version code must be unique and higher than previous versions</li>
                        <li>Maximum file size: 150 MB</li>
                        <li>Only .apk files are accepted</li>
                        <li>Leave APK file empty to keep the current file</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
