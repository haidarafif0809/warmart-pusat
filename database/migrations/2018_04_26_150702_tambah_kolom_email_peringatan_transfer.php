<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomEmailPeringatanTransfer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pesanan_pelanggans', function (Blueprint $table) {
            $table->integer('email_peringatan_transfer')->default(0)->comment('0 = belum dikirimi peringatan, 1 = sudah melebihi 11 jam, 2 = sudah melebihi 11 jam 30 menit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pesanan_pelanggans', function (Blueprint $table) {
            $table->dropColumn('email_peringatan_transfer');
        });
    }
}
