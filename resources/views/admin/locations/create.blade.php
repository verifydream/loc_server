@extends('layouts.admin')

@section('title', 'Add Location')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Add New Location</h2>
        <a href="{{ route('admin.locations.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Locations
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.locations.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="location_code" class="form-label">Location Code <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('location_code') is-invalid @enderror" 
                           id="location_code" 
                           name="location_code" 
                           value="{{ old('location_code') }}" 
                           maxlength="10"
                           placeholder="e.g., sby, jkt, blw"
                           required>
                    <small class="form-text text-muted">Short code for the location (max 10 characters)</small>
                    @error('location_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="location_name" class="form-label">Location Name <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('location_name') is-invalid @enderror" 
                           id="location_name" 
                           name="location_name" 
                           value="{{ old('location_name') }}" 
                           maxlength="100"
                           placeholder="e.g., Surabaya, Jakarta"
                           required>
                    @error('location_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="online_url" class="form-label">Online URL <span class="text-danger">*</span></label>
                    <input type="url" 
                           class="form-control @error('online_url') is-invalid @enderror" 
                           id="online_url" 
                           name="online_url" 
                           value="{{ old('online_url') }}" 
                           placeholder="e.g., https://sby.web.com or sby.web.com"
                           required>
                    <small class="form-text text-muted">Full URL for the location's API server</small>
                    @error('online_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="logo" class="form-label">Location Logo</label>
                    <input type="file" 
                           class="form-control @error('logo') is-invalid @enderror" 
                           id="logo" 
                           name="logo"
                           accept="image/jpeg,image/jpg,image/png,image/gif,image/svg+xml">
                    <small class="form-text text-muted">Optional. Accepted formats: JPEG, JPG, PNG, GIF, SVG (Max: 2MB)</small>
                    @error('logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Save Location
                    </button>
                    <a href="{{ route('admin.locations.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
