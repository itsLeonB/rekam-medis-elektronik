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
        Schema::create('codesystem_bcp13', function (Blueprint $table) {
            $table->string('code', 73);
            $table->string('display', 78);
            $table->string('extension', 12);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codesystem_bcp13');
    }
};
