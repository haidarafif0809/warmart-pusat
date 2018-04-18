<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomIdwarungDIdefaultAlamatPelanggan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     Schema::table('setting_default_alamat_pelanggans', function (Blueprint $table) {
        $table->integer('warung_id')->nullable();
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
        $table->dropColumn('warung_id'); 
    });
 }
}
