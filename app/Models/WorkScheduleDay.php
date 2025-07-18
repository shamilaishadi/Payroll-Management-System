<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkScheduleDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_schedule_id', // Foreign key to work_schedule
        'day',              // Day of the week
        'start_time',       // Start time
        'end_time',         // End time
    ];

    // Define the inverse relationship to WorkSchedule
    public function workSchedule()
    {
        return $this->belongsTo(WorkSchedule::class);
    }
}