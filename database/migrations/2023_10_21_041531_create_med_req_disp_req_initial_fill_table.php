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
        Schema::create('med_req_disp_req_initial_fill', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('med_req_disp_req_id');
            $table->index('med_req_disp_req_id');
            $table->foreign('med_req_disp_req_id')->references('id')->on('med_req_dispense_request');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('med_req_disp_req_initial_fill');
    }
};
