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
        Schema::create('location', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->enum('status', ['active', 'suspended', 'inactive'])->nullable();
            $table->enum('operational_status', ['C', 'H', 'I', 'K', 'O', 'U'])->nullable();
            $table->string('name');
            $table->string('alias')->nullable();
            $table->string('description')->nullable();
            $table->enum('mode', ['instance', 'kind'])->nullable();
            $table->json('type')->nullable();
            $table->enum('address_use', ['home', 'work', 'temp', 'old', 'billing'])->nullable();
            $table->json('address_line')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->integer('province')->unsigned()->nullable();
            $table->integer('city')->unsigned()->nullable();
            $table->bigInteger('district')->unsigned()->nullable();
            $table->bigInteger('village')->unsigned()->nullable();
            $table->integer('rw')->unsigned()->nullable();
            $table->integer('rt')->unsigned()->nullable();
            $table->enum('physical_type', ['si', 'bu', 'wi', 'wa', 'lvl', 'co', 'ro', 'bd', 've', 'ho', 'ca', 'rd', 'area', 'jdn', 'vir'])->nullable();
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();
            $table->double('altitude')->nullable();
            $table->string('managing_organization')->nullable();
            $table->string('part_of')->nullable();
            $table->string('availability_exceptions')->nullable();
            $table->json('endpoint')->nullable();
            $table->enum('service_class', ['1', '2', '3', 'vip', 'vvip', 'reguler', 'eksekutif'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location');
    }
};
