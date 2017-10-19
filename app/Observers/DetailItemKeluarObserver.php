<?php

namespace App\Observers;

use App\DetailItemKeluar;
use App\Hpp;
use Auth;

class DetailItemKeluarObserver
{
	public function creating(DetailItemKeluar $DetailItemKeluar){

		$total_nilai = $DetailItemKeluar->jumlah_produk *  $DetailItemKeluar->produk->harga_beli;

		Hpp::create([
			'no_faktur' 		=> $DetailItemKeluar->no_faktur, 
			'id_produk' 		=> $DetailItemKeluar->id_produk, 
			'jenis_transaksi'	=> 'item_keluar', 
			'jumlah_keluar'		=> $DetailItemKeluar->jumlah_produk, 
			'harga_unit'		=> $DetailItemKeluar->produk->harga_beli, 
			'total_nilai'		=> $total_nilai, 
			'warung_id'			=> Auth::user()->id_warung, 
			'jenis_hpp'			=> '2'
		]);
		
		return true;
    }
}