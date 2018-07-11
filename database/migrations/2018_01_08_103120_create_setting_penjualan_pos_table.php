<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingPenjualanPosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_penjualan_pos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jumlah_produk');
            $table->integer('stok')->comment = "0 = Stok tidak bisa minus, 1= Stok bisa minus";
            $table->integer('harga_jual');
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
        Schema::dropIfExists('setting_penjualan_pos');
    }
}
