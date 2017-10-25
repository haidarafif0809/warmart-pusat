<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Illuminate\Support\Facades\DB; 
use Auth;


class ItemMasuk extends Model
{
    //

    use AuditableTrait;

    protected $fillable = ['id','no_faktur','keterangan','total','created_at','updated_at','warung_id'];
 
   public static function no_faktur($warung_id){

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
      
      //ambil bulan dan no_faktur dari tanggal item_keluar terakhir
         $item_keluar = ItemMasuk::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])->where('warung_id',$warung_id)->orderBy('id','DESC')->first();


         if ($item_keluar != NULL) {
          $pisah_nomor = explode("/", $item_keluar->no_faktur);
          $ambil_nomor = $pisah_nomor[0];
          $bulan_akhir = $item_keluar->bulan;
         }
         else{
          $ambil_nomor = 1;
          $bulan_akhir = 13;
         }
         
      /*jika bulan terakhir dari item_keluar tidak sama dengan bulan sekarang, 
      maka nomor nya kembali mulai dari 1, jika tidak maka nomor terakhir ditambah dengan 1
      */
        if ($bulan_akhir != $bulan_sekarang) {
          $no_faktur = "1/IM/".$data_bulan_terakhir."/".$tahun_terakhir;
        }
        else {
          $nomor = 1 + $ambil_nomor ;
          $no_faktur = $nomor."/IM/".$data_bulan_terakhir."/".$tahun_terakhir;
        }

        return $no_faktur;
      //PROSES MEMBUAT NO. FAKTUR ITEM KELUAR
    }

}
