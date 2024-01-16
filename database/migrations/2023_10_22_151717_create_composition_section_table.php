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
        Schema::create('composition_section', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('composition_id')->nullable();
            $table->index('composition_id');
            $table->foreign('composition_id')->references('id')->on('composition')->onDelete('cascade');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->index('parent_id');
            $table->foreign('parent_id')->references('id')->on('composition_section')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->enum('mode', ['working', 'snapshot', 'changes'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('composition_section');
    }
};
