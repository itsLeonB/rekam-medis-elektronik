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
        Schema::create('clinic_impress_investigate_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('impress_investigate_id');
            $table->index('impress_investigate_id');
            $table->foreign('impress_investigate_id')->references('id')->on('clinical_impression_investigation')->onDelete('cascade');
            $table->string('reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_impress_investigate_item');
    }
};
