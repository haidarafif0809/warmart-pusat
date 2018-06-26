<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomCollapseKeranjangBelanja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warungs', function (Blueprint $table) {
            $table->integer('collapse_keranjang_belanja')->nullable()->comment('0 = tidak aktif, 1 = aktif')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warungs', function (Blueprint $table) {
            $table->dropColumn('collapse_keranjang_belanja');
        });
    }
}
