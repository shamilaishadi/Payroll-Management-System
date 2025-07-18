<?php
namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $leaves = Leave::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('leaves.index', compact('leaves'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);

        Leave::create([
            'user_id' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
        ]);

        return redirect()->route('leaves.index')->with('success', 'Leave request submitted.');
    }

    public function manage(Request $request)
    {
        $query = Leave::query()->with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('start_date', '<=', $request->date)
                  ->whereDate('end_date', '>=', $request->date);
        }

        $leaves = $query->latest()->get();
        $users = User::orderBy('name')->get();

        return view('leaves.manage', compact('leaves', 'users'));
    }

    public function approve(Leave $leave)
    {
        $leave->update([
            'status' => 'approved',
            'approved_by_id' => Auth::id(),
        ]);

        return back()->with('success', 'Leave approved.');
    }

    public function reject(Leave $leave)
    {
        $leave->update([
            'status' => 'rejected',
            'approved_by_id' => Auth::id(),
        ]);

        return back()->with('success', 'Leave rejected.');
    }
}
