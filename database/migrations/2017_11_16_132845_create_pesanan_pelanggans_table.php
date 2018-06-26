<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePesananPelanggansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pesanan_pelanggans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_pelanggan');
            $table->string('nama_pemesan');
            $table->string('no_telp_pemesan');
            $table->string('alamat_pemesan');
            $table->string('jumlah_produk');
            $table->string('subtotal');
            $table->integer('konfirmasi_pesanan')->nullable()->default(0)->comment = "0.belum di konfirmasi, 1.sudah di konfirmasi, 2.selesai, 3.batal";;
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
        Schema::dropIfExists('pesanan_pelanggans');
    }
}
