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
     * @OA\Get(
     *     path="/api/latest-version",
     *     summary="Get latest APK version",
     *     description="Returns the latest available APK version information including download URL",
     *     operationId="getLatestVersion",
     *     tags={"App Version"},
     *     security={{"apikey": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="version_name", type="string", example="1.0.0", description="Version name"),
     *                 @OA\Property(property="version_code", type="integer", example=1, description="Version code number"),
     *                 @OA\Property(property="release_notes", type="string", example="Initial release with new features", description="Release notes"),
     *                 @OA\Property(property="download_url", type="string", example="https://example.com/download/app.apk", description="APK download URL")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No version available",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No version available")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
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
