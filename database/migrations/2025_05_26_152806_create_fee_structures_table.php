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
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->integer('class_id')->nullable();
            $table->decimal('admission_fee', 10, 2)->nullable();
            $table->decimal('annual_fee', 10, 2)->nullable();
            $table->decimal('tution_fee', 10, 2)->nullable();
            $table->decimal('transport_fee', 10, 2)->nullable();
            $table->integer('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
};
