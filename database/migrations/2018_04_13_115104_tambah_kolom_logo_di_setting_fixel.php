<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomLogoDiSettingFixel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('setting_fixels', function (Blueprint $table) {
        $table->string('logo');
        $table->integer('warung_id')->nullable()->change();
    });
   }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

       Schema::table('setting_fixels', function (Blueprint $table) {
        $table->dropColumn('logo'); 
        $table->integer('warung_id')->nullable()->change();
    });
   }
}
