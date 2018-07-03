<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomDiPendafataranTopos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('pendaftar_topos', function (Blueprint $table) {
            $table->string('nama_subdomain');
            $table->string('foto')->nullable(); 
            $table->text('keterangan')->nullable(); 
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
            $table->dropColumn('nama_subdomain');
            $table->dropColumn('foto');
            $table->dropColumn('keterangan');
        });
    }
}
