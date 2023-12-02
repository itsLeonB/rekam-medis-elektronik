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
        Schema::create('medication_statement_note', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('statement_id');
            $table->index('statement_id');
            $table->foreign('statement_id')->references('id')->on('medication_statement')->onDelete('cascade');
            $table->json('author')->nullable();
            $table->dateTime('time')->nullable();
            $table->string('text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_statement_note');
    }
};
