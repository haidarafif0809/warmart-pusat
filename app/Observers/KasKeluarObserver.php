<?php

namespace App\Observers;

use App\KasKeluar;
use App\TransaksiKas;
use Auth;
class KasKeluarObserver
{
	public function creating(KasKeluar $KasKeluar){

		TransaksiKas::create(['no_faktur' => $KasKeluar->no_faktur, 'jenis_transaksi'=>'kas_keluar', 'jumlah_keluar' => $KasKeluar->jumlah, 'kas' => $KasKeluar->kas, 'warung_id' => Auth::user()->id_warung]);
    }

	public function updating(KasKeluar $KasKeluar){

		TransaksiKas::where('no_faktur' , $KasKeluar->no_faktur)->update(['jumlah_keluar' => $KasKeluar->jumlah,'kas' => $KasKeluar->kas] );
    }

	public function deleting(KasKeluar $KasKeluar){

		TransaksiKas::where('no_faktur',$KasKeluar->no_faktur)->delete();
    }
}