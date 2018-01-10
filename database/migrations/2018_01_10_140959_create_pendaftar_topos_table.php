<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePendaftarToposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendaftar_topos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('no_telp')->unique();
            $table->string('email')->nullable()->unique();
            $table->string('alamat')->nullable();
            $table->integer('lama_berlangganan')->comment = "1 = 1 Bulan, 2 = 6 Bulan, 3 = 12 Bulan";
            $table->string('berlaku_hingga');
            $table->string('jenis_pembayaran');
            $table->integer('total');
            $table->integer('bank_id');
            $table->string('no_rekening')->unique();
            $table->string('atas_nama');
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
        Schema::dropIfExists('pendaftar_topos');
    }
}
