<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingVerifikasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_verifikasis', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_warung')->nullable();
            $table->integer('email')->nullable()->comment("1 = ya, 0 = tidak");
            $table->integer('no_telp')->nullable()->comment("1 = ya, 0 = tidak");
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
        Schema::dropIfExists('setting_verifikasis');
    }
}
