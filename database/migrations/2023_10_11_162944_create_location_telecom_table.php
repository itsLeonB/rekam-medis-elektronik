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
        Schema::create('location_telecom', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id');
            $table->index('location_id');
            $table->foreign('location_id')->references('id')->on('location')->onDelete('cascade');
            $table->enum('system', ['phone', 'fax', 'email', 'pager', 'url', 'sms', 'other']);
            $table->enum('use', ['home', 'work', 'temp', 'old', 'mobile'])->nullable();
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_telecom');
    }
};
