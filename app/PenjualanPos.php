<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;

class PenjualanPos extends Model
{
    use AuditableTrait;
    protected $fillable = ['no_faktur', 'total', 'pelanggan_id', 'status_penjualan', 'potongan', 'tax', 'tunai', 'kembalian', 'kredit', 'nilai_kredit', 'id_kas', 'status_jual_awal', 'tanggal_jt_tempo', 'keterangan', 'ppn', 'warung_id'];

    // relasi ke suppier
    public function pelanggan()
    {
        return $this->hasOne('App\User', 'id', 'pelanggan_id');
    }
    // relasi ke kas
    public function kas()
    {
        return $this->hasOne('App\Kas', 'id', 'id_kas');
    }

    public function getPemisahTotalAttribute()
    {
        return number_format($this->total, 2, ',', '.');
    }
    public function getPemisahPotonganAttribute()
    {
        return number_format($this->potongan, 2, ',', '.');
    }
    public function getPemisahTunaiAttribute()
    {
        return number_format($this->tunai, 2, ',', '.');
    }
    public function getPemisahKreditAttribute()
    {
        return number_format($this->kredit, 2, ',', '.');
    }
    public function getPemisahKembalianAttribute()
    {
        return number_format($this->kembalian, 2, ',', '.');
    }

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

        //ambil bulan dan no_faktur dari tanggal penjualan terakhir
        $penjualan = PenjualanPos::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])->where('warung_id', $warung_id)->orderBy('id', 'DESC')->first();

        if ($penjualan != null) {
            $pisah_nomor = explode("/", $penjualan->no_faktur);
            $ambil_nomor = $pisah_nomor[0];
            $bulan_akhir = $penjualan->bulan;
        } else {
            $ambil_nomor = 1;
            $bulan_akhir = 13;
        }

        /*jika bulan terakhir dari penjualan tidak sama dengan bulan sekarang,
        maka nomor nya kembali mulai dari 1, jika tidak maka nomor terakhir ditambah dengan 1
         */
        if ($bulan_akhir != $bulan_sekarang) {
            $no_faktur = "1/JL/" . $data_bulan_terakhir . "/" . $tahun_terakhir;
        } else {
            $nomor     = 1 + $ambil_nomor;
            $no_faktur = $nomor . "/JL/" . $data_bulan_terakhir . "/" . $tahun_terakhir;
        }

        return $no_faktur;
        //PROSES MEMBUAT NO. FAKTUR ITEM KELUAR
    }

    public function tanggalSql($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "Y-m-d");
        return $date_format;
    }

    // LAP. LABA KOTOR PENJUALAN POS
    public function scopeLaporanLabaKotorPos($query_laporan_laba_kotor, $request)
    {
        if ($request->pelanggan == "") {
            $query_laporan_laba_kotor = PenjualanPos::select(['penjualan_pos.id', 'penjualan_pos.pelanggan_id', 'penjualan_pos.no_faktur', 'penjualan_pos.total', 'penjualan_pos.potongan', 'penjualan_pos.created_at', 'users.name'])
                ->leftJoin('users', 'users.id', '=', 'penjualan_pos.pelanggan_id')
                ->where(DB::raw('DATE(penjualan_pos.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(penjualan_pos.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
                ->orderBy('penjualan_pos.id', 'desc');
        } else {
            $query_laporan_laba_kotor = PenjualanPos::select(['penjualan_pos.id', 'penjualan_pos.pelanggan_id', 'penjualan_pos.no_faktur', 'penjualan_pos.total', 'penjualan_pos.potongan', 'penjualan_pos.created_at', 'users.name'])
                ->leftJoin('users', 'users.id', '=', 'penjualan_pos.pelanggan_id')
                ->where(DB::raw('DATE(penjualan_pos.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(penjualan_pos.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('users.id', $request->pelanggan)
                ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
                ->orderBy('penjualan_pos.id', 'desc');
        }

        return $query_laporan_laba_kotor;
    }

    // CARI LAP. LABA KOTOR PENJUALAN POS
    public function scopeCariLaporanLabaKotorPos($query_laporan_laba_kotor, $request)
    {
        if ($request->pelanggan == "") {
            $search                   = $request->search;
            $query_laporan_laba_kotor = PenjualanPos::select(['penjualan_pos.id', 'penjualan_pos.pelanggan_id', 'penjualan_pos.no_faktur', 'penjualan_pos.total', 'penjualan_pos.potongan', 'penjualan_pos.created_at', 'users.name'])
                ->leftJoin('users', 'users.id', '=', 'penjualan_pos.pelanggan_id')
                ->where(DB::raw('DATE(penjualan_pos.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(penjualan_pos.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
                ->where(function ($query) use ($search) {
                    $query->orwhere('penjualan_pos.no_faktur', 'LIKE', '%' . $search . '%')
                        ->orwhere('users.name', 'LIKE', '%' . $search . '%');
                })->orderBy('penjualan_pos.id', 'desc');
        } else {
            $search                   = $request->search;
            $query_laporan_laba_kotor = PenjualanPos::select(['penjualan_pos.id', 'penjualan_pos.pelanggan_id', 'penjualan_pos.no_faktur', 'penjualan_pos.total', 'penjualan_pos.potongan', 'penjualan_pos.created_at', 'users.name'])
                ->leftJoin('users', 'users.id', '=', 'penjualan_pos.pelanggan_id')
                ->where(DB::raw('DATE(penjualan_pos.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(penjualan_pos.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('users.id', $request->pelanggan)
                ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
                ->where(function ($query) use ($search) {
                    $query->orwhere('penjualan_pos.no_faktur', 'LIKE', '%' . $search . '%')
                        ->orwhere('users.name', 'LIKE', '%' . $search . '%');
                })->orderBy('penjualan_pos.id', 'desc');
        }

        return $query_laporan_laba_kotor;
    }

    // DISKON LABA KOTOR PENJUALAN POS
    public function scopePotonganLaporanLabaKotor($query_sub_potongan, $request)
    {
        if ($request->pelanggan == "") {
            $query_sub_potongan = PenjualanPos::select(DB::raw('SUM(potongan) as potongan'))
                ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('warung_id', Auth::user()->id_warung);
        } else {
            $query_sub_potongan = PenjualanPos::select(DB::raw('SUM(potongan) as potongan'))
                ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('pelanggan_id', $request->pelanggan)
                ->where('warung_id', Auth::user()->id_warung);
        }

        return $query_sub_potongan;
    }

}
