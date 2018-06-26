<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailPesananWarungsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pesanan_warungs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_pesanan_warung');
            $table->integer('id_produk');
            $table->integer('id_pelanggan');
            $table->string('harga_produk');
            $table->integer('jumlah_produk');
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
        Schema::dropIfExists('detail_pesanan_warungs');
    }
}
