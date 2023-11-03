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
        Schema::create('codesystem_dischargedisposition', function (Blueprint $table) {
            $table->string('code')->unique();
            $table->index('code');
            $table->string('display');
            $table->string('definition');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codesystem_dischargedisposition');
    }
};
