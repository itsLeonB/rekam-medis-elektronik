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
        Schema::create('practitioner_qualification', function (Blueprint $table) {
            $table->id();
            $table->integer('practitioner_id')->unsigned()->foreign('practitioner_id')->references('id')->on('practitioner');
            $table->string('code');
            $table->string('code_system');
            $table->string('display');
            $table->string('identifier');
            $table->string('issuer');
            $table->date('period_start');
            $table->date('period_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practitioner_qualification');
    }
};
