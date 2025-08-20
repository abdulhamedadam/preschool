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
        Schema::create('student_evaluation_details', function (Blueprint $table) {
            $table->id();
            $table->integer('student_evaluation_id')->nullable();
            $table->integer('student_id')->nullable();
            $table->double('noor_elbian_degree')->nullable();
            $table->text('noor_elbian_notes')->nullable();
            $table->double('hesab_degree')->nullable();
            $table->text('hesab_notes')->nullable();
            $table->double('english_degree')->nullable();
            $table->text('english_notes')->nullable();
            $table->double('math_degree')->nullable();
            $table->text('math_notes')->nullable();
            $table->double('activity_degree')->nullable();
            $table->text('activity_notes')->nullable();
            $table->double('mahfozat_degree')->nullable();
            $table->text('mahfozat_notes')->nullable();
            $table->double('qyam_degree')->nullable();
            $table->text('qyam_notes')->nullable();
            $table->double('quran_degree')->nullable();
            $table->text('quran_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_evaluation_details');
    }
};
