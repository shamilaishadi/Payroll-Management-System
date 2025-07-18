<?php
namespace App\Http\Controllers;

use App\Mail\PaySheetMail;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SalaryEmailController extends Controller
{
    // Send paysheet to a single user
    public function sendToUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|date_format:Y-m',
        ]);

        $user = User::findOrFail($request->user_id);
        $summary = $this->generatePaysheetSummary($user, $request->month);

        Mail::to($user->email)->send(new PaySheetMail($user, $summary));

        return back()->with('success', "Paysheet sent to {$user->name}");
    }

    // Send paysheet to all users with attendance in that month
    public function sendToAll(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $start = Carbon::parse($request->month)->startOfMonth();
        $end = Carbon::parse($request->month)->endOfMonth();

        $userIds = Attendance::whereBetween('date', [$start, $end])
            ->distinct()->pluck('user_id');

        $users = User::whereIn('id', $userIds)->get();
        $sentCount = 0;

        foreach ($users as $user) {
            $summary = $this->generatePaysheetSummary($user, $request->month);
            Mail::to($user->email)->send(new PaySheetMail($user, $summary));
            $sentCount++;
        }

        return back()->with('success', "Paysheets sent to {$sentCount} users.");
    }

    // You must replace this with your actual paysheet generation logic
    private function generatePaysheetSummary(User $user, string $month): array
    {
        $salaryInfo = $user->salaryInfo;
        $basicSalary = $salaryInfo->basic_salary ?? 0;
        $otRate = $salaryInfo->ot_rate_per_hour ?? 0;
        $lateDeductionRate = $salaryInfo->late_deduction_per_hour ?? 0;

        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        $workSchedule = $user->workSchedules()->with('days')->first(); // 1 schedule per user
        $scheduledDays = $workSchedule
            ? $workSchedule->days->pluck('day')->map(fn($d) => strtolower($d))->toArray()
            : [];

        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('sign_in_time', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy(fn($a) => Carbon::parse($a->sign_in_time)->format('Y-m-d'));

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

                    $totalWorkedMinutes += $worked;
                    $totalOTMinutes += $ot;
                    $totalLateMinutes += $late;
                } else {
                    $hasLeave = \App\Models\Leave::where('user_id', $user->id)
                        ->where('status', 'approved')
                        ->whereDate('start_date', '<=', $day)
                        ->whereDate('end_date', '>=', $day)
                        ->exists();

                    if (!$hasLeave && !$date->isToday() && !$date->isFuture()) {
                        $absentDays++;
                    }
                }
            }
        }

        $epf_rate = 0.08;
        $workedDays = $shouldWorkDays - $absentDays;
        $salaryForWorkedDays = ($shouldWorkDays > 0) ? ($basicSalary / $shouldWorkDays) * $workedDays : 0;
        $otPay = round($totalOTMinutes / 60, 2) * $otRate;
        $absentDeduction = ($shouldWorkDays > 0) ? ($basicSalary / $shouldWorkDays) * $absentDays : 0;
        $lateDeduction = round($totalLateMinutes / 60, 2) * $lateDeductionRate;
        $epfDeduction = ($basicSalary + $otPay - ($absentDeduction + $lateDeduction)) * $epf_rate;
        $totalPay = $basicSalary + $otPay - ($absentDeduction + $lateDeduction + $epfDeduction);

        return [
            'month' => Carbon::parse($month)->format('F Y'),
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

}
