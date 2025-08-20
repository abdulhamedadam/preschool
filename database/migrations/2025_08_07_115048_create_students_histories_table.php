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
        Schema::create('students_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id')->nullable();
            $table->string('action')->nullable(); // e.g., 'enrolled', 'graduated', 'transferred'
            $table->string('date')->nullable(); 
            $table->text('details')->nullable(); 
            $table->integer('created_by')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students_histories');
    }
};
