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
        Schema::create('specimen_processing', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('specimen_id');
            $table->index('specimen_id');
            $table->foreign('specimen_id')->references('id')->on('specimen')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->enum('procedure', ['ACID', 'ALK', 'DEFB', 'FILT', 'LDLP', 'NEUT', 'RECA', 'UFIL'])->nullable();
            $table->json('additive')->nullable();
            $table->json('time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specimen_processing');
    }
};
