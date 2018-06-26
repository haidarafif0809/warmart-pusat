<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomNamaTampilBankDiBankWarung extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_warungs', function (Blueprint $table) {
            $table->string('nama_tampil');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_warungs', function (Blueprint $table) {
            $table->dropColumn('nama_tampil');
        });
    }
}
