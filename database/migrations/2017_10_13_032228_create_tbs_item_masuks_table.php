<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbsItemMasuksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbs_item_masuks', function (Blueprint $table) {
            $table->increments('id_tbs_item_masuk'); 
            $table->string('session_id');
            $table->integer('id_produk');
            $table->float('jumlah_produk', 65,2);
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
        Schema::dropIfExists('tbs_item_masuks');
    }
}
