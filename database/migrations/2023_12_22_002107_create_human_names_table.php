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
        Schema::create('human_names', function (Blueprint $table) {
            $table->id();
            $table->string('use')->nullable();
            $table->string('text')->nullable();
            $table->string('family')->nullable();
            $table->json('given')->nullable();
            $table->json('prefix')->nullable();
            $table->json('suffix')->nullable();
            $table->unsignedBigInteger('human_nameable_id');
            $table->string('human_nameable_type');
            $table->string('attr_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('human_names');
    }
};
