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
        Schema::create('med_disp_subs_responsible_party', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('med_disp_subs_id');
            $table->index('med_disp_subs_id');
            $table->foreign('med_disp_subs_id')->references('id')->on('medication_dispense_substitution')->onDelete('cascade');
            $table->string('reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('med_disp_subs_responsible_party');
    }
};
