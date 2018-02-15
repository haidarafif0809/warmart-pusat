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
    public function user_edit()
    {
        return $this->hasOne('App\User', 'id', 'updated_by');
    }

    // relasi ke kas
    public function user_buat()
    {
        return $this->hasOne('App\User', 'id', 'created_by');
    }
    // relasi ke kas
    public function kas()
    {
        return $this->hasOne('App\Kas', 'id', 'id_kas');
    }

    public function getWaktuAttribute()
    {
        $tanggal       = date($this->created_at);
        $date          = date_create($tanggal);
        $date_terbalik = date_format($date, "d/m/Y H:i:s");
        return $date_terbalik;
    }

    public function getWaktuEditAttribute()
    {
        $tanggal       = date($this->updated_at);
        $date          = date_create($tanggal);
        $date_terbalik = date_format($date, "d/m/Y H:i:s");
        return $date_terbalik;
    }

    public function getJatuhTempoAttribute()
    {
        $jatuh_tempo = $this->tanggal_jt_tempo;
        if ($jatuh_tempo == '') {
            return "-";
        } else {
            $tanggal       = date($this->tanggal_jt_tempo);
            $date          = date_create($tanggal);
            $date_terbalik = date_format($date, "d/m/Y");
            return $date_terbalik;
        }
    }

    public function getTotalJualAttribute()
    {
        return number_format($this->total, 2, ',', '.');
    }
    public function getPotonganJualAttribute()
    {
        return number_format($this->potongan, 2, ',', '.');
    }
    public function getTunaiJualAttribute()
    {
        return number_format($this->tunai, 2, ',', '.');
    }
    public function getKreditJualAttribute()
    {
        return number_format($this->kredit, 2, ',', '.');
    }
    public function getKembalianJualAttribute()
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

    // query pencarian
    public function scopePencarian($query, $user_warung, $request)
    {

        $query->select('penjualan_pos.pelanggan_id AS pelanggan_id', 'penjualan_pos.id AS id', 'penjualan_pos.status_penjualan AS status_penjualan', 'penjualan_pos.total AS total', 'users.name AS nama_pelanggan', DB::raw('DATE_FORMAT(penjualan_pos.created_at, "%d/%m/%Y %H:%i:%s") as waktu_jual'), 'kas.nama_kas AS nama_kas', 'penjualan_pos.potongan AS potongan', 'penjualan_pos.tunai AS tunai', 'penjualan_pos.kembalian AS kembalian', 'penjualan_pos.nilai_kredit AS nilai_kredit', DB::raw('DATE_FORMAT(penjualan_pos.tanggal_jt_tempo, "%d/%m/%Y") as jatuh_tempoo'), DB::raw('DATE_FORMAT(penjualan_pos.updated_at, "%d/%m/%Y %H:%i:%s") as waktu_edit'), 'userbuat.name AS user_buat', 'useredit.name AS user_edit')
            ->leftJoin('users', 'users.id', '=', 'penjualan_pos.pelanggan_id')
            ->leftJoin('users AS userbuat', 'userbuat.id', '=', 'penjualan_pos.created_by')
            ->leftJoin('users AS useredit', 'useredit.id', '=', 'penjualan_pos.updated_by')
            ->leftJoin('kas', 'kas.id', '=', 'penjualan_pos.id_kas')
            ->where('penjualan_pos.warung_id', $user_warung)
            ->where(function ($query) use ($request) {

                $query->orWhere('penjualan_pos.id', 'LIKE', $request->search . '%')
                    ->orWhere('penjualan_pos.status_penjualan', 'LIKE', $request->search . '%')
                    ->orWhere('penjualan_pos.total', 'LIKE', $request->search . '%')
                    ->orWhere(DB::raw('DATE_FORMAT(penjualan_pos.created_at, "%d/%m/%Y %H:%i:%s")'), 'LIKE', $request->search . '%')
                    ->orWhere('users.name', 'LIKE', $request->search . '%');

            })->orderBy('penjualan_pos.id', 'desc');
        return $query;
    }

    public function scopeQueryCetak($query, $id)
    {
        $query->select('w.name AS nama_warung', 'w.alamat AS alamat_warung', 'p.name AS pelanggan', 'u.name AS kasir', 'penjualan_pos.potongan AS potongan', 'penjualan_pos.total AS total', 'penjualan_pos.tunai AS tunai', 'penjualan_pos.kembalian AS kembalian', DB::raw('DATE_FORMAT(penjualan_pos.created_at, "%d/%m/%Y %H:%i:%s") as waktu_jual'), 'w.no_telpon AS no_telp_warung', 'penjualan_pos.id AS id', 'p.alamat AS alamat_pelanggan', 'penjualan_pos.status_penjualan AS status_penjualan', 'kas.nama_kas AS nama_kas', 'penjualan_pos.pelanggan_id AS pelanggan_id')
            ->leftJoin('warungs AS w', 'penjualan_pos.warung_id', '=', 'w.id')
            ->leftJoin('users AS u', 'u.id', '=', 'penjualan_pos.created_by')
            ->leftJoin('users AS p', 'p.id', '=', 'penjualan_pos.pelanggan_id')
            ->leftJoin('kas', 'kas.id', '=', 'penjualan_pos.id_kas')
            ->where('penjualan_pos.id', $id);
        return $query;
    }

    // LAP. LABA KOTOR PENJUALAN POS
    public function scopeLaporanLabaKotorPos($query_laporan_laba_kotor, $request)
    {

        if ($request->pelanggan == "" || $request->pelanggan == null || $request->pelanggan == 0) {
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
        if ($request->pelanggan == "" || $request->pelanggan == null || $request->pelanggan == 0) {
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

    // DATA PENJUALAN PIUTANG
    public function scopeDataPenjualanPiutang($query_penjualan_piutang)
    {
        $query_penjualan_piutang = PenjualanPos::select(['penjualan_pos.id', 'penjualan_pos.no_faktur', 'penjualan_pos.pelanggan_id', 'penjualan_pos.kredit', 'penjualan_pos.tanggal_jt_tempo', 'users.name'])
            ->where('status_penjualan', 'Piutang')
            ->where('warung_id', Auth::user()->id_warung)
            ->leftJoin('users', 'users.id', '=', 'penjualan_pos.pelanggan_id');

        return $query_penjualan_piutang;
    }

    // DATA PENJUALAN PIUTANG
    public function scopeCountFaktur($query_count_faktur, $request)
    {
        $query_count_faktur = PenjualanPos::select([DB::raw('COUNT(no_faktur) as no_faktur')])
            ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('warung_id', Auth::user()->id_warung);

        return $query_count_faktur;
    }

    // DATA Jam Penjualan 
    public function scopeGrafikJamTransaksiPenjualan($query_grafik, $tanggal)
    {
        $query_grafik = PenjualanPos::select([DB::raw('COUNT(DATE_FORMAT(created_at, "%H")) as hitung')])
            ->where('warung_id', Auth::user()->id_warung)
            ->where(DB::raw('DATE(created_at)'), '=', $this->tanggalSql($tanggal));

        return $query_grafik;
    }

    // DATA PENJUALAN HARIAN
    public function scopeDataPenjualanHarian($query_laporan, $request)
    {
        $query_laporan = PenjualanPos::select([DB::raw('DATE(created_at) as tanggal'), DB::raw('SUM(total) as total')])
            ->where('warung_id', Auth::user()->id_warung)
            ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->groupBy(DB::raw('DATE(created_at)'));

        return $query_laporan;
    }

}
