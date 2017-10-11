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
    	$kas->kode_kas = "1.1";    	
    	$kas->nama_kas = "KAS WARUNG";
    	$kas->status_kas = "1";
    	$kas->default_kas = "1";
	    $kas->save();

      // Membuat Seeder Kas
    	$kas = new Kas();
    	$kas->kode_kas = "1.2";    	
    	$kas->nama_kas = "KAS UMAT";
    	$kas->status_kas = "2";
    	$kas->default_kas = "2";
	    $kas->save();
    
    }
}
