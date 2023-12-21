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
        Schema::create('extensions', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('value_base_64_binary')->nullable();
            $table->boolean('value_boolean')->nullable();
            $table->string('value_canonical')->nullable();
            $table->string('value_code')->nullable();
            $table->date('value_date')->nullable();
            $table->dateTime('value_date_time')->nullable();
            $table->decimal('value_decimal')->nullable();
            $table->string('value_id')->nullable();
            $table->dateTime('value_instant')->nullable();
            $table->integer('value_integer')->nullable();
            $table->string('value_markdown')->nullable();
            $table->string('value_oid')->nullable();
            $table->unsignedInteger('value_positive_int')->nullable();
            $table->string('value_string')->nullable();
            $table->time('value_time')->nullable();
            $table->unsignedInteger('value_unsigned_int')->nullable();
            $table->string('value_uri')->nullable();
            $table->string('value_url')->nullable();
            $table->string('value_uuid')->nullable();
            $table->unsignedBigInteger('extendable_id');
            $table->string('extendable_type');
            $table->string('attr_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extensions');
    }
};
