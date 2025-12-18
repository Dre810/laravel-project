<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesTableSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Haircut',
                'description' => 'Basic haircut and styling',
                'duration' => 30,
                'price' => 25.00,
                'category' => 'Hair',
                'is_active' => true,
            ],
            [
                'name' => 'Hair Coloring',
                'description' => 'Full hair coloring service',
                'duration' => 120,
                'price' => 80.00,
                'category' => 'Hair',
                'is_active' => true,
            ],
            [
                'name' => 'Manicure',
                'description' => 'Basic nail care and polish',
                'duration' => 45,
                'price' => 20.00,
                'category' => 'Nails',
                'is_active' => true,
            ],
            [
                'name' => 'Massage Therapy',
                'description' => 'Full body therapeutic massage',
                'duration' => 60,
                'price' => 65.00,
                'category' => 'Spa',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}