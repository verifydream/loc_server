<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get location IDs
        $locations = DB::table('locations')->get()->keyBy('location_code');

        $users = [
            // Surabaya users
            ['email' => 'sby_survey@web.com', 'location_code' => 'sby'],
            ['email' => 'sby_crani@web.com', 'location_code' => 'sby'],
            
            // Jakarta users
            ['email' => 'jkt_survey@web.com', 'location_code' => 'jkt'],
            ['email' => 'jkt_crani@web.com', 'location_code' => 'jkt'],
            
            // Belawan users
            ['email' => 'blw_survey@web.com', 'location_code' => 'blw'],
            ['email' => 'blw_crani@web.com', 'location_code' => 'blw'],
            
            // Semarang users
            ['email' => 'smr_survey@web.com', 'location_code' => 'smr'],
            ['email' => 'smr_crani@web.com', 'location_code' => 'smr'],
            
            // BNS users
            ['email' => 'bns_survey@web.com', 'location_code' => 'bns'],
            ['email' => 'bns_crani@web.com', 'location_code' => 'bns'],
            
            // Java users
            ['email' => 'java_survey@web.com', 'location_code' => 'java'],
            ['email' => 'java_crani@web.com', 'location_code' => 'java'],
            
            // Test users
            ['email' => 'test_survey@web.com', 'location_code' => 'test'],
            ['email' => 'test_crani@web.com', 'location_code' => 'test'],
            
            // Dev users
            ['email' => 'dev_survey@web.com', 'location_code' => 'dev'],
            ['email' => 'dev_crani@web.com', 'location_code' => 'dev'],
        ];

        foreach ($users as $user) {
            // Check if user already exists (idempotent)
            $exists = DB::table('users')
                ->where('email', $user['email'])
                ->exists();

            if (!$exists && isset($locations[$user['location_code']])) {
                DB::table('users')->insert([
                    'email' => $user['email'],
                    'location_id' => $locations[$user['location_code']]->id,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
