<?php

namespace App\Http\Controllers;

use App\Models\AppVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppVersionController extends Controller
{
    /**
     * Display a listing of app versions.
     */
    public function index()
    {
        $versions = AppVersion::latest()->paginate(10);
        return view('admin.app-versions.index', compact('versions'));
    }

    /**
     * Show the form for creating a new app version.
     */
    public function create()
    {
        return view('admin.app-versions.create');
    }

    /**
     * Store a newly created app version in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'version_name' => 'required|string|max:255',
                'version_code' => 'required|integer|unique:app_versions,version_code',
                'release_notes' => 'nullable|string',
                'apk_file' => 'required|file|max:153600',
            ]);

            // Validate APK file extension manually
            $file = $request->file('apk_file');
            if ($file->getClientOriginalExtension() !== 'apk') {
                throw new \Exception('File must be an APK file');
            }

            // Generate custom filename with .apk extension
            $fileName = 'app-v' . $validated['version_name'] . '-' . time() . '.apk';
            
            // Store the APK file with custom name
            $filePath = $file->storeAs('public/updates', $fileName);

            // Create the app version record
            AppVersion::create([
                'version_name' => $validated['version_name'],
                'version_code' => $validated['version_code'],
                'file_path' => $filePath,
                'release_notes' => $validated['release_notes'] ?? null,
            ]);

            return redirect()->route('admin.app-versions.index')
                ->with('success', 'Version uploaded successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to upload version: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified app version.
     */
    public function show(AppVersion $appVersion)
    {
        return view('admin.app-versions.show', compact('appVersion'));
    }

    /**
     * Show the form for editing the specified app version.
     */
    public function edit(AppVersion $appVersion)
    {
        return view('admin.app-versions.edit', compact('appVersion'));
    }

    /**
     * Update the specified app version in storage.
     */
    public function update(Request $request, AppVersion $appVersion)
    {
        try {
            $validated = $request->validate([
                'version_name' => 'required|string|max:255',
                'version_code' => 'required|integer|unique:app_versions,version_code,' . $appVersion->id,
                'release_notes' => 'nullable|string',
                'apk_file' => 'nullable|file|max:153600',
            ]);

            // If new APK file is uploaded
            if ($request->hasFile('apk_file')) {
                $file = $request->file('apk_file');
                if ($file->getClientOriginalExtension() !== 'apk') {
                    throw new \Exception('File must be an APK file');
                }

                // Delete old file
                if (Storage::exists($appVersion->file_path)) {
                    Storage::delete($appVersion->file_path);
                }

                // Generate custom filename with .apk extension
                $fileName = 'app-v' . $validated['version_name'] . '-' . time() . '.apk';
                
                // Store new file with custom name
                $validated['file_path'] = $file->storeAs('public/updates', $fileName);
            }

            // Update the app version record
            $appVersion->update($validated);

            return redirect()->route('admin.app-versions.index')
                ->with('success', 'Version updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update version: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Download the APK file.
     */
    public function download(AppVersion $appVersion)
    {
        if (!Storage::exists($appVersion->file_path)) {
            return redirect()->back()
                ->with('error', 'APK file not found');
        }

        $fileName = "app-v{$appVersion->version_name}.apk";
        return Storage::download($appVersion->file_path, $fileName);
    }

    /**
     * Remove the specified app version from storage.
     */
    public function destroy(AppVersion $appVersion)
    {
        try {
            // Delete the file from storage if it exists
            if (Storage::exists($appVersion->file_path)) {
                Storage::delete($appVersion->file_path);
            }

            // Delete the database record
            $appVersion->delete();

            return redirect()->route('admin.app-versions.index')
                ->with('success', 'Version deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete version: ' . $e->getMessage());
        }
    }
}
