<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class ExternalApiService
{
    protected $timeout = 30;
    protected $retryTimes = 2;
    protected $retryDelay = 1000; // milliseconds

    /**
     * Login to external API and get JWT token
     */
    public function login(string $baseUrl): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->retry($this->retryTimes, $this->retryDelay)
                ->withHeaders([
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Accept' => 'application/json',
                ])
                ->post("{$baseUrl}/api/auth/login", [
                    'email' => config('services.external_api.email'),
                    'password' => config('services.external_api.password'),
                ]);

            if (!$response->successful()) {
                throw new Exception("Login failed: " . $response->body());
            }

            $data = $response->json();
            
            // Log response untuk debugging
            Log::info("Login response from {$baseUrl}", ['response' => $data]);
            
            // Check berbagai kemungkinan format response
            $token = null;
            $tokenType = 'bearer';
            
            if (isset($data['access_token'])) {
                $token = $data['access_token'];
                $tokenType = $data['token_type'] ?? 'bearer';
            } elseif (isset($data['token'])) {
                $token = $data['token'];
                $tokenType = $data['token_type'] ?? 'bearer';
            } elseif (isset($data['object']['access_token'])) {
                // Format: {"object": {"access_token": "..."}}
                $token = $data['object']['access_token'];
                $tokenType = $data['object']['token_type'] ?? 'bearer';
            } elseif (isset($data['object']['token'])) {
                $token = $data['object']['token'];
                $tokenType = $data['object']['token_type'] ?? 'bearer';
            } elseif (isset($data['data']['access_token'])) {
                $token = $data['data']['access_token'];
                $tokenType = $data['data']['token_type'] ?? 'bearer';
            } elseif (isset($data['data']['token'])) {
                $token = $data['data']['token'];
                $tokenType = $data['data']['token_type'] ?? 'bearer';
            }
            
            if (!$token) {
                Log::error("Access token not found in response", ['response' => $data]);
                throw new Exception("Access token not found in response. Available keys: " . implode(', ', array_keys($data)));
            }

            return [
                'success' => true,
                'token' => $token,
                'token_type' => $tokenType,
            ];
        } catch (Exception $e) {
            Log::error("External API login failed for {$baseUrl}: " . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Fetch users from external API
     */
    public function fetchUsers(string $baseUrl, string $token, int $page = 1, int $limit = 100): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->retry($this->retryTimes, $this->retryDelay)
                ->withHeaders([
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$token}",
                ])
                ->get("{$baseUrl}/api/conf/users", [
                    'q' => '',
                    'limit' => $limit,
                    'page' => $page,
                    'sort' => 'id_user',
                    'order' => 'desc',
                ]);

            if (!$response->successful()) {
                Log::error("Fetch users HTTP error", [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new Exception("Fetch users failed (HTTP {$response->status()}): " . $response->body());
            }

            $data = $response->json();
            
            // Log response untuk debugging
            Log::info("Fetch users response from {$baseUrl}", [
                'page' => $page,
                'keys' => array_keys($data),
                'count' => isset($data['object']) ? count($data['object']) : 0
            ]);
            
            // Check berbagai kemungkinan format response
            $users = null;
            if (isset($data['object'])) {
                $users = $data['object'];
            } elseif (isset($data['data'])) {
                $users = $data['data'];
            } elseif (isset($data['users'])) {
                $users = $data['users'];
            }
            
            if ($users === null) {
                Log::error("Users data not found in response", ['response' => $data]);
                throw new Exception("Invalid response format: users data not found. Available keys: " . implode(', ', array_keys($data)));
            }

            return [
                'success' => true,
                'users' => $users,
                'total' => count($users),
            ];
        } catch (Exception $e) {
            Log::error("External API fetch users failed for {$baseUrl}: " . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Fetch all users from external API (handle pagination if needed)
     */
    public function fetchAllUsers(string $baseUrl, string $token): array
    {
        $allUsers = [];
        $page = 1;
        $limit = 100;

        do {
            $result = $this->fetchUsers($baseUrl, $token, $page, $limit);
            
            if (!$result['success']) {
                return $result;
            }

            $users = $result['users'];
            $allUsers = array_merge($allUsers, $users);
            
            // If we got less than limit, we've reached the end
            $hasMore = count($users) >= $limit;
            $page++;
            
        } while ($hasMore && $page <= 10); // Safety limit: max 10 pages (1000 users)

        return [
            'success' => true,
            'users' => $allUsers,
            'total' => count($allUsers),
        ];
    }
}
