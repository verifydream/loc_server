<?php

namespace App\Services;

use App\Repositories\LocationRepository;
use App\Models\Location;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LocationManagementService
{
    protected LocationRepository $locationRepository;

    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    /**
     * Create a new location with validation.
     *
     * @param array $data
     * @return Location
     * @throws ValidationException
     */
    public function createLocation(array $data): Location
    {
        $validator = Validator::make($data, [
            'location_code' => 'required|unique:locations,location_code|max:10',
            'location_name' => 'required|max:100',
            'online_url' => 'required|url',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->locationRepository->create($validator->validated());
    }

    /**
     * Update an existing location with validation.
     *
     * @param Location $location
     * @param array $data
     * @return Location
     * @throws ValidationException
     */
    public function updateLocation(Location $location, array $data): Location
    {
        $validator = Validator::make($data, [
            'location_code' => 'required|unique:locations,location_code,' . $location->id . '|max:10',
            'location_name' => 'required|max:100',
            'online_url' => 'required|url',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->locationRepository->update($location, $validator->validated());
    }

    /**
     * Delete a location with usage validation.
     *
     * @param Location $location
     * @return bool
     * @throws \Exception
     */
    public function deleteLocation(Location $location): bool
    {
        if (!$this->canDeleteLocation($location)) {
            throw new \Exception('Cannot delete location that is assigned to users', 400);
        }

        return $this->locationRepository->delete($location);
    }

    /**
     * Check if location can be deleted (has no users).
     *
     * @param Location $location
     * @return bool
     */
    public function canDeleteLocation(Location $location): bool
    {
        return !$this->locationRepository->hasUsers($location);
    }
}
