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
        Schema::create('sampled_data', function (Blueprint $table) {
            $table->id();
            $table->decimal('period')->nullable();
            $table->decimal('factor')->nullable();
            $table->decimal('lower_limit')->nullable();
            $table->decimal('upper_limit')->nullable();
            $table->unsignedInteger('dimensions');
            $table->string('data')->nullable();
            $table->unsignedBigInteger('sampleable_id');
            $table->string('sampleable_type');
            $table->string('attr_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sampled_data');
    }
};
