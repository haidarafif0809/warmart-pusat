<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;

class DetailPenjualanPos extends Model
{
    use AuditableTrait;

    protected $fillable   = ['id_penjualan_pos', 'no_faktur', 'satuan_id', 'id_produk', 'jumlah_produk', 'harga_produk', 'subtotal', 'tax', 'potongan', 'warung_id', 'ppn', 'created_at', 'updated_at', 'satuan_dasar','created_by'];
    protected $primaryKey = 'id_detail_penjualan_pos';

    // relasi ke produk
    public function produk()
    {
        return $this->hasOne('App\Barang', 'id', 'id_produk');
    }

    // relasi ke satuan
    public function satuan()
    {
        return $this->hasOne('App\Satuan', 'id', 'satuan_id');
    }

    public function getNamaProdukAttribute()
    {
        return title_case($this->produk->nama_barang);
    }

    public function scopeHargaProduk($harga, $no_faktur, $id_produk)
    {

        $harga->select('harga_produk')
        ->where('id_penjualan_pos', $no_faktur)
        ->where('id_produk', $id_produk)
        ->where('warung_id', Auth::user()->id_warung);

        return $harga;

    }

    public function stok_produk($id_produk)
    {

        $stok_produk = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as jumlah_produk')])->where('id_produk', $id_produk)
        ->where('warung_id', Auth::user()->id_warung)->first();

        return $sisa_stok_keluar = $stok_produk->jumlah_produk;

    }

    public function subtotalTbs($user_warung, $id)
    {
        $detail_penjualan = DetailPenjualanPos::select([DB::raw('SUM(subtotal) as subtotal')])->where('warung_id', $user_warung)->where('id_penjualan_pos', $id)->first();
        if ($detail_penjualan->subtotal == null || $detail_penjualan->subtotal == '') {
            return 0;
        } else {
            return $detail_penjualan->subtotal;
        }
    }

    public function tanggalSql($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "Y-m-d");
        return $date_format;
    }

    //pencarian
    public function scopePencarian($query, $user_warung, $id, $request)
    {

        $query->select('detail_penjualan_pos.id_detail_penjualan_pos AS id_detail_penjualan_pos', 'detail_penjualan_pos.jumlah_produk AS jumlah_produk', 'barangs.nama_barang AS nama_barang', 'barangs.kode_barang AS kode_barang', 'detail_penjualan_pos.id_produk AS id_produk', 'detail_penjualan_pos.potongan AS potongan', 'detail_penjualan_pos.subtotal AS subtotal', 'detail_penjualan_pos.harga_produk AS harga_produk', 'satuans.nama_satuan AS satuan')
        ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualan_pos.id_produk')
        ->leftJoin('satuans', 'satuans.id', '=', 'detail_penjualan_pos.satuan_id')
        ->where('warung_id', $user_warung)->where('detail_penjualan_pos.id_penjualan_pos', $id)
        ->where(function ($query) use ($request) {
            $query->orWhere('barangs.kode_barang', 'LIKE', $request->search . '%')
            ->orWhere('barangs.nama_barang', 'LIKE', $request->search . '%')
            ->orWhere('detail_penjualan_pos.id_penjualan_pos', 'LIKE', $request->search . '%');

        })->orderBy('detail_penjualan_pos.id_detail_penjualan_pos', 'desc');

        return $query;

    }

    // SUBTOTAL LABA KOTOR PENJUALAN POS
    public function scopeSubtotalLaporanLabaKotor($query_sub_total_penjualan, $request)
    {
        if ($request->pelanggan == "" || $request->pelanggan == null || $request->pelanggan == 0) {
            $query_sub_total_penjualan = DetailPenjualanPos::select(DB::raw('SUM(detail_penjualan_pos.subtotal) as subtotal'))
            ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('detail_penjualan_pos.warung_id', Auth::user()->id_warung);
        } else {
            $query_sub_total_penjualan = DetailPenjualanPos::select(DB::raw('SUM(detail_penjualan_pos.subtotal) as subtotal'))
            ->leftJoin('penjualan_pos', 'penjualan_pos.id', '=', 'detail_penjualan_pos.id_penjualan_pos')
            ->where(DB::raw('DATE(.detail_penjualan_pos.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(.detail_penjualan_pos.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('penjualan_pos.pelanggan_id', $request->pelanggan)
            ->where('detail_penjualan_pos.warung_id', Auth::user()->id_warung);
        }

        return $query_sub_total_penjualan;
    }

    // LAP. LABA KOTOR PENJUALAN PRODUK POS
    public function scopeLaporanLabaKotorProdukPos($query_laporan_laba_kotor, $request)
    {
        if ($request->produk == "" || $request->produk == null || $request->produk == 0) {
            $query_laporan_laba_kotor = DetailPenjualanPos::select(['detail_penjualan_pos.id_produk', 'detail_penjualan_pos.harga_produk', DB::raw('SUM(detail_penjualan_pos.subtotal) as subtotal'), 'barangs.kode_barang', 'barangs.nama_barang'])
            ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualan_pos.id_produk')
            ->leftJoin('penjualan_pos', 'penjualan_pos.id', '=', 'detail_penjualan_pos.id_penjualan_pos')
            ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
            ->groupBy('detail_penjualan_pos.id_produk')
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        } else {
            $query_laporan_laba_kotor = DetailPenjualanPos::select(['detail_penjualan_pos.id_produk', 'detail_penjualan_pos.harga_produk', DB::raw('SUM(detail_penjualan_pos.subtotal) as subtotal'), 'barangs.kode_barang', 'barangs.nama_barang'])
            ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualan_pos.id_produk')
            ->leftJoin('penjualan_pos', 'penjualan_pos.id', '=', 'detail_penjualan_pos.id_penjualan_pos')
            ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('detail_penjualan_pos.id_produk', $request->produk)
            ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
            ->groupBy('detail_penjualan_pos.id_produk')
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        }

        return $query_laporan_laba_kotor;
    }

    // CARI LAP. LABA KOTOR /PRODUK PENJUALAN POS
    public function scopeCariLaporanLabaKotorProdukPos($query_laporan_laba_kotor, $request)
    {
        if ($request->produk == "") {
            $search                   = $request->search;
            $query_laporan_laba_kotor = DetailPenjualanPos::select(['detail_penjualan_pos.id_produk', 'detail_penjualan_pos.harga_produk', DB::raw('SUM(detail_penjualan_pos.subtotal) as subtotal'), 'barangs.kode_barang', 'barangs.nama_barang'])
            ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualan_pos.id_produk')
            ->leftJoin('penjualan_pos', 'penjualan_pos.id', '=', 'detail_penjualan_pos.id_penjualan_pos')
            ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
                $query->orwhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                ->orwhere('barangs.nama_barang', 'LIKE', '%' . $search . '%');})
            ->groupBy('detail_penjualan_pos.id_produk')
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        } else {
            $search                   = $request->search;
            $query_laporan_laba_kotor = DetailPenjualanPos::select(['detail_penjualan_pos.id_produk', 'detail_penjualan_pos.harga_produk', DB::raw('SUM(detail_penjualan_pos.subtotal) as subtotal'), 'barangs.kode_barang', 'barangs.nama_barang'])
            ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualan_pos.id_produk')
            ->leftJoin('penjualan_pos', 'penjualan_pos.id', '=', 'detail_penjualan_pos.id_penjualan_pos')
            ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('detail_penjualan_pos.id_produk', $request->produk)
            ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
                $query->orwhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                ->orwhere('barangs.nama_barang', 'LIKE', '%' . $search . '%');
            })
            ->groupBy('detail_penjualan_pos.id_produk')
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        }

        return $query_laporan_laba_kotor;
    }

    // SUBTOTAL LABA KOTOR /PRODUK PENJUALAN POS
    public function scopeSubtotalLaporanLabaKotorProduk($query_sub_total_penjualan, $request)
    {
        if ($request->produk == "" || $request->produk == null || $request->produk == 0) {
            $query_sub_total_penjualan = DetailPenjualanPos::select(DB::raw('SUM(detail_penjualan_pos.subtotal) as subtotal'))
            ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualan_pos.id_produk')
            ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('detail_penjualan_pos.warung_id', Auth::user()->id_warung);
        } else {
            $query_sub_total_penjualan = DetailPenjualanPos::select(DB::raw('SUM(subtotal) as subtotal'))
            ->leftJoin('penjualan_pos', 'penjualan_pos.no_faktur', '=', 'detail_penjualan_pos.no_faktur')
            ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('detail_penjualan_pos.id_produk', $request->produk)
            ->where('detail_penjualan_pos.warung_id', Auth::user()->id_warung);
        }
        return $query_sub_total_penjualan;
    }

    // LAPORAN PENJUALAN POS / PRODUK
    public function queryLaporanPenjualanPos($request)
    {
        $laporan_penjualan_pos = DetailPenjualanPos::select([
            'detail_penjualan_pos.no_faktur',
            'detail_penjualan_pos.satuan_id',
            'penjualan_pos.pelanggan_id',
            'barangs.kode_barang',
            'detail_penjualan_pos.id_produk',
            'barangs.nama_barang',
            'satuans.nama_satuan',
            'users.name',
            DB::raw('SUM(detail_penjualan_pos.jumlah_produk) as jumlah_produk'),
            DB::raw('sum(detail_penjualan_pos.harga_produk) as harga_produk'),
            DB::raw('sum(detail_penjualan_pos.subtotal) as total'),
            DB::raw('sum(detail_penjualan_pos.tax) as tax'),
            DB::raw('sum(detail_penjualan_pos.potongan) as potongan'),
            DB::raw('sum(detail_penjualan_pos.jumlah_produk * detail_penjualan_pos.harga_produk) as subtotal'),
            ])
        ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualan_pos.id_produk')
        ->leftJoin('satuans', 'satuans.id', '=', 'detail_penjualan_pos.satuan_id')
        ->leftJoin('penjualan_pos', function ($join) {
            $join->on('penjualan_pos.id', '=', 'detail_penjualan_pos.id_penjualan_pos');
            $join->on('penjualan_pos.no_faktur', '=', 'detail_penjualan_pos.no_faktur');
        })
        ->leftJoin('users', 'users.id', '=', 'penjualan_pos.pelanggan_id')
        ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
        ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal));
        return $laporan_penjualan_pos;
    }

    // LAPORAN PENJUALAN POS / PRODUK
    public function scopeLaporanPenjualanPosProduk($query_laporan_penjualan_pos_produk, $request)
    {
        if ($request->produk != "" and $request->kasir != 0) {
            $query_laporan_penjualan_pos_produk = $this->queryLaporanPenjualanPos($request)
            ->where('detail_penjualan_pos.id_produk', $request->produk)
            ->where('detail_penjualan_pos.created_by', $request->kasir)
            ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
            ->groupBy('detail_penjualan_pos.id_produk')
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        } elseif ($request->produk != "" and $request->kasir == 0) {
            $query_laporan_penjualan_pos_produk = $this->queryLaporanPenjualanPos($request)
            ->where('detail_penjualan_pos.id_produk', $request->produk)
            ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
            ->groupBy('detail_penjualan_pos.id_produk')
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        } elseif ($request->produk == "" and $request->kasir != 0) {
            $query_laporan_penjualan_pos_produk = $this->queryLaporanPenjualanPos($request)
            ->where('detail_penjualan_pos.created_by', $request->kasir)
            ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
            ->groupBy('detail_penjualan_pos.id_produk')
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        } else {
            $query_laporan_penjualan_pos_produk = $this->queryLaporanPenjualanPos($request)
            ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
            ->groupBy('detail_penjualan_pos.id_produk')
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        }

        return $query_laporan_penjualan_pos_produk;

    }
// LAPORAN PENJUALAN POS / PELANGGAN
    public function scopeLaporanPenjualanPosPelanggan($query_laporan_penjualan_pos_pelanggan, $request)
    {
        if ($request->pelanggan != "" and $request->kasir != 0) {
            $query_laporan_penjualan_pos_pelanggan = $this->queryLaporanPenjualanPos($request)
            ->where('penjualan_pos.pelanggan_id', $request->pelanggan)
            ->where('detail_penjualan_pos.created_by', $request->kasir)
            ->where('detail_penjualan_pos.warung_id', Auth::user()->id_warung)
            ->groupBy('penjualan_pos.pelanggan_id', 'barangs.id')
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
            # code...
        } elseif ($request->pelanggan != "" and $request->kasir == 0) {
            $query_laporan_penjualan_pos_pelanggan = $this->queryLaporanPenjualanPos($request)
            ->where('penjualan_pos.pelanggan_id', $request->pelanggan)
            ->where('detail_penjualan_pos.warung_id', Auth::user()->id_warung)
            ->groupBy('detail_penjualan_pos.id_produk')
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        } elseif ($request->pelanggan == "" and $request->kasir != 0) {
            $query_laporan_penjualan_pos_pelanggan = $this->queryLaporanPenjualanPos($request)
            ->where('detail_penjualan_pos.created_by', $request->kasir)
            ->where('detail_penjualan_pos.warung_id', Auth::user()->id_warung)
            ->groupBy('detail_penjualan_pos.id_produk')
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        } else {
            $query_laporan_penjualan_pos_pelanggan = $this->queryLaporanPenjualanPos($request)
            ->where('detail_penjualan_pos.warung_id', Auth::user()->id_warung)
            ->groupBy('penjualan_pos.pelanggan_id', 'barangs.id')
            ->orderBy('detail_penjualan_pos.created_at', 'desc');

        }

        return $query_laporan_penjualan_pos_pelanggan;

    }

    // TOTAL PEJUALAN POS PER PRODUK
    public function queryTotalPenjualanPos($request)
    {
        $total_penjualan_pos = DetailPenjualanPos::select(
            DB::raw('SUM(detail_penjualan_pos.jumlah_produk) as jumlah_produk'),
            DB::raw('SUM(detail_penjualan_pos.subtotal) as total'),
            DB::raw('SUM(detail_penjualan_pos.potongan) as potongan'),
            DB::raw('SUM(detail_penjualan_pos.tax) as pajak'),
            DB::raw('SUM(detail_penjualan_pos.jumlah_produk * detail_penjualan_pos.harga_produk) as subtotal'))
        ->leftJoin('penjualan_pos', 'penjualan_pos.id', '=', 'detail_penjualan_pos.id_penjualan_pos')
        ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
        ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal));
        return $total_penjualan_pos;
    }

    // TOTAL PEJUALAN POS PER PRODUK
    public function scopeTotalLaporanPenjualanPosProduk($query_total_penjualan_pos_produk, $request)
    {
        if ($request->produk != "" and $request->kasir != 0) {
            $query_total_penjualan_pos_produk = $this->queryTotalPenjualanPos($request)
            ->where('detail_penjualan_pos.id_produk', $request->produk)
            ->where('detail_penjualan_pos.created_by', $request->kasir)
            ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        } else if ($request->produk != "" and $request->kasir == 0) {
            $query_total_penjualan_pos_produk = $this->queryTotalPenjualanPos($request)
            ->where('detail_penjualan_pos.id_produk', $request->produk)
            ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        } else if ($request->produk == "" and $request->kasir != 0) {
            $query_total_penjualan_pos_produk = $this->queryTotalPenjualanPos($request)
            ->where('detail_penjualan_pos.created_by', $request->kasir)
            ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        } else {
            $query_total_penjualan_pos_produk = $this->queryTotalPenjualanPos($request)
            ->where('detail_penjualan_pos.warung_id', Auth::user()->id_warung)
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        }

        return $query_total_penjualan_pos_produk;
    }
    // TOTAL PEJUALAN POS PER PELANGGAN
    public function scopeTotalLaporanPenjualanPosPelanggan($query_total_penjualan_pos_pelanggan, $request)
    {
        if ($request->pelanggan != "" and $request->kasir != 0) {
            $query_total_penjualan_pos_pelanggan = $this->queryTotalPenjualanPos($request)
            ->where('penjualan_pos.pelanggan_id', $request->pelanggan)
            ->where('detail_penjualan_pos.created_by', $request->kasir)
            ->where('detail_penjualan_pos.warung_id', Auth::user()->id_warung)
            // ->groupBy('detail_penjualan_pos.id_produk', 'penjualan_pos.pelanggan_id')
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        } else if ($request->pelanggan != "" and $request->kasir == 0) {
            $query_total_penjualan_pos_pelanggan = $this->queryTotalPenjualanPos($request)
            ->where('penjualan_pos.pelanggan_id', $request->pelanggan)
            ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        } else if ($request->pelanggan == "" and $request->kasir != 0) {
            $query_total_penjualan_pos_pelanggan = $this->queryTotalPenjualanPos($request)
            ->where('detail_penjualan_pos.created_by', $request->kasir)
            ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        } else {
            $query_total_penjualan_pos_pelanggan = $this->queryTotalPenjualanPos($request)
            ->where('detail_penjualan_pos.warung_id', Auth::user()->id_warung)
            // ->groupBy('detail_penjualan_pos.id_produk', 'penjualan_pos.pelanggan_id')
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        }

        return $query_total_penjualan_pos_pelanggan;
    }
    // CARI LAPORAN PENJUALAN PER PERPRODUK
    public function scopeCariLaporanPenjualanPosProduk($query_cari_laporan_pos_produk, $request)
    {
        $search = $request->search;
        if ($request->produk != "" and $request->kasir != 0) {
            $query_cari_laporan_pos_produk = $this->queryLaporanPenjualanPos($request)
            ->where('detail_penjualan_pos.id_produk', $request->produk)
            ->where('detail_penjualan_pos.created_by', $request->kasir)
            ->where('detail_penjualan_pos.warung_id', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
                $query->orWhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
                ->orWhere('barangs.kode_barang', 'LIKE', '%' . $search . '%');
            })
            ->groupBy('detail_penjualan_pos.id_produk')
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        } elseif ($request->produk != "" and $request->kasir == 0) {
            $query_cari_laporan_pos_produk = $this->queryLaporanPenjualanPos($request)
            ->where('detail_penjualan_pos.id_produk', $request->produk)
            ->where('detail_penjualan_pos.warung_id', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
                $query->orWhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
                ->orWhere('barangs.kode_barang', 'LIKE', '%' . $search . '%');
            })
            ->groupBy('detail_penjualan_pos.id_produk')
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        } elseif ($request->produk == "" and $request->kasir != 0) {
            $query_cari_laporan_pos_produk = $this->queryLaporanPenjualanPos($request)
            ->where('detail_penjualan_pos.created_by', $request->kasir)
            ->where('detail_penjualan_pos.warung_id', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
                $query->orWhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
                ->orWhere('barangs.kode_barang', 'LIKE', '%' . $search . '%');
            })
            ->groupBy('detail_penjualan_pos.id_produk')
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        } else {
            $query_cari_laporan_pos_produk = $this->queryLaporanPenjualanPos($request)
            ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
                $query->orWhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
                ->orWhere('barangs.kode_barang', 'LIKE', '%' . $search . '%');
            })
            ->groupBy('detail_penjualan_pos.id_produk')
            ->orderBy('detail_penjualan_pos.created_at', 'desc');
        }
        return $query_cari_laporan_pos_produk;
    }

    // CARI LAPORAN PENJUALAN PER PELANGGAN
    public function scopeCariLaporanPenjualanPosPelanggan($query_cari_laporan_pos_pelanggan, $request)
    {
        $search = $request->search;
        if ($request->pelanggan != "" and $request->kasir != 0) {
            $query_cari_laporan_pos_pelanggan = $this->queryLaporanPenjualanPos($request)
            ->where('penjualan_pos.pelanggan_id', $request->pelanggan)
            ->where('detail_penjualan_pos.created_by', $request->kasir)
            ->where('detail_penjualan_pos.warung_id', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
                $query->orWhere('users.name', 'LIKE', '%' . $search . '%')
                ->orWhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
                ->orWhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                ->orWhere('detail_penjualan_pos.jumlah_produk', 'LIKE', '%' . $search . '%');
            });
        } elseif ($request->pelanggan != "" and $request->kasir == 0) {
            $query_cari_laporan_pos_pelanggan = $this->queryLaporanPenjualanPos($request)
            ->where('penjualan_pos.pelanggan_id', $request->pelanggan)
            ->where('detail_penjualan_pos.warung_id', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
                $query->orWhere('users.name', 'LIKE', '%' . $search . '%')
                ->orWhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
                ->orWhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                ->orWhere('detail_penjualan_pos.jumlah_produk', 'LIKE', '%' . $search . '%');
            });
        } elseif ($request->pelanggan == "" and $request->kasir != 0) {
            $query_cari_laporan_pos_pelanggan = $this->queryLaporanPenjualanPos($request)
            ->where('detail_penjualan_pos.created_by', $request->kasir)
            ->where('detail_penjualan_pos.warung_id', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
                $query->orWhere('users.name', 'LIKE', '%' . $search . '%')
                ->orWhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
                ->orWhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                ->orWhere('detail_penjualan_pos.jumlah_produk', 'LIKE', '%' . $search . '%');
            });
        } else {
            $query_cari_laporan_pos_pelanggan = $this->queryLaporanPenjualanPos($request)
            ->where('detail_penjualan_pos.warung_id', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
                $query->orWhere('users.name', 'LIKE', '%' . $search . '%')
                ->orWhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
                ->orWhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                ->orWhere('detail_penjualan_pos.jumlah_produk', 'LIKE', '%' . $search . '%');
            });
        }
        return $query_cari_laporan_pos_pelanggan;
    }

    // Data penjualan terbaik per item 
    public function scopePenjualanTerbaik($detail_penjualan_pos, $request)
    {
        $detail_penjualan_pos = DetailPenjualanPos::select([DB::raw('SUM(detail_penjualan_pos.jumlah_produk) as jumlah_produk'), 'barangs.nama_barang as nama_barang', 'barangs.kode_barang as kode_barang','barangs.kode_barcode as kode_barcode','satuans.nama_satuan as satuan', 'detail_penjualan_pos.id_produk as id_produk'])
        ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualan_pos.id_produk')
        ->leftJoin('satuans', 'satuans.id', '=', 'detail_penjualan_pos.satuan_id')
        ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
        ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal));
        return $detail_penjualan_pos;
    }

        //Data pencarian penjualan terbaik per item 
    public function scopeCariPenjualanTerbaik($detail_penjualan_pos, $request)
    {
        $search = $request->search;
        $detail_penjualan_pos = DetailPenjualanPos::select([DB::raw('SUM(detail_penjualan_pos.jumlah_produk) as jumlah_produk'), 'barangs.nama_barang as nama_barang', 'barangs.kode_barang as kode_barang', 'detail_penjualan_pos.id_produk as id_produk'])
        ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualan_pos.id_produk')
        ->leftJoin('satuans', 'satuans.id', '=', 'detail_penjualan_pos.satuan_id')
        ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
        ->where(DB::raw('DATE(detail_penjualan_pos.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
        ->where(function ($query) use ($search) {
            $query->orWhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
            ->orWhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
            ->orWhere('barangs.kode_barcode', 'LIKE', '%' . $search . '%');
        });
        return $detail_penjualan_pos;
    }

    // TOTAL PEJUALAN POS BULAN INI
    public function scopeTotalPenjualan($query_total_penjualan, $dari_tanggal, $sampai_tanggal)
    {
        $query_total_penjualan = DetailPenjualanPos::select(DB::raw('SUM(subtotal) as total'))
        ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($dari_tanggal))
        ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($sampai_tanggal))
        ->where('warung_id', Auth::user()->id_warung)
        ->orderBy('created_at', 'desc');

        return $query_total_penjualan;
    }

    // TOTAL LABA KOTOR PENJUALAN POS BULAN INI
    public function scopeLabaKotorPenjualan($query_laba_kotor_penjualan, $dari_tanggal, $sampai_tanggal)
    {
        $query_laba_kotor_penjualan = DetailPenjualanPos::select(DB::raw('SUM(subtotal) as subtotal'))
        ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($dari_tanggal))
        ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($sampai_tanggal))
        ->where('warung_id', Auth::user()->id_warung);

        return $query_laba_kotor_penjualan;
    }

    // Download Faktur Penjualan
    public function scopeDownloadPenjualan($query_download, $id_penjualan){
        $query_download->select('detail_penjualan_pos.no_faktur', 'barangs.kode_barang', 'detail_penjualan_pos.jumlah_produk', 'detail_penjualan_pos.harga_produk', 'detail_penjualan_pos.subtotal', 'detail_penjualan_pos.tax', 'detail_penjualan_pos.potongan', 'detail_penjualan_pos.warung_id', 'detail_penjualan_pos.created_by', 'detail_penjualan_pos.updated_by', 'detail_penjualan_pos.created_at', 'detail_penjualan_pos.updated_at', 'satuans.nama_satuan')
        ->leftJoin('barangs', 'detail_penjualan_pos.id_produk', '=', 'barangs.id')
        ->leftJoin('satuans', 'detail_penjualan_pos.satuan_id', '=', 'satuans.id')
        ->where('detail_penjualan_pos.id_penjualan_pos', $id_penjualan)->where('detail_penjualan_pos.warung_id', Auth::user()->id_warung);

        return $query_download;
    }

}
