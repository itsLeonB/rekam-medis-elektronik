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
        Schema::create('procedure', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->json('based_on')->nullable();
            $table->json('part_of')->nullable();
            $table->string('status')->default('unknown');
            $table->string('status_reason')->nullable();
            $table->string('category')->nullable();
            $table->string('code_system')->nullable();
            $table->string('code_code');
            $table->string('code_display')->nullable();
            $table->string('subject');
            $table->string('encounter');
            $table->json('performed')->nullable();
            $table->string('recorder')->nullable();
            $table->string('asserter')->nullable();
            $table->string('location')->nullable();
            $table->json('reason_code')->nullable();
            $table->json('reason_reference')->nullable();
            $table->json('body_site')->nullable();
            $table->string('outcome')->nullable();
            $table->json('report')->nullable();
            $table->json('complication')->nullable();
            $table->json('complication_detail')->nullable();
            $table->json('follow_up')->nullable();
            $table->json('used_reference')->nullable();
            $table->json('used_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedure');
    }
};
