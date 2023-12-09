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
            $table->json('type')->nullable();
            $table->unsignedInteger('service_type')->nullable();
            $table->char('priority', 3)->nullable();
            $table->string('subject');
            $table->json('episode_of_care')->nullable();
            $table->json('based_on')->nullable();
            $table->dateTime('period_start');
            $table->dateTime('period_end')->nullable();
            $table->decimal('length_value')->nullable();
            $table->enum('length_comparator', ['<', '<=', '>=', '>'])->nullable();
            $table->string('length_unit')->nullable();
            $table->string('length_system')->nullable();
            $table->string('length_code')->nullable();
            $table->json('reason_code')->nullable();
            $table->json('reason_reference')->nullable();
            $table->json('account')->nullable();
            $table->string('hospitalization_preadmission_identifier_system')->nullable();
            $table->enum('hospitalization_preadmission_identifier_use', ['usual', 'official', 'temp', 'secondary', 'old'])->nullable();
            $table->string('hospitalization_preadmission_identifier_value')->nullable();
            $table->string('hospitalization_origin')->nullable();
            $table->enum('hospitalization_admit_source', ['hosp-trans', 'emd', 'outp', 'born', 'gp', 'mp', 'nursing', 'psych', 'rehab', 'other'])->nullable();
            $table->enum('hospitalization_re_admission', ['R'])->nullable();
            $table->json('hospitalization_diet_preference')->nullable();
            $table->json('hospitalization_special_arrangement')->nullable();
            $table->string('hospitalization_destination')->nullable();
            $table->enum('hospitalization_discharge_disposition', ['home', 'alt-home', 'other-hcf', 'hosp', 'long', 'aadvice', 'exp', 'psy', 'rehab', 'snf', 'oth'])->nullable();
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
