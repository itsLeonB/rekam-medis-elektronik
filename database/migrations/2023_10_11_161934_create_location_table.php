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
            $table->enum('status', ['active', 'suspended', 'inactive']);
            $table->enum('operational_status', ['C', 'H', 'I', 'K', 'O', 'U'])->nullable();
            $table->string('name');
            $table->string('alias')->nullable();
            $table->string('description')->nullable();
            $table->enum('mode', ['instance', 'kind'])->nullable();
            $table->enum('address_use', ['home', 'work', 'temp', 'old', 'billing'])->nullable();
            $table->string('address_line')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->integer('province')->unsigned()->nullable();
            $table->integer('city')->unsigned()->nullable();
            $table->bigInteger('district')->unsigned()->nullable();
            $table->bigInteger('village')->unsigned()->nullable();
            $table->integer('rw')->unsigned()->nullable();
            $table->integer('rt')->unsigned()->nullable();
            $table->char('physical_type', 4);
            $table->double('longitude');
            $table->double('latitude');
            $table->double('altitude')->nullable();
            $table->string('managing_organization')->nullable();
            $table->string('part_of')->nullable();
            $table->string('availability_exceptions')->nullable();
            $table->enum('service_class', ['3', '2', '1', 'VIP', 'VVIP'])->nullable();
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
