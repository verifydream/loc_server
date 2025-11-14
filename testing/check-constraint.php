<?php

// Check database constraint
// Usage: php check-constraint.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking users table constraints...\n\n";

try {
    $result = DB::select("SHOW CREATE TABLE users");
    
    if (isset($result[0])) {
        $createTable = $result[0]->{'Create Table'};
        
        echo "CREATE TABLE statement:\n";
        echo str_repeat('=', 80) . "\n";
        echo $createTable . "\n";
        echo str_repeat('=', 80) . "\n\n";
        
        // Check for old constraint
        if (strpos($createTable, 'users_email_unique') !== false) {
            echo "❌ OLD CONSTRAINT FOUND: users_email_unique (email only)\n";
            echo "   Migration may not have run correctly!\n\n";
        } else {
            echo "✓ Old constraint removed\n\n";
        }
        
        // Check for new constraint
        if (strpos($createTable, 'users_email_location_unique') !== false) {
            echo "✓ NEW CONSTRAINT FOUND: users_email_location_unique (email + location_id)\n";
            echo "   Migration successful!\n\n";
        } else {
            echo "❌ NEW CONSTRAINT NOT FOUND: users_email_location_unique\n";
            echo "   Migration may have failed!\n\n";
        }
        
        // Test duplicate email in different locations
        echo "Testing duplicate email in different locations...\n";
        
        $testEmail = 'test_duplicate_' . time() . '@example.com';
        
        try {
            // Insert first user
            DB::table('users')->insert([
                'email' => $testEmail,
                'location_id' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "✓ Inserted: {$testEmail} with location_id=1\n";
            
            // Try insert same email with different location
            DB::table('users')->insert([
                'email' => $testEmail,
                'location_id' => 2,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "✓ Inserted: {$testEmail} with location_id=2\n";
            echo "\n✓✓✓ SUCCESS! Same email can be used in different locations!\n\n";
            
            // Cleanup
            DB::table('users')->where('email', $testEmail)->delete();
            echo "Cleanup: Test records deleted\n";
            
        } catch (\Exception $e) {
            echo "❌ FAILED: " . $e->getMessage() . "\n";
            echo "\nThis means the constraint is still the old one (email only).\n";
            echo "You need to run the migration again or manually fix the constraint.\n\n";
            
            // Cleanup
            DB::table('users')->where('email', $testEmail)->delete();
        }
        
    } else {
        echo "Failed to get table structure\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
