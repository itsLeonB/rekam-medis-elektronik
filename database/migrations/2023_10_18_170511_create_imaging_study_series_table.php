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
        Schema::create('imaging_study_series', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('imaging_id');
            $table->index('imaging_id');
            $table->foreign('imaging_id')->references('id')->on('imaging_study')->onDelete('cascade');
            $table->string('uid');
            $table->unsignedInteger('number')->nullable();
            $table->enum('modality', ['AR', 'BI', 'BMD', 'EPS', 'CR', 'CT', 'CFM', 'DMS', 'DG', 'DX', 'ECG', 'EEG', 'EMG', 'EOG', 'ES', 'XC', 'GM', 'HD', 'IO', 'IVOCT', 'IVUS', 'KER', 'LS', 'LEN', 'MR', 'MG', 'NM', 'OAM', 'OPM', 'OP', 'OPT', 'OPTBSV', 'OPTENF', 'OPV', 'OCT', 'OSS', 'PX', 'PA', 'POS', 'PT', 'RF', 'RG', 'RESP', 'RTIMAGE', 'SM', 'SRF', 'TG', 'US', 'BDUS', 'VA', 'XA']);
            $table->text('description')->nullable();
            $table->unsignedInteger('num_instances')->nullable();
            $table->json('endpoint')->nullable();
            $table->string('body_site_system')->nullable();
            $table->string('body_site_code')->nullable();
            $table->string('body_site_display')->nullable();
            $table->enum('laterality', ['419161000', '419465000', '51440002'])->nullable();
            $table->json('specimen')->nullable();
            $table->dateTime('started')->nullable();
            $table->json('performer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imaging_study_series');
    }
};
