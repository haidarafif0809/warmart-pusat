<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahHargaJual2Produk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('barangs', function (Blueprint $table) {        
        $table->bigInteger('harga_jual2')->nullable();; 
    });
   }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
     Schema::table('barangs', function (Blueprint $table) {
        $table->dropColumn('harga_jual2');
    });
 }
}
