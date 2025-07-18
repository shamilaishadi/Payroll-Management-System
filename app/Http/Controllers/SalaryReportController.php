<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\SalaryInfo;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Leave;

class SalaryReportController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
        $selectedUserId = $request->user_id ?? null;
        $selectedMonth = $request->month ?? now()->format('Y-m');

        //if no work schedule is set, send error and return salary-report.index with error message
        if ($selectedUserId && !User::find($selectedUserId)->workSchedules()->exists()) {
            return redirect()->route('salary-report.index')->withErrors(['error' => 'No work schedule set for this user.']);
        }

        $calendarData = [];
        $summary = [];

        if ($selectedUserId) {
            $user = User::findOrFail($selectedUserId);
            $salaryInfo = $user->salaryInfo;
            $basicSalary = $salaryInfo->basic_salary ?? 0;
            $otRate = $salaryInfo->ot_rate_per_hour ?? 0;
            $lateDeductionRate = $salaryInfo->late_deduction_per_hour ?? 0;

            $startOfMonth = Carbon::parse($selectedMonth)->startOfMonth();
            $endOfMonth = Carbon::parse($selectedMonth)->endOfMonth();

            // Get user's schedule days (e.g., Mon, Tue)
            $workSchedule = $user->workSchedules()->with('days')->first(); // assumes 1 schedule per user
            $scheduledDays = $workSchedule
                ? $workSchedule->days->pluck('day')->map(fn($d) => strtolower($d))->toArray()
                : [];

            // Get attendance records in the month
            $attendances = Attendance::where('user_id', $user->id)
                ->whereBetween('sign_in_time', [$startOfMonth, $endOfMonth])
                ->get()
                ->groupBy(fn($a) => Carbon::parse($a->sign_in_time)->format('Y-m-d'));

            $calendarData = [];
            $totalWorkedMinutes = 0;
            $totalOTMinutes = 0;
            $totalLateMinutes = 0;
            $absentDays = 0;
            $shouldWorkDays = 0;

            for ($date = $startOfMonth->copy(); $date <= $endOfMonth; $date->addDay()) {
                $dayName = strtolower($date->format('l'));
                $day = $date->format('Y-m-d');

                if (in_array($dayName, $scheduledDays)) {
                    $shouldWorkDays++;

                    if (isset($attendances[$day])) {
                        $record = $attendances[$day]->first(); // One per day
                        $worked = $record->worked_minutes;
                        $ot = $record->overtime_minutes;
                        $late = $record->late_minutes ?? 0;

                        $calendarData[$day] = [
                            'worked_hours' => round($worked / 60, 2),
                            'ot_hours' => round($ot / 60, 2),
                            'status' => 'Present',
                        ];

                        $totalWorkedMinutes += $worked;
                        $totalOTMinutes += $ot;
                        $totalLateMinutes += $late;
                    } else {
                        // Check for approved leave on this day
                        $hasLeave = Leave::where('user_id', $user->id)
                            ->where('status', 'approved')
                            ->whereDate('start_date', '<=', $day)
                            ->whereDate('end_date', '>=', $day)
                            ->exists();
                        if ($hasLeave) {
                            $calendarData[$day] = [
                                'worked_hours' => 0,
                                'ot_hours' => 0,
                                'status' => 'Leave',
                            ];
                        } else if ($date->isToday()) {
                            $calendarData[$day] = [
                                'worked_hours' => 0,
                                'ot_hours' => 0,
                                'status' => 'Today',
                            ];
                        } elseif ($date->isFuture()) {
                            $calendarData[$day] = [
                                'worked_hours' => 0,
                                'ot_hours' => 0,
                                'status' => 'Future',
                            ];
                        } else {
                            $calendarData[$day] = [
                                'worked_hours' => 0,
                                'ot_hours' => 0,
                                'status' => 'Absent',
                            ];
                            $absentDays++;
                        }
                    }
                }
            }

            $epf_rate =0.08;
            $workedDays = $shouldWorkDays - $absentDays;
            $salaryForWorkedDays = ($shouldWorkDays > 0) ? ($basicSalary / $shouldWorkDays) * $workedDays : 0;
            $otPay = round($totalOTMinutes / 60, 2) * $otRate;
            $absentDeduction = ($shouldWorkDays > 0) ? ($basicSalary / $shouldWorkDays) * $absentDays : 0;
            $lateDeduction = round($totalLateMinutes / 60, 2) * $lateDeductionRate;
            $epfDeduction = ($basicSalary + $otPay - ($absentDeduction + $lateDeduction)) * $epf_rate;
            $totalPay = $basicSalary + $otPay - ($absentDeduction + $lateDeduction + $epfDeduction);

            $summary = [
                'month' => Carbon::parse($selectedMonth)->format('F Y'),
                'basic_salary' => $basicSalary,
                'worked_hours' => round($totalWorkedMinutes / 60, 2),
                'ot_hours' => round($totalOTMinutes / 60, 2),
                'absent_days' => $absentDays,
                'absent_deduction' => round($absentDeduction, 2),
                'late_deduction' => round($lateDeduction, 2),
                'ot_pay' => round($otPay, 2),
                'salary' => round($salaryForWorkedDays, 2),
                'total_pay' => round($totalPay, 2),
                'epf_deduction' => round($epfDeduction, 2),
            ];
        }

        return view('salary.report', compact('users', 'calendarData', 'summary', 'selectedUserId', 'selectedMonth'));
    }

    public function myReport(Request $request)
    {
        $user = auth()->user();
        $selectedMonth = $request->month ?? now()->format('Y-m');
        $startOfMonth = Carbon::parse($selectedMonth)->startOfMonth();
        $endOfMonth = Carbon::parse($selectedMonth)->endOfMonth();
        $today = now();

        $salaryInfo = $user->salaryInfo;
        $basicSalary = $salaryInfo->basic_salary ?? 0;
        $otRate = $salaryInfo->ot_rate_per_hour ?? 0;
        $lateDeductionRate = $salaryInfo->late_deduction_per_hour ?? 0;

        $workSchedule = $user->workSchedules()->with('days')->first();
        $scheduledDays = $workSchedule
            ? $workSchedule->days->pluck('day')->map(fn($d) => strtolower($d))->toArray()
            : [];

        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('sign_in_time', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy(fn($a) => Carbon::parse($a->sign_in_time)->format('Y-m-d'));

        $calendarData = [];
        $totalWorkedMinutes = 0;
        $totalOTMinutes = 0;
        $totalLateMinutes = 0;
        $absentDays = 0;
        $shouldWorkDays = 0;

        for ($date = $startOfMonth->copy(); $date <= $endOfMonth; $date->addDay()) {
            $dayName = strtolower($date->format('l'));
            $day = $date->format('Y-m-d');

            if (in_array($dayName, $scheduledDays)) {
                $shouldWorkDays++;

                if (isset($attendances[$day])) {
                    $record = $attendances[$day]->first();
                    $worked = $record->worked_minutes;
                    $ot = $record->overtime_minutes;
                    $late = $record->late_minutes ?? 0;

                    $calendarData[$day] = [
                        'worked_hours' => round($worked / 60, 2),
                        'ot_hours' => round($ot / 60, 2),
                        'status' => 'Present',
                    ];

                    $totalWorkedMinutes += $worked;
                    $totalOTMinutes += $ot;
                    $totalLateMinutes += $late;
                } else {
                    // Check for approved leave on this day
                    $hasLeave = \App\Models\Leave::where('user_id', $user->id)
                        ->where('status', 'approved')
                        ->whereDate('start_date', '<=', $day)
                        ->whereDate('end_date', '>=', $day)
                        ->exists();
                    if ($hasLeave) {
                        $calendarData[$day] = [
                            'worked_hours' => 0,
                            'ot_hours' => 0,
                            'status' => 'Leave',
                        ];
                    } else {
                        $calendarData[$day] = [
                            'worked_hours' => 0,
                            'ot_hours' => 0,
                            'status' => 'Absent',
                        ];
                        $absentDays++;
                    }
                }
            }
        }

        $epf_rate =0.08;
        $workedDays = $shouldWorkDays - $absentDays;
        $salaryForWorkedDays = ($shouldWorkDays > 0) ? ($basicSalary / $shouldWorkDays) * $workedDays : 0;
        $otPay = round($totalOTMinutes / 60, 2) * $otRate;
        $absentDeduction = ($shouldWorkDays > 0) ? ($basicSalary / $shouldWorkDays) * $absentDays : 0;
        $lateDeduction = round($totalLateMinutes / 60, 2) * $lateDeductionRate;
        $epfDeduction = ($basicSalary + $otPay - ($absentDeduction + $lateDeduction)) * $epf_rate;
        $totalPay = $basicSalary + $otPay - ($absentDeduction + $lateDeduction + $epfDeduction);

        $summary = [
            'month' => Carbon::parse($selectedMonth)->format('F Y'),
            'basic_salary' => $basicSalary,
            'worked_hours' => round($totalWorkedMinutes / 60, 2),
            'ot_hours' => round($totalOTMinutes / 60, 2),
            'absent_days' => $absentDays,
            'absent_deduction' => round($absentDeduction, 2),
            'late_deduction' => round($lateDeduction, 2),
            'ot_pay' => round($otPay, 2),
            'salary' => round($salaryForWorkedDays, 2),
            'total_pay' => round($totalPay, 2),
            'epf_deduction' => round($epfDeduction, 2),
        ];

        $previousMonth = Carbon::parse($selectedMonth)->subMonth()->format('Y-m');
        $nextMonth = Carbon::parse($selectedMonth)->addMonth();
        $disableNext = $nextMonth->gt(Carbon::now());
        $nextMonth = $nextMonth->format('Y-m');

        return view('salary.my-report', compact('calendarData', 'summary', 'selectedMonth', 'previousMonth', 'nextMonth', 'disableNext'));
    }




}
