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
        Schema::create('location_type', function (Blueprint $table) {
            $table->id();
            $table->integer('location_id')->unsigned()->foreign('location_id')->references('id')->on('location');
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
        Schema::dropIfExists('location_type');
    }
};