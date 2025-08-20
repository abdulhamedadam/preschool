<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_attendance_details', function (Blueprint $table) {
            $table->id();
            $table->integer('student_attendance_id')->nullable();
            $table->integer('student_id')->nullable();
            $table->integer('teacher_id')->nullable();
            $table->integer('status')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_attendance_details');
    }
};
