<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomPenerimaanProduk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penerimaan_produks', function (Blueprint $table) {
            $table->string('no_faktur_penerimaan');
            $table->integer('suplier_id');
            $table->float('total',100,2)->default(0.00);  
            $table->longText('keterangan')->nullable();
            $table->integer('status_penerimaan')->default(0)->comment = "1 = Diterima, 2 = Diproses, 3 = Dibeli";
            $table->integer('warung_id');
            $table->unsignedInteger('created_by')->nullable()->index();            
            $table->unsignedInteger('updated_by')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penerimaan_produks', function (Blueprint $table) {
            $table->dropColumn('no_faktur_penerimaan');
            $table->dropColumn('suplier_id');
            $table->dropColumn('total');
            $table->dropColumn('keterangan');
            $table->dropColumn('status_penerimaan');
            $table->dropColumn('warung_id');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });
    }
}
