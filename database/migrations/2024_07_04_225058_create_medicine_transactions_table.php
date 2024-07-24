<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('medicine_transactions', function (Blueprint $table) {
            // id transaksi obat
            $table->uuid();
            // id transaksi hubungan bisa dipakai untuk hubungan transaksi pasien, rekam medis,dll yang membutukan transaksi obat
            $table->uuid('id_transaction');
            // id obat, obat mana yang di transakasikan
            $table->uuid('id_medicine');
            // jumlah obat yang ditransaksikan
            $table->integer('quantity');
            // deskripsi transaksi
            $table->integer('note');
            // waktu transaksi dan update transaksi
            $table->timestamps();
            // soft delete, untuk history jika ingin data dihapus atau dikembalikan
            $table->softDeletes();
            
            //hubungan data obat, bisa ditambahkan untuk id transaksinya nanti jika sudah terkoneksi dengan database lain
            $table->foreign('id_medicine')->references('id')->on('medicine')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_transactions');
    }
};
