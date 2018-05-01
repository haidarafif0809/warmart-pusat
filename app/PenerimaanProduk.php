<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;
use Auth;

class PenerimaanProduk extends Model
{
	use AuditableTrait;
	protected $fillable   = ['no_faktur_penerimaan', 'faktur_order', 'suplier_id', 'total', 'keterangan', 'status_penerimaan', 'warung_id', 'created_by', 'updated_by'];


    // MEMBUAT NO. FAKTUR   
	public static function no_faktur($warung_id)
	{

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

        //ambil bulan dan no_faktur dari tanggal pembelian terakhir
		$pembelian = PenerimaanProduk::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur_penerimaan'])->where('warung_id', $warung_id)->orderBy('id', 'DESC')->first();

		if ($pembelian != null) {
			$pisah_nomor = explode("/", $pembelian->no_faktur_penerimaan);
			$ambil_nomor = $pisah_nomor[0];
			$bulan_akhir = $pembelian->bulan;
		} else {
			$ambil_nomor = 1;
			$bulan_akhir = 13;
		}

        /*jika bulan terakhir dari pembelian tidak sama dengan bulan sekarang,
        maka nomor nya kembali mulai dari 1, jika tidak maka nomor terakhir ditambah dengan 1
         */
        if ($bulan_akhir != $bulan_sekarang) {
        	$no_faktur = "1/TP/" . $data_bulan_terakhir . "/" . $tahun_terakhir;
        } else {
        	$nomor     = 1 + $ambil_nomor;
        	$no_faktur = $nomor . "/TP/" . $data_bulan_terakhir . "/" . $tahun_terakhir;
        }

        return $no_faktur;
        //PROSES MEMBUAT NO. FAKTUR ITEM KELUAR
    }

    public function getStatusAttribute(){
    	if ($this->status_penerimaan == 1) {
    		$status_penerimaan = "Diterima";
    	}elseif($this->status_penerimaan == 2){
    		$status_penerimaan = "Proses";
    	}else{
    		$status_penerimaan = "Selesai";
    	}

    	return $status_penerimaan;
    }

    // Transaksi Pembelian Order
    public function scopeDataTransaksiPenerimaanProduk($query)
    {
    	$query->select('penerimaan_produks.no_faktur_penerimaan', 'penerimaan_produks.faktur_order' ,'penerimaan_produks.id', 'supliers.nama_suplier', 'penerimaan_produks.total', 'penerimaan_produks.keterangan', 'penerimaan_produks.status_penerimaan', 'penerimaan_produks.warung_id', 'penerimaan_produks.created_at', 'users.name')
    	->leftJoin('supliers', 'penerimaan_produks.suplier_id', '=', 'supliers.id')
    	->leftJoin('users', 'penerimaan_produks.created_by', '=', 'users.id')
    	->where('penerimaan_produks.warung_id', Auth::user()->id_warung)->orderBy('penerimaan_produks.id', 'desc');
    	return $query;
    }

    public function scopeCetakPenerimaanProduk($query, $warung_id, $id)
    {
    	$query->select(['penerimaan_produks.no_faktur_order', 'penerimaan_produks.created_at', 'penerimaan_produks.status_order', 'penerimaan_produks.total', 'supliers.nama_suplier', 'supliers.alamat', 'warungs.name', 'warungs.alamat', 'warungs.no_telpon',])
    	->leftJoin('supliers', 'supliers.id', '=', 'penerimaan_produks.suplier_id')
    	->leftJoin('warungs', 'warungs.id', '=', 'penerimaan_produks.warung_id')
    	->where('penerimaan_produks.warung_id', $warung_id)
    	->where('penerimaan_produks.id', $id);

    	return $query;
    }
}
