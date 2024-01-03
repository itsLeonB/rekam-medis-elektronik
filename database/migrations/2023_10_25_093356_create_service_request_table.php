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
        Schema::create('service_request', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->json('instantiates_canonical')->nullable();
            $table->json('instantiates_uri')->nullable();
            $table->string('status');
            $table->string('intent');
            $table->string('priority')->nullable();
            $table->boolean('do_not_perform')->nullable();
            $table->dateTime('occurrence_date_time')->nullable();
            $table->boolean('as_needed_boolean')->nullable();
            $table->dateTime('authored_on')->nullable();
            $table->text('patient_instruction')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_request');
    }
};
