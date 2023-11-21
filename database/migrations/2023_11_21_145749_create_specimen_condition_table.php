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
        Schema::create('specimen_condition', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('specimen_id');
            $table->index('specimen_id');
            $table->foreign('specimen_id')->references('id')->on('specimen')->onDelete('cascade');
            $table->string('system')->nullable();
            $table->enum('code', ['AUT', 'CFU', 'CLOT', 'CON', 'COOL', 'FROZ', 'HEM', 'LIVE', 'ROOM']);
            $table->string('display')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specimen_condition');
    }
};
