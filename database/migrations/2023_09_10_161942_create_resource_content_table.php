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
        Schema::create('resource_content', function (Blueprint $table) {
            $table->id();
            $table->string('res_id')->foreign('res_id')->references('res_id')->on('resource');
            $table->integer('res_ver')->default(1);
            $table->json('res_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_content');
    }
};
