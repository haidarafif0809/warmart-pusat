<?php

use Illuminate\Database\Seeder;
use App\Bank;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // Membuat Seeder Bank
    	$bank = new Bank();
	    $bank->nama_bank = "BNI SYARIAH";
	    $bank->atas_nama = 'PT ANDAGLOS GLOBAL TEKNOLOGI';
	    $bank->no_rek = "0123485697";
	    $bank->save();

    	$bank = new Bank();
	    $bank->nama_bank = "MANDIRI SYARIAH";
	    $bank->atas_nama = "IWAN SETIAWAN";
	    $bank->no_rek = "784596123";
	    $bank->save();
    }
}
