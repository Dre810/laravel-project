<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Staff;

class ServiceStaffTableSeeder extends Seeder
{
    public function run(): void
    {
        // Get all services and staff
        $services = Service::all();
        $staff = Staff::all();

        // Assign services to staff (example assignments)
        $assignments = [
            1 => [1, 4],  // Service ID 1 (Haircut) assigned to Staff ID 1 (John) and 4 (Emma)
            2 => [4],     // Service ID 2 (Hair Coloring) assigned to Staff ID 4 (Emma)
            3 => [3],     // Service ID 3 (Manicure) assigned to Staff ID 3 (Mike)
            4 => [2],     // Service ID 4 (Massage) assigned to Staff ID 2 (Sarah)
        ];

        foreach ($assignments as $serviceId => $staffIds) {
            $service = Service::find($serviceId);
            $service->staff()->attach($staffIds);
        }
    }
}