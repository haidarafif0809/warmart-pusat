<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UbahKolomStatusSettingDefaultPengiriman extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('setting_default_alamat_pelanggans', function (Blueprint $table) {
        $table->renameColumn('status', 'status_aktif');
    });

   }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('setting_default_alamat_pelanggans', function (Blueprint $table) {        
        $table->renameColumn('status', 'status_aktif');
    });
  }
}
