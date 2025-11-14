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
     * @OA\Post(
     *     path="/api/check-location",
     *     summary="Check user location by email",
     *     description="Validates user email and returns location data. Returns single location object if user exists in one location, or array of locations if user exists in multiple locations.",
     *     operationId="checkLocation",
     *     tags={"Location"},
     *     security={{"apikey": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User email to check location",
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com", description="User email address")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation - Single location",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="email", type="string", example="user@example.com"),
     *                 @OA\Property(property="online_url", type="string", example="https://jakarta.example.com"),
     *                 @OA\Property(property="location_name", type="string", example="Jakarta Office"),
     *                 @OA\Property(property="location_code", type="string", example="JKT"),
     *                 @OA\Property(property="location_logo", type="string", nullable=true, example="https://example.com/public/storage/location-logos/jakarta.png")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="The email field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Email not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Email not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An error occurred")
     *         )
     *     )
     * )
     * 
     * @OA\Get(
     *     path="/api/check-location",
     *     summary="Check user location by email (GET method)",
     *     description="Validates user email and returns location data. Returns single location object if user exists in one location, or array of locations if user exists in multiple locations.",
     *     operationId="checkLocationGet",
     *     tags={"Location"},
     *     security={{"apikey": {}}},
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User email address",
     *         required=true,
     *         @OA\Schema(type="string", format="email", example="user@example.com")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation - Single location",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="email", type="string", example="user@example.com"),
     *                 @OA\Property(property="online_url", type="string", example="https://jakarta.example.com"),
     *                 @OA\Property(property="location_name", type="string", example="Jakarta Office"),
     *                 @OA\Property(property="location_code", type="string", example="JKT"),
     *                 @OA\Property(property="location_logo", type="string", nullable=true, example="https://example.com/public/storage/location-logos/jakarta.png")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="The email field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Email not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Email not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An error occurred")
     *         )
     *     )
     * )
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
