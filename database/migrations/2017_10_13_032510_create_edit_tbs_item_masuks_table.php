<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditTbsItemMasuksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edit_tbs_item_masuks', function (Blueprint $table) {
            $table->increments('id_edit_tbs_item_masuk');
            $table->string('no_faktur');
            $table->integer('id_produk');
            $table->decimal('jumlah_produk', 65,2); 
            $table->string('session_id');
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
        Schema::dropIfExists('edit_tbs_item_masuks');
    }
}
