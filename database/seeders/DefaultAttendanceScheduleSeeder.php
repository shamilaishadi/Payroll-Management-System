<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkSchedule;
use App\Models\WorkScheduleDay;

class DefaultAttendanceScheduleSeeder extends Seeder
{
    public function run()
    {
        $schedule = WorkSchedule::create([
            'name' => 'Default Attendance Schedule (Mon-Fri 8AM–5PM SLT)',
        ]);

        $defaultStart = '08:00'; // UTC (8:00 SLT)
        $defaultEnd   = '17:00'; // UTC (5:00 SLT)

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        foreach ($days as $day) {
            WorkScheduleDay::create([
                'work_schedule_id' => $schedule->id,
                'day' => $day,
                'start_time' => $defaultStart,
                'end_time' => $defaultEnd,
            ]);
        }
    }
}
