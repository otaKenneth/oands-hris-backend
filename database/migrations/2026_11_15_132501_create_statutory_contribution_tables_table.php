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
        Schema::create('statutory_contribution_tables', function (Blueprint $table) {
            $table->id();
            $table->string('agency', 30);
            $table->string('rule_name', 100);
            $table->string('calculation_method', 40);
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->decimal('employee_rate', 10, 6)->nullable();
            $table->decimal('employer_rate', 10, 6)->nullable();
            $table->decimal('salary_floor', 15, 2)->nullable();
            $table->decimal('salary_ceiling', 15, 2)->nullable();
            $table->decimal('employee_fixed_amount', 15, 2)->nullable();
            $table->decimal('employer_fixed_amount', 15, 2)->nullable();
            $table->decimal('minimum_contribution', 15, 2)->nullable();
            $table->decimal('maximum_contribution', 15, 2)->nullable();
            $table->json('parameters')->nullable();
            $table->string('source_reference', 500)->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('status', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statutory_contribution_tables');
    }
};
