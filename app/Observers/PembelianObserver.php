<?php  
namespace App\Observers;

use Illuminate\Support\Facades\DB;
use App\Pembelian;
use App\TransaksiKas;
use App\TransaksiHutang;
use Auth;
use Session;

class PembelianObserver
{
	public function creating(Pembelian $Pembelian){
		if ($Pembelian->tunai > 0) {
			TransaksiKas::create([ 
				'no_faktur'         => $Pembelian->no_faktur, 
				'jenis_transaksi'   =>'pembelian' , 
				'jumlah_keluar'     => $Pembelian->tunai, 
				'kas'               => $Pembelian->cara_bayar, 
				'warung_id'         => $Pembelian->warung_id] );  
		}
		if ($Pembelian->kredit > 0) {
			TransaksiHutang::create([ 
				'no_faktur'         => $Pembelian->no_faktur, 
				'jenis_transaksi'   => 'pembelian' , 
				'jumlah_masuk'      => $Pembelian->kredit, 
				'suplier_id'        => $Pembelian->suplier_id, 
				'warung_id'         => $Pembelian->warung_id] );  
		}

		return true;
		
		
   	} // OBERVERS CREATING

   }