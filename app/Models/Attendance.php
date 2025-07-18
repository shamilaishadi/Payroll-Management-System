<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sign_in_time',
        'sign_out_time',
        'sign_in_status',
        'sign_out_status',
        'worked_minutes',
        'overtime_minutes',
        'notes',
    ];

    protected $casts = [
        'sign_in_time' => 'datetime',
        'sign_out_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor to compute worked minutes if not stored
    public function getComputedWorkedMinutesAttribute()
    {
        if ($this->sign_in_time && $this->sign_out_time) {
            return $this->sign_in_time->diffInMinutes($this->sign_out_time);
        }
        return null;
    }

    // Accessor to compute overtime (requires you to pass shift end)
    public function computeOvertimeMinutes($shiftEndTime)
    {
        if ($this->sign_out_time && $shiftEndTime && $this->sign_out_time->greaterThan($shiftEndTime)) {
            return $this->sign_out_time->diffInMinutes($shiftEndTime);
        }
        return 0;
    }
}
