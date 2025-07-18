<?php

// app/Models/WorkSchedule.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',            // Name of the schedule (e.g., "Default Schedule")
        'user_id',         // User associated with the schedule
    ];

    // Relationship to WorkScheduleDay (Days of the week for the schedule)
    public function days()
    {
        return $this->hasMany(WorkScheduleDay::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_work_schedule');
    }
    
}
