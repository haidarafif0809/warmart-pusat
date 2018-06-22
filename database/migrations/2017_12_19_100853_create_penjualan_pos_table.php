<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualanPosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualan_pos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_faktur');
            $table->float('total', 100, 2)->default(0.00);
            $table->integer('pelanggan_id');
            $table->string('status_penjualan')->nullable();
            $table->float('potongan', 100, 6)->default(0.00)->nullable();
            $table->float('tunai', 100, 2)->default(0.00);
            $table->float('kembalian', 100, 2)->default(0.00);
            $table->float('kredit', 100, 2)->default(0.00)->nullable();
            $table->float('nilai_kredit', 100, 2)->default(0.00)->nullable();
            $table->integer('id_kas');
            $table->string('status_jual_awal');
            $table->date('tanggal_jt_tempo')->nullable();
            $table->longText('keterangan')->nullable();
            $table->string('ppn')->nullable();
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
        Schema::dropIfExists('penjualan_pos');
    }
}
