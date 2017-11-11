<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UbahKolomKonfirmasiAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('konfirmasi_admin')->nullable()->default(0)->change();
        });
        Schema::table('barangs', function (Blueprint $table) {
            $table->integer('konfirmasi_admin')->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('konfirmasi_admin')->nullable()->default(0)->change();
        });
        Schema::table('barangs', function (Blueprint $table) {
            $table->integer('konfirmasi_admin')->nullable()->default(0)->change();
        });
    }
}
