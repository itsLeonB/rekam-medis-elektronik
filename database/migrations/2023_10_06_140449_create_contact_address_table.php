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
        Schema::create('contact_address', function (Blueprint $table) {
            $table->id();
            $table->integer('contact_id')->unsigned()->foreign('contact_id')->references('id')->on('patient_contact');
            $table->string('use');
            $table->string('line');
            $table->string('country');
            $table->string('postal_code');
            $table->integer('province');
            $table->integer('city');
            $table->integer('district');
            $table->integer('village');
            $table->integer('rw');
            $table->integer('rt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_address');
    }
};
