<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailReturPembeliansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_retur_pembelians', function (Blueprint $table) {
            $table->increments('id_detail_retur_pembelian');
            $table->string('no_faktur_retur');
            $table->string('no_faktur_pembelian');
            $table->string('no_faktur_hutang')->nullable();
            $table->integer('id_produk');
            $table->float('jumlah_produk', 100,2)->default(0.00); 
            $table->float('jumlah_beli', 100,2)->default(0.00); 
            $table->integer('satuan_id'); 
            $table->integer('satuan_dasar'); 
            $table->integer('satuan_beli'); 
            $table->float('harga_produk', 100,2)->default(0.00); 
            $table->float('subtotal', 100,2)->default(0.00);
            $table->float('tax', 100,6)->default(0.00)->nullable(); 
            $table->float('potongan', 100,6)->default(0.00)->nullable();  
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
        Schema::dropIfExists('detail_retur_pembelians');
    }
}
