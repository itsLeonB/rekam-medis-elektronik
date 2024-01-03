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
        Schema::create('contact_points', function (Blueprint $table) {
            $table->id();
            $table->string('system')->nullable();
            $table->string('value')->nullable();
            $table->string('use')->nullable();
            $table->unsignedInteger('rank')->nullable();
            $table->unsignedBigInteger('contact_pointable_id');
            $table->string('contact_pointable_type');
            $table->string('attr_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_points');
    }
};
