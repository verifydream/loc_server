<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Location;
use App\Models\AppVersion;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics.
     */
    public function index()
    {
        // Get statistics - count unique emails instead of total records
        // since one email can be registered in multiple locations
        $totalUsers = User::distinct('email')->count('email');
        $activeUsers = User::where('status', 'active')->distinct('email')->count('email');
        $inactiveUsers = User::where('status', 'inactive')->distinct('email')->count('email');
        $totalLocations = Location::count();
        
        // Get latest app version
        $latestVersion = AppVersion::latest()->first();
        $totalVersions = AppVersion::count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'totalLocations',
            'latestVersion',
            'totalVersions'
        ));
    }
}
