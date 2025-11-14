<?php

namespace App\Services;

use App\Models\User;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class UserSyncService
{
    protected $externalApiService;

    public function __construct(ExternalApiService $externalApiService)
    {
        $this->externalApiService = $externalApiService;
    }

    /**
     * Preview sync changes for a location
     */
    public function previewSync(Location $location): array
    {
        try {
            // Step 1: Login to external API
            $loginResult = $this->externalApiService->login($location->online_url);
            
            if (!$loginResult['success']) {
                return [
                    'success' => false,
                    'error' => 'Failed to login to external API: ' . $loginResult['error'],
                ];
            }

            // Step 2: Fetch all users from external API
            $fetchResult = $this->externalApiService->fetchAllUsers(
                $location->online_url,
                $loginResult['token']
            );
            
            if (!$fetchResult['success']) {
                return [
                    'success' => false,
                    'error' => 'Failed to fetch users from external API: ' . $fetchResult['error'],
                ];
            }

            // Step 3: Extract emails from API response
            $externalEmails = collect($fetchResult['users'])
                ->pluck('email')
                ->map(fn($email) => strtolower(trim($email)))
                ->filter()
                ->unique()
                ->values()
                ->toArray();

            // Step 4: Get existing emails in database for this location
            $existingEmails = User::where('location_id', $location->id)
                ->pluck('email')
                ->toArray();

            // Step 5: Compare and categorize
            $newEmails = array_diff($externalEmails, $existingEmails);
            $deletedEmails = array_diff($existingEmails, $externalEmails);
            $unchangedEmails = array_intersect($externalEmails, $existingEmails);

            return [
                'success' => true,
                'location' => $location,
                'summary' => [
                    'new' => count($newEmails),
                    'deleted' => count($deletedEmails),
                    'unchanged' => count($unchangedEmails),
                    'total_external' => count($externalEmails),
                    'total_existing' => count($existingEmails),
                ],
                'details' => [
                    'new' => array_values($newEmails),
                    'deleted' => array_values($deletedEmails),
                    'unchanged' => array_values($unchangedEmails),
                ],
            ];
        } catch (Exception $e) {
            Log::error("Sync preview failed for location {$location->id}: " . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'An unexpected error occurred: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Execute sync for a location
     */
    public function executeSync(Location $location, array $previewData): array
    {
        try {
            DB::beginTransaction();

            $inserted = 0;
            $deleted = 0;

            // Insert new users
            if (!empty($previewData['details']['new'])) {
                foreach ($previewData['details']['new'] as $email) {
                    User::create([
                        'email' => $email,
                        'location_id' => $location->id,
                        'status' => 'active',
                    ]);
                    $inserted++;
                }
            }

            // Delete/deactivate removed users
            if (!empty($previewData['details']['deleted'])) {
                // Option 1: Soft delete (set status to inactive)
                User::where('location_id', $location->id)
                    ->whereIn('email', $previewData['details']['deleted'])
                    ->update(['status' => 'inactive']);
                
                // Option 2: Hard delete (uncomment if you prefer)
                // User::where('location_id', $location->id)
                //     ->whereIn('email', $previewData['details']['deleted'])
                //     ->delete();
                
                $deleted = count($previewData['details']['deleted']);
            }

            DB::commit();

            Log::info("Sync completed for location {$location->id}: {$inserted} inserted, {$deleted} deactivated");

            return [
                'success' => true,
                'inserted' => $inserted,
                'deleted' => $deleted,
                'unchanged' => $previewData['summary']['unchanged'],
            ];
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Sync execution failed for location {$location->id}: " . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Failed to execute sync: ' . $e->getMessage(),
            ];
        }
    }
}
