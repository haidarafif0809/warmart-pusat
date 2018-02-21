<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;

class StokOpname extends Model
{
    use AuditableTrait;
    protected $fillable = ['no_faktur', 'produk_id', 'stok_sekarang', 'jumlah_fisik', 'selisih_fisik', 'harga', 'total', 'warung_id', 'keterangan'];

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

        //ambil bulan dan no_faktur dari tanggal stok_opname terakhir
        $stok_opname = StokOpname::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])->where('warung_id', $warung_id)->orderBy('id', 'DESC')->first();

        if ($stok_opname != null) {
            $pisah_nomor = explode("/", $stok_opname->no_faktur);
            $ambil_nomor = $pisah_nomor[0];
            $bulan_akhir = $stok_opname->bulan;
        } else {
            $ambil_nomor = 1;
            $bulan_akhir = 13;
        }

        /*jika bulan terakhir dari stok_opname tidak sama dengan bulan sekarang,
        maka nomor nya kembali mulai dari 1, jika tidak maka nomor terakhir ditambah dengan 1
         */
        if ($bulan_akhir != $bulan_sekarang) {
            $no_faktur = "1/SO/" . $data_bulan_terakhir . "/" . $tahun_terakhir;
        } else {
            $nomor     = 1 + $ambil_nomor;
            $no_faktur = $nomor . "/SO/" . $data_bulan_terakhir . "/" . $tahun_terakhir;
        }

        return $no_faktur;
        //PROSES MEMBUAT NO. FAKTUR STOK OPNAME
    }

    public function scopeDataStokOpname($query_stok_opname)
    {
        $query_stok_opname->select(['stok_opnames.no_faktur', 'stok_opnames.produk_id', 'stok_opnames.stok_sekarang', 'stok_opnames.jumlah_fisik', 'stok_opnames.selisih_fisik', 'stok_opnames.harga', 'stok_opnames.total', 'stok_opnames.keterangan', 'stok_opnames.created_by', 'stok_opnames.created_at', 'barangs.nama_barang', 'users.name as petugas'])
            ->leftJoin('barangs', 'barangs.id', '=', 'stok_opnames.produk_id')
            ->leftJoin('users', 'users.id', '=', 'stok_opnames.created_by')
            ->where('stok_opnames.warung_id', Auth::user()->id_warung)->orderBy('stok_opnames.id', 'desc');

        return $query_stok_opname;
    }

    public function scopeCariDataStokOpname($query_cari_stok_opname, $request)
    {
        $search = $request->search;
        $query_cari_stok_opname->select(['stok_opnames.no_faktur', 'stok_opnames.produk_id', 'stok_opnames.stok_sekarang', 'stok_opnames.jumlah_fisik', 'stok_opnames.selisih_fisik', 'stok_opnames.harga', 'stok_opnames.total', 'stok_opnames.keterangan', 'stok_opnames.created_by', 'stok_opnames.created_at', 'barangs.nama_barang'])
            ->leftJoin('barangs', 'barangs.id', '=', 'stok_opnames.produk_id')
            ->where('stok_opnames.warung_id', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
                $query->orwhere('stok_opnames.no_faktur', 'LIKE', '%' . $search . '%')
                    ->orWhere('barangs.nama_barang', 'LIKE', '%' . $search . '%');
            })->orderBy('stok_opnames.id', 'desc');

        return $query_cari_stok_opname;
    }
}
