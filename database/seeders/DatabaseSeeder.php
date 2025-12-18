<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
public function run(): void
{
    // Create a test user first if none exists
    if (!\App\Models\User::where('email', 'client@example.com')->exists()) {
        \App\Models\User::create([
            'name' => 'Test Client',
            'email' => 'client@example.com',
            'password' => bcrypt('password'),
        ]);
    }
    
    $this->call([
        ServicesTableSeeder::class,
        StaffTableSeeder::class,
        ServiceStaffTableSeeder::class,
        WorkingHoursTableSeeder::class,  // ADD THIS LINE
        
    ]);
}
}