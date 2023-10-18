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
        Schema::create('encounter_hospitalization', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('encounter_id');
            $table->index('encounter_id');
            $table->foreign('encounter_id')->references('id')->on('encounter')->onDelete('cascade');
            $table->string('preadmission_identifier_system')->nullable();
            $table->enum('preadmission_identifier_use', ['usual', 'official', 'temp', 'secondary', 'old']);
            $table->string('preadmission_identifier_value')->nullable();
            $table->string('origin')->nullable();
            $table->string('admit_source')->nullable();
            $table->enum('re_admission', ['R'])->nullable();
            $table->string('destination')->nullable();
            $table->string('discharge_disposition')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encounter_hospitalization');
    }
};
