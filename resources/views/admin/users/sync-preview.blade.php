@extends('layouts.admin')

@section('title', 'Sync Preview - ' . $location->location_name)

@section('page-icon')
    <span class="material-symbols-outlined mr-3 text-3xl text-slate-500 dark:text-slate-400">sync</span>
@endsection

@section('page-title', 'Sync Preview: ' . $location->location_name)

@section('content')

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-green-100 dark:bg-green-900/40 border border-green-200 dark:border-green-800 p-6 rounded-lg shadow-sm">
        <div class="flex items-center text-green-700 dark:text-green-300">
            <span class="material-symbols-outlined mr-3">add_circle</span>
            <h3 class="font-semibold">New Users</h3>
        </div>
        <p class="text-4xl font-bold text-green-800 dark:text-green-200 mt-3">{{ $summary['new'] }}</p>
        <p class="text-sm text-green-600 dark:text-green-400 mt-1">Will be added</p>
    </div>

    <div class="bg-red-100 dark:bg-red-900/40 border border-red-200 dark:border-red-800 p-6 rounded-lg shadow-sm">
        <div class="flex items-center text-red-700 dark:text-red-300">
            <span class="material-symbols-outlined mr-3">remove_circle</span>
            <h3 class="font-semibold">Deleted Users</h3>
        </div>
        <p class="text-4xl font-bold text-red-800 dark:text-red-200 mt-3">{{ $summary['deleted'] }}</p>
        <p class="text-sm text-red-600 dark:text-red-400 mt-1">Will be deactivated</p>
    </div>

    <div class="bg-cyan-100 dark:bg-cyan-900/40 border border-cyan-200 dark:border-cyan-800 p-6 rounded-lg shadow-sm">
        <div class="flex items-center text-cyan-700 dark:text-cyan-300">
            <span class="material-symbols-outlined mr-3">check_circle</span>
            <h3 class="font-semibold">Unchanged</h3>
        </div>
        <p class="text-4xl font-bold text-cyan-800 dark:text-cyan-200 mt-3">{{ $summary['unchanged'] }}</p>
        <p class="text-sm text-cyan-600 dark:text-cyan-400 mt-1">No changes</p>
    </div>

    <div class="bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-6 rounded-lg shadow-sm">
        <div class="flex items-center text-slate-700 dark:text-slate-300">
            <span class="material-symbols-outlined mr-3">storage</span>
            <h3 class="font-semibold">Total External</h3>
        </div>
        <p class="text-4xl font-bold text-slate-800 dark:text-slate-200 mt-3">{{ $summary['total_external'] }}</p>
        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">From API</p>
    </div>
</div>

<!-- Warning Message -->
@if($summary['new'] > 0 || $summary['deleted'] > 0)
    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-200 px-4 py-3 rounded-lg mb-6 flex items-start">
        <span class="material-symbols-outlined mr-3 mt-0.5">warning</span>
        <div>
            <strong>Warning:</strong> This action will modify {{ $summary['new'] + $summary['deleted'] }} user records. Please review the changes below before confirming.
        </div>
    </div>
@else
    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg mb-6 flex items-start">
        <span class="material-symbols-outlined mr-3 mt-0.5">check_circle</span>
        <div>
            <strong>All synced!</strong> No changes needed. All users are already up to date.
        </div>
    </div>
@endif

<!-- Details Sections -->
<div class="space-y-6">
    <!-- New Users -->
    @if(count($details['new']) > 0)
        <details class="group bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden" open>
            <summary class="flex items-center justify-between p-4 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <div class="flex items-center space-x-3">
                    <span class="material-symbols-outlined text-green-500">add_circle</span>
                    <h4 class="font-semibold text-slate-800 dark:text-white">New Users ({{ count($details['new']) }})</h4>
                </div>
                <span class="material-symbols-outlined transition-transform group-open:rotate-180">expand_more</span>
            </summary>
            <div class="p-6 border-t border-slate-200 dark:border-slate-800">
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">These users exist in the external server but not in your database. They will be added as active users.</p>
                <div class="overflow-x-auto max-h-96 overflow-y-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 dark:text-slate-400 uppercase bg-slate-50 dark:bg-slate-800 sticky top-0">
                            <tr>
                                <th class="px-6 py-3 w-16" scope="col">#</th>
                                <th class="px-6 py-3" scope="col">Email</th>
                                <th class="px-6 py-3" scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($details['new'] as $index => $email)
                                <tr class="bg-white dark:bg-slate-900 border-b dark:border-slate-800">
                                    <td class="px-6 py-4 font-medium text-slate-600 dark:text-slate-300">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">{{ $email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Will be added</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </details>
    @endif

    <!-- Deleted Users -->
    @if(count($details['deleted']) > 0)
        <details class="group bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden" open>
            <summary class="flex items-center justify-between p-4 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <div class="flex items-center space-x-3">
                    <span class="material-symbols-outlined text-red-500">remove_circle</span>
                    <h4 class="font-semibold text-slate-800 dark:text-white">Deleted Users ({{ count($details['deleted']) }})</h4>
                </div>
                <span class="material-symbols-outlined transition-transform group-open:rotate-180">expand_more</span>
            </summary>
            <div class="p-6 border-t border-slate-200 dark:border-slate-800">
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">These users exist in your database but not in the external server. They will be deactivated (status set to inactive).</p>
                <div class="overflow-x-auto max-h-96 overflow-y-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 dark:text-slate-400 uppercase bg-slate-50 dark:bg-slate-800 sticky top-0">
                            <tr>
                                <th class="px-6 py-3 w-16" scope="col">#</th>
                                <th class="px-6 py-3" scope="col">Email</th>
                                <th class="px-6 py-3" scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($details['deleted'] as $index => $email)
                                <tr class="bg-white dark:bg-slate-900 border-b dark:border-slate-800">
                                    <td class="px-6 py-4 font-medium text-slate-600 dark:text-slate-300">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">{{ $email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">Will be deactivated</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </details>
    @endif

    <!-- Unchanged Users -->
    @if(count($details['unchanged']) > 0)
        <details class="group bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
            <summary class="flex items-center justify-between p-4 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <div class="flex items-center space-x-3">
                    <span class="material-symbols-outlined text-cyan-500">check_circle</span>
                    <h4 class="font-semibold text-slate-800 dark:text-white">Unchanged Users ({{ count($details['unchanged']) }})</h4>
                </div>
                <span class="material-symbols-outlined transition-transform group-open:rotate-180">expand_more</span>
            </summary>
            <div class="p-6 border-t border-slate-200 dark:border-slate-800">
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">These users exist in both systems and will remain unchanged.</p>
                <div class="overflow-x-auto max-h-96 overflow-y-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 dark:text-slate-400 uppercase bg-slate-50 dark:bg-slate-800 sticky top-0">
                            <tr>
                                <th class="px-6 py-3 w-16" scope="col">#</th>
                                <th class="px-6 py-3" scope="col">Email</th>
                                <th class="px-6 py-3" scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($details['unchanged'] as $index => $email)
                                <tr class="bg-white dark:bg-slate-900 border-b dark:border-slate-800">
                                    <td class="px-6 py-4 font-medium text-slate-600 dark:text-slate-300">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">{{ $email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-300">No change</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </details>
    @endif
</div>

<!-- Action Buttons -->
@if($summary['new'] > 0 || $summary['deleted'] > 0)
    <form action="{{ route('admin.users.sync.execute', $location->id) }}" method="POST" id="syncForm">
        @csrf
        <input type="hidden" name="preview_data" value="{{ json_encode(['summary' => $summary, 'details' => $details]) }}">
        
        <div class="flex justify-end items-center mt-8 pt-6 border-t border-slate-200 dark:border-slate-800">
            <div class="flex space-x-4">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
                    Cancel
                </a>
                <button type="submit" onclick="return confirm('Are you sure you want to execute this sync? This action cannot be undone.')" class="inline-flex items-center px-5 py-2.5 bg-primary border border-transparent rounded-lg text-sm font-semibold text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-slate-900 transition-colors shadow-sm">
                    <span class="material-symbols-outlined mr-2 text-base">check</span>
                    Confirm & Execute Sync
                </button>
            </div>
        </div>
    </form>
@endif
@endsection
