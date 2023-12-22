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
        Schema::create('complex_extensions', function (Blueprint $table) {
            $table->id();
            $table->json('extension');
            $table->string('url');
            $table->unsignedBigInteger('complex_extendable_id');
            $table->string('complex_extendable_type');
            $table->string('attr_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complex_extensions');
    }
};
