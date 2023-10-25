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
        Schema::create('service_request_location', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->index('request_id');
            $table->foreign('request_id')->references('id')->on('service_request')->onDelete('cascade');
            $table->string('system')->nullable();
            $table->string('code')->nullable();
            $table->string('display')->nullable();
            $table->string('reference')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_request_location');
    }
};
