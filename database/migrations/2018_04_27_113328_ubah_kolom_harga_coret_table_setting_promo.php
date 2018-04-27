<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UbahKolomHargaCoretTableSettingPromo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting_promos', function (Blueprint $table) {
        $table->bigInteger('harga_coret')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setting_promos', function (Blueprint $table) {
        $table->bigInteger('harga_coret')->change();
        });
    }
}
