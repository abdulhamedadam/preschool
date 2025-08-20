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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('level_id')->nullable();
            $table->string(('date_of_birth'))->nullable();
            $table->string('address')->nullable();
            $table->integer('guardian_id')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->text('notes')->nullable();
            $table->string('enrollment_date')->nullable();
            $table->integer('status')->default(1)->nullable();
            $table->integer('teacher_id')->nullable();
            $table->integer('class_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
