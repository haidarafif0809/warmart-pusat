<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditTbsPembayaranHutangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edit_tbs_pembayaran_hutangs', function (Blueprint $table) {
            $table->increments('id_edit_tbs_pembayaran_hutang');
            $table->string('session_id');
            $table->string('no_faktur_pembayaran')->nullable();
            $table->string('no_faktur_pembelian');
            $table->string('jatuh_tempo');
            $table->float('hutang', 100, 2)->default(0.00)->nullable();
            $table->float('potongan', 100, 6)->default(0.00)->nullable();
            $table->float('subtotal_hutang', 100, 2)->default(0.00)->nullable();
            $table->float('jumlah_bayar', 100, 2)->default(0.00)->nullable();
            $table->integer('suplier_id');
            $table->integer('warung_id');
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edit_tbs_pembayaran_hutangs');
    }
}
