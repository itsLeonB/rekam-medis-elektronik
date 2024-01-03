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
        Schema::create('codesystem_resourcetype', function (Blueprint $table) {
            $table->id();
            $table->string('code', 33)->nullable();
            $table->string('display', 33)->nullable();
            $table->string('definition', 1545)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codesystem_resourcetype');
    }
};
