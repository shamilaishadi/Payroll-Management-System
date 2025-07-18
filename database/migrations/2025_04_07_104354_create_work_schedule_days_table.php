<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkScheduleDaysTable extends Migration
{
    public function up()
    {
        Schema::create('work_schedule_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_schedule_id')->constrained()->onDelete('cascade'); // Foreign key to WorkSchedule
            $table->string('day'); // Monday, Tuesday, etc.
            $table->time('start_time'); // Start time
            $table->time('end_time'); // End time
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('work_schedule_days');
    }
}
