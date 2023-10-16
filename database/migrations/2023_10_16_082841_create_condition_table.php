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
        Schema::create('condition', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->string('clinical_status');
            $table->string('verification_status');
            $table->unsignedBigInteger('severity');
            $table->string('code');
            $table->string('subject');
            $table->string('encounter');
            $table->dateTime('onset_datetime');
            $table->unsignedInteger('onset_age');
            $table->string('onset_string');
            $table->dateTime('abatement_datetime');
            $table->unsignedInteger('abatement_age');
            $table->string('abatement_string');
            $table->date('recorded_date');
            $table->string('recorder');
            $table->string('asserter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('condition');
    }
};
