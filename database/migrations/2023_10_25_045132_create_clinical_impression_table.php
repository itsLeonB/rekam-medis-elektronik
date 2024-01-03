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
        Schema::create('clinical_impression', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->enum('status', ['in-progress', 'completed', 'entered-in-error']);
            $table->text('description')->nullable();
            $table->dateTime('effective_date_time')->nullable();
            $table->dateTime('date')->nullable();
            $table->json('protocol')->nullable();
            $table->string('summary')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinical_impression');
    }
};
