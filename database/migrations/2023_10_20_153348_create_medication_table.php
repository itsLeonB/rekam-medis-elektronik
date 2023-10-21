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
        Schema::create('medication', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->string('system')->nullable();
            $table->unsignedBigInteger('code')->nullable();
            $table->string('display')->nullable();
            $table->enum('status', ['active', 'inactive', 'entered-in-error'])->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('form')->nullable();
            $table->decimal('amount_numerator_value')->nullable();
            $table->enum('amount_numerator_comparator', ['<', '<=', '>=', '>'])->nullable();
            $table->string('amount_numerator_unit')->nullable();
            $table->string('amount_numerator_system')->nullable();
            $table->string('amount_numerator_code')->nullable();
            $table->decimal('amount_denominator_value')->nullable();
            $table->enum('amount_denominator_comparator', ['<', '<=', '>=', '>'])->nullable();
            $table->string('amount_denominator_unit')->nullable();
            $table->string('amount_denominator_system')->nullable();
            $table->string('amount_denominator_code')->nullable();
            $table->string('batch_lot_number')->nullable();
            $table->dateTime('batch_expiration_date')->nullable();
            $table->enum('type', ['NC', 'EP','SD']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication');
    }
};
