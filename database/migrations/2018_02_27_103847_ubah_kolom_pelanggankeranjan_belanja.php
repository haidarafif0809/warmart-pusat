<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UbahKolomPelanggankeranjanBelanja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('keranjang_belanjas', function (Blueprint $table) {
            $table->integer('id_pelanggan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
     Schema::table('keranjang_belanjas', function (Blueprint $table) {
        $table->integer('id_pelanggan')->nullable()->change();
    });
 }
}
