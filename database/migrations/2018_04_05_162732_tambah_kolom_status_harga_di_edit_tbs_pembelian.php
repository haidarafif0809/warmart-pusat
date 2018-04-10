<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomStatusHargaDiEditTbsPembelian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('edit_tbs_pembelians', function (Blueprint $table) {
            $table->integer('status_harga')->default(0);
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
            $table->dropColumn('status_harga');
        });
    }
}
