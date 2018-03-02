<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;

class TransaksiHutang extends Model
{

	use LaratrustUserTrait;
	use AuditableTrait;

	protected $fillable = ['jenis_transaksi','suplier_id','no_faktur','jumlah_masuk','jumlah_keluar','warung_id','id_transaksi'];

        public function getWaktuAttribute()
    {
        $tanggal       = date($this->created_at);
        $date          = date_create($tanggal);
        $date_terbalik = date_format($date, "d-m-Y H:i:s");
        return $date_terbalik;
    }

        public function tanggalSql($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "Y-m-d");
        return $date_format;
    }

    // DATA PEMBELIAN HUTANG
    public function scopeDataPembelianHutang($data_supplier_hutang)
    {
         $data_supplier_hutang = TransaksiHutang::select(['pembelians.id', 'pembelians.no_faktur', 'pembelians.suplier_id', 'pembelians.kredit', 'pembelians.tanggal_jt_tempo', 'supliers.nama_suplier', DB::raw('IFNULL(SUM(transaksi_hutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_hutangs.jumlah_keluar),0) AS sisa_hutang')])
            ->leftJoin('pembelians', 'transaksi_hutangs.id_transaksi', '=', 'pembelians.id')
            ->leftJoin('supliers', 'supliers.id', '=', 'pembelians.suplier_id')
            ->where('pembelians.status_pembelian', 'Hutang')
            ->where('pembelians.warung_id', Auth::user()->id_warung)
            ->groupBy('transaksi_hutangs.id_transaksi')
            ->having('sisa_hutang', '>', 0);

        return $data_supplier_hutang;
    }

  // DATA PEMBELIAN HUTANG
    public function scopeGetDataPembelianHutang($data_supplier_hutang,$id_suplier)
    {
    $data_supplier_hutang = TransaksiHutang::select(['pembelians.id', 'pembelians.no_faktur','pembelians.created_at', 'pembelians.suplier_id', 'pembelians.total', 'pembelians.tanggal_jt_tempo',DB::raw('IFNULL(SUM(transaksi_hutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_hutangs.jumlah_keluar),0) AS sisa_hutang')])
            ->leftJoin('pembelians', 'transaksi_hutangs.id_transaksi', '=', 'pembelians.id')
            ->leftJoin('supliers', 'supliers.id', '=', 'pembelians.suplier_id')
            ->where('pembelians.status_pembelian', 'Hutang')
            ->where('pembelians.suplier_id', $id_suplier)
            ->where('pembelians.warung_id', Auth::user()->id_warung)
            ->groupBy('transaksi_hutangs.id_transaksi');
		
		return $data_supplier_hutang;
    }



      // DATA PEMBELIAN HUTANG
    public function scopeGetDataHutangBeredar($data_supplier_hutang,$request)
    {
        if ($request->suplier != "") {
            $data_supplier_hutang = $this->queryLaporanHutangBeredar($request)
            ->where('pembelians.status_pembelian', 'Hutang')
            ->where('pembelians.suplier_id', $request->suplier)
            ->where(DB::raw('DATE(transaksi_hutangs.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(transaksi_hutangs.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('pembelians.warung_id', Auth::user()->id_warung)
            ->groupBy('transaksi_hutangs.id_transaksi')
            ->havingRaw('IFNULL(SUM(transaksi_hutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_hutangs.jumlah_keluar),0) > 0');
        }else{
             $data_supplier_hutang = $this->queryLaporanHutangBeredar($request)
            ->where('pembelians.status_pembelian', 'Hutang')
            ->where(DB::raw('DATE(transaksi_hutangs.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(transaksi_hutangs.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('pembelians.warung_id', Auth::user()->id_warung)
            ->groupBy('transaksi_hutangs.id_transaksi')
            ->havingRaw('IFNULL(SUM(transaksi_hutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_hutangs.jumlah_keluar),0) > 0');
        }  
        return $data_supplier_hutang;
    }


          // DATA PEMBELIAN HUTANG
    public function scopeCariDataHutangBeredar($data_supplier_hutang,$request)
    {
        $search = $request->search;
        if ($request->suplier != "") {
            $data_supplier_hutang = $this->queryLaporanHutangBeredar($request)
            ->where('pembelians.status_pembelian', 'Hutang')
            ->where('pembelians.suplier_id', $request->suplier)
            ->where(DB::raw('DATE(transaksi_hutangs.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(transaksi_hutangs.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('pembelians.warung_id', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
                    $query->orWhere('pembelians.no_faktur', 'LIKE', '%' . $search . '%')
                        ->orWhere('supliers.nama_suplier', 'LIKE', '%' . $search . '%');
                })
            ->groupBy('transaksi_hutangs.id_transaksi')
            ->havingRaw('IFNULL(SUM(transaksi_hutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_hutangs.jumlah_keluar),0) > 0');
        }else{
             $data_supplier_hutang = $this->queryLaporanHutangBeredar($request)
            ->where('pembelians.status_pembelian', 'Hutang')
            ->where(DB::raw('DATE(transaksi_hutangs.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(transaksi_hutangs.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('pembelians.warung_id', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
                    $query->orWhere('pembelians.no_faktur', 'LIKE', '%' . $search . '%')
                        ->orWhere('supliers.nama_suplier', 'LIKE', '%' . $search . '%');
                })
            ->groupBy('transaksi_hutangs.id_transaksi')
            ->havingRaw('IFNULL(SUM(transaksi_hutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_hutangs.jumlah_keluar),0) > 0');
        }  
        return $data_supplier_hutang;
    }

          // DATA PEMBELIAN HUTANG
    public function scopeTotalHutangBeredar($data_supplier_hutang,$request)
    {

        if ($request->suplier != "") {
            $data_supplier_hutang = $this->queryTotalLaporanHutangBeredar($request)
            ->where('pembelians.status_pembelian', 'Hutang')
            ->where('pembelians.suplier_id', $request->suplier)
            ->where(DB::raw('DATE(transaksi_hutangs.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(transaksi_hutangs.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('pembelians.warung_id', Auth::user()->id_warung)
            ->havingRaw('IFNULL(SUM(transaksi_hutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_hutangs.jumlah_keluar),0) > 0');
        }else{
             $data_supplier_hutang = $this->queryTotalLaporanHutangBeredar($request)
            ->where('pembelians.status_pembelian', 'Hutang')
            ->where(DB::raw('DATE(transaksi_hutangs.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(transaksi_hutangs.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('pembelians.warung_id', Auth::user()->id_warung)
            ->havingRaw('IFNULL(SUM(transaksi_hutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_hutangs.jumlah_keluar),0) > 0');
        }  
        return $data_supplier_hutang;
    }

        public function scopeTotalSisaHutang($data_supplier_hutang,$request)
    {

        if ($request->suplier != "") {
            $data_supplier_hutang = $this->queryTotalLaporanHutangBeredar($request)
            ->where('pembelians.status_pembelian', 'Hutang')
            ->where('pembelians.suplier_id', $request->suplier)
            ->where(DB::raw('DATE(transaksi_hutangs.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(transaksi_hutangs.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('pembelians.warung_id', Auth::user()->id_warung)
            ->groupBy('transaksi_hutangs.id_transaksi')
            ->havingRaw('IFNULL(SUM(transaksi_hutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_hutangs.jumlah_keluar),0) = 0');
        }else{
             $data_supplier_hutang = $this->queryTotalLaporanHutangBeredar($request)
            ->where('pembelians.status_pembelian', 'Hutang')
            ->where(DB::raw('DATE(transaksi_hutangs.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(transaksi_hutangs.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('pembelians.warung_id', Auth::user()->id_warung)
            ->groupBy('transaksi_hutangs.id_transaksi')
            ->havingRaw('IFNULL(SUM(transaksi_hutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_hutangs.jumlah_keluar),0) = 0');
        }  
        return $data_supplier_hutang;
    }

        public function queryLaporanHutangBeredar($request)
    {
          $data_supplier_hutang =  TransaksiHutang::select(['supliers.nama_suplier as nama_suplier','users.name as name','transaksi_hutangs.created_at','pembelians.id', 'pembelians.no_faktur','pembelians.created_at', 'pembelians.suplier_id', 'pembelians.total', 'pembelians.tanggal_jt_tempo as tanggal_jt_tempo',DB::raw('IFNULL(SUM(transaksi_hutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_hutangs.jumlah_keluar),0) AS sisa_hutang'),DB::raw('IFNULL(SUM(transaksi_hutangs.jumlah_keluar),0) AS pembayaran'),DB::raw('DATEDIFF(pembelians.tanggal_jt_tempo,DATE(NOW())) AS usia_hutang')])
            ->leftJoin('pembelians', 'transaksi_hutangs.id_transaksi', '=', 'pembelians.id')
            ->leftJoin('users','transaksi_hutangs.created_by','=','users.id')            
            ->leftJoin('supliers','pembelians.suplier_id','=','supliers.id');

            return $data_supplier_hutang;
    }

            public function queryTotalLaporanHutangBeredar($request)
    {

                $data_supplier_hutang =  TransaksiHutang::select([DB::raw('IFNULL(SUM(transaksi_hutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_hutangs.jumlah_keluar),0) AS sisa_hutang'),DB::raw('IFNULL(SUM(.transaksi_hutangs.jumlah_keluar),0) AS pembayaran'),DB::raw('IFNULL(SUM(transaksi_hutangs.jumlah_masuk),0) AS nilai_transaksi')])
                   ->leftJoin('pembelians', 'transaksi_hutangs.id_transaksi', '=', 'pembelians.id')
                   ->leftJoin('supliers','pembelians.suplier_id','=','supliers.id');

            return $data_supplier_hutang;
    }



            // PENCARIAN TBS PEMBAYARAN hutang
    public function scopeGetDataCariPembelianHutang($query_pembayaran_hutang,$id_suplier,$request)
    {
        $search    = $request->search; 
    	$data_supplier_hutang = TransaksiHutang::select(['pembelians.id', 'pembelians.no_faktur','pembelians.created_at', 'pembelians.suplier_id', 'pembelians.total', 'pembelians.tanggal_jt_tempo',DB::raw('IFNULL(SUM(transaksi_hutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_hutangs.jumlah_keluar),0) AS sisa_hutang')])
            ->leftJoin('pembelians', 'transaksi_hutangs.id_transaksi', '=', 'pembelians.id')
            ->leftJoin('supliers', 'supliers.id', '=', 'pembelians.suplier_id')
            ->where('pembelians.status_pembelian', 'Hutang')
            ->where('pembelians.suplier_id', $id_suplier)
            ->where('pembelians.warung_id', Auth::user()->id_warung)
            ->groupBy('transaksi_hutangs.id_transaksi')
            ->where(function ($query) use ($search) {
                $query->orwhere('pembelians.no_faktur', 'LIKE', '%' . $search . '%');
            });

        return $data_supplier_hutang;
    }

}
