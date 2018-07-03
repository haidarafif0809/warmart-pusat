<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomDiDetailPenerimaanProduk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_penerimaan_produks', function (Blueprint $table) { 
            $table->string('no_faktur_order');
            $table->integer('jumlah_fisik')->default(0);
            $table->integer('selisih_fisik')->default(0);
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detail_penerimaan_produks', function (Blueprint $table) {
            $table->dropColumn('no_faktur_order');
            $table->dropColumn('jumlah_fisik');
            $table->dropColumn('selisih_fisik');
        });
    }
}
