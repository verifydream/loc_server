<?php

// Test check-location API
// Usage: php test-check-location.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Testing check-location API behavior...\n\n";

// Test data
$testEmails = [
    'user1@dkm' => 'Should have multiple locations',
    'test_single@dkm' => 'Should have single location',
    'notfound@dkm' => 'Should not be found',
];

foreach ($testEmails as $email => $description) {
    echo str_repeat('=', 80) . "\n";
    echo "Testing: {$email}\n";
    echo "Expected: {$description}\n";
    echo str_repeat('-', 80) . "\n";
    
    // Query database
    $users = DB::table('users')
        ->join('locations', 'users.location_id', '=', 'locations.id')
        ->where('users.email', $email)
        ->where('users.status', 'active')
        ->select(
            'users.email',
            'locations.online_url',
            'locations.location_name',
            'locations.location_code',
            'locations.logo'
        )
        ->get();
    
    if ($users->isEmpty()) {
        echo "Result: ❌ Email not found in database\n";
        echo "API should return: 404 Not Found\n\n";
        continue;
    }
    
    echo "Found {$users->count()} location(s):\n";
    foreach ($users as $index => $user) {
        echo "  " . ($index + 1) . ". {$user->location_name} ({$user->location_code}) - {$user->online_url}\n";
    }
    
    if ($users->count() === 1) {
        echo "\nAPI should return: Single object (backward compatible)\n";
        echo "Format: {\"success\": true, \"data\": {...}}\n\n";
    } else {
        echo "\nAPI should return: Array of objects\n";
        echo "Format: {\"success\": true, \"data\": [{...}, {...}]}\n\n";
    }
}

echo str_repeat('=', 80) . "\n";
echo "Test Summary\n";
echo str_repeat('=', 80) . "\n\n";

// Count users with multiple locations
$multipleLocations = DB::table('users')
    ->select('email', DB::raw('COUNT(*) as count'))
    ->where('status', 'active')
    ->groupBy('email')
    ->having('count', '>', 1)
    ->get();

echo "Users with multiple locations: {$multipleLocations->count()}\n";
if ($multipleLocations->count() > 0) {
    echo "\nList:\n";
    foreach ($multipleLocations as $user) {
        echo "  - {$user->email} ({$user->count} locations)\n";
    }
}

echo "\n";

// Count users with single location
$singleLocation = DB::table('users')
    ->select('email', DB::raw('COUNT(*) as count'))
    ->where('status', 'active')
    ->groupBy('email')
    ->having('count', '=', 1)
    ->get();

echo "Users with single location: {$singleLocation->count()}\n";

echo "\n" . str_repeat('=', 80) . "\n";
echo "Testing complete!\n";
echo "\nTo test via API, use:\n";
echo "curl -X POST http://localhost:8000/api/check-location \\\n";
echo "  -H \"Content-Type: application/json\" \\\n";
echo "  -H \"X-API-Key: your_api_key\" \\\n";
echo "  -d '{\"email\": \"user1@dkm\"}'\n";
