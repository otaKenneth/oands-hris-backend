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
        Schema::create('dtr_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dtr_period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shift_id')->nullable()->constrained()->nullOnDelete();
            $table->date('work_date');
            $table->time('scheduled_time_in')->nullable();
            $table->time('scheduled_time_out')->nullable();
            $table->dateTime('actual_time_in')->nullable();
            $table->dateTime('actual_time_out')->nullable();
            $table->integer('break_minutes')->nullable();
            $table->decimal('regular_hours', 8, 2)->nullable();
            $table->decimal('overtime_hours', 8, 2)->nullable();
            $table->decimal('night_differential_hours', 8, 2)->nullable();
            $table->integer('late_minutes')->nullable();
            $table->integer('undertime_minutes')->nullable();
            $table->string('attendance_status', 30)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique([
                'dtr_period_id',
                'employee_id',
                'work_date'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dtr_records');
    }
};
