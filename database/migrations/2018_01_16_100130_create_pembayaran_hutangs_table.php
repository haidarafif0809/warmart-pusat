<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePembayaranHutangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran_hutangs', function (Blueprint $table) {
            $table->increments('id_pembayaran_hutang');
            $table->string('no_faktur_pembayaran');
            $table->float('total',100,2)->default(0.00);
            $table->integer('suplier_id'); 
            $table->integer('cara_bayar'); 
            $table->integer('warung_id');
            $table->longText('keterangan')->nullable();
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
        Schema::dropIfExists('pembayaran_hutangs');
    }
}
