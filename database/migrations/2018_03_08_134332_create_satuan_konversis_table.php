<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSatuanKonversisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('satuan_konversis', function (Blueprint $table) {
            $table->increments('id_satuan_konversi');
            $table->integer('id_satuan');
            $table->integer('id_produk');
            $table->float('jumlah_konversi', 100, 2)->default(0.00);
            $table->float('harga_jual_konversi', 100, 2)->default(0.00);
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
        Schema::dropIfExists('satuan_konversis');
    }
}
