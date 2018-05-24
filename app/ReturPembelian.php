<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Illuminate\Support\Facades\DB;
use Auth;

class ReturPembelian extends Model
{
	use AuditableTrait;
	protected $fillable = ['no_faktur_retur', 'suplier_id', 'keterangan', 'total', 'total_bayar', 'potongan', 'potong_hutang', 'tax', 'ppn', 'warung_id'];

	public function getWaktuAttribute() {
		$tanggal       = date($this->created_at);
		$date          = date_create($tanggal);
		$date_terbalik = date_format($date, "d-m-Y H:i:s");
		return $date_terbalik;
	}

	public static function no_faktur($warung_id) {
		$tahun_sekarang = date('Y');
		$bulan_sekarang = date('m');
		$tahun_terakhir = substr($tahun_sekarang, 2);

        //mengecek jumlah karakter dari bulan sekarang
		$cek_jumlah_bulan = strlen($bulan_sekarang);

        //jika jumlah karakter dari bulannya sama dengan 1 maka di tambah 0 di depannya
		if ($cek_jumlah_bulan == 1) {
			$data_bulan_terakhir = "0" . $bulan_sekarang;
		} else {
			$data_bulan_terakhir = $bulan_sekarang;
		}

        //ambil bulan dan no_faktur dari tanggal retur_pembelian terakhir
		$retur_pembelian = ReturPembelian::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur_retur'])->where('warung_id', $warung_id)->orderBy('id', 'DESC')->first();

		if ($retur_pembelian != null) {
			$pisah_nomor = explode("/", $retur_pembelian->no_faktur_retur);
			$ambil_nomor = $pisah_nomor[0];
			$bulan_akhir = $retur_pembelian->bulan;
		} else {
			$ambil_nomor = 1;
			$bulan_akhir = 13;
		}

        /*jika bulan terakhir dari retur_pembelian tidak sama dengan bulan sekarang,
        maka nomor nya kembali mulai dari 1, jika tidak maka nomor terakhir ditambah dengan 1
         */
        if ($bulan_akhir != $bulan_sekarang) {
        	$no_faktur = "1/RB/" . $data_bulan_terakhir . "/" . $tahun_terakhir;
        } else {
        	$nomor     = 1 + $ambil_nomor;
        	$no_faktur = $nomor . "/RB/" . $data_bulan_terakhir . "/" . $tahun_terakhir;
        }

        return $no_faktur;
    }

    public function scopeDataReturPembelian ($query) {
    	$query = ReturPembelian::select(['retur_pembelians.no_faktur_retur', 'retur_pembelians.suplier_id', 'retur_pembelians.id', 'retur_pembelians.total', 'retur_pembelians.total_bayar', 'retur_pembelians.potongan', 'retur_pembelians.potong_hutang', 'supliers.nama_suplier'])
    	->leftJoin('supliers', 'supliers.id', '=', 'retur_pembelians.suplier_id')
    	->where('retur_pembelians.warung_id', Auth::user()->id_warung)
    	->orderBy('retur_pembelians.id', 'DESC');

    	return $query;
    }

    public function scopeQueryCetak($query,$id) {
    	$query =  ReturPembelian::select('warungs.name','warungs.alamat','warungs.no_telpon','retur_pembelians.no_faktur_retur', 'retur_pembelians.created_at', 'retur_pembelians.total', 'retur_pembelians.total_bayar', 'retur_pembelians.potongan', 'retur_pembelians.potong_hutang', 'supliers.nama_suplier')
    	->leftJoin('supliers', 'retur_pembelians.suplier_id', '=', 'supliers.id')
    	->leftJoin('warungs','retur_pembelians.warung_id','=','warungs.id')
    	->where('retur_pembelians.id',$id)
    	->where('retur_pembelians.warung_id', Auth::user()->id_warung)
    	->orderBy('retur_pembelians.id');

    	return $query;
    }
}
