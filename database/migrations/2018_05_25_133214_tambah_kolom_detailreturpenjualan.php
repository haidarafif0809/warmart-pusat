<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomDetailreturpenjualan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_retur_penjualans', function (Blueprint $table) {  
            $table->integer('id_pelanggan')->default(0)->nullable(); 
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
        $table->dropColumn('id_pelanggan');
        $table->dropColumn('satuan_dasar');
    }
}
