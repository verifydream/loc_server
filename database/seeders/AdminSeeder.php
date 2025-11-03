<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminEmail = 'admin@locationserver.com';
        
        // Check if admin already exists (idempotent)
        $exists = DB::table('admins')
            ->where('email', $adminEmail)
            ->exists();

        if (!$exists) {
            DB::table('admins')->insert([
                'name' => 'Administrator',
                'email' => $adminEmail,
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
