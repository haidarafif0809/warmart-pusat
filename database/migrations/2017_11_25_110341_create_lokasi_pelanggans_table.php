<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLokasiPelanggansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lokasi_pelanggans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_pelanggan');
            $table->integer('provinsi');
            $table->integer('kabupaten');
            $table->integer('kecamatan');
            $table->integer('kelurahan');
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
        Schema::dropIfExists('lokasi_pelanggans');
    }
}
