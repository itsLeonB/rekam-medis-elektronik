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
        Schema::create('quantities', function (Blueprint $table) {
            $table->id();
            $table->decimal('value')->nullable();
            $table->enum('comparator', ['<', '<=', '>=', '>'])->nullable();
            $table->string('unit')->nullable();
            $table->string('system')->nullable();
            $table->string('code')->nullable();
            $table->unsignedBigInteger('quantifiable_id');
            $table->string('quantifiable_type');
            $table->string('attr_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quantities');
    }
};
