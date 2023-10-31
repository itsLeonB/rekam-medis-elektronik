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
        Schema::create('codesystem_participanttype', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->index('code');
            $table->string('system');
            $table->string('display');
            $table->text('definition');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codesystem_participanttype');
    }
};
