<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallBackPortaPulsasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_back_porta_pulsas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('trxid');
            $table->string('code');
            $table->string('phone');
            $table->string('idcust');
            $table->string('sequence');
            $table->string('status');
            $table->string('sn');
            $table->string('note');
            $table->string('price');
            $table->string('trxid_api');
            $table->string('date_insert');
            $table->string('date_update');
            $table->string('last_balance');
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
        Schema::dropIfExists('call_back_porta_pulsas');
    }
}
