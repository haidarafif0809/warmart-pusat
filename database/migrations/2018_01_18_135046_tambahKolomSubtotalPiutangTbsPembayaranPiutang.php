<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TambahKolomSubtotalPiutangTbsPembayaranPiutang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbs_pembayaran_piutangs', function (Blueprint $table) {
            $table->string('no_faktur_pembayaran')->nullable()->change();
            $table->integer('subtotal_piutang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbs_pembayaran_piutangs', function (Blueprint $table) {
            $table->string('no_faktur_pembayaran')->nullable()->change();
            $table->dropColumn('subtotal_piutang');
        });
    }
}
