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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->nullable();
            $table->decimal('value')->nullable();
            $table->string('date')->nullable();
            $table->integer('student_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->text('notes')->nullable();
            $table->integer('status')->default(1)->nullable();
            $table->integer('table_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
