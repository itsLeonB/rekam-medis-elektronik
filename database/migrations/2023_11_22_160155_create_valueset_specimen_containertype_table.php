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
        Schema::create('valueset_specimen_containertype', function (Blueprint $table) {
            $table->string('code', 10)->primary();
            $table->string('display', 101);
            $table->string('definition', 119);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valueset_specimen_containertype');
    }
};
