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
        Schema::create('statutory_contribution_brackets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('statutory_contribution_table_id')->constrained()->cascadeOnDelete();
            $table->decimal('salary_min', 15, 2);
            $table->decimal('salary_max', 15, 2)->nullable();
            $table->decimal('monthly_salary_credit', 15, 2)->nullable();
            $table->decimal('employee_share', 15, 2);
            $table->decimal('employer_share', 15, 2);
            $table->decimal('employer_ec', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statutory_contribution_brackets');
    }
};
