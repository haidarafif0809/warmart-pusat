<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailItemKeluarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_item_keluars', function (Blueprint $table) {
            $table->increments('id_detail_item_keluar');
            $table->string('no_faktur');
            $table->integer('id_produk');
            $table->decimal('jumlah_produk', 65,2); 
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
        Schema::dropIfExists('detail_item_keluars');
    }
}
