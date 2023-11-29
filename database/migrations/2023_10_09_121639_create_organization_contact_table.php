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
            $table->index('organization_id');
            $table->foreign('organization_id')->references('id')->on('organization')->onDelete('cascade');
            $table->enum('purpose', ['BILL', 'ADMIN', 'HR', 'PAYOR', 'PATINF', 'PRESS'])->nullable();
            $table->enum('name_use', ['usual', 'official', 'temp', 'nickname', 'anonymous', 'old', 'maiden'])->nullable();
            $table->string('name_text');
            $table->enum('address_use', ['home', 'work', 'temp', 'old', 'billing'])->nullable();
            $table->enum('address_type', ['postal', 'physical', 'both'])->nullable();
            $table->json('address_line');
            $table->string('country');
            $table->string('postal_code')->nullable();
            $table->integer('province')->unsigned()->nullable();
            $table->integer('city')->unsigned()->nullable();
            $table->bigInteger('district')->unsigned()->nullable();
            $table->bigInteger('village')->unsigned()->nullable();
            $table->integer('rw')->unsigned()->nullable();
            $table->integer('rt')->unsigned()->nullable();
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
