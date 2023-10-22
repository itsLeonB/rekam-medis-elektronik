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
        Schema::create('composition_section', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('composition_id');
            $table->index('composition_id');
            $table->foreign('composition_id')->references('id')->on('composition')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('code')->nullable();
            $table->string('focus')->nullable();
            $table->enum('text_status', ['generated', 'extensions', 'additional', 'empty'])->nullable();
            $table->text('text_div')->nullable();
            $table->enum('mode', ['working', 'snapshot', 'changes'])->nullable();
            $table->enum('ordered_by', ['user', 'system', 'event-date', 'entry-date', 'priority', 'alphabetic', 'category', 'patient'])->nullable();
            $table->enum('empty_reason', ['nilknown', 'notasked', 'withheld', 'unavailable', 'notstarted', 'closed'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('composition_section');
    }
};
