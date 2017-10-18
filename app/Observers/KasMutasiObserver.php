<?php

namespace App\Observers;

use App\KasMutasi;
use App\TransaksiKas;

class KasMutasiObserver
{
	public function creating(KasMutasi $KasMutasi){

             //kas keluar  
             TransaksiKas::create([ 
                'no_faktur'         => $KasMutasi->no_faktur, 
                'jenis_transaksi'   =>'kas_mutasi' , 
                'jumlah_keluar'     => $KasMutasi->jumlah, 
                'kas'               => $KasMutasi->dari_kas, 
                'warung_id'         => $KasMutasi->id_warung] );  
             //kas masuk 
             TransaksiKas::create([ 
                'no_faktur'         => $KasMutasi->no_faktur, 
                'jenis_transaksi'   =>'kas_mutasi', 
                'jumlah_masuk'      => $KasMutasi->jumlah, 
                'kas'               => $KasMutasi->ke_kas, 
                'warung_id'         => $KasMutasi->id_warung] ); 
    }

	public function updating(KasMutasi $KasMutasi){

		    TransaksiKas::where('no_faktur' , $KasMutasi->no_faktur)->where('jumlah_masuk','>',0)->update(['jumlah_masuk' => $KasMutasi->jumlah,'kas' => $KasMutasi->ke_kas] ); 

            TransaksiKas::where('no_faktur' , $KasMutasi->no_faktur)->where('jumlah_keluar','>',0)->update(['jumlah_keluar' => $KasMutasi->jumlah,'kas' => $KasMutasi->dari_kas] ); 
    }

	public function deleting(KasMutasi $KasMutasi){

		TransaksiKas::where('no_faktur',$KasMutasi->no_faktur)->delete();

    }
}