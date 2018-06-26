<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Tambahkolomstatusdipendaftarantopos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pendaftar_topos', function (Blueprint $table) {
            $table->integer('status_pembayaran')->nullable(); 
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
            $table->dropColumn('status_pembayaran');
        });
    }
}
