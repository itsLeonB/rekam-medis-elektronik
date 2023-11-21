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
        Schema::create('specimen_parent', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('specimen_id');
            $table->index('specimen_id');
            $table->foreign('specimen_id')->references('id')->on('specimen')->onDelete('cascade');
            $table->string('reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specimen_parent');
    }
};
