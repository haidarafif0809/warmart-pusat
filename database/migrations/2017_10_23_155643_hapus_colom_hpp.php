<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HapusColomHpp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hpps', function (Blueprint $table) {
            $table->dropColumn(['no_faktur_hpp_masuk', 'no_faktur_hpp_keluar']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('hpps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_faktur'); 
            $table->integer('id_produk');
            $table->string('jenis_transaksi');
            $table->float('jumlah_masuk',11,2)->default(0.00);
            $table->float('jumlah_keluar',11,2)->default(0.00);
            $table->float('harga_unit',11,2)->default(0.00);
            $table->float('total_nilai',11,2)->default(0.00);
            $table->integer('jenis_hpp')->comment = "1 = masuk , 2 = keluar";
            $table->integer('warung_id');
            $table->timestamps();
        });
    }
}
