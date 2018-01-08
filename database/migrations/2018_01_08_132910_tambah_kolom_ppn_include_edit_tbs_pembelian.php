<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomPpnIncludeEditTbsPembelian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('edit_tbs_pembelians', function (Blueprint $table) {
        $table->float('tax_include', 100,6)->default(0.00)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('edit_tbs_pembelians', function (Blueprint $table) {
        $table->dropColumn('tax_include');
        });
    }
}
