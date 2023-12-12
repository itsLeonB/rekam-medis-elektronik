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
        Schema::create('resource', function (Blueprint $table) {
            $table->id();
            $table->string('satusehat_id')->nullable();
            $table->index('satusehat_id');
            $table->string('res_type');
            $table->index('res_type');
            $table->integer('res_version')->default(1);
            $table->string('fhir_ver')->default('R4');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource');
    }
};
