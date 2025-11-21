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
     * @param \Illuminate\Http\UploadedFile|null $logoFile
     * @return Location
     * @throws ValidationException
     */
    public function createLocation(array $data, $logoFile = null): Location
    {
        $validator = Validator::make($data, [
            'location_code' => 'required|unique:locations,location_code|max:10',
            'location_name' => 'required|max:100',
            'online_url' => 'required|url',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validatedData = $validator->validated();

        // Handle logo upload
        if ($logoFile) {
            $logoName = time() . '_' . $data['location_code'] . '.' . $logoFile->getClientOriginalExtension();
            $logoFile->move(public_path('storage/location-logos'), $logoName);
            $validatedData['logo'] = $logoName;
        }

        // Handle photo settings with default values
        $validatedData['photo_settings'] = [
            'survey_in' => isset($data['survey_in']) ? (int)$data['survey_in'] : 5,
            'survey_in_damage' => isset($data['survey_in_damage']) ? (int)$data['survey_in_damage'] : 1,
            'survey_out' => isset($data['survey_out']) ? (int)$data['survey_out'] : 1,
            'crani_in' => isset($data['crani_in']) ? (int)$data['crani_in'] : 2,
            'crani_out' => isset($data['crani_out']) ? (int)$data['crani_out'] : 4,
        ];

        return $this->locationRepository->create($validatedData);
    }

    /**
     * Update an existing location with validation.
     *
     * @param Location $location
     * @param array $data
     * @param \Illuminate\Http\UploadedFile|null $logoFile
     * @return Location
     * @throws ValidationException
     */
    public function updateLocation(Location $location, array $data, $logoFile = null): Location
    {
        $validator = Validator::make($data, [
            'location_code' => 'required|unique:locations,location_code,' . $location->id . '|max:10',
            'location_name' => 'required|max:100',
            'online_url' => 'required|url',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validatedData = $validator->validated();

        // Handle logo upload
        if ($logoFile) {
            // Delete old logo if exists
            if ($location->logo && file_exists(public_path('storage/location-logos/' . $location->logo))) {
                unlink(public_path('storage/location-logos/' . $location->logo));
            }

            $logoName = time() . '_' . $data['location_code'] . '.' . $logoFile->getClientOriginalExtension();
            $logoFile->move(public_path('storage/location-logos'), $logoName);
            $validatedData['logo'] = $logoName;
        }

        // Handle photo settings with default values
        $validatedData['photo_settings'] = [
            'survey_in' => isset($data['survey_in']) ? (int)$data['survey_in'] : 5,
            'survey_in_damage' => isset($data['survey_in_damage']) ? (int)$data['survey_in_damage'] : 1,
            'survey_out' => isset($data['survey_out']) ? (int)$data['survey_out'] : 1,
            'crani_in' => isset($data['crani_in']) ? (int)$data['crani_in'] : 2,
            'crani_out' => isset($data['crani_out']) ? (int)$data['crani_out'] : 4,
        ];

        return $this->locationRepository->update($location, $validatedData);
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
