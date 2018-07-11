<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UbahkolomnoRekDiPendaftaranTopos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('pendaftar_topos', function(Blueprint $table)
       {
        $table->dropUnique('pendaftar_topos_no_rekening_unique');

    });
   }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
     Schema::table('pendaftar_topos', function(Blueprint $table)
     {
            //Put the index back when the migration is rolled back
        $table->unique('no_rekening');
    });
 }
}
