<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStokOpnamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stok_opnames', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_faktur');
            $table->string('produk_id');
            $table->float('stok_sekarang', 100, 2)->default(0.00);
            $table->float('jumlah_fisik', 100, 2)->default(0.00);
            $table->float('selisih_fisik', 100, 2)->default(0.00);
            $table->float('harga', 100, 2)->default(0.00);
            $table->float('total', 100, 2)->default(0.00);
            $table->integer('warung_id');
            $table->string('keterangan');
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
        Schema::dropIfExists('stok_opnames');
    }
}
