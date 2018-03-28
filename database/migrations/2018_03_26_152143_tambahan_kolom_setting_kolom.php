<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahanKolomSettingKolom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting_promos', function (Blueprint $table) {
            $table->string('dari_tanggal');
            $table->string('sampai_tanggal');
            $table->string('status');
            $table->string('jenis_promo');
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
            $table->dropColumn('dari_tanggal');
            $table->dropColumn('sampai_tanggal');
            $table->dropColumn('status');
            $table->dropColumn('jenis_promo');
        });
    }
}
