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
        Schema::create('medication_dispense_identifier', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dispense_id');
            $table->index('dispense_id');
            $table->foreign('dispense_id')->references('id')->on('medication_dispense')->onDelete('cascade');
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
        Schema::dropIfExists('medication_dispense_identifier');
    }
};
