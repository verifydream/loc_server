@extends('layouts.admin')

@section('title', 'Edit Location')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Location</h2>
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
            <form action="{{ route('admin.locations.update', $location->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="location_code" class="form-label">Location Code <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('location_code') is-invalid @enderror" 
                           id="location_code" 
                           name="location_code" 
                           value="{{ old('location_code', $location->location_code) }}" 
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
                           value="{{ old('location_name', $location->location_name) }}" 
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
                           value="{{ old('online_url', $location->online_url) }}" 
                           placeholder="e.g., https://sby.web.com or sby.web.com"
                           required>
                    <small class="form-text text-muted">Full URL for the location's API server</small>
                    @error('online_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Location
                    </button>
                    <a href="{{ route('admin.locations.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
