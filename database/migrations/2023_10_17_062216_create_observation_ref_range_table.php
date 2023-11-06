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
        Schema::create('observation_ref_range', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('observation_id');
            $table->index('observation_id');
            $table->foreign('observation_id')->references('id')->on('observation')->onDelete('cascade');
            $table->decimal('value_low')->nullable();
            $table->decimal('value_high')->nullable();
            $table->string('unit')->nullable();
            $table->string('system')->nullable();
            $table->string('code')->nullable();
            $table->string('type')->nullable();
            $table->json('applies_to')->nullable();
            $table->integer('age_low')->nullable();
            $table->integer('age_high')->nullable();
            $table->text('text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observation_ref_range');
    }
};
