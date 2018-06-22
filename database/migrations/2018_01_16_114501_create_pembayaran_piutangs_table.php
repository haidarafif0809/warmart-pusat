<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaranPiutangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran_piutangs', function (Blueprint $table) {
            $table->increments('id_pembayaran_piutang');
            $table->string('no_faktur_pembayaran');
            $table->float('total', 100, 2)->default(0.00);
            $table->integer('cara_bayar');
            $table->integer('warung_id');
            $table->longText('keterangan')->nullable();
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
        Schema::dropIfExists('pembayaran_piutangs');
    }
}
