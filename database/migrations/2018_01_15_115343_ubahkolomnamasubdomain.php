<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Ubahkolomnamasubdomain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     Schema::table('pendaftar_topos', function (Blueprint $table) {
        $table->string('nama_subdomain')->nullable()->change();
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
        $table->string('nama_subdomain')->nullable()->change();
    });
 }
}
