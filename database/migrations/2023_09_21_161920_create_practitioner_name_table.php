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
        Schema::create('practitioner_name', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('practitioner_id');
            $table->index('practitioner_id');
            $table->foreign('practitioner_id')->references('id')->on('practitioner')->onDelete('cascade');
            $table->string('use')->nullable();
            $table->string('text')->nullable();
            $table->string('family')->nullable();
            $table->json('given')->nullable();
            $table->json('prefix')->nullable();
            $table->json('suffix')->nullable();
            $table->dateTime('period_start')->nullable();
            $table->dateTime('period_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practitioner_name');
    }
};
