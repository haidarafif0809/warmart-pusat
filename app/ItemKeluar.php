<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Illuminate\Support\Facades\DB;
use Auth;

class ItemKeluar extends Model
{
    use AuditableTrait;

    protected $fillable = ['id','no_faktur','keterangan','total','warung_id','created_at','updated_at'];


    //MODEL EVENT 
  	public static function boot() {
    parent::boot();
      
    self::deleting(function($itemKeluar) {

     

      $hpp_terpakai =  Hpp::where('no_faktur_hpp_masuk', $itemKeluar->no_faktur)->count();
      
      if ($hpp_terpakai > 0) {

         $pesan_alert = 
               '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">error</i>
                    </div>
                    <b>Gagal : Item Masuk Sudah Terpakai Tidak Boleh Di Hapus</b>
                </div>';

          Session:: flash("flash_notification", [
            "level"=>"danger",
            "message"=> $pesan_alert
            ]);
        return false;
      }
      else {
        DetailItemKeluar::where('no_faktur', $itemKeluar->no_faktur)->delete();
        Hpp::where('no_faktur', $itemKeluar->no_faktur)->delete();

        return true;
      }
 
    
    });   

    self::creating(function($itemKeluar) {


      $total_nilai_item_keluar = Hpp::where('no_faktur', $itemKeluar->no_faktur)->sum('total_nilai');

     $itemKeluar->total = $total_nilai_item_keluar;

      return true;
    
    });  




  } //end modal event


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

    //ambil bulan dan no_faktur dari tanggal item_keluar terakhir
    $item_keluar = ItemKeluar::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])->where('warung_id',Auth::user()->id_warung)->orderBy('id','DESC')->first();

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
        $no_faktur = "1/IK/".$data_bulan_terakhir."/".$tahun_terakhir;
      }
      else {
        $nomor = 1 + $ambil_nomor ;
        $no_faktur = $nomor."/IK/".$data_bulan_terakhir."/".$tahun_terakhir;
      }

      return $no_faktur;
      //PROSES MEMBUAT NO. FAKTUR ITEM KELUAR
    }
}
