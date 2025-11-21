@extends('layouts.admin')

@section('title', 'User Management')

@section('page-icon')
    <span class="material-symbols-outlined mr-3 text-3xl text-slate-500 dark:text-slate-400">group</span>
@endsection

@section('page-title', 'User Management')

@section('content')
<!-- Header Actions -->
<div class="flex justify-end items-center mb-8">
    <div class="flex items-center space-x-2">
        <div class="relative inline-block text-left">
            <button type="button" class="flex items-center justify-center gap-2 px-4 py-2 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 rounded-md shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors" id="syncDropdownButton">
                <span class="material-symbols-outlined text-base">sync</span>
                Sync from Server
            </button>
            <div class="hidden absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white dark:bg-slate-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" id="syncDropdownMenu">
                <div class="py-1">
                    @foreach($locations as $location)
                        <a href="{{ route('admin.users.sync.preview', $location->id) }}" class="flex items-center px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700">
                            <span class="material-symbols-outlined mr-2 text-base">cloud_sync</span>
                            Sync {{ $location->location_name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <a href="{{ route('admin.users.create') }}" class="flex items-center justify-center gap-2 px-4 py-2 bg-primary text-white rounded-md shadow-sm hover:bg-blue-600 transition-colors">
            <span class="material-symbols-outlined text-base">add</span>
            Add User
        </a>
    </div>
</div>

<!-- Search and Filter -->
<div class="bg-white dark:bg-slate-900/70 p-6 rounded-lg shadow-sm mb-8 border border-slate-200 dark:border-slate-700">
    <form method="GET" action="{{ route('admin.users.index') }}" id="filterForm">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1" for="search">Search by Email</label>
                <input class="w-full rounded-md border-slate-300 dark:border-slate-600 bg-background-light dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-primary focus:border-primary" 
                       id="search" 
                       name="search" 
                       type="text" 
                       value="{{ $query ?? '' }}" 
                       placeholder="Enter email..." 
                       autocomplete="off"/>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1" for="location_id">Filter by Location</label>
                <select class="w-full rounded-md border-slate-300 dark:border-slate-600 bg-background-light dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-primary focus:border-primary" 
                        id="location_id" 
                        name="location_id">
                    <option value="">All Locations</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}" {{ ($locationId ?? '') == $location->id ? 'selected' : '' }}>
                            {{ $location->location_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1" for="status">Filter by Status</label>
                <select class="w-full rounded-md border-slate-300 dark:border-slate-600 bg-background-light dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-primary focus:border-primary" 
                        id="status" 
                        name="status">
                    <option value="">All Status</option>
                    <option value="active" {{ ($status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ ($status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-primary text-white rounded-md shadow-sm hover:bg-blue-600 transition-colors h-10">Apply Filters</button>
            </div>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white dark:bg-slate-900/70 rounded-lg shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
    @if($users->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 dark:bg-slate-800 text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 font-medium" scope="col">Email</th>
                        <th class="px-6 py-3 font-medium" scope="col">Location Name</th>
                        <th class="px-6 py-3 font-medium" scope="col">Online URL</th>
                        <th class="px-6 py-3 font-medium" scope="col">Status</th>
                        <th class="px-6 py-3 font-medium text-right" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-800 dark:text-slate-200">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->location->location_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->location->online_url }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300 hover:bg-amber-200 dark:hover:bg-amber-900/80 transition-colors">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>Edit
                                    </a>
                                    <button type="button" onclick="openDeleteModal({{ $user->id }}, '{{ $user->email }}')" class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-900/80 transition-colors">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-200 dark:border-slate-700">
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Showing <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $users->firstItem() ?? 0 }}</span> to <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $users->lastItem() ?? 0 }}</span> of <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $users->total() }}</span> entries
            </p>
            <nav class="flex items-center space-x-1">
                @if ($users->onFirstPage())
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-md text-slate-400 dark:text-slate-600 cursor-not-allowed">
                        <span class="material-symbols-outlined text-lg">chevron_left</span>
                    </span>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="inline-flex items-center justify-center w-8 h-8 rounded-md text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <span class="material-symbols-outlined text-lg">chevron_left</span>
                    </a>
                @endif

                @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                    @if ($page == $users->currentPage())
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-primary text-white font-medium">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center justify-center w-8 h-8 rounded-md text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="inline-flex items-center justify-center w-8 h-8 rounded-md text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <span class="material-symbols-outlined text-lg">chevron_right</span>
                    </a>
                @else
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-md text-slate-400 dark:text-slate-600 cursor-not-allowed">
                        <span class="material-symbols-outlined text-lg">chevron_right</span>
                    </span>
                @endif
            </nav>
        </div>
    @else
        <div class="p-8 text-center">
            <span class="material-symbols-outlined text-slate-400 dark:text-slate-600" style="font-size: 4rem;">group_off</span>
            <p class="text-slate-500 dark:text-slate-400 mt-3">No users found</p>
        </div>
    @endif
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Confirm Delete</h3>
            <p class="text-slate-600 dark:text-slate-400 mb-6">
                Are you sure you want to delete user <strong id="deleteUserEmail"></strong>?
            </p>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 rounded-md hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dropdown toggle
    const dropdownButton = document.getElementById('syncDropdownButton');
    const dropdownMenu = document.getElementById('syncDropdownMenu');
    
    if (dropdownButton && dropdownMenu) {
        dropdownButton.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', function() {
            dropdownMenu.classList.add('hidden');
        });
    }

    // Auto-submit filters
    const searchInput = document.getElementById('search');
    const locationFilter = document.getElementById('location_id');
    const statusFilter = document.getElementById('status');
    const filterForm = document.getElementById('filterForm');
    let searchTimeout;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                filterForm.submit();
            }, 500);
        });
    }

    if (locationFilter) {
        locationFilter.addEventListener('change', function() {
            filterForm.submit();
        });
    }

    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            filterForm.submit();
        });
    }
});

function openDeleteModal(userId, userEmail) {
    const modal = document.getElementById('deleteModal');
    const emailSpan = document.getElementById('deleteUserEmail');
    const deleteForm = document.getElementById('deleteForm');
    
    emailSpan.textContent = userEmail;
    deleteForm.action = `/admin/users/${userId}`;
    modal.classList.remove('hidden');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
}
</script>
@endpush
