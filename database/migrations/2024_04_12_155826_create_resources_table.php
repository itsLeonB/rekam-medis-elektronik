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
        foreach (config('app.resourceTypes') as $res_type) {
            Schema::create($res_type, function (Blueprint $table) {
                $table->id();
                $table->index('id');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (config('app.resourceTypes') as $res_type) {
            Schema::dropIfExists($res_type);
        }
    }
};
