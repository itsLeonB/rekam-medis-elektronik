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
            $table->enum('status', ['preparation', 'in-progress', 'not-done', 'on-hold', 'stopped', 'completed', '  entered-in-error', 'unknown'])->default('unknown');
            $table->unsignedBigInteger('status_reason')->nullable();
            $table->enum('category', ['24642003', '409063005', '409073007', '387713003', '103693007', '46947000', '410606002'])->nullable();
            $table->string('code');
            $table->string('subject');
            $table->string('encounter');
            $table->json('performed')->nullable();
            $table->string('recorder')->nullable();
            $table->string('asserter')->nullable();
            $table->string('location');
            $table->enum('outcome', ['385669000', '385671000', '385670004'])->nullable();
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
