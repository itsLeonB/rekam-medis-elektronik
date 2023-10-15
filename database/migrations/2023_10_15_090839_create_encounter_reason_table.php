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
        Schema::create('encounter_reason', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('encounter_id')->foreign('encounter_id')->references('id')->on('encounter');
            $table->unsignedBigInteger('code');
            $table->string('reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encounter_reason');
    }
};
