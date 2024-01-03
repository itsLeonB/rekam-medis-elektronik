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
        Schema::create('medication_request', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->string('status');
            $table->string('intent');
            $table->enum('priority', ['routine', 'urgent', 'asap', 'stat'])->nullable();
            $table->boolean('do_not_perform')->nullable();
            $table->boolean('reported_boolean')->nullable();
            $table->dateTime('authored_on')->nullable();
            $table->json('instantiates_canonical')->nullable();
            $table->json('instantiates_uri')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_request');
    }
};
