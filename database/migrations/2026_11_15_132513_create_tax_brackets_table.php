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
        Schema::create('tax_brackets', function (Blueprint $table) {
            $table->id();
            $table->string('tax_type', 40);
            $table->year('tax_year');
            $table->string('compensation_period', 30);
            $table->decimal('income_min', 15, 2);
            $table->decimal('income_max', 15, 2)->nullable();
            $table->decimal('base_tax', 15, 2);
            $table->decimal('excess_rate', 10, 6);
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_brackets');
    }
};
