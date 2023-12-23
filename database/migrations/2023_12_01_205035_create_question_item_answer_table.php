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
        Schema::create('question_item_answer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_item_id');
            $table->index('question_item_id');
            $table->foreign('question_item_id')->references('id')->on('questionnaire_response_item')->onDelete('cascade');
            $table->boolean('value_boolean')->nullable();
            $table->decimal('value_decimal')->nullable();
            $table->integer('value_integer')->nullable();
            $table->date('value_date')->nullable();
            $table->dateTime('value_date_time')->nullable();
            $table->time('value_time')->nullable();
            $table->string('value_string')->nullable();
            $table->string('value_uri')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_item_answer');
    }
};
