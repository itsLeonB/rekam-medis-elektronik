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
        Schema::create('organization_type', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->unsigned()->foreign('organization_id')->references('id')->on('organization');
            $table->string('system');
            $table->string('code');
            $table->string('display');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_type');
    }
};
