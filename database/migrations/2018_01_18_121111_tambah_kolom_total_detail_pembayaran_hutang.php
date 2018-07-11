<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomTotalDetailPembayaranHutang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::table('detail_pembayaran_hutangs', function (Blueprint $table) {
             $table->float('subtotal_hutang', 100, 2)->default(0.00)->nullable();
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
      Schema::table('detail_pembayaran_hutangs', function (Blueprint $table) {
            $table->dropColumn('subtotal_hutang');
        });
    }
}
