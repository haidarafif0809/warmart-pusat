<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePembelianOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembelian_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_faktur_order');
            $table->integer('suplier_id');
            $table->float('total',100,2)->default(0.00);  
            $table->longText('keterangan')->nullable();
            $table->integer('status_order')->default(0)->comment = "1 = Diorder, 2 = Diproses, 3 = Dibeli";
            $table->integer('warung_id');
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
        Schema::dropIfExists('pembelian_orders');
    }
}
