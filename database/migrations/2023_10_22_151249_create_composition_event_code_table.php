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
        Schema::create('composition_event_code', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('composition_event_id');
            $table->index('composition_event_id');
            $table->foreign('composition_event_id')->references('id')->on('composition_event')->onDelete('cascade');
            $table->string('system')->nullable();
            $table->string('code');
            $table->string('display')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('composition_event_code');
    }
};
