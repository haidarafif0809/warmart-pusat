<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MengubahNamaKolomHeaderWarung extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting_footers', function (Blueprint $table) {
            $table->renameColumn('header_warung', 'judul_warung');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setting_footers', function (Blueprint $table) {
            $table->renameColumn('header_warung', 'judul_warung');
        });
    }
}
