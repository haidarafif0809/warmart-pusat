<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomSettingAlgolia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting_pembeda_aplikasis', function (Blueprint $table) {
            $table->string('algolia_app_id');
            $table->string('algolia_secret');
            $table->string('algolia_search');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setting_pembeda_aplikasis', function (Blueprint $table) {
            $table->dropColumn('algolia_app_id');
            $table->dropColumn('algolia_secret');
            $table->dropColumn('algolia_search');
        });
    }
}
