<?php

namespace App\Repositories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;

class LocationRepository
{
    /**
     * Get all locations.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Location::orderBy('location_name')->get();
    }

    /**
     * Create a new location.
     *
     * @param array $data
     * @return Location
     */
    public function create(array $data): Location
    {
        return Location::create($data);
    }

    /**
     * Update an existing location.
     *
     * @param Location $location
     * @param array $data
     * @return Location
     */
    public function update(Location $location, array $data): Location
    {
        $location->update($data);
        return $location->fresh();
    }

    /**
     * Delete a location.
     *
     * @param Location $location
     * @return bool
     */
    public function delete(Location $location): bool
    {
        return $location->delete();
    }

    /**
     * Check if location is assigned to any users.
     *
     * @param Location $location
     * @return bool
     */
    public function hasUsers(Location $location): bool
    {
        return $location->users()->exists();
    }
}
