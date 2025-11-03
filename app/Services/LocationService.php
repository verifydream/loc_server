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
     *
     * @param string $email
     * @return array
     * @throws \Exception
     */
    public function checkUserLocation(string $email): array
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new \Exception('Email not found', 404);
        }

        if (!$this->validateUserAccess($user)) {
            throw new \Exception('User is inactive', 403);
        }

        return [
            'email' => $user->email,
            'online_url' => $user->location->online_url,
            'location_name' => $user->location->location_name,
            'location_code' => $user->location->location_code,
        ];
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
