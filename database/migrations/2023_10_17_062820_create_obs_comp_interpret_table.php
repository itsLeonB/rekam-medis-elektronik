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
        Schema::create('obs_comp_interpret', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('obs_comp_id');
            $table->index('obs_comp_id');
            $table->foreign('obs_comp_id')->references('id')->on('observation_component')->onDelete('cascade');
            $table->string('system');
            $table->string('code');
            $table->string('display');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obs_comp_interpret');
    }
};
