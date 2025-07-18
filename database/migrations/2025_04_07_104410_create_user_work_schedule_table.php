<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWorkScheduleTable extends Migration
{
    public function up()
    {
        Schema::create('user_work_schedule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key referencing users table
            $table->foreignId('work_schedule_id')->constrained()->onDelete('cascade'); // Foreign key referencing work_schedules table
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_work_schedule');
    }
}
