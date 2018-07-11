<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UbahkolomTotalPendaftaranTopos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('pendaftar_topos', function (Blueprint $table) {
        $table->float('total', 100, 2)->default(0.00)->change();
    });
  }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {        
      Schema::table('pendaftar_topos', function (Blueprint $table) {        
        $table->float('total', 100, 2)->default(0.00)->change();
    });
  }
}
