<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditTbsPenerimaanProduksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edit_tbs_penerimaan_produks', function (Blueprint $table) {
            $table->increments('id_edit_tbs_penerimaan_produk');
            $table->string('no_faktur_penerimaan');
            $table->string('session_id');
            $table->integer('id_produk');
            $table->float('jumlah_produk', 100,2)->default(0.00);
            $table->integer('satuan_id')->default(0);
            $table->integer('satuan_dasar')->default(0); 
            $table->float('harga_produk', 100,2)->default(0.00); 
            $table->float('subtotal', 100,2)->default(0.00);
            $table->string('ppn')->nullable();
            $table->float('tax', 100,6)->default(0.00)->nullable();
            $table->float('tax_include', 100,6)->default(0.00)->nullable(); 
            $table->float('potongan', 100,6)->default(0.00)->nullable();
            $table->integer('status_harga')->default(0);  
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
        Schema::dropIfExists('edit_tbs_penerimaan_produks');
    }
}
