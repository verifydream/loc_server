<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppVersion;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class VersionController extends Controller
{
    /**
     * Get the latest APK version information
     *
     * @return JsonResponse
     */
    public function getLatestVersion(): JsonResponse
    {
        try {
            // Query the latest version based on created_at timestamp
            $version = AppVersion::latest()->first();

            // Return 404 if no version is available
            if (!$version) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No version available',
                ], 404);
            }

            // Generate public download URL
            $downloadUrl = route('apk.download', ['appVersion' => $version->id]);

            // Return success response with version details
            return response()->json([
                'status' => 'success',
                'data' => [
                    'version_name' => $version->version_name,
                    'version_code' => $version->version_code,
                    'release_notes' => $version->release_notes,
                    'download_url' => $downloadUrl,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error',
            ], 500);
        }
    }
}
