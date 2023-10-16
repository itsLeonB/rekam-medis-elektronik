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
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->boolean('active');
            $table->char('operational_status', 1);
            $table->string('name');
            $table->string('alias');
            $table->string('description');
            $table->string('mode');
            $table->string('address_use');
            $table->string('address_line');
            $table->string('country');
            $table->string('postal_code');
            $table->integer('province')->unsigned();
            $table->integer('city')->unsigned();
            $table->bigInteger('district')->unsigned();
            $table->bigInteger('village')->unsigned();
            $table->integer('rw')->unsigned();
            $table->integer('rt')->unsigned();
            $table->string('physical_type');
            $table->double('longitude');
            $table->double('latitude');
            $table->double('altitude');
            $table->string('managing_organization');
            $table->string('part_of');
            $table->string('availability_exceptions');
            $table->string('service_class');
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
