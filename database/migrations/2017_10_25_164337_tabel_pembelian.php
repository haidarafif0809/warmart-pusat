<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TabelPembelian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembelians', function (Blueprint $table) {
            $table->increments('id'); 
            $table->string('no_faktur');  
            $table->float('total',100,2)->default(0.00);  
            $table->integer('suplier_id');  
            $table->string('status_pembelian'); 
            $table->float('potongan',100,6)->default(0.00)->nullable();  
            $table->float('tunai',100,2)->default(0.00);  
            $table->float('kembalian',100,2)->default(0.00);  
            $table->float('kredit',100,2)->default(0.00)->nullable();  
            $table->float('nilai_kredit',100,2)->default(0.00)->nullable();  
            $table->integer('cara_bayar');  
            $table->string('status_beli_awal'); 
            $table->date('tanggal_jt_tempo')->nullable();  
            $table->longText('keterangan')->nullable();
            $table->string('ppn')->nullable();
            $table->integer('warung_id');
            $table->unsignedInteger('created_by')->nullable()->index();            
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembelians');
    }
}
