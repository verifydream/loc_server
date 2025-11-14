@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-icon')
    <span class="material-symbols-outlined mr-3 text-3xl text-slate-500 dark:text-slate-400">dashboard</span>
@endsection

@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-slate-200 dark:border-zinc-800 flex justify-between items-center">
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Total Users</p>
            <p class="text-4xl font-bold text-slate-800 dark:text-slate-100">{{ $totalUsers ?? 0 }}</p>
        </div>
        <div class="bg-blue-100 dark:bg-blue-900/40 p-3 rounded-full">
            <span class="material-symbols-outlined text-blue-500 text-3xl">groups</span>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-slate-200 dark:border-zinc-800 flex justify-between items-center">
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Active Users</p>
            <p class="text-4xl font-bold text-slate-800 dark:text-slate-100">{{ $activeUsers ?? 0 }}</p>
        </div>
        <div class="bg-green-100 dark:bg-green-900/40 p-3 rounded-full">
            <span class="material-symbols-outlined text-green-500 text-3xl">person_check</span>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-slate-200 dark:border-zinc-800 flex justify-between items-center">
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Inactive Users</p>
            <p class="text-4xl font-bold text-slate-800 dark:text-slate-100">{{ $inactiveUsers ?? 0 }}</p>
        </div>
        <div class="bg-yellow-100 dark:bg-yellow-900/40 p-3 rounded-full">
            <span class="material-symbols-outlined text-yellow-500 text-3xl">person_cancel</span>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-slate-200 dark:border-zinc-800 flex justify-between items-center">
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Total Locations</p>
            <p class="text-4xl font-bold text-slate-800 dark:text-slate-100">{{ $totalLocations ?? 0 }}</p>
        </div>
        <div class="bg-cyan-100 dark:bg-cyan-900/40 p-3 rounded-full">
            <span class="material-symbols-outlined text-cyan-500 text-3xl">explore</span>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-slate-200 dark:border-zinc-800 flex justify-between items-center">
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400">App Versions</p>
            <p class="text-4xl font-bold text-slate-800 dark:text-slate-100">{{ $totalVersions ?? 0 }}</p>
        </div>
        <div class="bg-red-100 dark:bg-red-900/40 p-3 rounded-full">
            <span class="material-symbols-outlined text-red-500 text-3xl">smartphone</span>
        </div>
    </div>
</div>

<!-- Latest App Version -->
<div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-slate-200 dark:border-zinc-800 mb-8">
    <div class="flex justify-between items-center pb-4 border-b border-slate-200 dark:border-zinc-800 mb-6">
        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 flex items-center">
            <span class="material-symbols-outlined mr-2 text-slate-500">label_important</span>
            Latest App Version
        </h3>
        <a href="{{ route('admin.app-versions.index') }}" class="flex items-center space-x-2 px-4 py-2 text-sm bg-slate-100 dark:bg-zinc-800 hover:bg-slate-200 dark:hover:bg-zinc-700 rounded-lg transition-colors">
            <span class="material-symbols-outlined text-lg">settings</span>
            <span>Manage Versions</span>
        </a>
    </div>
    
    @if($latestVersion)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2">
                <div class="flex items-baseline space-x-3">
                    <h4 class="text-3xl font-bold text-slate-900 dark:text-slate-50">Version {{ $latestVersion->version_name }}</h4>
                    <span class="bg-slate-200 dark:bg-zinc-700 text-slate-600 dark:text-slate-300 text-sm font-medium px-2.5 py-1 rounded-full">Code: {{ $latestVersion->version_code }}</span>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 flex items-center">
                    <span class="material-symbols-outlined mr-1.5 text-base">calendar_today</span>
                    Uploaded: {{ $latestVersion->created_at->format('d F Y, H:i') }} WIB
                </p>
                @if($latestVersion->release_notes)
                    <div class="mt-6">
                        <p class="font-semibold text-slate-700 dark:text-slate-300">Release Notes:</p>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{!! nl2br(e($latestVersion->release_notes)) !!}</p>
                    </div>
                @endif
            </div>
            <div class="flex flex-col space-y-3 justify-center">
                <a href="{{ route('admin.app-versions.show', $latestVersion->id) }}" class="flex items-center justify-center w-full px-4 py-3 bg-cyan-500 hover:bg-cyan-600 text-white font-semibold rounded-lg transition-colors">
                    <span class="material-symbols-outlined mr-2">visibility</span>
                    View Details
                </a>
                <a href="{{ route('apk.download', $latestVersion->id) }}" target="_blank" class="flex items-center justify-center w-full px-4 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition-colors">
                    <span class="material-symbols-outlined mr-2">download</span>
                    Download APK
                </a>
                <a href="{{ route('admin.app-versions.create') }}" class="flex items-center justify-center w-full px-4 py-3 bg-primary hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                    <span class="material-symbols-outlined mr-2">upload</span>
                    Upload New Version
                </a>
            </div>
        </div>
    @else
        <div class="text-center py-8">
            <span class="material-symbols-outlined text-slate-400 dark:text-slate-600" style="font-size: 4rem;">smartphone</span>
            <p class="text-slate-500 dark:text-slate-400 mt-3">No app versions uploaded yet.</p>
            <a href="{{ route('admin.app-versions.create') }}" class="inline-flex items-center mt-4 px-5 py-2.5 bg-primary hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors">
                <span class="material-symbols-outlined mr-2">upload</span>
                Upload First Version
            </a>
        </div>
    @endif
</div>

<!-- Recent Activity & Quick Links -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-slate-200 dark:border-zinc-800">
        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 flex items-center pb-4 border-b border-slate-200 dark:border-zinc-800 mb-4">
            <span class="material-symbols-outlined mr-2 text-slate-500">history</span>
            Recent Activity
        </h3>
        <div class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
            <p>Welcome to Location Server Admin Dashboard</p>
            <p>Use the navigation menu to manage users and locations.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-slate-200 dark:border-zinc-800">
        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 flex items-center pb-4 border-b border-slate-200 dark:border-zinc-800 mb-4">
            <span class="material-symbols-outlined mr-2 text-slate-500">link</span>
            Quick Links
        </h3>
        <div class="divide-y divide-slate-200 dark:divide-zinc-800">
            <a class="flex items-center py-3 text-sm hover:text-primary transition-colors" href="{{ route('admin.users.index') }}">
                <span class="material-symbols-outlined mr-3 text-slate-400">manage_accounts</span> Manage Users
            </a>
            <a class="flex items-center py-3 text-sm hover:text-primary transition-colors" href="{{ route('admin.locations.index') }}">
                <span class="material-symbols-outlined mr-3 text-slate-400">location_searching</span> Manage Locations
            </a>
            <a class="flex items-center py-3 text-sm hover:text-primary transition-colors" href="{{ route('admin.users.create') }}">
                <span class="material-symbols-outlined mr-3 text-slate-400">person_add</span> Add New User
            </a>
            <a class="flex items-center py-3 text-sm hover:text-primary transition-colors" href="{{ route('admin.locations.create') }}">
                <span class="material-symbols-outlined mr-3 text-slate-400">add_location_alt</span> Add New Location
            </a>
            <a class="flex items-center pt-3 text-sm hover:text-primary transition-colors" href="{{ route('admin.app-versions.index') }}">
                <span class="material-symbols-outlined mr-3 text-slate-400">install_mobile</span> Manage App Versions
            </a>
        </div>
    </div>
</div>
@endsection
