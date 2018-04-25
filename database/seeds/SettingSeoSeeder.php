<?php

use Illuminate\Database\Seeder;
use App\SettingSeo;

class SettingSeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // SETTING SEO
    	SettingSeo::create(['content_keyword'=> 'Keyword Toko','content_description' => 'Deskripsi Toko','warung_id'=>'1']);
    }
}
