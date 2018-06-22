<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomIdTransaksi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() 
    { 
        Schema::table('transaksi_hutangs', function (Blueprint $table) { 
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
        Schema::table('transaksi_hutangs', function (Blueprint $table) { 
            $table->dropColumn('id_transaksi'); 
        }); 
    } 
}
