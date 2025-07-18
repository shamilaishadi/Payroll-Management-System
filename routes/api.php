<?php

use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controllers\AttendanceAdminAuthController;

//test route to check if the API is working
Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

Route::post('/attendance/mark', [AttendanceApiController::class, 'mark']);

// Attendance Admin Login Routes
Route::post('/attendance-admin/login', [AttendanceAdminAuthController::class, 'login']);
Route::post('/attendance-admin/logout', [AttendanceAdminAuthController::class, 'logout']);
