<?php

use Illuminate\Database\Seeder;
use App\TemaWarna;

class TemaWarnaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$tema 				= new TemaWarna();
    	$tema->nama_tema    = "Default";
    	$tema->kode_tema	= "#2AC326";
    	$tema->header_tema	= "#E91E63";
    	$tema->default_tema	= 1;
    	$tema->warung_id 	= 1;
    	$tema->save();

    	$tema 				= new TemaWarna();
    	$tema->nama_tema    = "Meadowlark";
    	$tema->kode_tema	= "#ECDB54";
    	$tema->header_tema	= "#E91E63";
    	$tema->default_tema	= 0;
    	$tema->warung_id 	= 1;
    	$tema->save();

    	$tema 				= new TemaWarna();
    	$tema->nama_tema    = "Cherry Tomato";
    	$tema->kode_tema	= "#E94B3C";
    	$tema->header_tema	= "#E91E63";
    	$tema->default_tema	= 0;
    	$tema->warung_id 	= 1;
    	$tema->save();

    	$tema 				= new TemaWarna();
    	$tema->nama_tema    = "Little Boy Blue";
    	$tema->kode_tema	= "#6F9FD8";
    	$tema->header_tema	= "#E91E63";
    	$tema->default_tema	= 0;
    	$tema->warung_id 	= 1;
    	$tema->save();

    	$tema 				= new TemaWarna();
    	$tema->nama_tema    = "Chili Oil";
    	$tema->kode_tema	= "#944473";
    	$tema->header_tema	= "#E91E63";
    	$tema->default_tema	= 0;
    	$tema->warung_id 	= 1;
    	$tema->save();

    	$tema 				= new TemaWarna();
    	$tema->nama_tema    = "Pink Lavender";
    	$tema->kode_tema	= "#DBB1CD";
    	$tema->header_tema	= "#E91E63";
    	$tema->default_tema	= 0;
    	$tema->warung_id 	= 1;
    	$tema->save();
    }
}
