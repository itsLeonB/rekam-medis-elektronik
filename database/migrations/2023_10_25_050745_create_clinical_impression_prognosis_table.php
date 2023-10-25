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
        Schema::create('clinical_impression_prognosis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('impression_id');
            $table->index('impression_id');
            $table->foreign('impression_id')->references('id')->on('clinical_impression')->onDelete('cascade');
            $table->string('system')->nullable();
            $table->string('code')->nullable();
            $table->string('display')->nullable();
            $table->string('reference')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinical_impression_prognosis');
    }
};
