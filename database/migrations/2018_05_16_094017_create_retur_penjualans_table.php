<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturPenjualansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retur_penjualans', function (Blueprint $table) {
            $table->increments('id_retur_penjualan');
            $table->string('no_faktur_retur');   
            $table->integer('id_pelanggan'); 
            $table->longText('keterangan')->nullable(); 
            $table->float('total',100,2)->default(0.00);    
            $table->float('total_bayar',100,2)->default(0.00);    
            $table->float('potongan',100,6)->default(0.00)->nullable();     
            $table->float('tax',100,6)->default(0.00)->nullable();   
            $table->integer('id_kas');   
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
        Schema::dropIfExists('retur_penjualans');
    }
}
