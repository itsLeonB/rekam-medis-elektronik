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
        Schema::create('codings', function (Blueprint $table) {
            $table->id();
            $table->string('system')->nullable();
            $table->string('version')->nullable();
            $table->string('code')->nullable();
            $table->text('display')->nullable();
            $table->boolean('user_selected')->nullable();
            $table->unsignedBigInteger('codeable_id');
            $table->string('codeable_type');
            $table->string('attr_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codings');
    }
};
