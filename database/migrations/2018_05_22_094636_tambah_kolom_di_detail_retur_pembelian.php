<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomDiDetailReturPembelian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_retur_pembelians', function (Blueprint $table) { 
            $table->string('ppn')->nullable();
            $table->float('tax_include', 100,6)->default(0.00)->nullable();
            $table->integer('supplier')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detail_retur_pembelians', function (Blueprint $table) {
            $table->dropColumn('ppn');
            $table->dropColumn('tax_include');
            $table->dropColumn('supplier');
        });
    }
}
