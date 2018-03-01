<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahkanKodeUnikTransferDiPesananPelanggan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('pesanan_pelanggans', function (Blueprint $table) {
        $table->integer('kode_unik_transfer');
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
            $table->dropColumn('kode_unik_transfer');
        });
    }
}
