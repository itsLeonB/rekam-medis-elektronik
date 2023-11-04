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
        Schema::create('observation_component', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('observation_id');
            $table->index('observation_id');
            $table->foreign('observation_id')->references('id')->on('observation')->onDelete('cascade');
            $table->string('code_system')->nullable();
            $table->string('code_code');
            $table->string('code_display')->nullable();
            $table->json('value')->nullable();
            $table->string('data_absent_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observation_component');
    }
};
