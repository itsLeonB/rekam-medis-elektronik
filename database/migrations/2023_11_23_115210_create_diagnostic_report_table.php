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
        Schema::create('diagnostic_report', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->json('based_on')->nullable();
            $table->enum('status', ['registered', 'partial', 'preliminary', 'final', 'amended', 'corrected', 'appended', 'cancelled', 'entered-in-error', 'unknown']);
            $table->json('category')->nullable();
            $table->string('code');
            $table->string('subject');
            $table->string('encounter');
            $table->json('effective')->nullable();
            $table->dateTime('issued')->nullable();
            $table->json('performer')->nullable();
            $table->json('results_interpreter')->nullable();
            $table->json('specimen')->nullable();
            $table->json('result')->nullable();
            $table->json('imaging_study')->nullable();
            $table->string('conclusion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostic_report');
    }
};
