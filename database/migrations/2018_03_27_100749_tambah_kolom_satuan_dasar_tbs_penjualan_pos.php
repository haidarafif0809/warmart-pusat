<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomSatuanDasarTbsPenjualanPos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbs_penjualans', function (Blueprint $table) {
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
        Schema::table('tbs_penjualans', function (Blueprint $table) {
            $table->dropColumn('satuan_dasar');
        });
    }
}
