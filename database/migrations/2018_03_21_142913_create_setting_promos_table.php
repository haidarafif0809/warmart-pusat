<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingPromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('setting_promos', function (Blueprint $table) { 
            $table->increments('id_setting_promo'); 
            $table->string('baner_promo'); 
            $table->integer('id_produk'); 
            $table->float('harga_coret', 100, 2)->default(0.00)->nullable(); 
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
        Schema::dropIfExists('setting_promos');
    }
}
