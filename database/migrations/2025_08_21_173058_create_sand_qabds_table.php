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
        Schema::create('sand_qabds', function (Blueprint $table) {
            $table->id();
            $table->integer('code')->nullable();
            $table->integer('student_id')->nullable();
            $table->string('date')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('value')->nullable();
            $table->integer('status')->default(1)->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sand_qabds');
    }
};
