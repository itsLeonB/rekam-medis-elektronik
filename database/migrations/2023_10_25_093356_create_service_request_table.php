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
        Schema::create('service_request', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->string('requisition_system')->nullable();
            $table->enum('requisition_use', ['usual', 'official', 'temp', 'secondary', 'old'])->nullable();
            $table->string('requisition_value')->nullable();
            $table->enum('status', ['draft', 'active', 'on-hold', 'revoked', 'completed', 'entered-in-error', 'unknown']);
            $table->enum('intent', ['proposal', 'plan', 'directive', 'order', 'original-order', 'reflex-order', 'filler-order', 'instance-order', 'option']);
            $table->enum('priority', ['routine', 'urgent', 'asap', 'stat'])->nullable();
            $table->boolean('do_not_perform')->nullable();
            $table->string('code_system')->nullable();
            $table->string('code_code')->nullable();
            $table->string('code_display')->nullable();
            $table->json('quantity')->nullable();
            $table->string('subject');
            $table->string('encounter');
            $table->json('occurrence')->nullable();
            $table->json('as_needed')->nullable();
            $table->dateTime('authored_on')->nullable();
            $table->string('requester')->nullable();
            $table->string('performer_type_system')->nullable();
            $table->string('performer_type_code')->nullable();
            $table->string('performer_type_display')->nullable();
            $table->text('patient_instruction')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_request');
    }
};
