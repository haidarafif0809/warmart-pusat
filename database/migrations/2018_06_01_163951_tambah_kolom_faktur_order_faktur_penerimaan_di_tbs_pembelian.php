<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomFakturOrderFakturPenerimaanDiTbsPembelian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbs_pembelians', function (Blueprint $table) { 
            $table->string('faktur_order')->nullable();
            $table->string('faktur_penerimaan')->nullable();
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
            $table->dropColumn('faktur_order');
            $table->dropColumn('faktur_penerimaan');
        });
    }
}
