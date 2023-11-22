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
        Schema::create('specimen_container_identifier', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('specimen_container_id');
            $table->index('specimen_container_id');
            $table->foreign('specimen_container_id')->references('id')->on('specimen_container')->onDelete('cascade');
            $table->string('system')->nullable();
            $table->enum('use', ['usual', 'official', 'temp', 'secondary', 'old'])->nullable();
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specimen_container_identifier');
    }
};
