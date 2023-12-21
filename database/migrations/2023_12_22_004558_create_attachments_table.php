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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('content_type')->nullable();
            $table->string('language')->nullable();
            $table->string('data')->nullable();
            $table->string('url')->nullable();
            $table->unsignedInteger('size')->nullable();
            $table->string('hash')->nullable();
            $table->string('title')->nullable();
            $table->dateTime('creation')->nullable();
            $table->unsignedBigInteger('attachable_id');
            $table->string('attachable_type');
            $table->string('attr_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
