<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomNoTelponWarung extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
         Schema::table('warungs', function (Blueprint $table) {
            //
            $table->string('no_telpon');
            $table->string('email');
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
        Schema::table('warungs', function (Blueprint $table) {
            $table->dropColumn('no_telpon');
            $table->dropColumn('email');
            $table->dropColumn('created_by');            
            $table->dropColumn('updated_by');
        });
    }
}
