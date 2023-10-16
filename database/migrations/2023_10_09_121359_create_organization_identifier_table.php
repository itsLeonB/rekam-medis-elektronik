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
        Schema::create('organization_identifier', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->foreign('organization_id')->references('id')->on('organization')->onDelete('cascade');
            $table->string('system');
            $table->string('use');
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_identifier');
    }
};
