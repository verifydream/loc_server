<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Location Server API Documentation",
 *     version="1.0.0",
 *     description="API documentation for Location Server and App Version Management",
 *     @OA\Contact(
 *         email="support@example.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Local Development (localhost)"
 * )
 * @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Local Development (127.0.0.1)"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="apikey",
 *     type="apiKey",
 *     in="header",
 *     name="X-API-KEY",
 *     description="API Key for authentication"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
