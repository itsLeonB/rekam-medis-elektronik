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
        Schema::create('specimen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->string('accession_identifier_system')->nullable();
            $table->enum('accession_identifier_use', ['usual', 'official', 'temp', 'secondary', 'old'])->nullable();
            $table->string('accession_identifier_value')->nullable();
            $table->enum('status', ['available', 'unavailable', 'unsatisfactory', 'entered-in-error']);
            $table->string('type_system')->nullable();
            $table->string('type_code');
            $table->string('type_display')->nullable();
            $table->string('subject');
            $table->dateTime('received_time')->nullable();
            $table->string('collection_collector')->nullable();
            $table->json('collection_collected')->nullable();
            $table->decimal('collection_duration_value')->nullable();
            $table->enum('collection_duration_comparator', ['<', '<=', '>', '>='])->nullable();
            $table->string('collection_duration_unit')->nullable();
            $table->string('collection_duration_system')->nullable();
            $table->string('collection_duration_code')->nullable();
            $table->decimal('collection_quantity_value')->nullable();
            $table->string('collection_quantity_unit')->nullable();
            $table->string('collection_quantity_system')->nullable();
            $table->string('collection_quantity_code')->nullable();
            $table->enum('collection_method', ['129316008', '129314006', '129300006', '129304002', '129323009', '82078001', '225113003', '386089008', '713143008', '1048003', '70777001', '73416001', '243776001', '278450005', '285570007'])->nullable();
            $table->string('collection_body_site_system')->nullable();
            $table->string('collection_body_site_code')->nullable();
            $table->string('collection_body_site_display')->nullable();
            $table->json('collection_fasting_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specimen');
    }
};
