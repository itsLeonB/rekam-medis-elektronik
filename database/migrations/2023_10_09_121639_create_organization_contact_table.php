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
        Schema::create('organization_contact', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->foreign('organization_id')->references('id')->on('organization')->onDelete('cascade');
            $table->string('purpose_system');
            $table->string('purpose_code');
            $table->string('purpose_display');
            $table->string('name');
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_contact');
    }
};
