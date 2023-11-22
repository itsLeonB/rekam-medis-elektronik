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
        Schema::create('specimen_container', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('specimen_id');
            $table->index('specimen_id');
            $table->foreign('specimen_id')->references('id')->on('specimen')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->decimal('capacity_value')->nullable();
            $table->string('capacity_unit')->nullable();
            $table->string('capacity_system')->nullable();
            $table->string('capacity_code')->nullable();
            $table->decimal('specimen_quantity_value')->nullable();
            $table->string('specimen_quantity_unit')->nullable();
            $table->string('specimen_quantity_system')->nullable();
            $table->string('specimen_quantity_code')->nullable();
            $table->json('additive')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specimen_container');
    }
};
