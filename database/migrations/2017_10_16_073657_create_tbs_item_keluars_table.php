<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbsItemKeluarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbs_item_keluars', function (Blueprint $table) {
            $table->increments('id_tbs_item_keluar'); 
            $table->string('session_id');
            $table->integer('id_produk');
            $table->float('jumlah_produk',11,2)->default(0.00);
            $table->integer('warung_id');
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
        Schema::dropIfExists('tbs_item_keluars');
    }
}
