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
        Schema::create('clinical_impression', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->enum('status', ['in-progress', 'completed', 'entered-in-error']);
            $table->string('status_reason_system')->nullable();
            $table->string('status_reason_code')->nullable();
            $table->string('status_reason_display')->nullable();
            $table->string('status_reason_text')->nullable();
            $table->string('code_system')->nullable();
            $table->string('code_code')->nullable();
            $table->string('code_display')->nullable();
            $table->text('description')->nullable();
            $table->string('subject');
            $table->string('encounter');
            $table->json('effective')->nullable();
            $table->dateTime('date')->nullable();
            $table->string('assessor')->nullable();
            $table->string('previous')->nullable();
            $table->text('summary')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinical_impression');
    }
};
