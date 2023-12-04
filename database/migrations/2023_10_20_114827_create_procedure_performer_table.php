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
        Schema::create('procedure_performer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('procedure_id');
            $table->index('procedure_id');
            $table->foreign('procedure_id')->references('id')->on('procedure')->onDelete('cascade');
            $table->string('function')->nullable();
            $table->string('actor');
            $table->string('on_behalf_of')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedure_performer');
    }
};
