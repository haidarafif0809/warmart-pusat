<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailReturPenjualansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_retur_penjualans', function (Blueprint $table) {
           $table->increments('id_detail_retur_penjualan');
            $table->string('no_faktur_retur');
            $table->string('no_faktur_penjualan');
            $table->integer('id_produk');
            $table->float('jumlah_jual', 100,2)->default(0.00)->nullable();  
            $table->float('jumlah_retur', 100,2)->default(0.00)->nullable();  
            $table->integer('id_satuan');
            $table->integer('id_satuan_jual'); 
            $table->float('harga_produk', 100, 2)->default(0.00);
            $table->float('subtotal', 100, 2)->default(0.00);
            $table->float('tax', 100, 6)->default(0.00)->nullable();
            $table->float('potongan', 100, 6)->default(0.00)->nullable();
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
        Schema::dropIfExists('detail_retur_penjualans');
    }
}
