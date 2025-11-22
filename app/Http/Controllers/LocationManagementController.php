<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Services\LocationManagementService;
use Illuminate\Http\Request;

class LocationManagementController extends Controller
{
    protected $locationManagementService;

    public function __construct(LocationManagementService $locationManagementService)
    {
        $this->locationManagementService = $locationManagementService;
    }

    /**
     * Display a listing of locations
     */
    public function index()
    {
        $locations = Location::orderBy('location_code')->paginate(10);
        return view('admin.locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new location
     */
    public function create()
    {
        return view('admin.locations.create');
    }

    /**
     * Store a newly created location in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_code' => 'required|unique:locations,location_code|max:10',
            'location_name' => 'required|max:100',
            'online_url' => 'required|url',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
            'survey_in' => 'nullable|integer|min:0|max:100',
            'survey_in_damage' => 'nullable|integer|min:0|max:100',
            'survey_out' => 'nullable|integer|min:0|max:100',
            'crani_in' => 'nullable|integer|min:0|max:100',
            'crani_out' => 'nullable|integer|min:0|max:100',
        ], [
            'location_code.required' => 'Location code is required',
            'location_code.unique' => 'Location code already exists',
            'location_code.max' => 'Location code must not exceed 10 characters',
            'location_name.required' => 'Location name is required',
            'location_name.max' => 'Location name must not exceed 100 characters',
            'online_url.required' => 'Online URL is required',
            'online_url.url' => 'Invalid URL format',
            'logo.image' => 'Logo must be an image file',
            'logo.mimes' => 'Logo must be a file of type: jpeg, jpg, png, gif, svg',
            'logo.max' => 'Logo size must not exceed 2MB',
            'survey_in.integer' => 'Survey In must be a number',
            'survey_in.min' => 'Survey In must be at least 0',
            'survey_in.max' => 'Survey In must not exceed 100',
            'survey_in_damage.integer' => 'Survey In Damage must be a number',
            'survey_in_damage.min' => 'Survey In Damage must be at least 0',
            'survey_in_damage.max' => 'Survey In Damage must not exceed 100',
            'survey_out.integer' => 'Survey Out must be a number',
            'survey_out.min' => 'Survey Out must be at least 0',
            'survey_out.max' => 'Survey Out must not exceed 100',
            'crani_in.integer' => 'Crani In must be a number',
            'crani_in.min' => 'Crani In must be at least 0',
            'crani_in.max' => 'Crani In must not exceed 100',
            'crani_out.integer' => 'Crani Out must be a number',
            'crani_out.min' => 'Crani Out must be at least 0',
            'crani_out.max' => 'Crani Out must not exceed 100',
        ]);

        try {
            $this->locationManagementService->createLocation($validated, $request->file('logo'));
            return redirect()->route('admin.locations.index')->with('success', 'Location created successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create location: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified location
     */
    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    /**
     * Update the specified location in storage
     */
    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'location_code' => 'required|unique:locations,location_code,' . $location->id . '|max:10',
            'location_name' => 'required|max:100',
            'online_url' => 'required|url',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
            'survey_in' => 'nullable|integer|min:0|max:100',
            'survey_in_damage' => 'nullable|integer|min:0|max:100',
            'survey_out' => 'nullable|integer|min:0|max:100',
            'crani_in' => 'nullable|integer|min:0|max:100',
            'crani_out' => 'nullable|integer|min:0|max:100',
        ], [
            'location_code.required' => 'Location code is required',
            'location_code.unique' => 'Location code already exists',
            'location_code.max' => 'Location code must not exceed 10 characters',
            'location_name.required' => 'Location name is required',
            'location_name.max' => 'Location name must not exceed 100 characters',
            'online_url.required' => 'Online URL is required',
            'online_url.url' => 'Invalid URL format',
            'logo.image' => 'Logo must be an image file',
            'logo.mimes' => 'Logo must be a file of type: jpeg, jpg, png, gif, svg',
            'logo.max' => 'Logo size must not exceed 2MB',
            'survey_in.integer' => 'Survey In must be a number',
            'survey_in.min' => 'Survey In must be at least 0',
            'survey_in.max' => 'Survey In must not exceed 100',
            'survey_in_damage.integer' => 'Survey In Damage must be a number',
            'survey_in_damage.min' => 'Survey In Damage must be at least 0',
            'survey_in_damage.max' => 'Survey In Damage must not exceed 100',
            'survey_out.integer' => 'Survey Out must be a number',
            'survey_out.min' => 'Survey Out must be at least 0',
            'survey_out.max' => 'Survey Out must not exceed 100',
            'crani_in.integer' => 'Crani In must be a number',
            'crani_in.min' => 'Crani In must be at least 0',
            'crani_in.max' => 'Crani In must not exceed 100',
            'crani_out.integer' => 'Crani Out must be a number',
            'crani_out.min' => 'Crani Out must be at least 0',
            'crani_out.max' => 'Crani Out must not exceed 100',
        ]);

        try {
            $this->locationManagementService->updateLocation($location, $validated, $request->file('logo'));
            return redirect()->route('admin.locations.index')->with('success', 'Location updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update location: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified location from storage
     */
    public function destroy(Location $location)
    {
        try {
            if (!$this->locationManagementService->canDeleteLocation($location)) {
                return back()->with('error', 'Cannot delete location that is assigned to users');
            }

            $this->locationManagementService->deleteLocation($location);
            return redirect()->route('admin.locations.index')->with('success', 'Location deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete location: ' . $e->getMessage());
        }
    }
}
