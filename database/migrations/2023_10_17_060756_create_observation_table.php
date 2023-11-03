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
        Schema::create('observation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->enum('status', ['registered', 'preliminary', 'final', 'amended', 'corrected', 'cancelled', 'entered-in-error', 'unknown']);
            $table->string('code_system')->nullable();
            $table->string('code_code');
            $table->string('code_display')->nullable();
            $table->string('subject');
            $table->string('encounter');
            $table->json('effective')->nullable();
            $table->dateTime('issued')->nullable();
            $table->json('value')->nullable();
            $table->string('data_absent_reason')->nullable();
            $table->string('body_site_system')->nullable();
            $table->string('body_site_code')->nullable();
            $table->string('body_site_display')->nullable();
            $table->string('method_system')->nullable();
            $table->string('method_code')->nullable();
            $table->string('method_display')->nullable();
            $table->string('specimen')->nullable();
            $table->string('device')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observation');
    }
};
