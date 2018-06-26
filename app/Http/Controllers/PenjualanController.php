<?php

namespace App\Http\Controllers;

use App\Barang;
use App\DetailPenjualan;
use App\DetailPenjualanPos;
use App\EditTbsPenjualan;
use App\Kas;
use App\Penjualan;
use App\PenjualanPos;
use App\SettingAplikasi;
use App\SettingPenjualanPos;
use App\SettingPromo;
use App\TbsPenjualan;
use App\TransaksiKas;
use App\SatuanKonversi;
use App\TransaksiPiutang;
use App\User;
use App\Warung;
use App\Satuan;
use App\Antrian;
use Auth;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Laratrust;

class PenjualanController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function pilihPelanggan()
    {
        $pelanggan = User::where('tipe_user', 3)->where('id_warung', Auth::user()->id_warung)->get();
        $array     = array(['id' => '', 'nama_pelanggan' => 'SEMUA PELANGGAN', 'pelanggan' => 'SEMUA PELANGGAN'], ['id' => '0', 'nama_pelanggan' => 'Umum', 'pelanggan' => 'Umum']);
        foreach ($pelanggan as $pelanggans) {
            array_push($array, [
                'id'             => $pelanggans->id,
                'nama_pelanggan' => $pelanggans->name,
                'pelanggan'      => $pelanggans->name . " - " . $pelanggans->kode_pelanggan ." - " . $pelanggans->no_telp]);
        }

        return response()->json($array);
    }
    public function pilihKasir()
    {
        $kasir = User::where('tipe_user', 4)->where('id_warung', Auth::user()->id_warung)->get();
        $array = array(['id' => '', 'nama_kasir' => 'SEMUA KASIR']);
        foreach ($kasir as $kasirs) {
            array_push($array, [
                'id'         => $kasirs->id,
                'nama_kasir' => $kasirs->name]);
        }

        return response()->json($array);
    }

    public function pilih_kas()
    {
        $kas = Kas::select('id', 'nama_kas', 'default_kas')->where('warung_id', Auth::user()->id_warung)->where('status_kas', 1)->get();
        return response()->json($kas);
    }

    public function paginationData($penjualan, $array, $url)
    {
        $session_id = session()->getId();
    //DATA PAGINATION
        $respons['current_page']   = $penjualan->currentPage();
        $respons['data']           = $array;
        $respons['otoritas']      = $this->otoritasPenjualan();
        $respons['session_id']     = $session_id;
        $respons['first_page_url'] = url($url . '?page=' . $penjualan->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $penjualan->lastPage();
        $respons['last_page_url']  = url($url . '?page=' . $penjualan->lastPage());
        $respons['next_page_url']  = $penjualan->nextPageUrl();
        $respons['path']           = url($url);
        $respons['per_page']       = $penjualan->perPage();
        $respons['prev_page_url']  = $penjualan->previousPageUrl();
        $respons['to']             = $penjualan->perPage();
        $respons['total']          = $penjualan->total();
    //DATA PAGINATION

        return $respons;
    }
    public function paginationPencarianData($penjualan, $array, $url, $search)
    {
    //DATA PAGINATION
        $respons['current_page']   = $penjualan->currentPage();
        $respons['data']           = $array;
        $respons['otoritas']      = $this->otoritasPenjualan();
        $respons['first_page_url'] = url($url . '?page=' . $penjualan->firstItem() . '&search=' . $search);
        $respons['from']           = 1;
        $respons['last_page']      = $penjualan->lastPage();
        $respons['last_page_url']  = url($url . '?page=' . $penjualan->lastPage() . '&search=' . $search);
        $respons['next_page_url']  = $penjualan->nextPageUrl();
        $respons['path']           = url($url);
        $respons['per_page']       = $penjualan->perPage();
        $respons['prev_page_url']  = $penjualan->previousPageUrl();
        $respons['to']             = $penjualan->perPage();
        $respons['total']          = $penjualan->total();
    //DATA PAGINATION

        return $respons;
    }

    public function viewOnline()
    {
        $user_warung = Auth::user()->id_warung;
        $penjualan   = Penjualan::with(['pelanggan', 'kas'])->where('id_warung', $user_warung)->orderBy('id', 'desc')->paginate(10);
        $array       = array();

        foreach ($penjualan as $penjualans) {

            if ($penjualans->pelanggan_id == '0') {
                $pelanggan = 'Umum';
            } else {
                $pelanggan = $penjualans->pelanggan->name;
            }

            array_push($array, [
                'id'         => $penjualans->id,
                'id_pesanan' => $penjualans->id_pesanan,
                'waktu'      => $penjualans->Waktu,
                'pelanggan'  => $pelanggan,
                'kas'        => $penjualans->kas->nama_kas,
                'id_pesanan' => $penjualans->id_pesanan,
                'kas'        => $penjualans->kas->nama_kas,
                'total'      => $penjualans->TotalJual,
                'waktu_edit' => $penjualans->WaktuEdit,
            ]);
        }

        $url     = '/penjualan/view-online';
        $respons = $this->paginationData($penjualan, $array, $url);

        return response()->json($respons);
    }

    public function pencarianOnline(Request $request)
    {
        $user_warung = Auth::user()->id_warung;
        $penjualan   = Penjualan::Pencarian($user_warung, $request)->paginate(10);
        $array       = array();
        foreach ($penjualan as $penjualans) {

            if ($penjualans['pelanggan_id'] == '0') {
                $nama_pelanggan = 'Umum';
            } else {
                $nama_pelanggan = $penjualans['nama_pelanggan'];
            }

            array_push($array, [
                'id'         => $penjualans['id'],
                'id_pesanan' => $penjualans['id_pesanan'],
                'waktu'      => $penjualans['Waktu'],
                'pelanggan'  => $nama_pelanggan,
                'total'      => number_format($penjualans['total'], 2, ',', '.'),
                'kas'        => $penjualans['nama_kas'],
                'waktu_edit' => $penjualans['WaktuEdit'],
            ]);
        }

        $url    = '/penjualan/pencarian-online';
        $search = $request->search;

        $respons = $this->paginationPencarianData($penjualan, $array, $url, $search);

        return response()->json($respons);
    }


    public function dataSatuanProduk($id_produk)
    {
        $satuan_dasar = Barang::select('barangs.satuan_id', 'satuans.nama_satuan','barangs.harga_jual')
        ->leftJoin('satuans', 'satuans.id', '=', 'barangs.satuan_id')->where('barangs.id_warung', Auth::user()->id_warung)
        ->where('barangs.id', $id_produk)->first();
        $data_satuans = SatuanKonversi::select('satuan_konversis.id_satuan', 'satuan_konversis.jumlah_konversi', 'satuan_konversis.satuan_dasar', 'satuan_konversis.harga_jual_konversi', 'satuans.nama_satuan')
        ->leftJoin('satuans', 'satuans.id', '=', 'satuan_konversis.id_satuan')
        ->where('warung_id', Auth::user()->id_warung)
        ->where('satuan_konversis.id_produk', $id_produk)->get();

        $array = array([
            'id'              => $satuan_dasar->satuan_id,
            'nama_satuan'     => $satuan_dasar->nama_satuan,
            'satuan_dasar'    => $satuan_dasar->satuan_id,
            'jumlah_konversi' => 1,
            'satuan'          => $satuan_dasar->satuan_id . "|" . strtoupper($satuan_dasar->nama_satuan) . "|" . $satuan_dasar->satuan_id . "|1|1|". $satuan_dasar->harga_jual."|".$id_produk,
        ]);

        foreach ($data_satuans as $data_satuan) {
    // Jika satuan dasar == satuan terkecil maka jumlah konversi dasar = 1
            $jumlah_dasar = SatuanKonversi::select('jumlah_konversi')->where('id_satuan', $data_satuan->satuan_dasar);
            if ($jumlah_dasar->count() > 0) {
                $jumlah_konversi_dasar = $jumlah_dasar->first()->jumlah_konversi;
            } else {
                $jumlah_konversi_dasar = 1;
            }
            array_push($array, [
                'id'              => $data_satuan->id_satuan,
                'nama_satuan'     => $data_satuan->nama_satuan,
                'satuan_dasar'    => $data_satuan->satuan_dasar,
                'jumlah_konversi' => $data_satuan->jumlah_konversi,
                'satuan'          => $data_satuan->id_satuan . "|" . strtoupper($data_satuan->nama_satuan) . "|" . $data_satuan->satuan_dasar . "|" . $data_satuan->jumlah_konversi . "|" . $jumlah_konversi_dasar . "|" . $data_satuan->harga_jual_konversi . "|" . $id_produk,
            ]);
        }

        return response()->json($array);
    }


    public function view()
    {
        $user_warung = Auth::user()->id_warung;
        $penjualan   = PenjualanPos::with(['pelanggan', 'kas', 'user_edit', 'user_buat'])->where('warung_id', $user_warung)->orderBy('id', 'desc')->paginate(10);
        $array       = array();

        foreach ($penjualan as $penjualans) {

            if ($penjualans->pelanggan_id == '0') {
                $pelanggan = 'Umum';
            } else {
                $pelanggan = $penjualans->pelanggan->name;
            }

            array_push($array, [
                'id'               => $penjualans->id,
                'waktu'            => $penjualans->Waktu,
                'pelanggan'        => $pelanggan,
                'status_penjualan' => $penjualans->status_penjualan,
                'total'            => $penjualans->TotalJual,
                'kas'              => $penjualans->kas->nama_kas,
                'potongan'         => $penjualans->PotonganJual,
                'tunai'            => $penjualans->TunaiJual,
                'kembalian'        => $penjualans->KembalianJual,
                'piutang'          => $penjualans->KreditJual,
                'jatuh_tempo'      => $penjualans->JatuhTempo,
                'user_buat'        => $penjualans->user_buat->name,
                'user_edit'        => $penjualans->user_edit->name,
                'waktu_edit'       => $penjualans->WaktuEdit,
            ]);
        }

        $url     = '/penjualan/view';
        $respons = $this->paginationData($penjualan, $array, $url);

        return response()->json($respons);
    }

    public function totalLaporanPenjualan()
    {
    // TOTAL KESELURUHAN
        $user_warung = Auth::user()->id_warung;
        $penjualan   = PenjualanPos::with(['pelanggan', 'kas', 'user_edit', 'user_buat'])
        ->where('warung_id', $user_warung)->orderBy('id', 'desc')->get();

        $total_penjualan = 0;
        foreach ($penjualan as $penjualans) {
            $total_penjualan += $penjualans->total;
        }
        $respons['total_penjualan'] = number_format($total_penjualan, 2, ',', '.');

        return response()->json($respons);
    }

    public function totalLaporanPenjualanFilter(Request $request)
    {
    // TOTAL KESELURUHAN
        $user_warung = Auth::user()->id_warung;
        $penjualan   = PenjualanPos::with(['pelanggan', 'kas', 'user_edit', 'user_buat'])
        ->where('warung_id', $user_warung)
        ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
        ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))->get();

        $total_penjualan = 0;
        foreach ($penjualan as $penjualans) {
            $total_penjualan += $penjualans->total;
        }
        $respons['total_penjualan'] = number_format($total_penjualan, 2, ',', '.');

        return response()->json($respons);
    }

    public function viewFilter(Request $request)
    {
        $user_warung = Auth::user()->id_warung;
        $penjualan   = PenjualanPos::with(['pelanggan', 'kas', 'user_edit', 'user_buat'])
        ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
        ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
        ->where('warung_id', $user_warung)->orderBy('id', 'desc')->paginate(10);
        $array = array();

        foreach ($penjualan as $penjualans) {

            if ($penjualans->pelanggan_id == '0') {
                $pelanggan = 'Umum';
            } else {
                $pelanggan = $penjualans->pelanggan->name;
            }

            array_push($array, [
                'id'               => $penjualans->id,
                'waktu'            => $penjualans->Waktu,
                'pelanggan'        => $pelanggan,
                'status_penjualan' => $penjualans->status_penjualan,
                'total'            => $penjualans->TotalJual,
                'kas'              => $penjualans->kas->nama_kas,
                'potongan'         => $penjualans->PotonganJual,
                'tunai'            => $penjualans->TunaiJual,
                'kembalian'        => $penjualans->KembalianJual,
                'piutang'          => $penjualans->KreditJual,
                'jatuh_tempo'      => $penjualans->JatuhTempo,
                'user_buat'        => $penjualans->user_buat->name,
                'user_edit'        => $penjualans->user_edit->name,
                'waktu_edit'       => $penjualans->WaktuEdit,
            ]);
        }

        $url     = '/penjualan/view-filter';
        $respons = $this->paginationData($penjualan, $array, $url);

        return response()->json($respons);
    }

    public function tanggalSql($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "Y-m-d");
        return $date_format;
    }

    public function pencarian(Request $request)
    {
        $user_warung = Auth::user()->id_warung;
        $penjualan   = PenjualanPos::Pencarian($user_warung, $request)->paginate(10);
        $array       = array();
        foreach ($penjualan as $penjualans) {

            $jatuh_tempo = $penjualans['jatuh_tempoo'];
            if ($jatuh_tempo == '' or $jatuh_tempo == null) {
                $jatuh_tempo = "-";
            }

            if ($penjualans['pelanggan_id'] == '0') {
                $nama_pelanggan = 'Umum';
            } else {
                $nama_pelanggan = $penjualans['nama_pelanggan'];
            }

            array_push($array, [
                'id'               => $penjualans['id'],
                'waktu'            => $penjualans['waktu_jual'],
                'pelanggan'        => $nama_pelanggan,
                'status_penjualan' => $penjualans['status_penjualan'],
                'total'            => $penjualans['TotalJual'],
                'kas'              => $penjualans['nama_kas'],
                'potongan'         => $penjualans['potongan'],
                'tunai'            => $penjualans['TunaiJual'],
                'kembalian'        => $penjualans['KembalianJual'],
                'piutang'          => $penjualans['KreditJual'],
                'jatuh_tempo'      => $jatuh_tempo,
                'user_buat'        => $penjualans['user_buat'],
                'user_edit'        => $penjualans['user_edit'],
                'waktu_edit'       => $penjualans['waktu_edit'],
            ]);
        }

        $url    = '/penjualan/pencarian';
        $search = $request->search;

        $respons = $this->paginationPencarianData($penjualan, $array, $url, $search);

        return response()->json($respons);
    }

    public function viewDetailPenjualanOnline($id)
    {
        $detail_penjualan = DetailPenjualan::with(['produk'])->where('id_penjualan', $id)->orderBy('id', 'desc')->paginate(10);
        $array            = array();

        foreach ($detail_penjualan as $detail_penjualans) {

            array_push($array, [
                'id'           => $detail_penjualans->id,
                'id_penjualan' => $id,
                'nama_produk'  => $detail_penjualans->NamaProduk,
                'kode_produk'  => $detail_penjualans->produk->kode_barang,
                'jumlah'       => $detail_penjualans->jumlah,
                'satuan'       => $detail_penjualans->produk->satuan->nama_satuan,
                'harga'        => $detail_penjualans->harga,
                'potongan'     => 0,
                'subtotal'     => $detail_penjualans->subtotal,
                'produk'       => $detail_penjualans->id_produk . "|" . $detail_penjualans->NamaProduk . "|" . $detail_penjualans->harga]);
        }

        $url     = '/penjualan/view-detail-penjualan-online/' . $id;
        $respons = $this->paginationData($detail_penjualan, $array, $url);

        return response()->json($respons);
    }

    public function pencarianDetailPenjualanOnline(Request $request, $id)
    {
        $detail_penjualan = DetailPenjualan::Pencarian($id, $request)->paginate(10);

        $array = array();
        foreach ($detail_penjualan as $detail_penjualans) {

            array_push($array, [
                'id'           => $detail_penjualans['id'],
                'id_penjualan' => $id,
                'nama_produk'  => title_case($detail_penjualans['nama_barang']),
                'kode_produk'  => $detail_penjualans['kode_barang'],
                'jumlah'       => $detail_penjualans['jumlah'],
                'harga'        => $detail_penjualans['harga'],
                'subtotal'     => $detail_penjualans['subtotal'],
                'produk'       => $detail_penjualans['id_produk'] . "|" . title_case($detail_penjualans['nama_barang']) . "|" . $detail_penjualans->harga]);
        }

        $url    = '/penjualan/pencarian-detail-penjualan-online/' . $id;
        $search = $request->search;

        $respons = $this->paginationPencarianData($detail_penjualan, $array, $url, $search);

        return response()->json($respons);
    }
    public function viewDetailPenjualan($id)
    {
        $user_warung      = Auth::user()->id_warung;
        $detail_penjualan = DetailPenjualanPos::with(['produk', 'satuan'])->where('warung_id', $user_warung)->where('id_penjualan_pos', $id)->orderBy('id_detail_penjualan_pos', 'desc')->paginate(10);
        $array            = array();

        $DetailPenjualanPos = new DetailPenjualanPos();
        $subtotal           = $DetailPenjualanPos->subtotalTbs($user_warung, $id);

        foreach ($detail_penjualan as $detail_penjualans) {

            $potongan = $this->tampilPotongan($detail_penjualans->potongan, $detail_penjualans->jumlah_produk, $detail_penjualans->harga_produk);

            array_push($array, [
                'id_detail_penjualan_pos' => $detail_penjualans->id_detail_penjualan_pos,
                'id_penjualan_pos'        => $id,
                'nama_produk'             => $detail_penjualans->NamaProduk,
                'kode_produk'             => $detail_penjualans->produk->kode_barang,
                'satuan'                  => $detail_penjualans->satuan->nama_satuan,
                'jumlah_produk'           => $detail_penjualans->jumlah_produk,
                'harga_produk'            => $detail_penjualans->harga_produk,
                'potongan'                => $potongan,
                'subtotal'                => $detail_penjualans->subtotal,
                'produk'                  => $detail_penjualans->id_produk . "|" . $detail_penjualans->NamaProduk . "|" . $detail_penjualans->harga_produk]);
        }

        $url                 = '/penjualan/view-detail-penjualan/' . $id;
        $respons             = $this->paginationData($detail_penjualan, $array, $url);
        $respons['subtotal'] = $subtotal;

        return response()->json($respons);
    }

    public function pencarianDetailPenjualan(Request $request, $id)
    {
        $user_warung      = Auth::user()->id_warung;
        $detail_penjualan = DetailPenjualanPos::Pencarian($user_warung, $id, $request)->paginate(10);

        $array = array();
        foreach ($detail_penjualan as $detail_penjualans) {

            $potongan = $this->tampilPotongan($detail_penjualans['potongan'], $detail_penjualans['jumlah_produk'], $detail_penjualans['harga_produk']);

            array_push($array, [
                'id_detail_penjualan_pos' => $detail_penjualans['id_detail_penjualan_pos'],
                'id_penjualan_pos'        => $id,
                'nama_produk'             => title_case($detail_penjualans['nama_barang']),
                'kode_produk'             => $detail_penjualans['kode_barang'],
                'jumlah_produk'           => $detail_penjualans['jumlah_produk'],
                'satuan'                  => $detail_penjualans['satuan'],
                'potongan'                => $potongan,
                'harga_produk'            => $detail_penjualans['harga_produk'],
                'subtotal'                => $detail_penjualans['subtotal'],
                'produk'                  => $detail_penjualans['id_produk'] . "|" . title_case($detail_penjualans['nama_barang']) . "|" . $detail_penjualans->harga_jual]);
        }

        $url    = '/penjualan/pencarian-detail-penjualan/' . $id;
        $search = $request->search;

        $respons = $this->paginationPencarianData($detail_penjualan, $array, $url, $search);

        return response()->json($respons);
    }
    public function viewTbsPenjualan()
    {
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;
        $tbs_penjualan = TbsPenjualan::with(['produk', 'satuan'])->where('warung_id', $user_warung)->where('session_id', $session_id)            ->whereNull('no_antrian')->orderBy('id_tbs_penjualan', 'desc')->get();
        $array         = array();

        foreach ($tbs_penjualan as $tbs_penjualans) {

            $potongan = $this->tampilPotongan($tbs_penjualans->potongan, $tbs_penjualans->jumlah_produk, $tbs_penjualans->harga_produk);

            array_push($array, [
                'id_tbs_penjualan' => $tbs_penjualans->id_tbs_penjualan,
                'nama_produk'      => $tbs_penjualans->NamaProduk,
                'kode_produk'      => $tbs_penjualans->produk->kode_barang,
                'jumlah_produk'    => $tbs_penjualans->jumlah_produk,
                'satuan'           => $tbs_penjualans->satuan->nama_satuan,
                'satuan_id'        => $tbs_penjualans->satuan_id,
                'harga_produk'     => $tbs_penjualans->harga_produk,
                'potongan'         => $potongan,
                'subtotal'         => $tbs_penjualans->subtotal,
                'id_produk'        => $tbs_penjualans->id_produk,
                'level_harga'      => $this->cekLevelHarga($tbs_penjualans->harga_produk,$tbs_penjualans->produk->harga_jual),
                'produk'           => $tbs_penjualans->id_produk . "|" . $tbs_penjualans->NamaProduk . "|" . $tbs_penjualans->produk->harga_jual]);
        }

        return response()->json($array);
    }

    public function pencarianTbsPenjualan(Request $request)
    {
        $session_id  = session()->getId();
        $user_warung = Auth::user()->id_warung;

        $tbs_penjualan = TbsPenjualan::Pencarian($user_warung, $session_id, $request)->paginate(10);

        $array = array();
        foreach ($tbs_penjualan as $tbs_penjualans) {

            $potongan = $this->tampilPotongan($tbs_penjualans['potongan'], $tbs_penjualans['jumlah_produk'], $tbs_penjualans['harga_produk']);

            array_push($array, [
                'id_tbs_penjualan' => $tbs_penjualans['id_tbs_penjualan'],
                'nama_produk'      => title_case($tbs_penjualans['nama_barang']),
                'kode_produk'      => $tbs_penjualans['kode_barang'],
                'jumlah_produk'    => $tbs_penjualans['jumlah_produk'],
                'satuan'           => $tbs_penjualans['satuan'],
                'satuan_id'           => $tbs_penjualans['satuan_id'],
                'harga_produk'     => $tbs_penjualans['harga_produk'],
                'potongan'         => $potongan,
                'subtotal'         => $tbs_penjualans['subtotal'],
                'id_produk'       => $tbs_penjualans['id_produk'],
                'level_harga'     => $this->cekLevelHarga($tbs_penjualans['harga_produk'],$tbs_penjualans['harga_jual']),
                'produk'           => $tbs_penjualans['id_produk'] . "|" . title_case($tbs_penjualans['nama_barang']) . "|" . $tbs_penjualans['harga_jual'],
            ]);
        }
        return response()->json($array);
    }

    public function viewEditTbsPenjualan($id)
    {
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;
        $tbs_penjualan = EditTbsPenjualan::with(['produk', 'satuan'])->where('warung_id', $user_warung)->where('id_penjualan_pos', $id)->orderBy('id_edit_tbs_penjualans', 'desc')->get();
        $array         = array();

        foreach ($tbs_penjualan as $tbs_penjualans) {

            $potongan = $this->tampilPotongan($tbs_penjualans->potongan, $tbs_penjualans->jumlah_produk, $tbs_penjualans->harga_produk);

            array_push($array, [
                'id_edit_tbs_penjualans' => $tbs_penjualans->id_edit_tbs_penjualans,
                'id_penjualan_pos'       => $tbs_penjualans->id_penjualan_pos,
                'nama_produk'            => $tbs_penjualans->NamaProduk,
                'kode_produk'            => $tbs_penjualans->produk->kode_barang,
                'satuan'                 => $tbs_penjualans->satuan->nama_satuan,
                'jumlah_produk'          => $tbs_penjualans->jumlah_produk,
                'harga_produk'           => $tbs_penjualans->harga_produk,
                'potongan'               => $potongan,
                'subtotal'               => $tbs_penjualans->subtotal,
                'id_produk'              => $tbs_penjualans->id_produk,
                'satuan_id'              => $tbs_penjualans->satuan_id,
                'level_harga'           => $this->cekLevelHarga($tbs_penjualans->harga_produk,$tbs_penjualans->produk->harga_jual),
                'produk'                 => $tbs_penjualans->id_produk . "|" . $tbs_penjualans->NamaProduk . "|" . $tbs_penjualans->produk->harga_jual]);
        }

        return response()->json($array);
    }

    public function pencarianEditTbsPenjualan(Request $request, $id)
    {
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;
        $tbs_penjualan = EditTbsPenjualan::Pencarian($user_warung, $id, $request)->get();

        $array = array();
        foreach ($tbs_penjualan as $tbs_penjualans) {

            $potongan = $this->tampilPotongan($tbs_penjualans['potongan'], $tbs_penjualans['jumlah_produk'], $tbs_penjualans['harga_produk']);

            array_push($array, [
                'id_edit_tbs_penjualans' => $tbs_penjualans['id_edit_tbs_penjualans'],
                'id_penjualan_pos'       => $id,
                'nama_produk'            => title_case($tbs_penjualans['nama_barang']),
                'kode_produk'            => $tbs_penjualans['kode_barang'],
                'jumlah_produk'          => $tbs_penjualans['jumlah_produk'],
                'satuan'                 => $tbs_penjualans['satuan'],
                'potongan'               => $potongan,
                'harga_produk'           => $tbs_penjualans['harga_produk'],
                'subtotal'               => $tbs_penjualans['subtotal'],
                'level_harga'           => $this->cekLevelHarga($tbs_penjualans['harga_produk'],$tbs_penjualans['harga_jual']),
                'produk'                 => $tbs_penjualans['id_produk'] . "|" . title_case($tbs_penjualans['nama_barang']) . "|" . $tbs_penjualans['harga_jual'],
            ]);
        }

        return response()->json($array);
    }

    public function cekHargaProduk($produk)
    {

        $settings = SettingPenjualanPos::where('id_warung', Auth::user()->id_warung);
        if ($settings->count() == 0) {
    $harga_jual = $produk[3]; // harga jual 1
} else {
    if ($settings->first()->harga_jual == 1) {
        $harga_jual = $produk[3]; // harga jual 1
    } else if ($settings->first()->harga_jual == 2) {
        $harga_jual = $produk[5]; // harga jual 2
    }
}
return $harga_jual;
}

public  function cekHargaProdukPromo($produk){
    $barang = Barang::select(['id','harga_jual',DB::raw('CURDATE() as tanggal_sekarang')])->where('id', $produk);
    $data_tanggal_promo = SettingPromo::settingPromoTanggal($barang->first());
    if ($data_tanggal_promo->count() > 0) {
        $dari_tanggal = $data_tanggal_promo->first()->dari_tanggal;
        $sampai_tanggal = $data_tanggal_promo->first()->sampai_tanggal;

        $data_harga_coret = SettingPromo::settingPromoData($barang->first(),$dari_tanggal,$sampai_tanggal);
    }
    else{
        $dari_tanggal = '0000-00-00';
        $sampai_tanggal = '0000-00-00';

        $data_harga_coret = SettingPromo::settingPromoData($barang->first(),$dari_tanggal,$sampai_tanggal);
    }
                //Mencari hari sekarang
    $tgl= substr($barang->first()->tanggal_sekarang,8,2);
    $bln= substr($barang->first()->tanggal_sekarang,5,2);
    $thn= substr($barang->first()->tanggal_sekarang,0,4);

    $info= date('w', mktime(0,0,0,$bln,$tgl,$thn));
    if ($info == 0) {
        $hari = "minggu";
    }elseif($info == 1){
        $hari = "senin";
    }elseif($info == 2){
        $hari = "selasa";
    }elseif($info == 3){
        $hari = "rabu";
    }elseif($info == 4){
        $hari = "kamis";
    }elseif($info == 5){
        $hari = "jumat";
    }elseif($info == 6){
        $hari = "sabtu";
    }
                //Mencari hari sekarang
    if ($data_harga_coret->count() > 0 ) {
        foreach ($data_harga_coret->get() as $data) {
            if ($hari == $data->name) {
                $harga_produk    = $data->harga_coret;
                break;
            }else{
                $harga_produk    = "";
            }
        }
    }else{
        $harga_produk    = "";
    }
    return $harga_produk;
}

public function prosesTambahTbsPenjualan(Request $request)
{   
    $settings = SettingPenjualanPos::where('id_warung', Auth::user()->id_warung);
    $produk      = explode("|", $request->produk);
    $id_produk   = $produk[0];
    $nama_produk = $produk[1];
    $satuan_id   = $produk[4];
    $session_id  = session()->getId();

    if ($settings->first()->jumlah_produk == 0) {
        $satuan_produk = explode("|", $request->satuan_produk);

        $satuan_id = $satuan_produk[0];
        $satuan_dasar = $satuan_produk[2];
        $nama_satuan = $satuan_produk[1];

    if ($satuan_produk[0] === $satuan_produk[2]) { //$satuan_produk[0] == Satuan Konversi & $satuan_produk[2] == Satuan Dasar
        $cek_harga = $this->cekHargaProdukPromo($id_produk);
        if ($cek_harga == ""){
            $harga_jual = $this->cekHargaProduk($produk);
        }else{
            $harga_jual = $cek_harga;
        }

    }else{
        $harga_jual_konversi = SatuanKonversi::select('harga_jual_konversi')->where('id_produk', $id_produk)->where('id_satuan', $satuan_produk[0])->first()->harga_jual_konversi;
        $harga_jual = $harga_jual_konversi;        
    }
}else{

    $satuan_id = $produk[4];
    $satuan_dasar = $produk[4];
    $nama_satuan = Satuan::select('nama_satuan')->where('id', $satuan_id)->first()->nama_satuan;
    $cek_harga = $this->cekHargaProdukPromo($id_produk);
    if ($cek_harga == ""){
        $harga_jual = $this->cekHargaProduk($produk);
    }else{
        $harga_jual = $cek_harga;
    }
}

if ($harga_jual == '' || $harga_jual == 0) {

    $respons['harga_jual'] = $harga_jual;
    return response()->json($respons);

} else {

    $data_tbs = TbsPenjualan::where('id_produk', $id_produk)->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->whereNull('no_antrian');

    //JIKA PRODUK YG DIPILIH SUDAH ADA DI TBS
    if ($data_tbs->count() > 0) {

        $jumlah_produk = $data_tbs->first()->jumlah_produk + $request->jumlah_produk;

        $subtotal_edit = ($jumlah_produk * $harga_jual) - $data_tbs->first()->potongan;

        $data_tbs->update([
            'jumlah_produk' => $jumlah_produk,
            'satuan_id' => $satuan_id,
            'satuan_dasar' => $satuan_dasar,
            'harga_produk' => $harga_jual,
            'subtotal' => $subtotal_edit,
        ]);

        $subtotal = $request->jumlah_produk * $data_tbs->first()->harga_produk;

        $respons['id_tbs_penjualan']    = $data_tbs->first()->id_tbs_penjualan;
        $respons['jumlah_produk']       = $jumlah_produk;
        $respons['subtotal']            = $subtotal;
        $respons['satuan'] = $nama_satuan;
        $respons['harga_produk'] = $harga_jual;
        $respons['id_produk']         = $id_produk;
        $respons['satuan_id']         = $satuan_id;

        $respons['subtotalKeseluruhan'] = $subtotal_edit;

        return response()->json($respons);

    } else {

        $subtotal = $request->jumlah_produk * $harga_jual;

        $tbspenjualan = TbsPenjualan::create([
            'session_id'    => $session_id,
            'satuan_id'     => $satuan_id,
            'satuan_dasar'  => $satuan_dasar,
            'id_produk'     => $id_produk,
            'jumlah_produk' => $request->jumlah_produk,
            'harga_produk'  => $harga_jual,
            'subtotal'      => $subtotal,
            'warung_id'     => Auth::user()->id_warung,
        ]);

        $respons['id_tbs_penjualan'] = $tbspenjualan->id_tbs_penjualan;
        $respons['nama_produk']      = $nama_produk;
        $respons['kode_produk']      = $tbspenjualan->produk->kode_barang;
        $respons['jumlah_produk']    = $request->jumlah_produk;
        $respons['satuan']          = $tbspenjualan->satuan->nama_satuan;
        $respons['satuan_id']        = $satuan_id;
        $respons['harga_produk']     = $harga_jual;
        $respons['potongan']         = 0;
        $respons['subtotal']         = $subtotal;
        $respons['id_produk']        = $id_produk;
        $respons['level_harga']        = $this->cekLevelHarga($harga_jual,$tbspenjualan->produk->harga_jual);
        $respons['produk']           = $id_produk . "|" . $nama_produk . "|" . $harga_jual;

        return response()->json($respons);
    }

}

}
    //PROSE EDIT JUMLAH TBS PENJUALAN
public function prosesEditJumlahTbsPenjualan(Request $request)
{

    $tbs_penjualan = TbsPenjualan::find($request->id_tbs);

    $subtotal = ($tbs_penjualan->harga_produk * $request->jumlah_produk) - $tbs_penjualan->potongan;

    $tbs_penjualan->update(['jumlah_produk' => $request->jumlah_produk, 'subtotal' => $subtotal]);

    $respons['jumlah_produk'] = $request->jumlah_produk;
    $respons['subtotal']      = $subtotal;

    return response()->json($respons);
}

    //PROSE EDIT HARGA TBS PENJUALAN
public function prosesEditHargaTbsPenjualan(Request $request)
{

    $tbs_penjualan = TbsPenjualan::find($request->id_tbs);
    if ($request->level_harga_produk == 1) {
       $harga_produk = $tbs_penjualan->produk->harga_jual;
   }else{
       $harga_produk = $tbs_penjualan->produk->harga_jual2;
   }

   $subtotal = ($harga_produk * $tbs_penjualan->jumlah_produk) - $tbs_penjualan->potongan;

   $tbs_penjualan->update(['harga_produk' => $harga_produk, 'subtotal' => $subtotal]);

   $respons['subtotal']      = $subtotal;
   $respons['harga_produk']      = $harga_produk;
   $respons['potongan']      = $this->tampilPotongan($tbs_penjualan->potongan, $tbs_penjualan->jumlah_produk, $harga_produk);

   return response()->json($respons);
}

public function prosesEditPotonganTbsPenjualan(Request $request)
{
    $tbs_penjualan = TbsPenjualan::find($request->id_tbs);

    $total = $tbs_penjualan->jumlah_produk * $tbs_penjualan->harga_produk;

    $potongan_produk = $this->cekPotongan($request->potongan_produk, $tbs_penjualan->harga_produk, $tbs_penjualan->jumlah_produk);

    if ($potongan_produk == '') {

        $respons['status'] = 0;

        return response()->json($respons);

    } else if ($potongan_produk > $total) {

        $respons['status'] = 1;

        return response()->json($respons);

    } else {
        $subtotal = ($tbs_penjualan->jumlah_produk * $tbs_penjualan->harga_produk) - $potongan_produk;

        $tbs_penjualan->update(['potongan' => $potongan_produk, 'subtotal' => $subtotal]);

        $potongan            = $this->tampilPotongan($potongan_produk, $tbs_penjualan->jumlah_produk, $tbs_penjualan->harga_produk);
        $respons['potongan'] = $potongan;
        $respons['subtotal'] = $subtotal;

        return response()->json($respons);
    }

}

public function prosesHapusTbsPenjualan($id)
{

    if (!TbsPenjualan::destroy($id)) {
        return 0;
    } else {
        return response(200);
    }

}

public function proses_batal_penjualan()
{

    $session_id         = session()->getId();
    $data_tbs_penjualan = TbsPenjualan::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->whereNull('no_antrian')->delete();

    return response(200);
}

public function cekPotongan($potongan, $harga_produk, $jumlah_produk)
{
    $cek_potongan = substr_count($potongan, '%'); // UNTUK CEK APAKAH ADA STRING "%" atau maksudnya untuk cek apakah pot. dalam bentuk persen atau tidak

    // JIKA POTONGAN TIDAK DALAM BENTUK PERSEN
    if ($cek_potongan == 0) {

    // FILTER POTONGAN, SEMUA BENTUK STRING AKAN DI DIFILTER KECUALI FLOAT/KOMA
        $potongan_produk = filter_var($potongan, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    } else {
    // JIKA POTONGAN DALAM BENTUK PERSEN
    // FILTER POTONGAN, SEMUA BENTUK STRING AKAN DI DIFILTER KECUALI FLOAT/KOMA
        $potongan_persen = filter_var($potongan, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // UBAH NILAI POTONGAN KE BENTUK DESIMAL
        $potongan_produk = ($harga_produk * $jumlah_produk) * $potongan_persen / 100;
    }

    return $potongan_produk;
}

public function cekDataTbsPenjualan($id)
{

    return response()->json([
        "penjualan" => PenjualanPos::find($id)->toArray(),
    ]);
}

public function queryProduk($id_produk)
{

    $barang = Barang::find($id_produk);

    return $barang;

}

public function index()
{
    //
}

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
    //
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
    //START TRANSAKSI
        DB::beginTransaction();
        $warung_id  = Auth::user()->id_warung;
        $session_id = session()->getId();
        $user       = Auth::user()->id;
        $no_faktur  = PenjualanPos::no_faktur($warung_id);
    //INSERT DETAIL PENJUALAN

        $cek_status = intval($request->pembayaran) - intval($request->total_akhir);

        if ($cek_status >= 0) {

            $status_penjualan = "Tunai";
            $this->validate($request, [
                'pelanggan' => 'required',
                'kas'       => 'required']);

        } else {

            $status_penjualan = "Piutang";
            $this->validate($request, [
                'pelanggan'   => 'required',
                'kas'         => 'required',
                'jatuh_tempo' => 'required']);

        }

        $data_produk_penjualan = TbsPenjualan::with('produk')->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->whereNull('no_antrian');

        if ($data_produk_penjualan->count() == 0) {

            return $data_produk_penjualan->count();

        } else {

    //INSERT PENJUALAN

            $penjualan = PenjualanPos::create([
                'no_faktur'        => $no_faktur,
                'total'            => $request->total_akhir,
                'pelanggan_id'     => $request->pelanggan,
                'status_penjualan' => $status_penjualan,
                'potongan'         => $request->potongan,
                'tunai'            => $request->pembayaran,
                'kembalian'        => $request->kembalian,
                'kredit'           => $request->kredit,
                'nilai_kredit'     => $request->kredit,
                'id_kas'           => $request->kas,
                'status_jual_awal' => $status_penjualan,
                'tanggal_jt_tempo' => $request->jatuh_tempo,
                'warung_id'        => Auth::user()->id_warung,
            ]);

    // INSERT KAS DAN PIUTANG TIDAK DIBUAT DI OBSERVER KARENA DI OBSERVER ID PENJUALAN DI ANGGAP NULL
            $kas = intval($penjualan->tunai) - intval($penjualan->kembalian);
            if ($kas > 0) {
                TransaksiKas::create([
                    'no_faktur'       => $penjualan->id,
                    'jenis_transaksi' => 'PenjualanPos',
                    'jumlah_masuk'    => $kas,
                    'kas'             => $penjualan->id_kas,
                    'warung_id'       => $penjualan->warung_id]);
            }

            if ($penjualan->kredit > 0) {
                TransaksiPiutang::create([
                    'no_faktur'       => $penjualan->id,
                    'id_transaksi'    => $penjualan->id,
                    'jenis_transaksi' => 'PenjualanPos',
                    'jumlah_masuk'    => $penjualan->kredit,
                    'pelanggan_id'    => $penjualan->pelanggan_id,
                    'warung_id'       => $penjualan->warung_id,
                ]);
            }

            foreach ($data_produk_penjualan->get() as $data_tbs) {

                if ($data_tbs->produk->hitung_stok == 1 and $this->cekSettingStok() == 0) {

                    $detail_penjualan = new DetailPenjualanPos();
                    $stok_produk      = $detail_penjualan->stok_produk($data_tbs->id_produk);
                    $sisa             = $stok_produk - $data_tbs->jumlah_produk;

                    if ($data_tbs->satuan_id != $data_tbs->satuan_dasar) {

                        $jumlah_konversi = SatuanKonversi::select('jumlah_konversi')->where('warung_id', Auth::user()->id_warung)
                        ->where('id_produk', $data_tbs->id_produk)
                        ->where('id_satuan', $data_tbs->satuan_id)->first()->jumlah_konversi;

                        $jumlah_dasar = SatuanKonversi::select('jumlah_konversi')->where('id_satuan', $data_tbs->satuan_dasar);
                        if ($jumlah_dasar->count() > 0) {
                            $jumlah_konversi_dasar = intval($data_tbs->jumlah_produk) * (intval($jumlah_dasar->first()->jumlah_konversi) * intval($jumlah_konversi));
                        } else {
                            $jumlah_konversi_dasar = intval($data_tbs->jumlah_produk) * intval($jumlah_konversi);
                        }

                        $sisa = $stok_produk - $jumlah_konversi_dasar;

                    }

                    if ($sisa < 0) {
    //DI BATALKAN PROSES NYA

                        $respons['respons']     = 1;
                        $respons['nama_produk'] = title_case($data_tbs->produk->nama_barang);
                        $respons['stok_produk'] = $stok_produk;
                        DB::rollBack();
                        return response()->json($respons);

                    } else {

                        $detail_penjualan = DetailPenjualanPos::create([
                            'id_penjualan_pos' => $penjualan->id,
                            'no_faktur'        => $no_faktur,
                            'satuan_id'        => $data_tbs->satuan_id,
                            'satuan_dasar'     => $data_tbs->satuan_dasar,
                            'id_produk'        => $data_tbs->id_produk,
                            'jumlah_produk'    => $data_tbs->jumlah_produk,
                            'harga_produk'     => $data_tbs->harga_produk,
                            'subtotal'         => $data_tbs->subtotal,
                            'potongan'         => $data_tbs->potongan,
                            'warung_id'        => Auth::user()->id_warung,
                            'created_at'       => $penjualan->created_at,
                        ]);

                    }
                } else {

                    $detail_penjualan = DetailPenjualanPos::create([
                        'id_penjualan_pos' => $penjualan->id,
                        'no_faktur'        => $no_faktur,
                        'satuan_id'        => $data_tbs->satuan_id,
                        'satuan_dasar'     => $data_tbs->satuan_dasar,
                        'id_produk'        => $data_tbs->id_produk,
                        'jumlah_produk'    => $data_tbs->jumlah_produk,
                        'harga_produk'     => $data_tbs->harga_produk,
                        'subtotal'         => $data_tbs->subtotal,
                        'potongan'         => $data_tbs->potongan,
                        'warung_id'        => Auth::user()->id_warung,
                        'created_at'       => $penjualan->created_at,
                    ]);

                }
            }

    //HAPUS TBS PENJUALAN
            $data_produk_penjualan->delete();
            DB::commit();

            $respons['respons_penjualan'] = $penjualan->id;
            return response()->json($respons);

        }
    }

    public function cekSettingStok()
    {

        $user_warung = Auth::user()->id_warung;
        $settings    = SettingPenjualanPos::where('id_warung', $user_warung);

        if ($settings->count() == 0) {
            return 0;
        } else {
            return $settings->first()->stok;
        }

    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
    //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
    //
        $session_id            = session()->getId();
        $data_produk_penjualan = DetailPenjualanPos::where('id_penjualan_pos', $id)->where('warung_id', Auth::user()->id_warung);

        $hapus_semua_edit_tbs_penjualan = EditTbsPenjualan::where('id_penjualan_pos', $id)->where('warung_id', Auth::user()->id_warung)->delete();
        foreach ($data_produk_penjualan->get() as $data_tbs) {
            $detail_penjualan = EditTbsPenjualan::create([
                'session_id'       => $session_id,
                'id_penjualan_pos' => $id,
                'id_produk'        => $data_tbs->id_produk,
                'satuan_id'        => $data_tbs->satuan_id,
                'satuan_dasar'     => $data_tbs->satuan_dasar,
                'jumlah_produk'    => $data_tbs->jumlah_produk,
                'harga_produk'     => $data_tbs->harga_produk,
                'subtotal'         => $data_tbs->subtotal,
                'tax'              => $data_tbs->tax,
                'potongan'         => $data_tbs->potongan,
                'warung_id'        => Auth::user()->id_warung,
            ]);
        }

        return response(200);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
    //START TRANSAKSI
        DB::beginTransaction();
        $data_penjualan_pos = PenjualanPos::find($id);
        $user               = Auth::user()->id;

        $cek_status = intval($request->pembayaran) - intval($request->total_akhir);

        if ($cek_status >= 0) {

            $status_penjualan = "Tunai";
            $this->validate($request, [
                'pelanggan' => 'required',
                'kas'       => 'required']);

        } else {

            $status_penjualan = "Piutang";
            $this->validate($request, [
                'pelanggan'   => 'required',
                'kas'         => 'required',
                'jatuh_tempo' => 'required']);

        }

        $data_detail_penjualan_pos = DetailPenjualanPos::where('id_penjualan_pos', $id)->where('warung_id', Auth::user()->id_warung)->get();

    //HAPUS DETAIL PENJUALAN
        foreach ($data_detail_penjualan_pos as $data_detail) {

            if (!$hapus_detail = DetailPenjualanPos::destroy($data_detail->id_detail_penjualan_pos)) {
    //DI BATALKAN PROSES NYA

                $respons['respons'] = 2;
                $respons['info']    = "Gagal Hapus Hpp Penjualan";
                DB::rollBack();
                return response()->json($respons);
            }
        }

    //INSERT DETAIL PENJUALAN POS
        $data_produk_penjualan_pos = EditTbsPenjualan::with('produk')->where('id_penjualan_pos', $id)->where('warung_id', Auth::user()->id_warung);

        if ($data_produk_penjualan_pos->count() == 0) {

            DB::rollBack();
            return 0;

        } else {

    //UPDATE PENJUALAN

            $data_penjualan_pos->update([
                'total'            => $request->total_akhir,
                'pelanggan_id'     => $request->pelanggan,
                'status_penjualan' => $status_penjualan,
                'potongan'         => $request->potongan,
                'tunai'            => $request->pembayaran,
                'kembalian'        => $request->kembalian,
                'kredit'           => $request->kredit,
                'nilai_kredit'     => $request->kredit,
                'id_kas'           => $request->kas,
                'status_jual_awal' => $status_penjualan,
                'tanggal_jt_tempo' => $request->jatuh_tempo,
                'updated_at'       => DB::raw('NOW()'),
                'updated_by'       => $user,
            ]);

            $kas = intval($data_penjualan_pos->tunai) - intval($data_penjualan_pos->kembalian);

            TransaksiPiutang::where('no_faktur', $id)->delete();
            TransaksiKas::where('no_faktur', $id)->delete();
            if ($kas > 0) {

                TransaksiKas::create([
                    'no_faktur'       => $id,
                    'jenis_transaksi' => 'PenjualanPos',
                    'jumlah_masuk'    => $kas,
                    'kas'             => $data_penjualan_pos->id_kas,
                    'warung_id'       => $data_penjualan_pos->warung_id]);
            }

            if ($data_penjualan_pos->kredit > 0) {

                TransaksiPiutang::create([
                    'no_faktur'       => $id,
                    'jenis_transaksi' => 'PenjualanPos',
                    'jumlah_masuk'    => $data_penjualan_pos->kredit,
                    'pelanggan_id'    => $data_penjualan_pos->pelanggan_id,
                    'warung_id'       => $data_penjualan_pos->warung_id]);

            }

    // inset detail penjualan
            foreach ($data_produk_penjualan_pos->get() as $data_tbs) {

                if ($data_tbs->produk->hitung_stok == 1 and $this->cekSettingStok() == 0) {

                    $detail_penjualan = new DetailPenjualanPos();
                    $stok_produk      = $detail_penjualan->stok_produk($data_tbs->id_produk);
                    $sisa = $stok_produk - $data_tbs->jumlah_produk;

                    if ($data_tbs->satuan_id != $data_tbs->satuan_dasar) {

                        $jumlah_konversi = SatuanKonversi::select('jumlah_konversi')->where('warung_id', Auth::user()->id_warung)
                        ->where('id_produk', $data_tbs->id_produk)
                        ->where('id_satuan', $data_tbs->satuan_id)->first()->jumlah_konversi;

                        $jumlah_dasar = SatuanKonversi::select('jumlah_konversi')->where('id_satuan', $data_tbs->satuan_dasar);
                        if ($jumlah_dasar->count() > 0) {
                            $jumlah_konversi_dasar = intval($data_tbs->jumlah_produk) * (intval($jumlah_dasar->first()->jumlah_konversi) * intval($jumlah_konversi));
                        } else {
                            $jumlah_konversi_dasar = intval($data_tbs->jumlah_produk) * intval($jumlah_konversi);
                        }

                        $sisa = $stok_produk - $jumlah_konversi_dasar;

                    }

                    if ($sisa < 0) {
                        //DI BATALKAN PROSES NYA

                        $respons['respons']     = 1;
                        $respons['nama_produk'] = title_case($data_tbs->produk->nama_barang);
                        $respons['stok_produk'] = $stok_produk;
                        DB::rollBack();
                        return response()->json($respons);

                    } else {

                        $detail_penjualan = DetailPenjualanPos::create([
                            'id_penjualan_pos' => $id,
                            'no_faktur'        => $data_penjualan_pos->no_faktur,
                            'satuan_id'        => $data_tbs->satuan_id,
                            'satuan_dasar'     => $data_tbs->satuan_dasar,
                            'id_produk'        => $data_tbs->id_produk,
                            'jumlah_produk'    => $data_tbs->jumlah_produk,
                            'harga_produk'     => $data_tbs->harga_produk,
                            'subtotal'         => $data_tbs->subtotal,
                            'potongan'         => $data_tbs->potongan,
                            'warung_id'        => Auth::user()->id_warung,
                            'created_at'       => $data_penjualan_pos->created_at,
                            'updated_at'       => $data_penjualan_pos->updated_at,
                            'created_by'       => $data_penjualan_pos->created_by,
                            'updated_by'       => $data_penjualan_pos->updated_by,
                        ]);

                    }
                } else {

                    $detail_penjualan = DetailPenjualanPos::create([
                        'id_penjualan_pos' => $id,
                        'no_faktur'        => $data_penjualan_pos->no_faktur,
                        'satuan_id'        => $data_tbs->satuan_id,
                        'satuan_dasar'     => $data_tbs->satuan_dasar,
                        'id_produk'        => $data_tbs->id_produk,
                        'jumlah_produk'    => $data_tbs->jumlah_produk,
                        'harga_produk'     => $data_tbs->harga_produk,
                        'subtotal'         => $data_tbs->subtotal,
                        'potongan'         => $data_tbs->potongan,
                        'warung_id'        => Auth::user()->id_warung,
                        'created_at'       => $data_penjualan_pos->created_at,
                        'updated_at'       => $data_penjualan_pos->updated_at,
                        'created_by'       => $data_penjualan_pos->created_by,
                        'updated_by'       => $data_penjualan_pos->updated_by,
                    ]);

                }
            }

            $data_produk_penjualan_pos = EditTbsPenjualan::where('id_penjualan_pos', $id)->where('warung_id', Auth::user()->id_warung)->delete();
            DB::commit();

            $respons['respons_penjualan'] = $id;
            return response()->json($respons);
        }

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
    //START TRANSAKSI
        DB::beginTransaction();

        if (!PenjualanPos::destroy($id)) {
            DB::rollBack();
            return 0;
        } else {
            DB::commit();
            return response(200);
        }
    }

    // edit penjualan

    // proses tambah tbs edit penjualan
    public function prosesTambahEditTbsPenjualan(Request $request, $id)
    {
        $settings = SettingPenjualanPos::where('id_warung', Auth::user()->id_warung);
        $produk      = explode("|", $request->produk);
        $id_produk   = $produk[0];
        $nama_produk = $produk[1];
        $satuan_id   = $produk[4];
        $session_id  = session()->getId();

        if ($settings->first()->jumlah_produk == 0) {
            $satuan_produk = explode("|", $request->satuan_produk);

            $satuan_id = $satuan_produk[0];
            $satuan_dasar = $satuan_produk[2];
            $nama_satuan = $satuan_produk[1];

            if ($satuan_produk[0] === $satuan_produk[2]) { //$satuan_produk[0] == Satuan Konversi & $satuan_produk[2] == Satuan Dasar
                $cek_harga = $this->cekHargaProdukPromo($id_produk);
                if ($cek_harga == ""){
                    $harga_jual = $this->cekHargaProduk($produk);
                }else{
                    $harga_jual = $cek_harga;
                }
            }else{
                $harga_jual_konversi = SatuanKonversi::select('harga_jual_konversi')->where('id_produk', $id_produk)->where('id_satuan', $satuan_produk[0])->first()->harga_jual_konversi;
                $harga_jual = $harga_jual_konversi;        
            }
        }else{

            $satuan_id = $produk[4];
            $satuan_dasar = $produk[4];
            $nama_satuan = Satuan::select('nama_satuan')->where('id', $satuan_id)->first()->nama_satuan;
            $cek_harga = $this->cekHargaProdukPromo($id_produk);
            if ($cek_harga == ""){
                $harga_jual = $this->cekHargaProduk($produk);
            }else{
                $harga_jual = $cek_harga;
            }
        }


        if ($harga_jual == '' || $harga_jual == 0) {

            $respons['harga_jual'] = $harga_jual;
            return response()->json($respons);

        } else {

            $data_tbs = EditTbsPenjualan::where('id_produk', $id_produk)
            ->where('id_penjualan_pos', $id)->where('warung_id', Auth::user()->id_warung);

            //JIKA PRODUK YG DIPILIH SUDAH ADA DI TBS
            if ($data_tbs->count() > 0) {

                $jumlah_produk = $data_tbs->first()->jumlah_produk + $request->jumlah_produk;

                $subtotal_edit = ($jumlah_produk * $harga_jual) - $data_tbs->first()->potongan;

                $data_tbs->update([
                    'jumlah_produk' => $jumlah_produk, 
                    'satuan_id'     => $satuan_id,
                    'satuan_dasar'  => $satuan_dasar,
                    'harga_produk'  => $harga_jual,
                    'subtotal'      => $subtotal_edit,
                ]);

                $subtotal = $request->jumlah_produk * $data_tbs->first()->harga_produk;

                $respons['id_edit_tbs_penjualans'] = $data_tbs->first()->id_edit_tbs_penjualans;
                $respons['jumlah_produk']          = $jumlah_produk;
                $respons['subtotal']               = $subtotal;
                $respons['satuan']                 = $nama_satuan;
                $respons['harga_produk']           = $harga_jual;
                $respons['id_produk']              = $id_produk;
                $respons['satuan_id']              = $satuan_id;

                $respons['subtotalKeseluruhan']    = $subtotal_edit;

                return response()->json($respons);

            } else {
                $penjualan    = PenjualanPos::find($id);
                $subtotal     = $request->jumlah_produk * $harga_jual;
                $tbspenjualan = EditTbsPenjualan::create([
                    'id_penjualan_pos' => $id,
                    'session_id'       => $session_id,
                    'satuan_id'        => $satuan_id,
                    'satuan_dasar'     => $satuan_dasar,
                    'id_produk'        => $id_produk,
                    'jumlah_produk'    => $request->jumlah_produk,
                    'harga_produk'     => $harga_jual,
                    'subtotal'         => $subtotal,
                    'warung_id'        => Auth::user()->id_warung,
                ]);

                $respons['id_edit_tbs_penjualans'] = $tbspenjualan->id_edit_tbs_penjualans;
                $respons['id_penjualan_pos']       = $id;
                $respons['nama_produk']            = $nama_produk;
                $respons['kode_produk']            = $tbspenjualan->produk->kode_barang;
                $respons['satuan']                 = $tbspenjualan->satuan->nama_satuan;
                $respons['jumlah_produk']          = $request->jumlah_produk;
                $respons['harga_produk']           = $harga_jual;
                $respons['potongan']               = 0;
                $respons['subtotal']               = $subtotal;
                $respons['id_produk']              = $id_produk;
                $respons['satuan_id']              = $satuan_id;
                $respons['level_harga']           = $this->cekLevelHarga($harga_jual,$tbspenjualan->produk->harga_jual);
                $respons['produk']                 = $id_produk . "|" . $nama_produk . "|" . $harga_jual;

                return response()->json($respons);
            }
        }
    }

    // proses edit jumlah edit tbs penjualan
    public function prosesEditJumlahEditTbsPenjualan(Request $request)
    {

        $tbs_penjualan = EditTbsPenjualan::find($request->id_tbs);

        $subtotal = ($tbs_penjualan->harga_produk * $request->jumlah_produk) - $tbs_penjualan->potongan;

        $tbs_penjualan->update(['jumlah_produk' => $request->jumlah_produk, 'subtotal' => $subtotal]);

        $respons['subtotal'] = $subtotal;

        return response()->json($respons);
    }

    public function prosesEditPotonganEditTbsPenjualan(Request $request)
    {
        $tbs_penjualan = EditTbsPenjualan::find($request->id_tbs);

        $total = $tbs_penjualan->jumlah_produk * $tbs_penjualan->harga_produk;

        $potongan_produk = $this->cekPotongan($request->potongan_produk, $tbs_penjualan->harga_produk, $tbs_penjualan->jumlah_produk);

        if ($potongan_produk == '') {

            $respons['status'] = 0;

            return response()->json($respons);

        } else if ($potongan_produk > $total) {

            $respons['status'] = 1;

            return response()->json($respons);

        } else {

            $subtotal = ($tbs_penjualan->jumlah_produk * $tbs_penjualan->harga_produk) - $potongan_produk;

            $tbs_penjualan->update(['potongan' => $potongan_produk, 'subtotal' => $subtotal]);

            $potongan            = $this->tampilPotongan($potongan_produk, $tbs_penjualan->jumlah_produk, $tbs_penjualan->harga_produk);
            $respons['potongan'] = $potongan;
            $respons['subtotal'] = $subtotal;

            return response()->json($respons);
        }

    }

    public function prosesEditHargaEditTbsPenjualan(Request $request){

        $tbs_penjualan = EditTbsPenjualan::find($request->id_tbs);
        if ($request->level_harga_produk == 1) {
           $harga_produk = $tbs_penjualan->produk->harga_jual;
       }else{
           $harga_produk = $tbs_penjualan->produk->harga_jual2;
       }

       $subtotal = ($harga_produk * $tbs_penjualan->jumlah_produk) - $tbs_penjualan->potongan;

       $tbs_penjualan->update(['harga_produk' => $harga_produk, 'subtotal' => $subtotal]);

       $respons['subtotal']      = $subtotal;
       $respons['harga_produk']      = $harga_produk;
       $respons['potongan']      = $this->tampilPotongan($tbs_penjualan->potongan, $tbs_penjualan->jumlah_produk, $harga_produk);

       return response()->json($respons);
   }

   public function prosesHapusEditTbsPenjualan($id)
   {

    if (!EditTbsPenjualan::destroy($id)) {
        return 0;
    } else {
        return response(200);
    }

}

public function proses_batal_edit_penjualan($id)
{

    $data_tbs_penjualan = EditTbsPenjualan::where('id_penjualan_pos', $id)->where('warung_id', Auth::user()->id_warung)->delete();

    return response(200);
}

public function tampilPotongan($potongan_produk, $jumlah_produk, $harga_produk)
{

    $potongan_persen = ($potongan_produk / ($jumlah_produk * $harga_produk)) * 100;

    if ($potongan_produk > 0) {
        $potongan = number_format($potongan_produk, 2, ',', '.') . " (" . round($potongan_persen, 2) . "%)";
    } else {
        $potongan = number_format($potongan_produk, 0, ',', '.');
    }

    return $potongan;

}

public function cekLevelHarga($harga_produk,$harga_jual)
{
    if ($harga_produk == $harga_jual) {                
        $level_harga = 1;
    }else{                
        $level_harga = 2;
    }
    return $level_harga;
}

public function cetakPeriode($dari_tanggal, $sampai_tanggal)
{
    //SETTING APLIKASI
    $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();
    $data_warung = Warung::where('id', Auth::user()->id_warung)->first();

    $penjualans = PenjualanPos::QueryCetakPeriode($dari_tanggal, $sampai_tanggal)->get();
    $total_penjualan = 0;
    foreach ($penjualans as $penjualan) {
        $total_penjualan += $penjualan->total;
    }

    // return $penjualan;
    return view('penjualan.cetak_periode', ['penjualans' => $penjualans, 'setting_aplikasi' => $setting_aplikasi, 'data_warung' => $data_warung, 'dari_tanggal' => $dari_tanggal, 'sampai_tanggal' => $sampai_tanggal, 'total_penjualan' => $total_penjualan])->with(compact('html'));
}

public function cetakBesar($id)
{
    //SETTING APLIKASI
    $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

    $penjualan = PenjualanPos::QueryCetak($id)->first();

    if ($penjualan['pelanggan_id'] == '0') {
        $nama_pelanggan   = 'Umum';
        $alamat_pelanggan = '-';
    } else {
        $nama_pelanggan   = $penjualan['pelanggan'];
        $alamat_pelanggan = $penjualan['alamat_pelanggan'];
    }

    $detail_penjualan = DetailPenjualanPos::with(['produk','satuan'])->where('id_penjualan_pos', $penjualan['id'])->get();
    $terbilang        = $this->kekata($penjualan->total);
    $subtotal         = 0;
    foreach ($detail_penjualan as $detail_penjualans) {
        $subtotal += $detail_penjualans->subtotal;

    }

    return view('penjualan.cetak_besar', ['penjualan' => $penjualan, 'detail_penjualan' => $detail_penjualan, 'subtotal' => $subtotal, 'terbilang' => $terbilang, 'nama_pelanggan' => $nama_pelanggan, 'alamat_pelanggan' => $alamat_pelanggan, 'setting_aplikasi' => $setting_aplikasi])->with(compact('html'));
}

public function cetakKecil($id)
{
    //SETTING APLIKASI
    $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

    $penjualan = PenjualanPos::QueryCetak($id)->first();

    if ($penjualan['pelanggan_id'] == '0') {
        $nama_pelanggan = 'Umum';
    } else {
        $nama_pelanggan = $penjualan['pelanggan'];
    }

    $detail_penjualan = DetailPenjualanPos::with('produk')->where('id_penjualan_pos', $penjualan['id'])->get();
    $potongan         = $penjualan['potongan'];
    $subtotal         = 0;
    foreach ($detail_penjualan as $detail_penjualans) {
        $subtotal += $detail_penjualans->jumlah_produk * $detail_penjualans->harga_produk;
        $potongan += $detail_penjualans->potongan;

    }

    $footer_struk = Warung::select('footer_struk')->where('id', Auth::user()->id_warung)->first()->footer_struk;

    return view('penjualan.cetak_kecil', ['penjualan' => $penjualan, 'detail_penjualan' => $detail_penjualan, 'subtotal' => $subtotal, 'nama_pelanggan' => $nama_pelanggan, 'potongan' => $potongan, 'setting_aplikasi' => $setting_aplikasi, 'footer_struk' => $footer_struk])->with(compact('html'));
}

public function cekSettingPenjualanPos()
{

    $user_warung = Auth::user()->id_warung;
    $settings    = SettingPenjualanPos::where('id_warung', $user_warung);

    if ($settings->count() == 0) {

        $respons['status'] = 0;
        return response()->json($respons);

    } else {
        $respons['status']        = 1;
        $respons['jumlah_produk'] = $settings->first()->jumlah_produk;
        $respons['stok']          = $settings->first()->stok;
        $respons['harga_jual']    = $settings->first()->harga_jual;
        return response()->json($respons);
    }
}

public function settingPenjualanPos(Request $request)
{

    $user_warung = Auth::user()->id_warung;
    $settings    = SettingPenjualanPos::where('id_warung', $user_warung);

    if ($settings->count() == 0) {

        SettingPenjualanPos::create(['jumlah_produk' => $request->jumlah_produk, 'stok' => $request->stok, 'harga_jual' => $request->harga_jual, 'id_warung' => $user_warung]);

    } else {
        $settings->update(['jumlah_produk' => $request->jumlah_produk, 'stok' => $request->stok, 'harga_jual' => $request->harga_jual]);
    }

    return response(200);

}

public function kekata($x)
{
    $x     = abs($x);
    $angka = array("", "satu", "dua", "tiga", "empat", "lima",
        "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($x < 12) {
        $temp = " " . $angka[$x];
    } else if ($x < 20) {
        $temp = $this->kekata($x - 10) . " belas";
    } else if ($x < 100) {
        $temp = $this->kekata($x / 10) . " puluh" . $this->kekata($x % 10);
    } else if ($x < 200) {
        $temp = " seratus" . $this->kekata($x - 100);
    } else if ($x < 1000) {
        $temp = $this->kekata($x / 100) . " ratus" . $this->kekata($x % 100);
    } else if ($x < 2000) {
        $temp = " seribu" . $this->kekata($x - 1000);
    } else if ($x < 1000000) {
        $temp = $this->kekata($x / 1000) . " ribu" . $this->kekata($x % 1000);
    } else if ($x < 1000000000) {
        $temp = $this->kekata($x / 1000000) . " juta" . $this->kekata($x % 1000000);
    } else if ($x < 1000000000000) {
        $temp = $this->kekata($x / 1000000000) . " milyar" . $this->kekata(fmod($x, 1000000000));
    } else if ($x < 1000000000000000) {
        $temp = $this->kekata($x / 1000000000000) . " trilyun" . $this->kekata(fmod($x, 1000000000000));
    }
    return $temp;
}

public function cekSubtotalTbsPenjualan()
{
    $session_id          = session()->getId();
    $user_warung         = Auth::user()->id_warung;
    $TbsPenjualan        = new TbsPenjualan();
    $subtotal            = $TbsPenjualan->subtotalTbs($user_warung, $session_id);
    $respons['subtotal'] = $subtotal;

    return response()->json($respons);
}

    //DOWNLOAD EXCEL - LAPORAN LABA KOTOR /PELANGGAN
public function downloadExcelPenjualan(Request $request, $id_penjualan)
{

    $data_tbs_penjualan_pos = DetailPenjualanPos::downloadPenjualan($id_penjualan);

    Excel::create('Data Export Penjualan', function ($excel) use ($request, $data_tbs_penjualan_pos) {
    // Set property
        $excel->sheet('Data Export Penjualan', function ($sheet) use ($request, $data_tbs_penjualan_pos) {

            $row = 1;
            $sheet->row($row, [
                'Kode Produk',
                'Jumlah Produk',
                'Satuan Produk',
                'Harga Produk',
                'Subtotal',
                'Tax',
                'Potongan',
            ]);

            foreach ($data_tbs_penjualan_pos->get() as $data_tbs_penjualan_poss) {
                $sheet->row(++$row, [
                    $data_tbs_penjualan_poss->kode_barang,
                    $data_tbs_penjualan_poss->jumlah_produk,
                    $data_tbs_penjualan_poss->nama_satuan,
                    $data_tbs_penjualan_poss->harga_produk,
                    $data_tbs_penjualan_poss->subtotal,
                    $data_tbs_penjualan_poss->tax,
                    $data_tbs_penjualan_poss->potongan,
                ]);

            }

        });
    })->download('xls');
}

public function otoritasPenjualan(){

    if (Laratrust::can('edit_penjualan')) {
        $edit_penjualan = 1;
    }else{
        $edit_penjualan = 0;            
    }
    if (Laratrust::can('hapus_penjualan')) {
        $hapus_penjualan = 1;
    }else{
        $hapus_penjualan = 0;            
    }
    $respons['edit_penjualan'] = $edit_penjualan;
    $respons['hapus_penjualan'] = $hapus_penjualan;

    return response()->json($respons);
}

public function editSatuan($request, $db){

    $satuan_konversi = explode("|", $request->satuan_produk);
    $edit_tbs_penjualan = $db::find($request->id_tbs);

    $subtotal = ($edit_tbs_penjualan->jumlah_produk * $satuan_konversi[5]) - $edit_tbs_penjualan->potongan;

    $edit_tbs_penjualan->update(['satuan_id' => $satuan_konversi[0], 'harga_produk' => $satuan_konversi[5], 'subtotal' => $subtotal]);

    $respons['harga_produk'] = $satuan_konversi[5];
    $respons['nama_satuan']     = $satuan_konversi[1];
    $respons['satuan_id']     = $satuan_konversi[0];
    $respons['subtotal']     = $subtotal;

    return $respons;
}

public function editSatuanTbsPenjualan(Request $request){

    $db = 'App\TbsPenjualan';
    $respons = $this->editSatuan($request, $db);

    return response()->json($respons);
}

public function editSatuanEditTbsPenjualan(Request $request){

    $db = 'App\EditTbsPenjualan';
    $respons = $this->editSatuan($request, $db);

    return response()->json($respons);
}

public function simpanTbsPenjualan(Request $request){

    $session_id    = session()->getId();
    $user_warung   = Auth::user()->id_warung;

    $antrian = Antrian::select('no_antrian')->where('warung_id', $user_warung)->where('session_id', $session_id)->orderBy('id', 'DESC');
    if ($antrian->count() > 0) {
        $no_antrian = $antrian->first()->no_antrian + 1;
    }else{
        $no_antrian = 1;
    }

    $newAntrian = Antrian::create(['no_antrian' => $no_antrian, 'warung_id' => $user_warung, 'pelanggan_id' => $request->pelanggan, 'session_id' => $session_id]);

    $tbs_penjualan = TbsPenjualan::where('warung_id', $user_warung)->where('session_id', $session_id)->whereNull('no_antrian');

    $tbs_penjualan->update(['no_antrian' => $no_antrian]);

    $pelanggan = $newAntrian->pelanggan_id == 0 ? 'Umum' : $newAntrian->pelanggan->name;

    $respons['id'] = $newAntrian->id;
    $respons['no_antrian'] = $no_antrian;
    $respons['pelanggan'] = $pelanggan;

    return $respons;
}

public function getAntrian(Request $request){

    $session_id    = session()->getId();
    $user_warung   = Auth::user()->id_warung;
    $antrian = Antrian::with('pelanggan')->where('warung_id', $user_warung)->where('session_id',$session_id)->paginate(10);
    $array = [];

    foreach ($antrian as $antrians) {

        $total_belanja = TbsPenjualan::select([DB::raw('SUM(subtotal) as subtotal')])->where('warung_id', $user_warung)->where('session_id', $session_id)->where('no_antrian',$antrians->no_antrian)->first()->subtotal;

        $pelanggan = $antrians->pelanggan_id == 0 ? 'Umum' : $antrians->pelanggan->name;

        array_push($array, [
            'id'            => $antrians->id,
            'no_antrian'    => $antrians->no_antrian,
            'pelanggan_id'  => $antrians->pelanggan_id,
            'pelanggan'     => $pelanggan,
            'total_belanja' => number_format($total_belanja, 0, ',', '.')
        ]);
    }
    $url                 = 'penjualan/get-antrian-penjualan/';
    $respons             = $this->paginationData($antrian, $array, $url);

    return response()->json($respons);
}

public function pilihAntrian(Request $request){

    $session_id = session()->getId();
    $warung_id = Auth::user()->id_warung;
    $tbs_penjualan = TbsPenjualan::where('session_id',$session_id)->where('warung_id',$warung_id)->where('no_antrian',$request->no_antrian);
    $tbs_penjualan->update(['no_antrian' => null]);
    $delete_antrian = Antrian::where('session_id',$session_id)->where('warung_id',$warung_id)->where('no_antrian',$request->no_antrian)->delete();

}

public function deleteAntrian($id){

    $antrian = Antrian::where('id',$id);
    $tbs_penjualan = TbsPenjualan::where('session_id',$antrian->first()->session_id)->where('warung_id',$antrian->first()->warung_id)->where('no_antrian',$antrian->first()->no_antrian)->delete();
    $antrian->delete();

}

}
