<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; 
use Auth;

class KasKeluar extends Model
{
    //

     protected $fillable = ['no_faktur','kas','kategori','jumlah','keterangan', 'warung_id'];

    public function kas(){
    	return $this->belongsTo('App\Kas','kas','id');
    }
    public function kategori(){
    	return $this->belongsTo('App\KategoriTransaksi','kategori','id');
    }


      public static function no_faktur(){

        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');
        $tahun_terakhir = substr($tahun_sekarang, 2);
      
      //mengecek jumlah karakter dari bulan sekarang
        $cek_jumlah_bulan = strlen($bulan_sekarang);

      //jika jumlah karakter dari bulannya sama dengan 1 maka di tambah 0 di depannya
        if ($cek_jumlah_bulan == 1) {
          $data_bulan_terakhir = "0".$bulan_sekarang;
         }
        else{
          $data_bulan_terakhir = $bulan_sekarang;
         }
      
      //ambil bulan dan no_faktur dari tanggal kas_keluar terakhir
         $kas_keluar = KasKeluar::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])->where('warung_id',Auth::user()->id_warung)->orderBy('id','DESC')->first();


         if ($kas_keluar != NULL) {
          $pisah_nomor = explode("/", $kas_keluar->no_faktur);
          $ambil_nomor = $pisah_nomor[0];
          $bulan_akhir = $kas_keluar->bulan;
         }
         else{
          $ambil_nomor = 1;
          $bulan_akhir = 13;
         }
         
      /*jika bulan terakhir dari kas_keluar tidak sama dengan bulan sekarang, 
      maka nomor nya kembali mulai dari 1, jika tidak maka nomor terakhir ditambah dengan 1
      */
        if ($bulan_akhir != $bulan_sekarang) {
          $no_faktur = "1/KK/".$data_bulan_terakhir."/".$tahun_terakhir;
        }
        else {
          $nomor = 1 + $ambil_nomor ;
          $no_faktur = $nomor."/KK/".$data_bulan_terakhir."/".$tahun_terakhir;
        }

        return $no_faktur;
      //PROSES MEMBUAT NO. FAKTUR ITEM KELUAR
    }

}
