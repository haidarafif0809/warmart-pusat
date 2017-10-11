<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBarang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_barang');
            $table->string('kode_barcode')->nullable();
            $table->string('nama_barang');
            $table->bigInteger('harga_beli');
            $table->bigInteger('harga_jual'); 
            $table->integer('satuan_id');
            $table->integer('kategori_barang_id')->nullable(); 
            $table->integer('status_aktif')->nullable();  
            $table->string('foto')->nullable();  
            $table->integer('hitung_stok')->nullable(); 
            $table->integer('id_warung');             
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
        Schema::dropIfExists('barangs');
    }
}
