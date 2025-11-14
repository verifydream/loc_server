@extends('layouts.admin')

@section('title', 'Location Management')

@section('page-icon')
    <span class="material-symbols-outlined mr-3 text-3xl text-slate-500 dark:text-slate-400">pin_drop</span>
@endsection

@section('page-title', 'Location Management')

@section('content')
<div class="flex justify-end items-center mb-8">
    <a href="{{ route('admin.locations.create') }}" class="flex items-center gap-2 bg-primary text-white font-semibold px-4 py-2.5 rounded-md shadow-sm hover:bg-blue-600 transition-all duration-200 ease-in-out transform hover:-translate-y-0.5">
        <span class="material-symbols-outlined">add_circle</span>
        Add Location
    </a>
</div>

<div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-800">
    @if($locations->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="p-4 font-semibold text-slate-600 dark:text-slate-400 tracking-wider" scope="col">Logo</th>
                        <th class="p-4 font-semibold text-slate-600 dark:text-slate-400 tracking-wider" scope="col">Location Code</th>
                        <th class="p-4 font-semibold text-slate-600 dark:text-slate-400 tracking-wider" scope="col">Location Name</th>
                        <th class="p-4 font-semibold text-slate-600 dark:text-slate-400 tracking-wider" scope="col">Online URL</th>
                        <th class="p-4 font-semibold text-slate-600 dark:text-slate-400 tracking-wider text-right" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locations as $location)
                        <tr class="border-b border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="p-4">
                                @if($location->logo)
                                    <img src="{{ url('public/storage/location-logos/' . $location->logo) }}" 
                                         alt="{{ $location->location_name }}" 
                                         class="w-10 h-10 object-contain p-1 border border-slate-200 dark:border-slate-700 rounded-md">
                                @else
                                    <div class="w-10 h-10 flex items-center justify-center bg-slate-100 dark:bg-slate-700 rounded-md text-xs text-slate-500 dark:text-slate-400">
                                        No logo
                                    </div>
                                @endif
                            </td>
                            <td class="p-4 font-medium text-slate-800 dark:text-slate-200">{{ $location->location_code }}</td>
                            <td class="p-4 text-slate-700 dark:text-slate-300">{{ $location->location_name }}</td>
                            <td class="p-4 text-slate-600 dark:text-slate-400">{{ $location->online_url }}</td>
                            <td class="p-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.locations.edit', $location->id) }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300 hover:bg-amber-200 dark:hover:bg-amber-900/80 transition-colors">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>Edit
                                    </a>
                                    <button type="button" onclick="openDeleteModal({{ $location->id }}, '{{ $location->location_name }}')" class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-900/80 transition-colors">
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
        <div class="p-4 text-sm text-slate-600 dark:text-slate-400 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <span>Showing {{ $locations->firstItem() ?? 0 }} to {{ $locations->lastItem() ?? 0 }} of {{ $locations->total() }} entries</span>
            <div class="flex items-center gap-1">
                @if ($locations->onFirstPage())
                    <span class="px-3 py-1.5 rounded-md text-slate-400 dark:text-slate-600 cursor-not-allowed">« Prev</span>
                @else
                    <a href="{{ $locations->previousPageUrl() }}" class="px-3 py-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">« Prev</a>
                @endif

                @foreach ($locations->getUrlRange(1, $locations->lastPage()) as $page => $url)
                    @if ($page == $locations->currentPage())
                        <span class="px-3 py-1.5 rounded-md bg-primary text-white">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($locations->hasMorePages())
                    <a href="{{ $locations->nextPageUrl() }}" class="px-3 py-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">Next »</a>
                @else
                    <span class="px-3 py-1.5 rounded-md text-slate-400 dark:text-slate-600 cursor-not-allowed">Next »</span>
                @endif
            </div>
        </div>
    @else
        <div class="p-8 text-center">
            <span class="material-symbols-outlined text-slate-400 dark:text-slate-600" style="font-size: 4rem;">location_off</span>
            <p class="text-slate-500 dark:text-slate-400 mt-3">No locations found</p>
            <a href="{{ route('admin.locations.create') }}" class="inline-flex items-center mt-4 px-5 py-2.5 bg-primary hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors">
                <span class="material-symbols-outlined mr-2">add_circle</span>
                Add First Location
            </a>
        </div>
    @endif
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Confirm Delete</h3>
            <p class="text-slate-600 dark:text-slate-400 mb-6">
                Are you sure you want to delete location <strong id="deleteLocationName"></strong>?
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
function openDeleteModal(locationId, locationName) {
    const modal = document.getElementById('deleteModal');
    const nameSpan = document.getElementById('deleteLocationName');
    const deleteForm = document.getElementById('deleteForm');
    
    nameSpan.textContent = locationName;
    deleteForm.action = `/admin/locations/${locationId}`;
    modal.classList.remove('hidden');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
}
</script>
@endpush
