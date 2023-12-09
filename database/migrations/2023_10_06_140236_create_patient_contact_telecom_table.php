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
        Schema::create('patient_contact_telecom', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id');
            $table->index('contact_id');
            $table->foreign('contact_id')->references('id')->on('patient_contact')->onDelete('cascade');
            $table->enum('system', ['phone', 'fax', 'email', 'pager', 'url', 'sms', 'other']);
            $table->enum('use', ['home', 'work', 'temp', 'old', 'mobile']);
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_contact_telecom');
    }
};
