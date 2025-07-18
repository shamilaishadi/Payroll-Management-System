<?php

namespace App\Http\Controllers\Api;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class AttendanceApiController extends Controller
{
    public function mark(Request $request)
    {
        $request->validate([
            'auth_type' => 'required|in:email,employee_id',
            'employee_id' => 'integer',
            'email' => 'email',
            'pin' => 'required|string',
        ]);
        $auth_type = $request->auth_type;

        if ($auth_type === 'email') {
            $request->validate(['email' => 'required|email']);
            $user = User::where('email', $request->email)->first();
        } else {
            $request->validate(['employee_id' => 'required|integer']);
            $user = User::where('id', $request->employee_id)->first();
        }
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }
        if ($user->pin !== $request->pin) {
            return response()->json(['error' => 'Invalid PIN.'], 401);
        }
        

        $now = now();
        $today = $now->format('l');

        $existing = Attendance::where('user_id', $user->id)
            ->whereDate('sign_in_time', $now->toDateString())
            ->latest()
            ->first();

        // === SIGN OUT if already signed in and not signed out ===
        if ($existing && !$existing->sign_out_time) {
            $worked_minutes = $existing->sign_in_time->diffInMinutes($now);
            $overtime_minutes = 0;
            $status = 'on_time';

            $schedule = $user->workSchedules()->with('days')->first();
            if ($schedule) {
                $day = $schedule->days->firstWhere('day', $today);
                if ($day) {
                    $shiftStart = Carbon::createFromFormat('H:i:s', $day->start_time);
                    $shiftEnd = Carbon::createFromFormat('H:i:s', $day->end_time);
                    $shiftDuration = $shiftStart->diffInMinutes($shiftEnd);

                    if ($worked_minutes > $shiftDuration) {
                        $overtime_minutes = $worked_minutes - $shiftDuration;
                        $status = 'over_time';
                    } elseif ($now->lt($shiftEnd)) {
                        $status = 'early';
                    }
                } else {
                    $overtime_minutes = $worked_minutes;
                    $status = 'over_time';
                }
            }

            $existing->update([
                'sign_out_time' => $now,
                'sign_out_status' => $status,
                'notes' => $request->notes,
                'worked_minutes' => $worked_minutes,
                'overtime_minutes' => $overtime_minutes,
            ]);

            return response()->json([
                'status' => 'signed_out',
                'sign_out_status' => $status,
                'user' => $user->name,
                'timestamp' => $now,
                'worked_minutes' => $worked_minutes,
                'overtime_minutes' => $overtime_minutes,
            ]);
        }

        // === SIGN IN ===
        $status = 'on_time';
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
            'sign_in_time' => $now,
            'sign_in_status' => $status,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'status' => 'signed_in',
            'sign_in_status' => $status,
            'user' => $user->name,
            'timestamp' => $now,
        ]);
    }
}
