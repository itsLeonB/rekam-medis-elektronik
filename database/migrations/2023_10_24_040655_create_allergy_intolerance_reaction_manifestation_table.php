<?php

use App\Models\AllergyIntoleranceReactionManifestation;
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
        Schema::create('allergy_react_manifest', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('allergy_react_id');
            $table->index('allergy_react_id');
            $table->foreign('allergy_react_id')->references('id')->on('allergy_intolerance_reaction')->onDelete('cascade');
            $table->string('system')->nullable();
            $table->enum('code', AllergyIntoleranceReactionManifestation::CODE);
            $table->string('display')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allergy_react_manifest');
    }
};
