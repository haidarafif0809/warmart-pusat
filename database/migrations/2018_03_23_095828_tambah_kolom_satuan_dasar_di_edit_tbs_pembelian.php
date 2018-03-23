<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TambahKolomSatuanDasarDiEditTbsPembelian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('edit_tbs_pembelians', function (Blueprint $table) {
            $table->integer('satuan_dasar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('edit_tbs_pembelians', function (Blueprint $table) {
            $table->dropColumn('satuan_dasar');
        });
    }
}
