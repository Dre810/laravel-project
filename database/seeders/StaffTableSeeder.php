<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Staff;

class StaffTableSeeder extends Seeder
{
    public function run(): void
    {
        $staff = [
            [
                'first_name' => 'John',
                'last_name' => 'Barber',
                'email' => 'john@salon.com',
                'phone' => '555-0101',
                'specialty' => 'Senior Barber',
                'bio' => '10 years of experience in modern haircuts.',
                'photo_url' => null,
                'is_active' => true,
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Smith',
                'email' => 'sarah@salon.com',
                'phone' => '555-0102',
                'specialty' => 'Head Therapist',
                'bio' => 'Specialized in therapeutic and relaxation massages.',
                'photo_url' => null,
                'is_active' => true,
            ],
            [
                'first_name' => 'Mike',
                'last_name' => 'Johnson',
                'email' => 'mike@salon.com',
                'phone' => '555-0103',
                'specialty' => 'Nail Artist',
                'bio' => 'Creative nail designs and professional manicures.',
                'photo_url' => null,
                'is_active' => true,
            ],
            [
                'first_name' => 'Emma',
                'last_name' => 'Davis',
                'email' => 'emma@salon.com',
                'phone' => '555-0104',
                'specialty' => 'Color Specialist',
                'bio' => 'Expert in hair coloring and styling.',
                'photo_url' => null,
                'is_active' => true,
            ],
        ];

        foreach ($staff as $staffMember) {
            Staff::create($staffMember);
        }
    }
}