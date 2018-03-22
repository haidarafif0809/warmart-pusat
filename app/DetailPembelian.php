<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;

class DetailPembelian extends Model
{
    use AuditableTrait;
    protected $fillable   = ['no_faktur', 'satuan_id', 'id_produk', 'jumlah_produk', 'harga_produk', 'subtotal', 'tax', 'potongan', 'warung_id', 'ppn', 'tax_include', 'satuan_dasar'];
    protected $primaryKey = 'id_detail_pembelian';

// relasi ke produk
    public function produk()
    {
        return $this->hasOne('App\Barang', 'id', 'id_produk');
    }

    public static function hargaProduk($produk_id, $warung_id)
    {
        $harga = DetailPembelian::select('harga_produk')->where('id_produk', $produk_id)
            ->where('warung_id', $warung_id)->orderBy('id_detail_pembelian', 'DESC');

        return $harga;
    }

    public function getTitleCaseBarangAttribute()
    {
        return title_case($this->produk->nama_barang);
    }
    public function getPemisahJumlahAttribute()
    {
        return number_format($this->jumlah_produk, 2, ',', '.');
    }
    public function getPemisahHargaAttribute()
    {
        return number_format($this->harga_produk, 2, ',', '.');
    }
    public function getPemisahPotonganAttribute()
    {
        return number_format($this->potongan, 2, ',', '.');
    }
    public function getPemisahTaxAttribute()
    {
        return number_format($this->tax, 2, ',', '.');
    }
    public function getPemisahSubtotalAttribute()
    {
        return number_format($this->subtotal, 2, ',', '.');
    }

    public function tanggalSql($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "Y-m-d");
        return $date_format;
    }

//QUERY PENCARIAN DAN PROSES LAPORAN PEMBELIAN
    public function queryLaporanPembelian($request)
    {
        $query_laporan_pembelian = DetailPembelian::select(['detail_pembelians.no_faktur AS no_faktur', 'detail_pembelians.id_produk', DB::raw('SUM(detail_pembelians.jumlah_produk) as jumlah_produk'), 'detail_pembelians.harga_produk', DB::raw('SUM(detail_pembelians.subtotal) as total'), DB::raw('SUM(detail_pembelians.tax) as tax'), DB::raw('SUM(detail_pembelians.potongan) as potongan'), 'satuans.nama_satuan', 'supliers.nama_suplier', 'barangs.kode_barang', 'barangs.nama_barang', DB::raw('SUM(detail_pembelians.harga_produk * detail_pembelians.jumlah_produk) as subtotal')])
            ->leftJoin('barangs', 'barangs.id', '=', 'detail_pembelians.id_produk')
            ->leftJoin('satuans', 'satuans.id', '=', 'detail_pembelians.satuan_id')
            ->leftJoin('pembelians', function ($join) {
                $join->on('pembelians.no_faktur', '=', 'detail_pembelians.no_faktur');
                $join->on('pembelians.warung_id', '=', 'detail_pembelians.warung_id');
            })
            ->leftJoin('supliers', 'supliers.id', '=', 'pembelians.suplier_id')
            ->where(DB::raw('DATE(detail_pembelians.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(detail_pembelians.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal));

        return $query_laporan_pembelian;
    }

//QUERY SUBTOTAL LAPORAN PEMBELIAN
    public function querySubtotalLaporanPembelian($request)
    {
        $query_laporan_pembelian = DetailPembelian::select(DB::raw('SUM(detail_pembelians.jumlah_produk) as jumlah_produk'), DB::raw('SUM(detail_pembelians.potongan) as potongan'), DB::raw('SUM(detail_pembelians.tax) as pajak'), DB::raw('SUM(detail_pembelians.subtotal) as total'), DB::raw('SUM(detail_pembelians.harga_produk * detail_pembelians.jumlah_produk) as subtotal'))
            ->leftJoin('pembelians', 'pembelians.no_faktur', '=', 'detail_pembelians.no_faktur')
            ->where(DB::raw('DATE(detail_pembelians.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(detail_pembelians.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal));

        return $query_laporan_pembelian;
    }

    // LAPORAN PEMBELIAN /PRODUK
    public function scopeLaporanPembelianProduk($query_laporan_pembelian, $request)
    {
        if ($request->suplier == "" && $request->produk != "") {
            $query_laporan_pembelian = $this->queryLaporanPembelian($request)
                ->where('detail_pembelians.id_produk', $request->produk)
                ->where('pembelians.warung_id', Auth::user()->id_warung)
                ->groupBy('detail_pembelians.id_produk', 'pembelians.suplier_id')
                ->orderBy('detail_pembelians.created_at', 'desc');
            # code...
        } elseif ($request->suplier != "" && $request->produk == "") {
            $query_laporan_pembelian = $this->queryLaporanPembelian($request)
                ->where('pembelians.suplier_id', $request->suplier)
                ->where('pembelians.warung_id', Auth::user()->id_warung)
                ->groupBy('detail_pembelians.id_produk', 'pembelians.suplier_id')
                ->orderBy('detail_pembelians.created_at', 'desc');
            # code...
        } elseif ($request->suplier != "" && $request->produk != "") {
            $query_laporan_pembelian = $this->queryLaporanPembelian($request)
                ->where('detail_pembelians.id_produk', $request->produk)
                ->where('pembelians.suplier_id', $request->suplier)
                ->where('pembelians.warung_id', Auth::user()->id_warung)
                ->groupBy('detail_pembelians.id_produk', 'pembelians.suplier_id')
                ->orderBy('detail_pembelians.created_at', 'desc');
            # code...
        } else {
            $query_laporan_pembelian = $this->queryLaporanPembelian($request)
                ->where('pembelians.warung_id', Auth::user()->id_warung)
                ->groupBy('detail_pembelians.id_produk', 'pembelians.suplier_id')
                ->orderBy('detail_pembelians.created_at', 'desc');

        }

        return $query_laporan_pembelian;
    }

    // SUBTOTAL LAPORAN PEMBELIAN /PRODUK
    public function scopeSubtotalLaporanPembelianProduk($query_subtotal_laporan_pembelian, $request)
    {
        if ($request->suplier == "" && $request->produk != "") {
            $query_subtotal_laporan_pembelian = $this->querySubtotalLaporanPembelian($request)
                ->where('detail_pembelians.id_produk', $request->produk)
                ->where('pembelians.warung_id', Auth::user()->id_warung)
                ->orderBy('detail_pembelians.created_at', 'desc');
            # code...
        } elseif ($request->suplier != "" && $request->produk == "") {
            $query_subtotal_laporan_pembelian = $this->querySubtotalLaporanPembelian($request)
                ->where('pembelians.suplier_id', $request->suplier)
                ->where('pembelians.warung_id', Auth::user()->id_warung)
                ->orderBy('detail_pembelians.created_at', 'desc');
            # code...
        } elseif ($request->suplier != "" && $request->produk != "") {
            $query_subtotal_laporan_pembelian = $this->querySubtotalLaporanPembelian($request)
                ->where('detail_pembelians.id_produk', $request->produk)
                ->where('pembelians.suplier_id', $request->suplier)
                ->where('pembelians.warung_id', Auth::user()->id_warung)
                ->orderBy('detail_pembelians.created_at', 'desc');
            # code...
        } else {
            $query_subtotal_laporan_pembelian = $this->querySubtotalLaporanPembelian($request)
                ->where('pembelians.warung_id', Auth::user()->id_warung)
                ->orderBy('detail_pembelians.created_at', 'desc');

        }

        return $query_subtotal_laporan_pembelian;
    }

    // PENCARIAN LAPORAN PEMBELIAN /PRODUK
    public function scopeCariLaporanPembelianProduk($query_laporan_pembelian, $request)
    {
        $search = $request->search;
        if ($request->suplier == "" && $request->produk != "") {
            $query_laporan_pembelian = $this->queryLaporanPembelian($request)
                ->where('detail_pembelians.id_produk', $request->produk)
                ->where('pembelians.warung_id', Auth::user()->id_warung)
                ->where(function ($query) use ($search) {
                    $query->orwhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                        ->orwhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
                        ->orwhere('supliers.nama_suplier', 'LIKE', '%' . $search . '%')
                        ->orwhere('satuans.nama_satuan', 'LIKE', '%' . $search . '%');
                })->orderBy('detail_pembelians.created_at', 'desc');
            # code...
        } elseif ($request->suplier != "" && $request->produk == "") {
            $query_laporan_pembelian = $this->queryLaporanPembelian($request)
                ->where('pembelians.suplier_id', $request->suplier)
                ->where('pembelians.warung_id', Auth::user()->id_warung)
                ->where(function ($query) use ($search) {
                    $query->orwhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                        ->orwhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
                        ->orwhere('supliers.nama_suplier', 'LIKE', '%' . $search . '%')
                        ->orwhere('satuans.nama_satuan', 'LIKE', '%' . $search . '%');
                })->orderBy('detail_pembelians.created_at', 'desc');
            # code...
        } elseif ($request->suplier != "" && $request->produk != "") {
            $query_laporan_pembelian = $this->queryLaporanPembelian($request)
                ->where('detail_pembelians.id_produk', $request->produk)
                ->where('pembelians.suplier_id', $request->suplier)
                ->where('pembelians.warung_id', Auth::user()->id_warung)
                ->where(function ($query) use ($search) {
                    $query->orwhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                        ->orwhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
                        ->orwhere('supliers.nama_suplier', 'LIKE', '%' . $search . '%')
                        ->orwhere('satuans.nama_satuan', 'LIKE', '%' . $search . '%');
                })->orderBy('detail_pembelians.created_at', 'desc');
            # code...
        } else {
            $query_laporan_pembelian = $this->queryLaporanPembelian($request)
                ->where('pembelians.warung_id', Auth::user()->id_warung)
                ->where(function ($query) use ($search) {
                    $query->orwhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                        ->orwhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
                        ->orwhere('supliers.nama_suplier', 'LIKE', '%' . $search . '%')
                        ->orwhere('satuans.nama_satuan', 'LIKE', '%' . $search . '%');
                })->orderBy('detail_pembelians.created_at', 'desc');

        }

        return $query_laporan_pembelian;
    }
    public function subtotalTbs($user_warung, $no_faktur)
    {
        $detail_pembelian = DetailPembelian::select([DB::raw('SUM(subtotal) as subtotal')])->where('warung_id', $user_warung)->where('no_faktur', $no_faktur)->first();
        if ($detail_pembelian->subtotal == null || $detail_pembelian->subtotal == '') {
            return 0;
        } else {
            return $detail_pembelian->subtotal;
        }
    }
}
