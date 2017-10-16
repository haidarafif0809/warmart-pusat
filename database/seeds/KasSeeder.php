<?php

use Illuminate\Database\Seeder;
use App\Kas;


class KasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         
      // Membuat Seeder Kas
    	$kas = new Kas();
    	$kas->kode_kas = "K001";    	
    	$kas->nama_kas = "KAS BESAR";
    	$kas->status_kas = "1";
      $kas->default_kas = "0";
      $kas->warung_id = "4";
	    $kas->save();

      // Membuat Seeder Kas
    	$kas = new Kas();
    	$kas->kode_kas = "K002";    	
    	$kas->nama_kas = "KAS WARUNG";
    	$kas->status_kas = "1";
      $kas->default_kas = "1";
      $kas->warung_id = "4";
	    $kas->save();
    
    }
}
