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
        Schema::create('organization', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->boolean('active');
            $table->string('name');
            $table->string('alias');
            $table->string('part_of');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization');
    }
};
