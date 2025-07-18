<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    public function showAttendanceForm()
    {
        $user = Auth::user();
        $now = now();

        // Get latest attendance
        $latestAttendance = Attendance::where('user_id', $user->id)->latest()->first();
        $nextAction = (!$latestAttendance || $latestAttendance->sign_out_time) ? 'in' : 'out';

        $statusMessage = $nextAction === 'in' ? 'You can sign in.' : 'Please sign out.';

        $workSchedule = $user->workSchedules()->with('days')->first();
        if (!$workSchedule) {
            return view('attendance.attendance-form', compact('nextAction') + [
                'statusMessage' => 'You have no work schedule assigned.',
                'isLate' => false,
                'isShortLeave' => false,
                'shiftStart' => null,
                'shiftEnd' => null,
            ]);
        }

        $timezone = config('app.timezone', 'Asia/Colombo');
        $now = Carbon::now($timezone);
        $today = $now->format('l');

        $workScheduleDay = $workSchedule->days->firstWhere('day', $today);
        if (!$workScheduleDay) {
            return view('attendance.attendance-form', compact('nextAction') + [
                'statusMessage' => 'You have no work schedule today.',
                'isLate' => false,
                'isShortLeave' => false,
                'shiftStart' => null,
                'shiftEnd' => null,
            ]);
        }

        $shiftStart = Carbon::createFromTimeString($workScheduleDay->start_time);
        $shiftEnd = Carbon::createFromTimeString($workScheduleDay->end_time);

        $isLate = $nextAction === 'in' && $now->gt($shiftStart);
        $isShortLeave = $nextAction === 'out' && $now->lt($shiftEnd);

        if ($isLate) {
            $statusMessage = 'You are late! Shift started at ' . $shiftStart->format('H:i');
        } elseif ($isShortLeave) {
            $statusMessage = 'You are on short leave. Shift ends at ' . $shiftEnd->format('H:i');
        }

        return view('attendance.attendance-form', compact(
            'nextAction', 'statusMessage', 'isLate', 'shiftStart', 'shiftEnd', 'isShortLeave'
        ));
    }

    public function storeSignIn(Request $request)
    {
        $request->validate(['notes' => 'nullable|string|max:255']);

        $user = Auth::user();
        $latest = Attendance::where('user_id', $user->id)->latest()->first();

        if ($latest && !$latest->sign_out_time) {
            return redirect()->route('attendance.create')->with('error', 'You are already signed in.');
        }

        $today = now()->format('l');
        $status = 'on_time';
        $now = now();

        $schedule = $user->workSchedules()->with('days')->first();
        if ($schedule) {
            $day = $schedule->days->firstWhere('day', $today);
            if ($day) {
                $shiftStart = Carbon::createFromFormat('H:i:s', $day->start_time);
                if ($now->gt($shiftStart)) $status = 'late';
                elseif ($now->lt($shiftStart->copy()->subHour())) $status = 'before';
                elseif ($now->gt($shiftStart->copy()->addHours(5))) $status = 'absent';
            } else {
                $status = 'before';
            }
        } else {
            $status = 'before';
        }

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'sign_in_time' => now(),
            'sign_in_status' => $status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('attendance.receipt', $attendance->id)
            ->with('success', 'Signed in successfully.');
    }

    public function storeSignOut(Request $request)
    {
        $request->validate(['notes' => 'nullable|string|max:255']);

        $user = Auth::user();
        $attendance = Attendance::where('user_id', $user->id)->whereNull('sign_out_time')->latest()->first();

        if (!$attendance) {
            return redirect()->route('attendance.create')->with('error', 'No active sign-in found.');
        }

        $now = now();
        $today = $now->format('l');
        $status = 'on_time';

        // Calculate total worked minutes
        $worked_minutes = $attendance->sign_in_time->diffInMinutes($now);

        // Default overtime to 0
        $overtime_minutes = 0;

        $schedule = $user->workSchedules()->with('days')->first();
        if ($schedule) {
            $day = $schedule->days->firstWhere('day', $today);
            if ($day) {
                // Create Carbon instances for shift start and end time on today's date for accurate diff
                $shiftStart = Carbon::createFromFormat('H:i:s', $day->start_time);
                $shiftEnd = Carbon::createFromFormat('H:i:s', $day->end_time);

                // Calculate scheduled shift duration in minutes
                $shiftDuration = $shiftStart->diffInMinutes($shiftEnd);

                if ($worked_minutes > $shiftDuration) {
                    // Worked more than scheduled shift — overtime is difference
                    $overtime_minutes = $worked_minutes - $shiftDuration;
                    $status = 'over_time';
                } else {
                    // Worked within shift hours — no overtime
                    $overtime_minutes = 0;

                    // Optionally check if early leaving
                    if ($now->lt($shiftEnd)) {
                        $status = 'early';
                    } else {
                        $status = 'on_time';
                    }
                }
            } else {
                // Day not found in schedule, all time considered overtime
                $overtime_minutes = $worked_minutes;
                $status = 'over_time';
            }
        } else {
            // No schedule found, all time considered overtime
            $overtime_minutes = $worked_minutes;
            $status = 'over_time';
        }

        $attendance->update([
            'sign_out_time' => $now,
            'sign_out_status' => $status,
            'notes' => $request->notes,
            'worked_minutes' => $worked_minutes,
            'overtime_minutes' => $overtime_minutes,
        ]);

        return redirect()->route('attendance.receipt', $attendance->id)->with('success', 'Signed out successfully.');
    }


    public function showReceipt($id)
    {
        $attendance = Attendance::with('user')->find($id);

        if (!$attendance) {
            return redirect()->route('attendance.create')->with('error', 'Invalid attendance record.');
        }

        return view('attendance.attendance-receipt', compact('attendance'));
    }

    public function view(Request $request)
    {
        $query = Attendance::with('user');

        if ($request->from_date) {
            $query->whereDate('sign_in_time', '>=', $request->from_date);
        } else {
            $query->whereDate('sign_in_time', now()->toDateString());
        }

        if ($request->to_date) {
            $query->whereDate('sign_in_time', '<=', $request->to_date);
        }

        $user = Auth::user();
        if (!$user->can('view_attendance')) {
            $query->where('user_id', $user->id);
        }

        if ($request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $attendances = $query->latest()->get();
        return view('attendance.view', compact('attendances'));
    }
}
