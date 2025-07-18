<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateAttendancesTable extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->timestamp('sign_in_time')->nullable();
            $table->timestamp('sign_out_time')->nullable();

            $table->string('sign_in_status')->nullable(); // e.g. 'late', 'before'
            $table->string('sign_out_status')->nullable(); // e.g. 'early', 'over_time'

            $table->integer('worked_minutes')->nullable(); // derived
            $table->integer('overtime_minutes')->nullable(); // derived

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
