<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UbahKolomFakturEditTbsPenjualan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('edit_tbs_penjualans', function (Blueprint $table) {
        $table->string('no_faktur')->nullable()->change();
    });

   }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('edit_tbs_penjualans', function (Blueprint $table) {
        $table->string('no_faktur')->nullable()->change();
    });
   }
}
