<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Repositories\LocationRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;
    protected $locationRepository;

    public function __construct(UserService $userService, LocationRepository $locationRepository)
    {
        $this->userService = $userService;
        $this->locationRepository = $locationRepository;
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
            'email' => 'required|email|unique:users,email',
            'location_id' => 'required|exists:locations,id',
            'status' => 'required|in:active,inactive',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format',
            'email.unique' => 'Email already exists',
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
            'email' => 'required|email|unique:users,email,' . $user->id,
            'location_id' => 'required|exists:locations,id',
            'status' => 'required|in:active,inactive',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format',
            'email.unique' => 'Email already exists',
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
}
