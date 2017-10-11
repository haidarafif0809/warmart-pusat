<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankKomunitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_komunitas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_bank');
            $table->string('atas_nama');
            $table->string('no_rek', 100); 
            $table->integer('komunitas_id');
            $table->unsignedInteger('created_by')->nullable()->index();            
            $table->unsignedInteger('updated_by')->nullable()->index();
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
        Schema::dropIfExists('bank_komunitas');
    }
}
