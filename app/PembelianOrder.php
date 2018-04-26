<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;
use Auth;

class PembelianOrder extends Model
{
	use AuditableTrait;
	protected $fillable = ['no_faktur_order', 'suplier_id', 'total', 'keterangan', 'status_order', 'warung_id', 'created_by', 'updated_by'];

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
		$pembelian = PembelianOrder::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur_order'])->where('warung_id', $warung_id)->orderBy('id', 'DESC')->first();

		if ($pembelian != null) {
			$pisah_nomor = explode("/", $pembelian->no_faktur_order);
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
        	$no_faktur = "1/OP/" . $data_bulan_terakhir . "/" . $tahun_terakhir;
        } else {
        	$nomor     = 1 + $ambil_nomor;
        	$no_faktur = $nomor . "/OP/" . $data_bulan_terakhir . "/" . $tahun_terakhir;
        }

        return $no_faktur;
        //PROSES MEMBUAT NO. FAKTUR ITEM KELUAR
    }

    public function getStatusAttribute(){
    	if ($this->status_order == 1) {
    		$status_order = "Order";
    	}elseif($this->status_order == 2){
    		$status_order = "Proses Order";
    	}else{
    		$status_order = "Selesai Order";
    	}

    	return $status_order;
    }

    // Transaksi Pembelian Order
    public function scopeDataTransaksiPembelianOrder($query)
    {
    	$query->select('pembelian_orders.id', 'pembelian_orders.no_faktur_order', 'supliers.nama_suplier', 'pembelian_orders.total', 'pembelian_orders.keterangan', 'pembelian_orders.status_order', 'pembelian_orders.created_by', 'pembelian_orders.created_at', 'users.name')
    	->leftJoin('supliers', 'pembelian_orders.suplier_id', '=', 'supliers.id')
    	->leftJoin('users', 'pembelian_orders.created_by', '=', 'users.id')
    	->where('pembelian_orders.warung_id', Auth::user()->id_warung)->orderBy('pembelian_orders.id', 'desc');
    	return $query;
    }
}
