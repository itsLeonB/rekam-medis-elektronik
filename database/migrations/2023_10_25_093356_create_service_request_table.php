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
            $table->json('based_on')->nullable();
            $table->json('replaces')->nullable();
            $table->string('requisition_system')->nullable();
            $table->enum('requisition_use', ['usual', 'official', 'temp', 'secondary', 'old'])->nullable();
            $table->string('requisition_value')->nullable();
            $table->string('status');
            $table->string('intent');
            $table->json('category')->nullable();
            $table->string('priority')->nullable();
            $table->boolean('do_not_perform')->nullable();
            $table->string('code_system');
            $table->string('code_code');
            $table->string('code_display');
            $table->json('order_detail')->nullable();
            $table->json('quantity')->nullable();
            $table->string('subject');
            $table->string('encounter');
            $table->json('occurrence')->nullable();
            $table->json('as_needed')->nullable();
            $table->dateTime('authored_on')->nullable();
            $table->string('requester')->nullable();
            $table->string('performer_type')->nullable();
            $table->json('performer')->nullable();
            $table->json('location_code')->nullable();
            $table->json('location_reference')->nullable();
            $table->json('reason_code')->nullable();
            $table->json('reason_reference')->nullable();
            $table->json('insurance')->nullable();
            $table->json('supporting_info')->nullable();
            $table->json('specimen')->nullable();
            $table->json('body_site')->nullable();
            $table->text('patient_instruction')->nullable();
            $table->json('relevant_history')->nullable();
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
