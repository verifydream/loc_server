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
                    <form action="{{ route('admin.app-versions.update', $appVersion->id) }}" method="POST" enctype="multipart/form-data" id="edit-form">
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
                            
                            <!-- File Info Display -->
                            <div id="file-info" class="mt-2 d-none">
                                <small class="text-muted">
                                    <i class="bi bi-file-earmark-zip"></i> 
                                    <span id="file-name"></span> 
                                    (<span id="file-size"></span>)
                                </small>
                            </div>
                        </div>

                        <!-- Upload Progress Bar -->
                        <div id="upload-progress-container" class="mb-3 d-none">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold">Uploading...</span>
                                        <span id="progress-percentage" class="badge bg-primary">0%</span>
                                    </div>
                                    <div class="progress" style="height: 25px;">
                                        <div id="progress-bar" 
                                             class="progress-bar progress-bar-striped progress-bar-animated" 
                                             role="progressbar" 
                                             style="width: 0%"
                                             aria-valuenow="0" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            <span id="progress-text">0%</span>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <span id="upload-speed">0 KB/s</span> • 
                                            <span id="upload-eta">Calculating...</span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="update-btn">
                                <i class="bi bi-save"></i> Update Version
                            </button>
                            <a href="{{ route('admin.app-versions.show', $appVersion->id) }}" class="btn btn-secondary" id="cancel-btn">
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

<script>
(function() {
    'use strict';
    
    console.log('[INLINE-EDIT] Upload progress script loaded');
    
    const form = document.getElementById('edit-form');
    const fileInput = document.getElementById('apk_file');
    const updateBtn = document.getElementById('update-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const progressContainer = document.getElementById('upload-progress-container');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const progressPercentage = document.getElementById('progress-percentage');
    const uploadSpeed = document.getElementById('upload-speed');
    const uploadEta = document.getElementById('upload-eta');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');

    console.log('[INLINE-EDIT] Elements found:', {
        form: !!form,
        fileInput: !!fileInput,
        updateBtn: !!updateBtn,
        progressContainer: !!progressContainer
    });

    let startTime;
    let hasNewFile = false;

    // Show file info when file is selected
    fileInput.addEventListener('change', function(e) {
        console.log('[INLINE-EDIT] File selected:', this.files.length);
        if (this.files.length > 0) {
            const file = this.files[0];
            console.log('[INLINE-EDIT] File info:', file.name, file.size);
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            fileInfo.classList.remove('d-none');
            hasNewFile = true;
        } else {
            fileInfo.classList.add('d-none');
            hasNewFile = false;
        }
    });

    // Handle form submission with progress tracking
    form.addEventListener('submit', function(e) {
        console.log('[INLINE-EDIT] Form submitted, hasNewFile:', hasNewFile);
        
        // If no new file, submit normally
        if (!hasNewFile) {
            console.log('[INLINE-EDIT] No new file, submitting normally');
            return true;
        }

        e.preventDefault();

        // Validate form
        if (!form.checkValidity()) {
            console.log('[INLINE-EDIT] Form validation failed');
            form.classList.add('was-validated');
            return;
        }

        console.log('[INLINE-EDIT] Starting upload...');
        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();

        // Initialize upload tracking
        startTime = Date.now();

        // Show progress bar and disable buttons
        progressContainer.classList.remove('d-none');
        updateBtn.disabled = true;
        cancelBtn.classList.add('disabled');
        fileInput.disabled = true;

        // Track upload progress
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = Math.round((e.loaded / e.total) * 100);
                
                // Update progress bar
                progressBar.style.width = percentComplete + '%';
                progressBar.setAttribute('aria-valuenow', percentComplete);
                progressText.textContent = percentComplete + '%';
                progressPercentage.textContent = percentComplete + '%';

                // Calculate upload speed
                const elapsedTime = (Date.now() - startTime) / 1000;
                const speed = e.loaded / elapsedTime;
                uploadSpeed.textContent = formatFileSize(speed) + '/s';

                // Calculate ETA
                const remainingBytes = e.total - e.loaded;
                const eta = remainingBytes / speed;
                uploadEta.textContent = 'ETA: ' + formatTime(eta);

                // Change color based on progress
                if (percentComplete < 50) {
                    progressBar.classList.remove('bg-warning', 'bg-success');
                    progressBar.classList.add('bg-primary');
                } else if (percentComplete < 90) {
                    progressBar.classList.remove('bg-primary', 'bg-success');
                    progressBar.classList.add('bg-warning');
                } else {
                    progressBar.classList.remove('bg-primary', 'bg-warning');
                    progressBar.classList.add('bg-success');
                }
            }
        });

        // Handle successful upload
        xhr.addEventListener('load', function() {
            console.log('[INLINE-EDIT] Upload complete, status:', xhr.status);
            if (xhr.status === 200 || xhr.status === 302) {
                progressBar.classList.remove('progress-bar-animated');
                progressBar.classList.add('bg-success');
                progressText.textContent = 'Upload Complete!';
                uploadSpeed.textContent = 'Completed';
                uploadEta.textContent = 'Done!';

                // Redirect after a short delay
                setTimeout(function() {
                    window.location.href = '{{ route("admin.app-versions.index") }}';
                }, 1000);
            } else {
                handleUploadError('Upload failed. Please try again.');
            }
        });

        // Handle upload error
        xhr.addEventListener('error', function() {
            console.error('[INLINE-EDIT] Upload error');
            handleUploadError('Network error occurred. Please check your connection.');
        });

        // Handle upload abort
        xhr.addEventListener('abort', function() {
            console.log('[INLINE-EDIT] Upload aborted');
            handleUploadError('Upload cancelled.');
        });

        // Send the request
        xhr.open('POST', form.action);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        xhr.send(formData);
    });

    function handleUploadError(message) {
        progressBar.classList.remove('progress-bar-animated', 'bg-primary', 'bg-warning');
        progressBar.classList.add('bg-danger');
        progressText.textContent = 'Failed!';
        uploadSpeed.textContent = message;
        uploadEta.textContent = '';
        
        // Re-enable buttons
        updateBtn.disabled = false;
        cancelBtn.classList.remove('disabled');
        fileInput.disabled = false;

        // Hide progress after delay
        setTimeout(function() {
            progressContainer.classList.add('d-none');
            progressBar.style.width = '0%';
            progressBar.classList.remove('bg-danger');
            progressBar.classList.add('bg-primary', 'progress-bar-animated');
        }, 3000);
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
    }

    function formatTime(seconds) {
        if (isNaN(seconds) || seconds === Infinity) return 'Calculating...';
        if (seconds < 60) {
            return Math.round(seconds) + 's';
        } else if (seconds < 3600) {
            const minutes = Math.floor(seconds / 60);
            const secs = Math.round(seconds % 60);
            return minutes + 'm ' + secs + 's';
        } else {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            return hours + 'h ' + minutes + 'm';
        }
    }
})();
</script>
@endsection
