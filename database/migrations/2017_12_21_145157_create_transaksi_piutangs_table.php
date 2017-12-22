<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiPiutangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_piutangs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('jenis_transaksi');
            $table->integer('pelanggan_id');
            $table->string('no_faktur');
            $table->float('jumlah_masuk', 100, 2)->default(0.00);
            $table->float('jumlah_keluar', 100, 2)->default(0.00);
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
        Schema::dropIfExists('transaksi_piutangs');
    }
}
