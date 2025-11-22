<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Location;
use App\Services\UserService;
use App\Services\UserSyncService;
use App\Repositories\LocationRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;
    protected $locationRepository;
    protected $userSyncService;

    public function __construct(
        UserService $userService, 
        LocationRepository $locationRepository,
        UserSyncService $userSyncService
    ) {
        $this->userService = $userService;
        $this->locationRepository = $locationRepository;
        $this->userSyncService = $userSyncService;
    }

    /**
     * Display a listing of users with search and filters
     */
    public function index(Request $request)
    {
        $query = $request->input('search');
        $locationId = $request->input('location_id');
        $status = $request->input('status');

        $users = $this->userService->searchUsers($query, $locationId, $status);
        $locations = $this->locationRepository->getAll();

        return view('admin.users.index', compact('users', 'locations', 'query', 'locationId', 'status'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $locations = $this->locationRepository->getAll();
        return view('admin.users.create', compact('locations'));
    }

    /**
     * Store a newly created user in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                \Illuminate\Validation\Rule::unique('users')->where(function ($query) use ($request) {
                    return $query->where('location_id', $request->location_id);
                }),
            ],
            'location_id' => 'required|exists:locations,id',
            'status' => 'required|in:active,inactive',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format',
            'email.unique' => 'Email already exists for this location',
            'location_id.required' => 'Location is required',
            'location_id.exists' => 'Selected location is invalid',
            'status.required' => 'Status is required',
            'status.in' => 'Status must be either active or inactive',
        ]);

        try {
            $this->userService->createUser($validated);
            return redirect()->route('admin.users.index')->with('success', 'User created successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $locations = $this->locationRepository->getAll();
        return view('admin.users.edit', compact('user', 'locations'));
    }

    /**
     * Update the specified user in storage
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                \Illuminate\Validation\Rule::unique('users')->where(function ($query) use ($request) {
                    return $query->where('location_id', $request->location_id);
                })->ignore($user->id),
            ],
            'location_id' => 'required|exists:locations,id',
            'status' => 'required|in:active,inactive',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format',
            'email.unique' => 'Email already exists for this location',
            'location_id.required' => 'Location is required',
            'location_id.exists' => 'Selected location is invalid',
            'status.required' => 'Status is required',
            'status.in' => 'Status must be either active or inactive',
        ]);

        try {
            $this->userService->updateUser($user, $validated);
            return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(User $user)
    {
        try {
            $this->userService->deleteUser($user);
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Show sync preview for a location
     */
    public function syncPreview(Location $location)
    {
        $result = $this->userSyncService->previewSync($location);

        if (!$result['success']) {
            return back()->with('error', $result['error']);
        }

        return view('admin.users.sync-preview', [
            'location' => $location,
            'summary' => $result['summary'],
            'details' => $result['details'],
        ]);
    }

    /**
     * Execute sync for a location
     */
    public function syncExecute(Request $request, Location $location)
    {
        // Validate preview data from session or request
        $previewData = $request->input('preview_data');
        
        if (!$previewData) {
            return back()->with('error', 'Preview data not found. Please preview sync again.');
        }

        $result = $this->userSyncService->executeSync($location, json_decode($previewData, true));

        if (!$result['success']) {
            return redirect()->route('admin.users.index')
                ->with('error', $result['error']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', sprintf(
                'Sync completed successfully! %d users added, %d users deactivated, %d users unchanged.',
                $result['inserted'],
                $result['deleted'],
                $result['unchanged']
            ));
    }
}
