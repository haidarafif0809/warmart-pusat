<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PerbaikanNoFakturKas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('kas_masuks', function (Blueprint $table) {
            $table->dropUnique('kas_masuks_no_faktur_unique');
        });
       Schema::table('kas_keluars', function (Blueprint $table) {
            $table->dropUnique('kas_keluars_no_faktur_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kas_masuks', function (Blueprint $table) {
            $table->string('no_faktur')->unique()->change();
        });
        Schema::table('kas_keluars', function (Blueprint $table) {
            $table->string('no_faktur')->unique()->change();
        });
    }
}
