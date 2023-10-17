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
        Schema::create('observation_val_ratio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('observation_id');
            $table->foreign('observation_id')->references('id')->on('observation')->onDelete('cascade');
            $table->decimal('value_numerator');
            $table->decimal('value_denominator');
            $table->char('comparator', 2);
            $table->string('unit');
            $table->string('system');
            $table->string('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observation_val_ratio');
    }
};
