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
        Schema::create('imaging_study_identifier', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('imaging_id');
            $table->index('imaging_id');
            $table->foreign('imaging_id')->references('id')->on('imaging_study')->onDelete('cascade');
            $table->string('system');
            $table->enum('use', ['usual', 'official', 'temp', 'secondary', 'old']);
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imaging_study_identifier');
    }
};
