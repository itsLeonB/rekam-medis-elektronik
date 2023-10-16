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
        Schema::create('organization_contact_telecom', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->unsignedBigInteger('organization_contact_id');
            $table->foreign('organization_contact_id')->references('id')->on('organization_contact')->onDelete('cascade');
            $table->string('system');
            $table->string('use');
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_contact_telecom');
    }
};
