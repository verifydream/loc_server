@extends('layouts.admin')

@section('title', 'App Updates')

@section('page-icon')
    <span class="material-symbols-outlined mr-3 text-3xl text-slate-500 dark:text-slate-400">smartphone</span>
@endsection

@section('page-title', 'App Updates')

@section('content')
<div class="flex justify-end items-center mb-8">
    <a href="{{ route('admin.app-versions.create') }}" class="flex items-center justify-center gap-2 px-5 py-3 bg-primary text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 transition-all duration-200 transform hover:scale-105">
        <span class="material-symbols-outlined">upload</span>
        <span>Upload New Version</span>
    </a>
</div>

<div class="bg-white dark:bg-zinc-900/50 rounded-lg shadow-sm border border-slate-200 dark:border-zinc-800 overflow-hidden">
    @if($versions->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 dark:text-slate-400 uppercase bg-slate-50 dark:bg-zinc-800/60">
                    <tr>
                        <th class="px-6 py-4 font-medium tracking-wider" scope="col">Version Name</th>
                        <th class="px-6 py-4 font-medium tracking-wider" scope="col">Version Code</th>
                        <th class="px-6 py-4 font-medium tracking-wider" scope="col">Release Notes</th>
                        <th class="px-6 py-4 font-medium tracking-wider" scope="col">Upload Date</th>
                        <th class="px-6 py-4 font-medium tracking-wider text-right" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($versions as $version)
                        <tr class="border-b border-slate-200 dark:border-zinc-800 hover:bg-slate-50 dark:hover:bg-zinc-800/40 transition-colors duration-150">
                            <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-100">{{ $version->version_name }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center justify-center w-6 h-6 bg-slate-200 dark:bg-zinc-700 text-slate-600 dark:text-slate-300 rounded-md text-xs font-bold">{{ $version->version_code }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400">
                                @if($version->release_notes)
                                    {{ Str::limit($version->release_notes, 50) }}
                                @else
                                    <span class="text-slate-400 dark:text-slate-600">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $version->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.app-versions.show', $version->id) }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-900/80 transition-colors">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">visibility</span>View
                                    </a>
                                    <a href="{{ route('apk.download', $version->id) }}" target="_blank" class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-900/80 transition-colors">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">download</span>Download
                                    </a>
                                    <a href="{{ route('admin.app-versions.edit', $version->id) }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300 hover:bg-amber-200 dark:hover:bg-amber-900/80 transition-colors">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>Edit
                                    </a>
                                    <button type="button" onclick="openDeleteModal({{ $version->id }}, '{{ $version->version_name }}', {{ $version->version_code }})" class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-900/80 transition-colors">
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
        <div class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
            Showing <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $versions->firstItem() ?? 0 }}</span> to <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $versions->lastItem() ?? 0 }}</span> of <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $versions->total() }}</span> entries
        </div>
    @else
        <div class="p-8 text-center">
            <span class="material-symbols-outlined text-slate-400 dark:text-slate-600" style="font-size: 4rem;">smartphone</span>
            <p class="text-slate-500 dark:text-slate-400 mt-3">No APK versions found.</p>
            <a href="{{ route('admin.app-versions.create') }}" class="inline-flex items-center mt-4 px-5 py-2.5 bg-primary hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors">
                <span class="material-symbols-outlined mr-2">upload</span>
                Upload First Version
            </a>
        </div>
    @endif
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Confirm Delete</h3>
            <p class="text-slate-600 dark:text-slate-400 mb-2">
                Are you sure you want to delete version <strong id="deleteVersionName"></strong> (Code: <strong id="deleteVersionCode"></strong>)?
            </p>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                This will also delete the APK file from storage.
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
function openDeleteModal(versionId, versionName, versionCode) {
    const modal = document.getElementById('deleteModal');
    const nameSpan = document.getElementById('deleteVersionName');
    const codeSpan = document.getElementById('deleteVersionCode');
    const deleteForm = document.getElementById('deleteForm');
    
    nameSpan.textContent = versionName;
    codeSpan.textContent = versionCode;
    deleteForm.action = `/admin/app-versions/${versionId}`;
    modal.classList.remove('hidden');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
}
</script>
@endpush
