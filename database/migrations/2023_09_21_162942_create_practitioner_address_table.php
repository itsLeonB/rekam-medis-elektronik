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
        Schema::create('practitioner_address', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('practitioner_id');
            $table->index('practitioner_id');
            $table->foreign('practitioner_id')->references('id')->on('practitioner')->onDelete('cascade');
            $table->enum('use', ['home', 'work', 'temp', 'old', 'billing'])->nullable();
            $table->enum('type', ['postal', 'physical', 'both'])->nullable();
            $table->json('line')->nullable();
            $table->string('postal_code')->nullable();
            $table->char('country', 3);
            $table->unsignedInteger('province')->nullable();
            $table->unsignedInteger('city')->nullable();
            $table->unsignedBigInteger('district')->nullable();
            $table->unsignedBigInteger('village')->nullable();
            $table->unsignedInteger('rw')->nullable();
            $table->unsignedInteger('rt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practitioner_address');
    }
};
