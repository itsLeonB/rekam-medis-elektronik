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
        Schema::create('composition', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->string('identifier_system')->nullable();
            $table->string('identifier_use')->nullable();
            $table->string('identifier_value')->nullable();
            $table->enum('status', ['preliminary', 'final', 'amended', 'entered-in-error']);
            $table->string('type_system');
            $table->string('type_code');
            $table->string('type_display');
            $table->string('subject');
            $table->string('encounter')->nullable();
            $table->dateTime('date');
            $table->string('title');
            $table->enum('confidentiality', ['U', 'L', 'M', 'N', 'R', 'V'])->nullable();
            $table->string('custodian')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('composition');
    }
};
