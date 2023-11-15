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
        Schema::create('composition_section_entry', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('composition_section_id');
            $table->index('composition_section_id');
            $table->foreign('composition_section_id')->references('id')->on('composition_section')->onDelete('cascade');
            $table->string('reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('composition_section_entry');
    }
};
