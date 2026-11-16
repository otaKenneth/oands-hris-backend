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
        Schema::create('payroll_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_record_id')->constrained()->cascadeOnDelete();
            $table->string('earning_code', 50);
            $table->string('earning_name', 100);
            $table->decimal('quantity', 10, 2);
            $table->decimal('rate', 15, 4);
            $table->decimal('amount', 15, 2);
            $table->boolean('is_taxable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_earnings');
    }
};
