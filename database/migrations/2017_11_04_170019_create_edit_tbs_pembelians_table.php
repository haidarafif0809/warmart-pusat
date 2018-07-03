<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditTbsPembeliansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edit_tbs_pembelians', function (Blueprint $table) {
            $table->increments('id_edit_tbs_pembelians');
            $table->string('session_id');
            $table->string('no_faktur');
            $table->integer('id_produk');
            $table->integer('satuan_id'); 
            $table->float('jumlah_produk', 100,2)->default(0.00); 
            $table->float('harga_produk', 100,2)->default(0.00); 
            $table->float('subtotal', 100,2)->default(0.00); 
            $table->float('tax', 100,6)->default(0.00)->nullable(); 
            $table->float('potongan', 100,6)->default(0.00)->nullable();  
            $table->string('ppn',100)->nullable();
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
        Schema::dropIfExists('edit_tbs_pembelians');
    }
}
