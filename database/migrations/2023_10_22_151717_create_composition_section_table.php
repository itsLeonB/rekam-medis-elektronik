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
            $table->enum('code', ['10154-3', '10157-6', '10160-0', '10164-2', '10183-2', '10184-0', '10187-3', '10210-3', '10216-0', '10218-6', '10218-6', '10223-6', '10222-8', '11329-0', '11348-0', '11369-6', '57852-6', '11493-4', '11535-2', '11537-8', '18776-5', '18841-7', '29299-5', '29545-1', '29549-3', '29554-3', '29762-2', '30954-2', '42344-2', '42346-7', '42348-3', '42349-1', '46240-8', '46241-6', '46264-8', '47420-5', '47519-4', '48765-2', '48768-6', '51848-0', '55109-3', '55122-6', '59768-2', '59769-0', '59770-8', '59771-6', '59772-4', '59773-2', '59775-7', '59776-5', '61149-1', '61150-9', '61150-9', '69730-0', '8648-8', '8653-8', '8716-3'])->nullable();
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
