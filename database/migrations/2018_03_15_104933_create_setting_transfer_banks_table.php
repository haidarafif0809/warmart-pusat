<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingTransferBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_transfer_banks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_bank');
            $table->integer('tampil_bank');
            $table->integer('default_bank');
            $table->string('logo_bank');
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
        Schema::dropIfExists('setting_transfer_banks');
    }
}
