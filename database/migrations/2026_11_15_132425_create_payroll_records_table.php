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
        Schema::create('payroll_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payroll_run_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('salary_type', 30);
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('regular_hours', 8, 2);
            $table->decimal('overtime_hours', 8, 2);
            $table->decimal('gross_pay', 15, 2);
            $table->decimal('employee_deductions', 15, 2);
            $table->decimal('employer_contributions', 15, 2);
            $table->decimal('taxable_income', 15, 2);
            $table->decimal('withholding_tax', 15, 2);
            $table->decimal('net_pay', 15, 2);
            $table->json('calculation_details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_records');
    }
};
