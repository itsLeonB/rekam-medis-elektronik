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
        Schema::create('patient_contact', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id')->unsigned()->foreign('patient_id')->references('id')->on('patient');
            $table->char('relationship', 1);
            $table->string('name');
            $table->string('prefix');
            $table->string('suffix');
            $table->string('gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_contact');
    }
};
