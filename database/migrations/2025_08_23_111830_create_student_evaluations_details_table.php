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
        Schema::create('student_evaluations_details', function (Blueprint $table) {
            $table->id();
            $table->integer('student_evaluation_id')->nullable();
            $table->integer('student_id')->nullable();
            $table->integer('subject_id')->nullable();
            $table->decimal('grade')->nullable();
            $table->string('evaluation')->nullable();
            $table->text('notes')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_evaluations_details');
    }
};
