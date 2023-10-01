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
        Schema::create('practitioner_address', function (Blueprint $table) {
            $table->id();
            $table->integer('practitioner_id')->unsigned()->foreign('practitioner_id')->references('id')->on('practitioner');
            $table->string('use');
            $table->string('line');
            $table->string('postal_code');
            $table->string('country');
            $table->integer('rt');
            $table->integer('rw');
            $table->integer('village');
            $table->integer('district');
            $table->integer('city');
            $table->integer('province');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practitioner_address');
    }
};
