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
        Schema::create('practitioner_qualification', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('practitioner_id');
            $table->index('practitioner_id');
            $table->foreign('practitioner_id')->references('id')->on('practitioner')->onDelete('cascade');
            $table->json('identifier')->nullable();
            $table->json('code');
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->string('issuer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practitioner_qualification');
    }
};
