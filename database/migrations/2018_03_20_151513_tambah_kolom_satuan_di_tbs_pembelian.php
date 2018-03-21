<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TambahKolomSatuanDiTbsPembelian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbs_pembelians', function (Blueprint $table) {
            $table->integer('satuan');
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
        Schema::table('tbs_pembelians', function (Blueprint $table) {
            $table->dropColumn('satuan');
            $table->dropColumn('satuan_dasar');
        });
    }
}
