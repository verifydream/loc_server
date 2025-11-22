@extends('layouts.admin')

@section('title', 'Upload New Version')

@section('page-icon')
    <span class="material-symbols-outlined mr-3 text-3xl text-slate-500 dark:text-slate-400">upload</span>
@endsection

@section('page-title', 'Upload New Version')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800">
        <form action="{{ route('admin.app-versions.store') }}" method="POST" enctype="multipart/form-data" id="upload-form">
            @csrf
            <div class="p-6 md:p-8 space-y-6">
                <!-- Version Name -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5" for="version_name">
                        Version Name <span class="text-red-500">*</span>
                    </label>
                    <input class="block w-full rounded-md border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 shadow-sm focus:border-primary focus:ring-primary sm:text-sm @error('version_name') border-red-500 @enderror" 
                           id="version_name" 
                           name="version_name" 
                           type="text" 
                           value="{{ old('version_name') }}" 
                           placeholder="e.g., 1.2.0" 
                           required/>
                    @error('version_name')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @else
                        <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Human-readable version identifier (e.g., 1.2.0, 2.0.1)</p>
                    @enderror
                </div>

                <!-- Version Code -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5" for="version_code">
                        Version Code <span class="text-red-500">*</span>
                    </label>
                    <input class="block w-full rounded-md border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 shadow-sm focus:border-primary focus:ring-primary sm:text-sm @error('version_code') border-red-500 @enderror" 
                           id="version_code" 
                           name="version_code" 
                           type="number" 
                           value="{{ old('version_code') }}" 
                           placeholder="e.g., 3" 
                           min="1"
                           required/>
                    @error('version_code')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @else
                        <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Numeric version code (must be unique and incrementing)</p>
                    @enderror
                </div>

                <!-- Release Notes -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5" for="release_notes">
                        Release Notes
                    </label>
                    <textarea class="block w-full rounded-md border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 shadow-sm focus:border-primary focus:ring-primary sm:text-sm @error('release_notes') border-red-500 @enderror" 
                              id="release_notes" 
                              name="release_notes" 
                              rows="5"
                              placeholder="What's new in this version?">{{ old('release_notes') }}</textarea>
                    @error('release_notes')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @else
                        <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Optional changelog or release information</p>
                    @enderror
                </div>

                <!-- APK File -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5" for="apk_file">
                        APK File <span class="text-red-500">*</span>
                    </label>
                    <input class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-blue-600 cursor-pointer border border-slate-300 dark:border-slate-700 rounded-md bg-slate-50 dark:bg-slate-800 @error('apk_file') border-red-500 @enderror" 
                           id="apk_file" 
                           name="apk_file" 
                           type="file" 
                           accept=".apk"
                           required/>
                    @error('apk_file')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @else
                        <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Upload APK file (maximum 150MB)</p>
                    @enderror
                    
                    <!-- File Info Display -->
                    <div id="file-info" class="hidden mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <div class="flex items-center text-sm text-blue-800 dark:text-blue-200">
                            <span class="material-symbols-outlined mr-2">description</span>
                            <span id="file-name" class="font-medium"></span>
                            <span class="mx-2">•</span>
                            <span id="file-size"></span>
                        </div>
                    </div>
                </div>

                <!-- Upload Progress -->
                <div id="upload-progress-container" class="hidden">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-semibold text-blue-800 dark:text-blue-200">Uploading...</span>
                            <span id="progress-percentage" class="text-sm font-bold text-blue-800 dark:text-blue-200">0%</span>
                        </div>
                        <div class="w-full bg-blue-200 dark:bg-blue-900 rounded-full h-6 overflow-hidden">
                            <div id="progress-bar" class="h-full bg-gradient-to-r from-blue-500 to-blue-600 transition-all duration-300 flex items-center justify-center text-xs font-semibold text-white" style="width: 0%">
                                <span id="progress-text">0%</span>
                            </div>
                        </div>
                        <div class="mt-2 flex justify-between text-xs text-blue-700 dark:text-blue-300">
                            <span id="upload-speed">0 KB/s</span>
                            <span id="upload-eta">Calculating...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="bg-slate-50 dark:bg-slate-900/50 px-6 py-4 flex justify-end items-center space-x-3 rounded-b-lg border-t border-slate-200 dark:border-slate-800">
                <a href="{{ route('admin.app-versions.index') }}" id="cancel-btn" class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-md text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
                    Cancel
                </a>
                <button id="upload-btn" class="inline-flex items-center px-5 py-2.5 bg-primary border border-transparent rounded-md text-sm font-semibold text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors shadow-sm" type="submit">
                    <span class="material-symbols-outlined mr-2 text-lg">upload</span>
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    'use strict';
    
    const form = document.getElementById('upload-form');
    const fileInput = document.getElementById('apk_file');
    const uploadBtn = document.getElementById('upload-btn');
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

    let startTime;

    // Show file info when file is selected
    fileInput.addEventListener('change', function(e) {
        if (this.files.length > 0) {
            const file = this.files[0];
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            fileInfo.classList.remove('hidden');
        } else {
            fileInfo.classList.add('hidden');
        }
    });

    // Handle form submission with progress tracking
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate form
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();

        // Initialize upload tracking
        startTime = Date.now();

        // Show progress bar and disable buttons
        progressContainer.classList.remove('hidden');
        uploadBtn.disabled = true;
        uploadBtn.classList.add('opacity-50', 'cursor-not-allowed');
        cancelBtn.classList.add('opacity-50', 'pointer-events-none');
        fileInput.disabled = true;

        // Track upload progress
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = Math.round((e.loaded / e.total) * 100);
                
                // Update progress bar
                progressBar.style.width = percentComplete + '%';
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
                if (percentComplete >= 90) {
                    progressBar.classList.remove('from-blue-500', 'to-blue-600', 'from-yellow-500', 'to-yellow-600');
                    progressBar.classList.add('from-green-500', 'to-green-600');
                } else if (percentComplete >= 50) {
                    progressBar.classList.remove('from-blue-500', 'to-blue-600', 'from-green-500', 'to-green-600');
                    progressBar.classList.add('from-yellow-500', 'to-yellow-600');
                }
            }
        });

        // Handle successful upload
        xhr.addEventListener('load', function() {
            if (xhr.status === 200 || xhr.status === 302) {
                progressBar.classList.remove('from-blue-500', 'to-blue-600', 'from-yellow-500', 'to-yellow-600');
                progressBar.classList.add('from-green-500', 'to-green-600');
                progressText.textContent = 'Complete!';
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
            handleUploadError('Network error occurred. Please check your connection.');
        });

        // Handle upload abort
        xhr.addEventListener('abort', function() {
            handleUploadError('Upload cancelled.');
        });

        // Send the request
        xhr.open('POST', form.action);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        xhr.send(formData);
    });

    function handleUploadError(message) {
        progressBar.classList.remove('from-blue-500', 'to-blue-600', 'from-yellow-500', 'to-yellow-600', 'from-green-500', 'to-green-600');
        progressBar.classList.add('from-red-500', 'to-red-600');
        progressText.textContent = 'Failed!';
        uploadSpeed.textContent = message;
        uploadEta.textContent = '';
        
        // Re-enable buttons
        uploadBtn.disabled = false;
        uploadBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        cancelBtn.classList.remove('opacity-50', 'pointer-events-none');
        fileInput.disabled = false;

        // Hide progress after delay
        setTimeout(function() {
            progressContainer.classList.add('hidden');
            progressBar.style.width = '0%';
            progressBar.classList.remove('from-red-500', 'to-red-600');
            progressBar.classList.add('from-blue-500', 'to-blue-600');
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
@endpush
