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
        Schema::create('encounter', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->enum('status', ['planned', 'arrived', 'triaged', 'in-progress', 'onleave', 'finished', 'cancelled', 'entered-in-error', 'unknown']);
            $table->string('class');
            $table->unsignedInteger('service_type')->nullable();
            $table->char('priority', 3)->nullable();
            $table->string('subject');
            $table->string('episode_of_care')->nullable();
            $table->string('based_on')->nullable();
            $table->dateTime('period_start');
            $table->dateTime('period_end')->nullable();
            $table->string('account')->nullable();
            $table->string('location');
            $table->enum('service_class', ['1', '2', '3', 'vip', 'vvip', 'reguler', 'eksekutif'])->nullable();
            $table->enum('upgrade_class', ['kelas-tetap', 'naik-kelas', 'turun-kelas', 'titip-rawat'])->nullable();
            $table->string('service_provider');
            $table->string('part_of')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encounter');
    }
};
