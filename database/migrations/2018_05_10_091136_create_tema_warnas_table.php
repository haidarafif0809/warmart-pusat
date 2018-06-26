<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemaWarnasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tema_warnas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_tema');
            $table->string('kode_tema');
            $table->string('header_tema');
            $table->integer('default_tema');
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
        Schema::dropIfExists('tema_warnas');
    }
}
