<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkSchedule;
use App\Models\WorkScheduleDay;
use Illuminate\Http\Request;

class WorkScheduleController extends Controller
{
    // Display a listing of work schedules
    public function index()
    {
        $schedules = WorkSchedule::with('days')->get();
        return view('work_schedules.index', compact('schedules'));
    }

    // Show the form for creating a new work schedule
    public function create()
    {
        return view('work_schedules.create');
    }

    // Store a newly created work schedule in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'days' => 'array',
            'days.*.start_time' => 'nullable|date_format:H:i',
            'days.*.end_time' => 'nullable|date_format:H:i|after:days.*.start_time',
        ]);

        // Create the work schedule
        $schedule = WorkSchedule::create([
            'name' => $request->name,
        ]);

        // Store the schedule days
        foreach ($request->days as $day => $times) {
            if (!empty($times['start_time']) && !empty($times['end_time'])) {
                $schedule->days()->create([
                    'day_of_week' => $day,
                    'start_time' => $times['start_time'],
                    'end_time' => $times['end_time'],
                ]);
            }
        }

        return redirect()->route('work-schedules.index')->with('success', 'Work schedule created successfully.');
    }

    // Show the form for editing the specified work schedule
    public function edit($id)
    {
        $schedule = WorkSchedule::findOrFail($id);
        if (!$schedule) {
            return redirect()->route('work-schedules.index')->with('error', 'Work schedule not found.');
        }

        //get all days from work_schedule_days where work_schedule_id = $id
        $days = $schedule->days()->get()->keyBy('day')->map(function ($day) {
            return [
                'start_time' => $day->start_time,
                'end_time' => $day->end_time,
            ];
        });
    
        return view('work_schedules.edit', compact('schedule', 'days'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'days' => 'array',
            'days.*.start_time' => 'nullable|date_format:H:i',
            'days.*.end_time' => 'nullable|date_format:H:i|after:days.*.start_time',
        ]);

        $schedule = WorkSchedule::findOrFail($id);
        $schedule->update([
            'name' => $request->name,
        ]);

        // Clear existing schedule days
        $schedule->days()->delete();

        // Save updated schedule days
        foreach ($request->days as $dayName => $times) {
            if (!empty($times['start_time']) && !empty($times['end_time'])) {
                $dayIndex = \Carbon\Carbon::parse($dayName)->dayOfWeek; // Optional fallback if needed

                $schedule->days()->create([
                    'day' => $dayName, // Use day name directly like 'Monday'
                    'start_time' => $times['start_time'],
                    'end_time' => $times['end_time'],
                ]);
            }
        }

        return redirect()->route('work-schedules.index')
            ->with('success', 'Work schedule updated successfully.');
    }



    // Remove the specified work schedule from storage
    public function destroy($id)
    {
        $schedule = WorkSchedule::findOrFail($id);
        $schedule->days()->delete();  // Delete all days associated with the schedule
        $schedule->delete();          // Delete the schedule itself

        return redirect()->route('work-schedules.index')->with('success', 'Work schedule deleted successfully.');
    }

    // Show the form to assign work schedules to users
    public function assign()
    {
        $users = User::all();
        $schedules = WorkSchedule::all();
        
        // Get the currently assigned users for each schedule
        $assignedUsers = [];

        foreach ($schedules as $schedule) {
            $assignedUsers[$schedule->id] = $schedule->users->pluck('id')->toArray();
        }

        return view('work_schedules.assign', compact('users', 'schedules', 'assignedUsers'));
    }

    // Store the work schedule assignment for a user
    public function storeAssignment(Request $request)
    {
        // Validate the request
        $request->validate([
            'work_schedule_id' => 'required|exists:work_schedules,id',
        ]);

        //if request->users is empty, delete all users from work_schedule_user table where work_schedule_id = $request->work_schedule_id
        if (empty($request->users)) {
            WorkSchedule::findOrFail($request->work_schedule_id)->users()->detach();
            return redirect()->route('work-schedules.assign')->with('success', 'All users removed from the schedule successfully.');
        }

        // Get the work schedule
        $workSchedule = WorkSchedule::findOrFail($request->work_schedule_id);

        // Sync users with the work schedule
        $workSchedule->users()->sync($request->users);

        // delete recors from work_schedule_user table where work_schedule_id = $request->work_schedule_id and user_id not in $request->users
        $workSchedule->users()->whereNotIn('user_id', $request->users)->delete();
        
        return redirect()->route('work-schedules.assign')->with('success', 'Users assigned to the schedule successfully.');
    }

}
