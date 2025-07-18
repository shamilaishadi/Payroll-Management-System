<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'basic_salary',
        'ot_rate_per_hour',
        'late_deduction_per_hour',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}