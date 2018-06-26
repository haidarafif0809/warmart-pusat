<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahColomFotoProdukTableBarang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barangs', function (Blueprint $table) { 
        $table->string('foto_2')->nullable();
        $table->string('foto_3')->nullable(); 
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('barangs', function (Blueprint $table) {
        $table->dropColumn('foto_2');
        $table->dropColumn('foto_3');
       });
    }
}
