<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomDiTablePemesananPelanggan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     Schema::table('pesanan_pelanggans', function (Blueprint $table) { 
        $table->string('kurir')->nullable(); 
        $table->string('layanan_kurir')->nullable(); 
        $table->string('metode_pembayaran')->nullable(); 
        $table->integer('biaya_kirim')->nullable(); 
        $table->string('bank_transfer')->nullable(); 
    }); 
 }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

     Schema::table('pesanan_pelanggans', function (Blueprint $table) { 
        $table->dropColumn('kurir'); 
        $table->dropColumn('layanan_kurir'); 
        $table->dropColumn('metode_pembayaran'); 
        $table->dropColumn('biaya_kirim'); 
        $table->dropColumn('bank_transfer'); 
    }); 
 }
}
