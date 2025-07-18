<?php

namespace App\Http\Controllers;

use App\Models\AttendanceAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AttendanceAdminAuthController extends Controller
{
    // --- API: Attendance Admin Login ---
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = AttendanceAdmin::where('username', $request->username)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $admin->createToken('attendance-admin-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'admin' => $admin->username,
        ]);
    }

    // --- Web: Show all Attendance Admins ---
    public function index()
    {
        $admins = AttendanceAdmin::all();
        return view('attendance_admins.index', compact('admins'));
    }

    // --- Web: Show create form ---
    public function create()
    {
        return view('attendance_admins.create');
    }

    // --- Web: Store new Attendance Admin ---
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:attendance_admins,username',
            'password' => 'required|string|min:6|confirmed',
        ]);

        AttendanceAdmin::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('attendance-admins.index')->with('success', 'Attendance admin created.');
    }

    // --- Web: Delete Attendance Admin ---
    public function destroy(AttendanceAdmin $attendanceAdmin)
    {
        $attendanceAdmin->delete();
        return redirect()->route('attendance-admins.index')->with('success', 'Attendance admin deleted.');
    }
}
