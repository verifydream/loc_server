@extends('layouts.admin')

@section('title', 'App Version Details')

@section('page-icon')
    <span class="material-symbols-outlined mr-3 text-3xl text-slate-500 dark:text-slate-400">info</span>
@endsection

@section('page-title', 'App Version Details')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-sm border border-slate-200 dark:border-zinc-800">
            <div class="p-6 border-b border-slate-200 dark:border-zinc-800">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center">
                    <span class="material-symbols-outlined mr-2 text-slate-500">info</span>
                    Version Information
                </h3>
            </div>
            <div class="p-6">
                <table class="w-full">
                    <tr class="border-b border-slate-100 dark:border-slate-800">
                        <td class="py-3 text-sm font-medium text-slate-600 dark:text-slate-400 w-48">Version Name:</td>
                        <td class="py-3 text-lg font-bold text-slate-800 dark:text-slate-100">{{ $appVersion->version_name }}</td>
                    </tr>
                    <tr class="border-b border-slate-100 dark:border-slate-800">
                        <td class="py-3 text-sm font-medium text-slate-600 dark:text-slate-400">Version Code:</td>
                        <td class="py-3">
                            <span class="inline-flex items-center px-3 py-1 bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-slate-300 rounded-full text-sm font-bold">{{ $appVersion->version_code }}</span>
                        </td>
                    </tr>
                    <tr class="border-b border-slate-100 dark:border-slate-800">
                        <td class="py-3 text-sm font-medium text-slate-600 dark:text-slate-400">Upload Date:</td>
                        <td class="py-3 text-slate-700 dark:text-slate-300">{{ $appVersion->created_at->format('d F Y, H:i') }} WIB</td>
                    </tr>
                    <tr class="border-b border-slate-100 dark:border-slate-800">
                        <td class="py-3 text-sm font-medium text-slate-600 dark:text-slate-400">Last Updated:</td>
                        <td class="py-3 text-slate-700 dark:text-slate-300">{{ $appVersion->updated_at->format('d F Y, H:i') }} WIB</td>
                    </tr>
                    <tr>
                        <td class="py-3 text-sm font-medium text-slate-600 dark:text-slate-400">File Size:</td>
                        <td class="py-3 text-slate-700 dark:text-slate-300">
                            @if(Storage::exists($appVersion->file_path))
                                {{ number_format(Storage::size($appVersion->file_path) / 1024 / 1024, 2) }} MB
                            @else
                                <span class="text-red-500">File not found</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-sm border border-slate-200 dark:border-zinc-800">
            <div class="p-6 border-b border-slate-200 dark:border-zinc-800">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center">
                    <span class="material-symbols-outlined mr-2 text-slate-500">description</span>
                    Release Notes
                </h3>
            </div>
            <div class="p-6">
                @if($appVersion->release_notes)
                    <div class="text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line">
                        {{ $appVersion->release_notes }}
                    </div>
                @else
                    <p class="text-slate-500 dark:text-slate-400 italic">No release notes available.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-sm border border-slate-200 dark:border-zinc-800">
            <div class="p-6 border-b border-slate-200 dark:border-zinc-800">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center">
                    <span class="material-symbols-outlined mr-2 text-slate-500">touch_app</span>
                    Actions
                </h3>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('apk.download', $appVersion->id) }}" target="_blank" class="flex items-center justify-center w-full px-4 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition-colors">
                    <span class="material-symbols-outlined mr-2">download</span>
                    Download APK
                </a>
                <a href="{{ route('admin.app-versions.edit', $appVersion->id) }}" class="flex items-center justify-center w-full px-4 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg transition-colors">
                    <span class="material-symbols-outlined mr-2">edit</span>
                    Edit Version
                </a>
                <button type="button" onclick="openDeleteModal()" class="flex items-center justify-center w-full px-4 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition-colors">
                    <span class="material-symbols-outlined mr-2">delete</span>
                    Delete Version
                </button>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-sm border border-slate-200 dark:border-zinc-800">
            <div class="p-6 border-b border-slate-200 dark:border-zinc-800">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center">
                    <span class="material-symbols-outlined mr-2 text-slate-500">api</span>
                    API Endpoint
                </h3>
            </div>
            <div class="p-6">
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">Check for updates:</p>
                <code class="block p-3 bg-slate-100 dark:bg-slate-800 rounded-lg text-xs text-slate-700 dark:text-slate-300 break-all">
                    GET {{ url('/api/latest-version') }}
                </code>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Confirm Delete</h3>
            <p class="text-slate-600 dark:text-slate-400 mb-2">
                Are you sure you want to delete version <strong>{{ $appVersion->version_name }}</strong> (Code: {{ $appVersion->version_code }})?
            </p>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                This will also delete the APK file from storage.
            </p>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 rounded-md hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    Cancel
                </button>
                <form action="{{ route('admin.app-versions.destroy', $appVersion->id) }}" method="POST" class="inline">
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
function openDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endpush
