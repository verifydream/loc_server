@extends('layouts.admin')

@section('title', 'Add User')

@section('page-icon')
    <span class="material-symbols-outlined mr-3 text-3xl text-slate-500 dark:text-slate-400">person_add</span>
@endsection

@section('page-title', 'Add New User')

@section('header-actions')
    <a class="inline-flex items-center px-4 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-md text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors" href="{{ route('admin.users.index') }}">
        <span class="material-symbols-outlined mr-2 text-lg">arrow_back</span>
        Back
    </a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800">
        <form action="{{ route('admin.users.store') }}" method="POST" id="userForm">
            @csrf
            <div class="p-6 md:p-8 space-y-6">
                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5" for="email">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input class="block w-full rounded-md border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 shadow-sm focus:border-primary focus:ring-primary dark:focus:border-primary dark:focus:ring-primary sm:text-sm transition-shadow @error('email') border-red-500 @enderror" 
                           id="email" 
                           name="email" 
                           type="email" 
                           value="{{ old('email') }}" 
                           placeholder="user@example.com" 
                           required/>
                    @error('email')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5" for="location_id">
                        Location <span class="text-red-500">*</span>
                    </label>
                    <select class="block w-full rounded-md border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 shadow-sm focus:border-primary focus:ring-primary dark:focus:border-primary dark:focus:ring-primary sm:text-sm transition-shadow @error('location_id') border-red-500 @enderror" 
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
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Online URL (Read-only) -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5" for="online_url">
                        Online URL
                    </label>
                    <input class="block w-full rounded-md border-slate-300 dark:border-slate-700 bg-slate-200/50 dark:bg-slate-800/50 shadow-sm sm:text-sm text-slate-500 dark:text-slate-400 cursor-not-allowed" 
                           id="online_url" 
                           type="text" 
                           readonly 
                           placeholder="Select a location to see URL"/>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">This field is auto-filled based on selected location.</p>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <fieldset class="flex items-center space-x-6">
                        <div class="flex items-center">
                            <input class="h-4 w-4 text-primary border-slate-300 dark:border-slate-600 focus:ring-primary dark:bg-slate-800 dark:checked:bg-primary dark:focus:ring-offset-slate-900 @error('status') border-red-500 @enderror" 
                                   id="status-active" 
                                   name="status" 
                                   type="radio" 
                                   value="active" 
                                   {{ old('status', 'active') == 'active' ? 'checked' : '' }}/>
                            <label class="ml-2 block text-sm text-slate-800 dark:text-slate-200" for="status-active">Active</label>
                        </div>
                        <div class="flex items-center">
                            <input class="h-4 w-4 text-primary border-slate-300 dark:border-slate-600 focus:ring-primary dark:bg-slate-800 dark:checked:bg-primary dark:focus:ring-offset-slate-900 @error('status') border-red-500 @enderror" 
                                   id="status-inactive" 
                                   name="status" 
                                   type="radio" 
                                   value="inactive" 
                                   {{ old('status') == 'inactive' ? 'checked' : '' }}/>
                            <label class="ml-2 block text-sm text-slate-800 dark:text-slate-200" for="status-inactive">Inactive</label>
                        </div>
                    </fieldset>
                    @error('status')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="bg-slate-50 dark:bg-slate-900/50 px-6 py-4 flex justify-end items-center space-x-3 rounded-b-lg border-t border-slate-200 dark:border-slate-800">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-md text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
                    Cancel
                </a>
                <button class="inline-flex items-center px-5 py-2.5 bg-primary border border-transparent rounded-md text-sm font-semibold text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-slate-900 transition-colors shadow-sm" type="submit">
                    <span class="material-symbols-outlined mr-2 text-lg">save</span>
                    Save User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
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
@endpush
