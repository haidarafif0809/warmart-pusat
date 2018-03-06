<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;

class DetailPenjualan extends Model
{
    use AuditableTrait;
    protected $fillable = ['id_penjualan', 'id_produk', 'harga', 'jumlah', 'potongan', 'subtotal'];

    // relasi ke penjualan
    public function penjualan()
    {
        return $this->hasOne('App\Penjualan', 'id', 'id_penjualan');
    }

    // relasi ke produk
    public function produk()
    {
        return $this->hasOne('App\Barang', 'id', 'id_produk');
    }

    public function getNamaProdukAttribute()
    {
        return title_case($this->produk->nama_barang);
    }

    public function tanggalSql($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "Y-m-d");
        return $date_format;
    }

    public function scopeHargaProduk($harga, $no_faktur, $id_produk)
    {

        $harga->select('harga')
            ->where('id_penjualan', $no_faktur)
            ->where('id_produk', $id_produk);

        return $harga;

    }

    //pencarian
    public function scopePencarian($query, $id, $request)
    {

        $query->select('detail_penjualans.id AS id', 'detail_penjualans.jumlah AS jumlah', 'barangs.nama_barang AS nama_barang', 'barangs.kode_barang AS kode_barang', 'detail_penjualans.id_produk AS id_produk', 'detail_penjualans.subtotal AS subtotal', 'detail_penjualans.harga AS harga')
            ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualans.id_produk')
            ->where('detail_penjualans.id_penjualan', $id)
            ->where(function ($query) use ($request) {

                $query->orWhere('barangs.kode_barang', 'LIKE', $request->search . '%')
                    ->orWhere('barangs.nama_barang', 'LIKE', $request->search . '%')
                    ->orWhere('detail_penjualans.id_penjualan', 'LIKE', $request->search . '%');

            })->orderBy('detail_penjualans.id', 'desc');

        return $query;

    }

    // SUBTOTAL LABA KOTOR PENJUALAN POS
    public function scopeSubtotalLaporanLabaKotorPesanan($query_sub_total_penjualan, $request)
    {
        if ($request->pelanggan == "" || $request->pelanggan == null || $request->pelanggan == 0) {
            $query_sub_total_penjualan = DetailPenjualan::select(DB::raw('SUM(detail_penjualans.subtotal) as subtotal'))
                ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('penjualans.id_warung', Auth::user()->id_warung);
        } else {
            $query_sub_total_penjualan = DetailPenjualan::select(DB::raw('SUM(detail_penjualans.subtotal) as subtotal'))
                ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('penjualans.id_pelanggan', $request->pelanggan)
                ->where('penjualans.id_warung', Auth::user()->id_warung);
        }

        return $query_sub_total_penjualan;
    }

    // LAP. LABA KOTOR PENJUALAN PRODUK POS
    public function scopeLaporanLabaKotorProdukPesanan($query_laporan_laba_kotor, $request)
    {
        if ($request->produk == "" || $request->produk == null || $request->produk == 0) {
            $query_laporan_laba_kotor = DetailPenjualan::select(['detail_penjualans.id_produk', 'detail_penjualans.harga', DB::raw('SUM(detail_penjualans.subtotal) as subtotal'), 'barangs.kode_barang', 'barangs.nama_barang'])
                ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualans.id_produk')
                ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->where('barangs.hitung_stok', 1)
                ->groupBy('detail_penjualans.id_produk')
                ->orderBy('detail_penjualans.created_at', 'desc');
        } else {
            $query_laporan_laba_kotor = DetailPenjualan::select(['detail_penjualans.id_produk', 'detail_penjualans.harga', DB::raw('SUM(detail_penjualans.subtotal) as subtotal'), 'barangs.kode_barang', 'barangs.nama_barang'])
                ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualans.id_produk')
                ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('detail_penjualans.id_produk', $request->produk)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->groupBy('detail_penjualans.id_produk')
                ->orderBy('detail_penjualans.created_at', 'desc');
        }

        return $query_laporan_laba_kotor;
    }

    // CARI LABA KOTOR PENJUALAN PRODUK PESANAN
    public function scopeCariLaporanLabaKotorProdukPesanan($query_laporan_laba_kotor, $request)
    {
        if ($request->produk == "") {
            $search                   = $request->search;
            $query_laporan_laba_kotor = DetailPenjualan::select(['detail_penjualans.id_produk', 'detail_penjualans.harga', DB::raw('SUM(detail_penjualans.subtotal) as subtotal'), 'barangs.kode_barang', 'barangs.nama_barang'])
                ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualans.id_produk')
                ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->where('barangs.hitung_stok', 1)
                ->where(function ($query) use ($search) {
                    $query->orwhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                        ->orwhere('barangs.nama_barang', 'LIKE', '%' . $search . '%');})
                ->groupBy('detail_penjualans.id_produk')
                ->orderBy('detail_penjualans.created_at', 'desc');
        } else {
            $search                   = $request->search;
            $query_laporan_laba_kotor = DetailPenjualan::select(['detail_penjualans.id_produk', 'detail_penjualans.harga', DB::raw('SUM(detail_penjualans.subtotal) as subtotal'), 'barangs.kode_barang', 'barangs.nama_barang'])
                ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualans.id_produk')
                ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('detail_penjualans.id_produk', $request->produk)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->where(function ($query) use ($search) {
                    $query->orwhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                        ->orwhere('barangs.nama_barang', 'LIKE', '%' . $search . '%');})
                ->groupBy('detail_penjualans.id_produk')
                ->orderBy('detail_penjualans.created_at', 'desc');
        }

        return $query_laporan_laba_kotor;
    }

    // SUBTOTAL LABA KOTOR /PRODUK PENJUALAN PESANAN
    public function scopeSubtotalLaporanLabaKotorProdukPesanan($query_sub_total_penjualan, $request)
    {
        if ($request->produk == "" || $request->produk == null || $request->produk == 0) {
            $query_sub_total_penjualan = DetailPenjualan::select(DB::raw('SUM(detail_penjualans.subtotal) as subtotal'))
                ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
                ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualans.id_produk')
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->where('barangs.hitung_stok', 1);
        } else {
            $query_sub_total_penjualan = DetailPenjualan::select(DB::raw('SUM(detail_penjualans.subtotal) as subtotal'))
                ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('detail_penjualans.id_produk', $request->produk)
                ->where('penjualans.id_warung', Auth::user()->id_warung);
        }

        return $query_sub_total_penjualan;
    }

    // QUERY LAPORAN PENJUALAN ONLINE
    public function queryLaporanPenjualanOnline($request)
    {
        $laporan_penjualan_online = DetailPenjualan::select([
            'detail_penjualans.id_produk',
            'detail_penjualans.harga',
            'penjualans.id_pelanggan',
            'detail_penjualans.potongan',
            'detail_penjualans.jumlah',
            'barangs.kode_barang',
            'users.name',
            'barangs.nama_barang',
            DB::raw('SUM(detail_penjualans.subtotal) as subtotal'),
            DB::raw('SUM(detail_penjualans.jumlah * detail_penjualans.harga) as total')])
            ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualans.id_produk')
            ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
            ->leftJoin('users', 'users.id', '=', 'penjualans.id_pelanggan')
            ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal));

        return $laporan_penjualan_online;
    }
    // LAPORAN PENJUALAN ONLINE PER PRODUK
    public function scopeLaporanPenjualanOnlineProduk($query_laporan_penjualan_online_produk, $request)
    {
        if ($request->produk != "" and $request->kasir != "") {
            $query_laporan_penjualan_online_produk = $this->queryLaporanPenjualanOnline($request)
                ->where('detail_penjualans.id_produk', $request->produk)
                ->where('detail_penjualans.created_by', $request->kasir)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->groupBy('detail_penjualans.id_produk')
                ->orderBy('detail_penjualans.created_at', 'desc');
        } elseif ($request->produk != "" and $request->kasir == "") {
            $query_laporan_penjualan_online_produk = $this->queryLaporanPenjualanOnline($request)
                ->where('detail_penjualans.id_produk', $request->produk)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->groupBy('detail_penjualans.id_produk')
                ->orderBy('detail_penjualans.created_at', 'desc');
        } elseif ($request->produk == "" and $request->kasir != "") {
            $query_laporan_penjualan_online_produk = $this->queryLaporanPenjualanOnline($request)
                ->where('detail_penjualans.created_by', $request->kasir)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->groupBy('detail_penjualans.id_produk')
                ->orderBy('detail_penjualans.created_at', 'desc');
        } else {
            $query_laporan_penjualan_online_produk = $this->queryLaporanPenjualanOnline($request)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->groupBy('detail_penjualans.id_produk')
                ->orderBy('detail_penjualans.created_at', 'desc');
        }

        return $query_laporan_penjualan_online_produk;
    }
    // LAPORAN PENJUALAN ONLINE PER PELANGGAN
    public function scopeLaporanPenjualanOnlinePelanggan($query_laporan_penjualan_online_pelanggan, $request)
    {
        if ($request->pelanggan != "" and $request->kasir != "") {
            $query_laporan_penjualan_online_pelanggan = $this->queryLaporanPenjualanOnline($request)
                ->where('penjualans.id_pelanggan', $request->pelanggan)
                ->where('detail_penjualans.created_by', $request->kasir)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->groupBy('penjualans.id_pelanggan', 'barangs.id')
                ->orderBy('detail_penjualans.created_at', 'desc');
        } elseif ($request->pelanggan != "" and $request->kasir == "") {
            $query_laporan_penjualan_online_pelanggan = $this->queryLaporanPenjualanOnline($request)
                ->where('penjualans.id_pelanggan', $request->pelanggan)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->groupBy('detail_penjualans.id_produk')
                ->orderBy('detail_penjualans.created_at', 'desc');
        } elseif ($request->pelanggan == "" and $request->kasir != "") {
            $query_laporan_penjualan_online_pelanggan = $this->queryLaporanPenjualanOnline($request)
                ->where('detail_penjualans.created_by', $request->kasir)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->groupBy('detail_penjualans.id_produk')
                ->orderBy('detail_penjualans.created_at', 'desc');
        } else {
            $query_laporan_penjualan_online_pelanggan = $this->queryLaporanPenjualanOnline($request)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->groupBy('penjualans.id_pelanggan', 'barangs.id')
                ->orderBy('detail_penjualans.created_at', 'desc');
        }

        return $query_laporan_penjualan_online_pelanggan;
    }
    // QUERY TOTAL PENJUALAN ONLINE
    public function queryTotalPenjualanOnline($request)
    {
        $total_penjualan_online_produk = DetailPenjualan::select(
            'detail_penjualans.id_produk',
            DB::raw('SUM(detail_penjualans.jumlah) as jumlah'),
            DB::raw('SUM(detail_penjualans.potongan) as potongan'),
            DB::raw('SUM(detail_penjualans.harga) as harga'),
            DB::raw('SUM(detail_penjualans.subtotal) as subtotal'),
            DB::raw('SUM(detail_penjualans.jumlah * detail_penjualans.harga) as total'))
            ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualans.id_produk')
            ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
            ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('penjualans.id_warung', Auth::user()->id_warung);
        return $total_penjualan_online_produk;
    }

    // TOTAL PENJUALAN ONLINE PER PRODUK
    public function scopeTotalLaporanPenjualanOnlineProduk($query_total_penjualan_online_produk, $request)
    {
        if ($request->produk != "" and $request->kasir != "") {
            $query_total_penjualan_online_produk = $this->queryTotalPenjualanOnline($request)
                ->where('detail_penjualans.created_by', $request->kasir)
                ->where('detail_penjualans.id_produk', $request->produk)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->orderBy('detail_penjualans.created_at', 'desc');
        } elseif ($request->produk != "" and $request->kasir == "") {
            $query_total_penjualan_online_produk = $this->queryTotalPenjualanOnline($request)
                ->where('detail_penjualans.id_produk', $request->produk)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->orderBy('detail_penjualans.created_at', 'desc');
        } elseif ($request->produk == "" and $request->kasir != "") {
            $query_total_penjualan_online_produk = $this->queryTotalPenjualanOnline($request)
                ->where('detail_penjualans.created_by', $request->kasir)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->orderBy('detail_penjualans.created_at', 'desc');
        } else {
            $query_total_penjualan_online_produk = $this->queryTotalPenjualanOnline($request)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->orderBy('detail_penjualans.created_at', 'desc');
        }

        return $query_total_penjualan_online_produk;

    }
    // TOTAL PENJUALAN ONLINE PER PELANGGAN
    public function scopeTotalLaporanPenjualanOnlinePelanggan($query_total_penjualan_online_pelanggan, $request)
    {
        if ($request->pelanggan != "" and $request->kasir != "") {
            $query_total_penjualan_online_pelanggan = $this->queryTotalPenjualanOnline($request)
                ->where('detail_penjualans.created_by', $request->kasir)
                ->where('penjualans.id_pelanggan', $request->pelanggan)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->orderBy('detail_penjualans.created_at', 'desc');
        } elseif ($request->pelanggan != "" and $request->kasir == "") {
            $query_total_penjualan_online_pelanggan = $this->queryTotalPenjualanOnline($request)
                ->where('penjualans.id_pelanggan', $request->pelanggan)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->orderBy('detail_penjualans.created_at', 'desc');
        } elseif ($request->pelanggan == "" and $request->kasir != "") {
            $query_total_penjualan_online_pelanggan = $this->queryTotalPenjualanOnline($request)
                ->where('detail_penjualans.created_by', $request->kasir)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->orderBy('detail_penjualans.created_at', 'desc');
        } else {
            $query_total_penjualan_online_pelanggan = $this->queryTotalPenjualanOnline($request)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->orderBy('detail_penjualans.created_at', 'desc');
        }

        return $query_total_penjualan_online_pelanggan;

    }
    // QUERY CARI LAPORAN PENJUALAN ONLINE PER PRODUK
    public function queryCariLaporanPenjualanOnline($request)
    {
        $cari_laporan_penjualan_produk = DetailPenjualan::select([
            'detail_penjualans.id_produk',
            'detail_penjualans.harga',
            'detail_penjualans.potongan',
            'detail_penjualans.jumlah',
            'barangs.kode_barang',
            'barangs.nama_barang',
            'users.name',
            DB::raw('SUM(detail_penjualans.subtotal) as subtotal'),
            DB::raw('SUM(detail_penjualans.jumlah * detail_penjualans.harga) as total')])
            ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualans.id_produk')
            ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
            ->leftJoin('users', 'users.id', '=', 'penjualans.id_pelanggan')
            ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('penjualans.id_warung', Auth::user()->id_warung)
            ->groupBy('detail_penjualans.id_produk')
            ->orderBy('detail_penjualans.created_at', 'desc');
        return $cari_laporan_penjualan_produk;
    }
    public function scopeCariLaporanPenjualanOnlineProduk($query_cari_laporan_penjualan_produk, $request)
    {
        $search = $request->search;
        if ($request->produk != "" and $request->kasir != "") {
            $query_cari_laporan_penjualan_produk = $this->queryCariLaporanPenjualanOnline($request)
                ->where('detail_penjualans.id_produk', $request->produk)
                ->where('detail_penjualans.created_by', $request->kasir)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->where(function ($query) use ($search) {
                    $query->orWhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                        ->orWhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.harga', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.potongan', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.jumlah', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.subtotal', 'LIKE', '%' . $search . '%');
                });

        } elseif ($request->produk != "" and $request->kasir == "") {
            $query_cari_laporan_penjualan_produk = $this->queryCariLaporanPenjualanOnline($request)
                ->where('detail_penjualans.id_produk', $request->produk)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->where(function ($query) use ($search) {
                    $query->orWhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                        ->orWhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.harga', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.potongan', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.jumlah', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.subtotal', 'LIKE', '%' . $search . '%');
                });
        } elseif ($request->produk == "" and $request->kasir != "") {
            $query_cari_laporan_penjualan_produk = $this->queryCariLaporanPenjualanOnline($request)
                ->where('detail_penjualans.created_by', $request->kasir)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->where(function ($query) use ($search) {
                    $query->orWhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                        ->orWhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.harga', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.potongan', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.jumlah', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.subtotal', 'LIKE', '%' . $search . '%');
                });
        } else {
            $query_cari_laporan_penjualan_produk = $this->queryCariLaporanPenjualanOnline($request)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->where(function ($query) use ($search) {
                    $query->orWhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                        ->orWhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.harga', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.potongan', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.jumlah', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.subtotal', 'LIKE', '%' . $search . '%');
                });
        }
        return $query_cari_laporan_penjualan_produk;
    }
    public function scopeCariLaporanPenjualanOnlinePelanggan($query_cari_laporan_penjualan_pelanggan, $request)
    {
        $search = $request->search;
        if ($request->pelanggan != "" and $request->kasir != "") {
            $query_cari_laporan_penjualan_pelanggan = $this->queryCariLaporanPenjualanOnline($request)
                ->where('penjualans.id_pelanggan', $request->pelanggan)
                ->where('detail_penjualans.created_by', $request->kasir)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->where(function ($query) use ($search) {
                    $query->orWhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                        ->orWhere('users.name', 'LIKE', '%' . $search . '%')
                        ->orWhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.harga', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.jumlah', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.subtotal', 'LIKE', '%' . $search . '%');
                });

        } elseif ($request->pelanggan != "" and $request->kasir == "") {
            $query_cari_laporan_penjualan_pelanggan = $this->queryCariLaporanPenjualanOnline($request)
                ->where('penjualans.id_pelanggan', $request->pelanggan)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->where(function ($query) use ($search) {
                    $query->orWhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                        ->orWhere('users.name', 'LIKE', '%' . $search . '%')
                        ->orWhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.harga', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.jumlah', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.subtotal', 'LIKE', '%' . $search . '%');
                });

        } elseif ($request->pelanggan == "" and $request->kasir != "") {
            $query_cari_laporan_penjualan_pelanggan = $this->queryCariLaporanPenjualanOnline($request)
                ->where('detail_penjualans.created_by', $request->kasir)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->where(function ($query) use ($search) {
                    $query->orWhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                        ->orWhere('users.name', 'LIKE', '%' . $search . '%')
                        ->orWhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.harga', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.jumlah', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.subtotal', 'LIKE', '%' . $search . '%');
                });

        } else {
            $query_cari_laporan_penjualan_pelanggan = $this->queryCariLaporanPenjualanOnline($request)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->where(function ($query) use ($search) {
                    $query->orWhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                        ->orWhere('users.name', 'LIKE', '%' . $search . '%')
                        ->orWhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.harga', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.jumlah', 'LIKE', '%' . $search . '%')
                        ->orWhere('detail_penjualans.subtotal', 'LIKE', '%' . $search . '%');
                });
        }
        return $query_cari_laporan_penjualan_pelanggan;
    }

    // TOTAL PEJUALAN POS PER PRODUK
    public function scopePenjualanTerbaik($detail_penjualan, $request)
    {
        $detail_penjualan = DetailPenjualan::select([DB::raw('SUM(detail_penjualans.jumlah) as jumlah_produk'), 'barangs.nama_barang as nama_barang', 'detail_penjualans.id_produk as id_produk'])
            ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualans.id_produk');
        return $detail_penjualan;
    }

    // TOTAL PEJUALAN BULAN INI
    public function scopeTotalPenjualan($query_total_penjualan, $dari_tanggal, $sampai_tanggal)
    {
        $query_total_penjualan = DetailPenjualan::select(DB::raw('SUM(detail_penjualans.subtotal) as total'))
            ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
            ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($dari_tanggal))
            ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($sampai_tanggal))
            ->where('penjualans.id_warung', Auth::user()->id_warung)
            ->orderBy('detail_penjualans.created_at', 'desc');

        return $query_total_penjualan;
    }

    // TOTAL LABA KOTOR PENJUALAN POS BULAN INI
    public function scopeLabaKotorPenjualan($query_laba_kotor_penjualan, $dari_tanggal, $sampai_tanggal)
    {
        $query_laba_kotor_penjualan = DetailPenjualan::select(DB::raw('SUM(detail_penjualans.subtotal) as subtotal'))
            ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
            ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($dari_tanggal))
            ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($sampai_tanggal))
            ->where('penjualans.id_warung', Auth::user()->id_warung);

        return $query_laba_kotor_penjualan;
    }

}
