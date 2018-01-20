<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TambahKolomIdTransaksiDiTransaksiPiutangs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaksi_piutangs', function (Blueprint $table) {
            $table->integer('id_transaksi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksi_piutangs', function (Blueprint $table) {
            $table->dropColumn('id_transaksi');
        });
    }
}
