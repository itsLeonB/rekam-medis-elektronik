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
        Schema::create('diagnostic_report_identifier', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('diagnostic_id');
            $table->index('diagnostic_id');
            $table->foreign('diagnostic_id')->references('id')->on('diagnostic_report')->onDelete('cascade');
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
        Schema::dropIfExists('diagnostic_report_identifier');
    }
};
