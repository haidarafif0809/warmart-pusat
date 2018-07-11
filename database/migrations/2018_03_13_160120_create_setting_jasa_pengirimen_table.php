<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingJasaPengirimenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_jasa_pengirimen', function (Blueprint $table) {
            $table->increments('id');
            $table->string('jasa_pengiriman');
            $table->integer('tampil_jasa_pengiriman');
            $table->integer('default_jasa_pengiriman');
            $table->string('logo_jasa');
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
        Schema::dropIfExists('setting_jasa_pengirimen');
    }
}
