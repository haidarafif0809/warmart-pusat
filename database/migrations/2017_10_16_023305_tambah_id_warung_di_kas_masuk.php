<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahIdWarungDiKasMasuk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
         Schema::table('kas_masuks', function (Blueprint $table) { 
            $table->integer('id_warung');  
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
            $table->dropColumn('id_warung'); 
        });
    }
}
