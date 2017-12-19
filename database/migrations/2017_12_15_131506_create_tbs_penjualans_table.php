<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbsPenjualansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbs_penjualans', function (Blueprint $table) {
            $table->increments('id_tbs_penjualan');
            $table->string('session_id');
            $table->integer('satuan_id');
            $table->integer('id_produk');
            $table->float('jumlah_produk', 100, 2)->default(0.00);
            $table->float('harga_produk', 100, 2)->default(0.00);
            $table->float('subtotal', 100, 2)->default(0.00);
            $table->float('tax', 100, 6)->default(0.00)->nullable();
            $table->float('potongan', 100, 6)->default(0.00)->nullable();
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
        Schema::dropIfExists('tbs_penjualans');
    }
}
