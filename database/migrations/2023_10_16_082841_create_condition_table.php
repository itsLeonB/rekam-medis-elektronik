<?php

use App\Models\Condition;
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
        Schema::create('condition', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->string('clinical_status')->nullable();
            $table->string('verification_status')->nullable();
            $table->json('category')->nullable();
            $table->enum('severity', ['24484000', '6736007', '255604002', '442452003'])->nullable();
            $table->string('code_system')->nullable();
            $table->string('code_code')->nullable();
            $table->string('code_display')->nullable();
            $table->json('body_site')->nullable();
            $table->string('subject');
            $table->string('encounter');
            $table->json('onset')->nullable();
            $table->json('abatement')->nullable();
            $table->dateTime('recorded_date')->nullable();
            $table->string('recorder')->nullable();
            $table->string('asserter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('condition');
    }
};
