<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomIdPelangganTbsReturPenjualan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() 
    { 
        Schema::table('tbs_retur_penjualans', function (Blueprint $table) {  
            $table->integer('id_pelanggan')->default(0)->nullable(); 
        }); 
    } 
 
    /** 
     * Reverse the migrations. 
     * 
     * @return void 
     */ 
    public function down() 
    { 
        Schema::table('tbs_retur_penjualans', function (Blueprint $table) { 
            $table->dropColumn('id_pelanggan'); 
        }); 
    } 
}
