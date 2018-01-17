<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPembayaranPiutangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pembayaran_piutangs', function (Blueprint $table) {
            $table->increments('id_detail_pembayaran_piutang');
            $table->string('no_faktur_pembayaran');
            $table->string('no_faktur_penjualan');
            $table->string('jatuh_tempo');
            $table->float('piutang', 100, 2)->default(0.00)->nullable();
            $table->float('potongan', 100, 6)->default(0.00)->nullable();
            $table->float('jumlah_bayar', 100, 2)->default(0.00)->nullable();
            $table->integer('pelanggan_id');
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
        Schema::dropIfExists('detail_pembayaran_piutangs');
    }
}
