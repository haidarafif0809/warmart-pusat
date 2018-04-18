<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomIdwarungKeranjangBelanja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('keranjang_belanjas', function (Blueprint $table) {
        $table->integer('warung_id')->nullable();
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
        $table->dropColumn('warung_id'); 
    });
  }
}
