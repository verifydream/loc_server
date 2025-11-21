@extends('layouts.admin')

@section('title', 'Add Location')

@section('page-icon')
    <span class="material-symbols-outlined mr-3 text-3xl text-slate-500 dark:text-slate-400">add_location</span>
@endsection

@section('page-title', 'Add New Location')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800">
        <form action="{{ route('admin.locations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6 md:p-8 space-y-6">
                <!-- Location Code -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5" for="location_code">
                        Location Code <span class="text-red-500">*</span>
                    </label>
                    <input class="block w-full rounded-md border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 shadow-sm focus:border-primary focus:ring-primary sm:text-sm @error('location_code') border-red-500 @enderror" 
                           id="location_code" 
                           name="location_code" 
                           type="text" 
                           value="{{ old('location_code') }}" 
                           maxlength="10"
                           placeholder="e.g., sby, jkt, blw" 
                           required/>
                    @error('location_code')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @else
                        <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Short code for the location (max 10 characters)</p>
                    @enderror
                </div>

                <!-- Location Name -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5" for="location_name">
                        Location Name <span class="text-red-500">*</span>
                    </label>
                    <input class="block w-full rounded-md border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 shadow-sm focus:border-primary focus:ring-primary sm:text-sm @error('location_name') border-red-500 @enderror" 
                           id="location_name" 
                           name="location_name" 
                           type="text" 
                           value="{{ old('location_name') }}" 
                           maxlength="100"
                           placeholder="e.g., Surabaya, Jakarta" 
                           required/>
                    @error('location_name')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Online URL -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5" for="online_url">
                        Online URL <span class="text-red-500">*</span>
                    </label>
                    <input class="block w-full rounded-md border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 shadow-sm focus:border-primary focus:ring-primary sm:text-sm @error('online_url') border-red-500 @enderror" 
                           id="online_url" 
                           name="online_url" 
                           type="url" 
                           value="{{ old('online_url') }}" 
                           placeholder="e.g., https://sby.web.com or sby.web.com" 
                           required/>
                    @error('online_url')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @else
                        <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Full URL for the location's API server</p>
                    @enderror
                </div>

                <!-- Location Logo -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5" for="logo">
                        Location Logo
                    </label>
                    <input class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-blue-600 cursor-pointer border border-slate-300 dark:border-slate-700 rounded-md bg-slate-50 dark:bg-slate-800 @error('logo') border-red-500 @enderror" 
                           id="logo" 
                           name="logo" 
                           type="file" 
                           accept="image/jpeg,image/jpg,image/png,image/gif,image/svg+xml"/>
                    @error('logo')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @else
                        <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Optional. Accepted formats: JPEG, JPG, PNG, GIF, SVG (Max: 2MB)</p>
                    @enderror
                </div>

                <!-- Photo Settings Section -->
                <div class="border-t border-slate-200 dark:border-slate-700 pt-6">
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-4">Photo Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Survey In -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5" for="survey_in">
                                Survey In
                            </label>
                            <input class="block w-full rounded-md border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 shadow-sm focus:border-primary focus:ring-primary sm:text-sm @error('survey_in') border-red-500 @enderror" 
                                   id="survey_in" 
                                   name="survey_in" 
                                   type="number" 
                                   value="{{ old('survey_in', 5) }}" 
                                   min="0"
                                   max="100"/>
                            @error('survey_in')
                                <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Survey In Damage -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5" for="survey_in_damage">
                                Survey In Damage
                            </label>
                            <input class="block w-full rounded-md border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 shadow-sm focus:border-primary focus:ring-primary sm:text-sm @error('survey_in_damage') border-red-500 @enderror" 
                                   id="survey_in_damage" 
                                   name="survey_in_damage" 
                                   type="number" 
                                   value="{{ old('survey_in_damage', 1) }}" 
                                   min="0"
                                   max="100"/>
                            @error('survey_in_damage')
                                <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Survey Out -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5" for="survey_out">
                                Survey Out
                            </label>
                            <input class="block w-full rounded-md border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 shadow-sm focus:border-primary focus:ring-primary sm:text-sm @error('survey_out') border-red-500 @enderror" 
                                   id="survey_out" 
                                   name="survey_out" 
                                   type="number" 
                                   value="{{ old('survey_out', 1) }}" 
                                   min="0"
                                   max="100"/>
                            @error('survey_out')
                                <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Crani In -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5" for="crani_in">
                                Crani In
                            </label>
                            <input class="block w-full rounded-md border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 shadow-sm focus:border-primary focus:ring-primary sm:text-sm @error('crani_in') border-red-500 @enderror" 
                                   id="crani_in" 
                                   name="crani_in" 
                                   type="number" 
                                   value="{{ old('crani_in', 2) }}" 
                                   min="0"
                                   max="100"/>
                            @error('crani_in')
                                <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Crani Out -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5" for="crani_out">
                                Crani Out
                            </label>
                            <input class="block w-full rounded-md border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 shadow-sm focus:border-primary focus:ring-primary sm:text-sm @error('crani_out') border-red-500 @enderror" 
                                   id="crani_out" 
                                   name="crani_out" 
                                   type="number" 
                                   value="{{ old('crani_out', 4) }}" 
                                   min="0"
                                   max="100"/>
                            @error('crani_out')
                                <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="bg-slate-50 dark:bg-slate-900/50 px-6 py-4 flex justify-end items-center space-x-3 rounded-b-lg border-t border-slate-200 dark:border-slate-800">
                <a href="{{ route('admin.locations.index') }}" class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-md text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
                    Cancel
                </a>
                <button class="inline-flex items-center px-5 py-2.5 bg-primary border border-transparent rounded-md text-sm font-semibold text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors shadow-sm" type="submit">
                    <span class="material-symbols-outlined mr-2 text-lg">save</span>
                    Save Location
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
