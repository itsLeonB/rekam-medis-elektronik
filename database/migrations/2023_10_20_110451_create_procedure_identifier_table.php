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
        Schema::create('procedure_identifier', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('procedure_id');
            $table->index('procedure_id');
            $table->foreign('procedure_id')->references('id')->on('procedure')->onDelete('cascade');
            $table->string('system');
            $table->enum('use', ['usual', 'official', 'temp', 'secondary', 'old']);
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedure_identifier');
    }
};
