<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WorkingHour;
use App\Models\Staff;

class WorkingHoursTableSeeder extends Seeder
{
    public function run(): void
    {
        // Days of the week
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        
        // Get all staff
        $staff = Staff::all();
        
        foreach ($staff as $staffMember) {
            foreach ($days as $day) {
                // Different schedules based on staff ID
                $isWorking = true;
                
                // Set different schedules
                if ($staffMember->id == 1) { // John - Mon-Fri, 9-5
                    if ($day == 'saturday' || $day == 'sunday') {
                        $isWorking = false;
                        WorkingHour::create([
                            'staff_id' => $staffMember->id,
                            'day_of_week' => $day,
                            'is_working' => false,
                        ]);
                        continue;
                    }
                    
                    WorkingHour::create([
                        'staff_id' => $staffMember->id,
                        'day_of_week' => $day,
                        'start_time' => '09:00:00',
                        'end_time' => '17:00:00',
                        'break_start' => '13:00:00',
                        'break_end' => '14:00:00',
                        'is_working' => true,
                    ]);
                    
                } elseif ($staffMember->id == 2) { // Sarah - Tue-Sat, 10-6
                    if ($day == 'sunday' || $day == 'monday') {
                        $isWorking = false;
                        WorkingHour::create([
                            'staff_id' => $staffMember->id,
                            'day_of_week' => $day,
                            'is_working' => false,
                        ]);
                        continue;
                    }
                    
                    WorkingHour::create([
                        'staff_id' => $staffMember->id,
                        'day_of_week' => $day,
                        'start_time' => '10:00:00',
                        'end_time' => '18:00:00',
                        'break_start' => '14:00:00',
                        'break_end' => '15:00:00',
                        'is_working' => true,
                    ]);
                    
                } elseif ($staffMember->id == 3) { // Mike - Wed-Sun, 11-7
                    if ($day == 'monday' || $day == 'tuesday') {
                        $isWorking = false;
                        WorkingHour::create([
                            'staff_id' => $staffMember->id,
                            'day_of_week' => $day,
                            'is_working' => false,
                        ]);
                        continue;
                    }
                    
                    WorkingHour::create([
                        'staff_id' => $staffMember->id,
                        'day_of_week' => $day,
                        'start_time' => '11:00:00',
                        'end_time' => '19:00:00',
                        'is_working' => true,
                    ]);
                    
                } elseif ($staffMember->id == 4) { // Emma - Mon-Sat, 8-4
                    if ($day == 'sunday') {
                        $isWorking = false;
                        WorkingHour::create([
                            'staff_id' => $staffMember->id,
                            'day_of_week' => $day,
                            'is_working' => false,
                        ]);
                        continue;
                    }
                    
                    WorkingHour::create([
                        'staff_id' => $staffMember->id,
                        'day_of_week' => $day,
                        'start_time' => '08:00:00',
                        'end_time' => '16:00:00',
                        'break_start' => '12:00:00',
                        'break_end' => '13:00:00',
                        'is_working' => true,
                    ]);
                }
            }
        }
    }
}