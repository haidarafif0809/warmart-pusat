<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Illuminate\Support\Facades\DB;
use Auth;

class Pembelian extends Model
{
	use AuditableTrait;
	protected $fillable = ['no_faktur','total','suplier_id','status_pembelian','potongan','tax','tunai','kembalian','kredit','nilai_kredit','cara_bayar','status_beli_awal','tanggal_jt_tempo','keterangan','ppn','warung_id'];

	// relasi ke suppier
	public function suplier(){
		return $this->hasOne('App\Suplier','id','suplier_id');
	}
	// relasi ke kas
	public function kas(){
		return $this->hasOne('App\Kas','id','cara_bayar');
	}

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

			    //ambil bulan dan no_faktur dari tanggal pembelian terakhir
		$pembelian = Pembelian::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])->where('warung_id', $warung_id)->orderBy('id','DESC')->first();

		if ($pembelian != NULL) {
			$pisah_nomor = explode("/", $pembelian->no_faktur);
			$ambil_nomor = $pisah_nomor[0];
			$bulan_akhir = $pembelian->bulan;
		}
		else{
			$ambil_nomor = 1;
			$bulan_akhir = 13;
		}

			      /*jika bulan terakhir dari pembelian tidak sama dengan bulan sekarang, 
			      maka nomor nya kembali mulai dari 1, jika tidak maka nomor terakhir ditambah dengan 1
			      */
			      if ($bulan_akhir != $bulan_sekarang) {
			      	$no_faktur = "1/BL/".$data_bulan_terakhir."/".$tahun_terakhir;
			      }
			      else {
			      	$nomor = 1 + $ambil_nomor ;
			      	$no_faktur = $nomor."/BL/".$data_bulan_terakhir."/".$tahun_terakhir;
			      }

			      return $no_faktur;
			      //PROSES MEMBUAT NO. FAKTUR ITEM KELUAR
			  }
			}
