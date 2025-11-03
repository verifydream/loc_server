<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    protected LocationService $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * Check user location based on email
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkLocation(Request $request): JsonResponse
    {
        // Validate email parameter
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $email = $request->input('email');
            $locationData = $this->locationService->checkUserLocation($email);

            return response()->json([
                'success' => true,
                'data' => $locationData,
            ], 200);
        } catch (\Exception $e) {
            // Handle specific exceptions based on code
            $statusCode = $e->getCode() ?: 500;
            $message = $e->getMessage();

            // Default to 500 if code is not a valid HTTP status code
            if ($statusCode < 100 || $statusCode >= 600) {
                $statusCode = 500;
                $message = 'An error occurred';
            }

            return response()->json([
                'success' => false,
                'message' => $message,
            ], $statusCode);
        }
    }
}
