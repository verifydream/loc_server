<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;

class LocationService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Check user location by email and return location data.
     * Returns all locations if user exists in multiple locations.
     *
     * @param string $email
     * @return array|array[]
     * @throws \Exception
     */
    public function checkUserLocation(string $email): array
    {
        $users = $this->userRepository->findAllByEmail($email);

        if ($users->isEmpty()) {
            throw new \Exception('Email not found', 404);
        }

        // If only one location, return single object (backward compatible)
        if ($users->count() === 1) {
            $user = $users->first();
            
            $locationLogo = $user->location->logo 
                ? url('/public/storage/location-logos/' . $user->location->logo)
                : null;

            return [
                'email' => $user->email,
                'online_url' => $user->location->online_url,
                'location_name' => $user->location->location_name,
                'location_code' => $user->location->location_code,
                'location_logo' => $locationLogo,
            ];
        }

        // If multiple locations, return array of locations
        $locations = [];
        foreach ($users as $user) {
            $locationLogo = $user->location->logo 
                ? url('/public/storage/location-logos/' . $user->location->logo)
                : null;

            $locations[] = [
                'email' => $user->email,
                'online_url' => $user->location->online_url,
                'location_name' => $user->location->location_name,
                'location_code' => $user->location->location_code,
                'location_logo' => $locationLogo,
            ];
        }

        return $locations;
    }

    /**
     * Validate if user has active access.
     *
     * @param User $user
     * @return bool
     */
    public function validateUserAccess(User $user): bool
    {
        return $user->status === 'active';
    }
}
