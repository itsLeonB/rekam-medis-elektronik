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
            $table->json('based_on')->nullable();
            $table->json('part_of')->nullable();
            $table->enum('status', ['registered', 'preliminary', 'final', 'amended', 'corrected', 'cancelled', 'entered-in-error', 'unknown']);
            $table->json('category')->nullable();
            $table->string('code');
            $table->string('subject');
            $table->json('focus')->nullable();
            $table->string('encounter');
            $table->json('effective')->nullable();
            $table->dateTime('issued')->nullable();
            $table->json('performer')->nullable();
            $table->json('value')->nullable();
            $table->string('data_absent_reason')->nullable();
            $table->json('interpretation')->nullable();
            $table->string('body_site')->nullable();
            $table->string('method')->nullable();
            $table->string('specimen')->nullable();
            $table->string('device')->nullable();
            $table->json('has_member')->nullable();
            $table->json('derived_from')->nullable();
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
