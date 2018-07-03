<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomDiTbsPenerimaanProduk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbs_penerimaan_produks', function (Blueprint $table) { 
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
        Schema::table('tbs_penerimaan_produks', function (Blueprint $table) {
            $table->dropColumn('jumlah_fisik');
            $table->dropColumn('selisih_fisik');
        });
    }
}
