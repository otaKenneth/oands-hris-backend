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
        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payroll_period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dtr_period_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('run_number');
            $table->string('run_type', 30);
            $table->string('status', 30);
            $table->integer('total_employees');
            $table->decimal('total_gross_pay', 15, 2);
            $table->decimal('total_deductions', 15, 2);
            $table->decimal('total_net_pay', 15, 2);
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('finalized_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_runs');
    }
};
