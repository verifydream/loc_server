@extends('layouts.admin')

@section('title', 'Add User')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Add New User</h2>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Users
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
            <form action="{{ route('admin.users.store') }}" method="POST" id="userForm">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="location_id" class="form-label">Location <span class="text-danger">*</span></label>
                    <select class="form-select @error('location_id') is-invalid @enderror" 
                            id="location_id" 
                            name="location_id" 
                            required>
                        <option value="">Select Location</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" 
                                    data-url="{{ $location->online_url }}"
                                    {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                {{ $location->location_name }} ({{ $location->location_code }})
                            </option>
                        @endforeach
                    </select>
                    @error('location_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="online_url" class="form-label">Online URL</label>
                    <input type="text" 
                           class="form-control" 
                           id="online_url" 
                           readonly 
                           placeholder="Select a location to see URL">
                    <small class="form-text text-muted">This field is auto-filled based on selected location</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input @error('status') is-invalid @enderror" 
                                   type="radio" 
                                   name="status" 
                                   id="status_active" 
                                   value="active" 
                                   {{ old('status', 'active') == 'active' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_active">
                                Active
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input @error('status') is-invalid @enderror" 
                                   type="radio" 
                                   name="status" 
                                   id="status_inactive" 
                                   value="inactive" 
                                   {{ old('status') == 'inactive' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_inactive">
                                Inactive
                            </label>
                        </div>
                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Save User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const locationSelect = document.getElementById('location_id');
    const onlineUrlInput = document.getElementById('online_url');

    // Auto-fill online_url when location is selected
    locationSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const url = selectedOption.getAttribute('data-url');
        
        if (url) {
            onlineUrlInput.value = url;
        } else {
            onlineUrlInput.value = '';
        }
    });

    // Trigger change event on page load if location is already selected
    if (locationSelect.value) {
        locationSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
