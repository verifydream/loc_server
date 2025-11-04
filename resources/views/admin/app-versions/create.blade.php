@extends('layouts.admin')

@section('title', 'Upload Versi Baru')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Upload Versi Baru</h2>
        <a href="{{ route('admin.app-versions.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <!-- Upload Form -->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.app-versions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Version Name -->
                <div class="mb-3">
                    <label for="version_name" class="form-label">Version Name <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('version_name') is-invalid @enderror" 
                           id="version_name" 
                           name="version_name" 
                           value="{{ old('version_name') }}" 
                           placeholder="e.g., 1.2.0"
                           required>
                    @error('version_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <small class="form-text text-muted">Human-readable version identifier (e.g., 1.2.0, 2.0.1)</small>
                </div>

                <!-- Version Code -->
                <div class="mb-3">
                    <label for="version_code" class="form-label">Version Code <span class="text-danger">*</span></label>
                    <input type="number" 
                           class="form-control @error('version_code') is-invalid @enderror" 
                           id="version_code" 
                           name="version_code" 
                           value="{{ old('version_code') }}" 
                           placeholder="e.g., 3"
                           min="1"
                           required>
                    @error('version_code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <small class="form-text text-muted">Numeric version code (must be unique and incrementing)</small>
                </div>

                <!-- Release Notes -->
                <div class="mb-3">
                    <label for="release_notes" class="form-label">Release Notes</label>
                    <textarea class="form-control @error('release_notes') is-invalid @enderror" 
                              id="release_notes" 
                              name="release_notes" 
                              rows="5"
                              placeholder="What's new in this version?">{{ old('release_notes') }}</textarea>
                    @error('release_notes')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <small class="form-text text-muted">Optional changelog or release information</small>
                </div>

                <!-- APK File -->
                <div class="mb-3">
                    <label for="apk_file" class="form-label">APK File <span class="text-danger">*</span></label>
                    <input type="file" 
                           class="form-control @error('apk_file') is-invalid @enderror" 
                           id="apk_file" 
                           name="apk_file" 
                           accept=".apk"
                           required>
                    @error('apk_file')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <small class="form-text text-muted">Upload APK file (maximum 100MB)</small>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.app-versions.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
