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
        Schema::create('dosages', function (Blueprint $table) {
            $table->id();
            $table->integer('sequence')->nullable();
            $table->text('text')->nullable();
            $table->text('patient_instruction')->nullable();
            $table->boolean('as_needed_boolean')->nullable();
            $table->unsignedBigInteger('dosageable_id');
            $table->string('dosageable_type');
            $table->string('attr_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosages');
    }
};
