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
        Schema::create('medication_request', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->enum('status', ['active', 'on-hold', 'cancelled', 'completed', 'entered-in-error', 'stopped', 'draft', 'unknown']);
            $table->char('status_reason', 9)->nullable();
            $table->enum('priority', ['routine', 'urgent', 'asap', 'stat'])->nullable();
            $table->boolean('do_not_perform')->nullable();
            $table->boolean('reported')->nullable();
            $table->string('medication');
            $table->string('subject');
            $table->string('encounter')->nullable();
            $table->dateTime('authored_on')->nullable();
            $table->string('requester')->nullable();
            $table->string('performer')->nullable();
            $table->unsignedBigInteger('performer_type')->nullable();
            $table->string('recorder')->nullable();
            $table->enum('course_of_therapy', ['continuous', 'acute', 'seasonal'])->nullable();
            $table->decimal('dispense_interval_value')->nullable();
            $table->enum('dispense_interval_comparator', ['<', '<=', '>=', '>'])->nullable();
            $table->string('dispense_interval_unit')->nullable();
            $table->string('dispense_interval_system')->nullable();
            $table->string('dispense_interval_code')->nullable();
            $table->dateTime('validity_period_start')->nullable();
            $table->dateTime('validity_period_end')->nullable();
            $table->unsignedInteger('repeats_allowed')->nullable();
            $table->decimal('quantity_value')->nullable();
            $table->string('quantity_unit')->nullable();
            $table->string('quantity_system')->nullable();
            $table->string('quantity_code')->nullable();
            $table->decimal('supply_duration_value')->nullable();
            $table->enum('supply_duration_comparator', ['<', '<=', '>=', '>'])->nullable();
            $table->string('supply_duration_unit')->nullable();
            $table->string('supply_duration_system')->nullable();
            $table->string('supply_duration_code')->nullable();
            $table->string('dispense_performer')->nullable();
            $table->json('substitution_allowed')->nullable();
            $table->enum('substitution_reason', ['CT', 'FP', 'OS', 'RR'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_request');
    }
};
