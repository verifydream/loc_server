<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['location_code' => 'sby', 'location_name' => 'Surabaya', 'online_url' => 'sby.web.com'],
            ['location_code' => 'jkt', 'location_name' => 'Jakarta', 'online_url' => 'jkt.web.com'],
            ['location_code' => 'blw', 'location_name' => 'Belawan', 'online_url' => 'blw.web.com'],
            ['location_code' => 'smr', 'location_name' => 'Semarang', 'online_url' => 'smr.web.com'],
            ['location_code' => 'bns', 'location_name' => 'BNS', 'online_url' => 'bns.web.com'],
            ['location_code' => 'java', 'location_name' => 'Java', 'online_url' => 'java.web.com'],
            ['location_code' => 'test', 'location_name' => 'Test', 'online_url' => 'test.web.com'],
            ['location_code' => 'dev', 'location_name' => 'Dev', 'online_url' => 'dev.web.com'],
        ];

        foreach ($locations as $location) {
            // Check if location already exists (idempotent)
            $exists = DB::table('locations')
                ->where('location_code', $location['location_code'])
                ->exists();

            if (!$exists) {
                DB::table('locations')->insert([
                    'location_code' => $location['location_code'],
                    'location_name' => $location['location_name'],
                    'online_url' => $location['online_url'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
