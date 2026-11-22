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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->text('description')->nullabe();
            $table->integer('day_of_week');
            $table->time('clock_in');
            $table->time('clock_out');
            $table->time('break_in')->nullable();
            $table->time('break_out')->nullable();
            $table->boolean('allow_break')->default(true);
            $table->boolean('is_flexible')->default(false);
            $table->boolean('is_general_shift')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
