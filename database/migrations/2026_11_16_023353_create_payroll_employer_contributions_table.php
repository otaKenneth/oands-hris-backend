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
        Schema::create('payroll_employer_contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_record_id')->constrained()->cascadeOnDelete();
            $table->string('agency', 30);
            $table->decimal('contribution_amount', 15, 2);
            $table->json('calculation_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_employer_contributions');
    }
};
