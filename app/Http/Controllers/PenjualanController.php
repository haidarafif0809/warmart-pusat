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
use App\TbsPenjualan;
use App\TransaksiKas;
use App\TransaksiPiutang;
use App\User;
use Auth;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function pilihPelanggan()
    {
        $pelanggan = User::where('tipe_user', 3)->get();
        $array     = array(['id' => '', 'nama_pelanggan' => 'SEMUA PELANGGAN'], ['id' => '0', 'nama_pelanggan' => 'Umum']);
        foreach ($pelanggan as $pelanggans) {
            array_push($array, [
                'id'             => $pelanggans->id,
                'nama_pelanggan' => $pelanggans->name]);
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
                'harga'        => $detail_penjualans->harga,
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
        $tbs_penjualan = TbsPenjualan::with(['produk', 'satuan'])->where('warung_id', $user_warung)->where('session_id', $session_id)->orderBy('id_tbs_penjualan', 'desc')->get();
        $array         = array();

        foreach ($tbs_penjualan as $tbs_penjualans) {

            $potongan = $this->tampilPotongan($tbs_penjualans->potongan, $tbs_penjualans->jumlah_produk, $tbs_penjualans->harga_produk);

            array_push($array, [
                'id_tbs_penjualan' => $tbs_penjualans->id_tbs_penjualan,
                'nama_produk'      => $tbs_penjualans->NamaProduk,
                'kode_produk'      => $tbs_penjualans->produk->kode_barang,
                'jumlah_produk'    => $tbs_penjualans->jumlah_produk,
                'satuan'           => $tbs_penjualans->satuan->nama_satuan,
                'harga_produk'     => $tbs_penjualans->harga_produk,
                'potongan'         => $potongan,
                'subtotal'         => $tbs_penjualans->subtotal,
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
                'potongan'         => $potongan,
                'harga_produk'     => $tbs_penjualans['harga_produk'],
                'subtotal'         => $tbs_penjualans['subtotal'],
                'produk'           => $tbs_penjualans['id_produk'] . "|" . title_case($tbs_penjualans['nama_barang']) . "|" . $tbs_penjualans['harga_jual'],
            ]);
        }
        return response()->json($array);
    }

    public function viewEditTbsPenjualan($id)
    {
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;
        $tbs_penjualan = EditTbsPenjualan::with(['produk'])->where('warung_id', $user_warung)->where('id_penjualan_pos', $id)->orderBy('id_edit_tbs_penjualans', 'desc')->paginate(10);
        $array         = array();

        foreach ($tbs_penjualan as $tbs_penjualans) {

            $potongan = $this->tampilPotongan($tbs_penjualans->potongan, $tbs_penjualans->jumlah_produk, $tbs_penjualans->harga_produk);

            array_push($array, [
                'id_edit_tbs_penjualans' => $tbs_penjualans->id_edit_tbs_penjualans,
                'id_penjualan_pos'       => $tbs_penjualans->id_penjualan_pos,
                'nama_produk'            => $tbs_penjualans->NamaProduk,
                'kode_produk'            => $tbs_penjualans->produk->kode_barang,
                'satuan'                 => $tbs_penjualans->produk->satuan->nama_satuan,
                'jumlah_produk'          => $tbs_penjualans->jumlah_produk,
                'harga_produk'           => $tbs_penjualans->harga_produk,
                'potongan'               => $potongan,
                'subtotal'               => $tbs_penjualans->subtotal,
                'produk'                 => $tbs_penjualans->id_produk . "|" . $tbs_penjualans->NamaProduk . "|" . $tbs_penjualans->produk->harga_jual]);
        }

        $url     = '/penjualan/view-edit-tbs-penjualan/' . $id;
        $respons = $this->paginationData($tbs_penjualan, $array, $url);

        return response()->json($respons);
    }

    public function pencarianEditTbsPenjualan(Request $request, $id)
    {
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;
        $tbs_penjualan = EditTbsPenjualan::Pencarian($user_warung, $id, $request)->paginate(10);

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
                'produk'                 => $tbs_penjualans['id_produk'] . "|" . title_case($tbs_penjualans['nama_barang']) . "|" . $tbs_penjualans['harga_jual'],
            ]);
        }

        $url    = '/penjualan/pencarian-edit-tbs-penjualan/' . $id;
        $search = $request->search;

        $respons = $this->paginationPencarianData($tbs_penjualan, $array, $url, $search);

        return response()->json($respons);
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

    public function prosesTambahTbsPenjualan(Request $request)
    {
        $produk      = explode("|", $request->produk);
        $id_produk   = $produk[0];
        $nama_produk = $produk[1];
        $satuan_id   = $produk[4];
        $session_id  = session()->getId();

        $harga_jual = $this->cekHargaProduk($produk);

        if ($harga_jual == '' || $harga_jual == 0) {

            $respons['harga_jual'] = $harga_jual;
            return response()->json($respons);

        } else {

            $data_tbs = TbsPenjualan::where('id_produk', $id_produk)->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung);

//JIKA PRODUK YG DIPILIH SUDAH ADA DI TBS
            if ($data_tbs->count() > 0) {

                $jumlah_produk = $data_tbs->first()->jumlah_produk + $request->jumlah_produk;

                $subtotal_edit = ($jumlah_produk * $data_tbs->first()->harga_produk) - $data_tbs->first()->potongan;

                $data_tbs->update(['jumlah_produk' => $jumlah_produk, 'subtotal' => $subtotal_edit]);

                $subtotal = $request->jumlah_produk * $data_tbs->first()->harga_produk;

                $respons['id_tbs_penjualan']    = $data_tbs->first()->id_tbs_penjualan;
                $respons['jumlah_produk']       = $jumlah_produk;
                $respons['subtotal']            = $subtotal;
                $respons['subtotalKeseluruhan'] = $subtotal_edit;
                return response()->json($respons);

            } else {

                $subtotal = $request->jumlah_produk * $harga_jual;

                $tbspenjualan = TbsPenjualan::create([
                    'session_id'    => $session_id,
                    'satuan_id'     => $satuan_id,
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
                $respons['satuan']           = $tbspenjualan->satuan->nama_satuan;
                $respons['harga_produk']     = $harga_jual;
                $respons['potongan']         = 0;
                $respons['subtotal']         = $subtotal;
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

        $respons['subtotal'] = $subtotal;

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
        $data_tbs_penjualan = TbsPenjualan::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->delete();

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
        $user_warung         = Auth::user()->id_warung;
        $TbsPenjualan        = new EditTbsPenjualan();
        $subtotal            = $TbsPenjualan->subtotalTbs($user_warung, $id);
        $respons['subtotal'] = $subtotal;
        return response()->json([
            "subtotal"  => $subtotal,
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

        $data_produk_penjualan = TbsPenjualan::with('produk')->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung);

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
                            'id_produk'        => $data_tbs->id_produk,
                            'jumlah_produk'    => $data_tbs->jumlah_produk,
                            'harga_produk'     => $data_tbs->harga_produk,
                            'subtotal'         => $data_tbs->subtotal,
                            'potongan'         => $data_tbs->potongan,
                            'warung_id'        => Auth::user()->id_warung,
                        ]);

                    }
                } else {

                    $detail_penjualan = DetailPenjualanPos::create([
                        'id_penjualan_pos' => $penjualan->id,
                        'no_faktur'        => $no_faktur,
                        'satuan_id'        => $data_tbs->satuan_id,
                        'id_produk'        => $data_tbs->id_produk,
                        'jumlah_produk'    => $data_tbs->jumlah_produk,
                        'harga_produk'     => $data_tbs->harga_produk,
                        'subtotal'         => $data_tbs->subtotal,
                        'potongan'         => $data_tbs->potongan,
                        'warung_id'        => Auth::user()->id_warung,
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
                'no_faktur'        => $data_tbs->no_faktur,
                'id_produk'        => $data_tbs->id_produk,
                'satuan_id'        => $data_tbs->satuan_id,
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
                    $sisa             = $stok_produk - $data_tbs->jumlah_produk;

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
                            'id_produk'        => $data_tbs->id_produk,
                            'jumlah_produk'    => $data_tbs->jumlah_produk,
                            'harga_produk'     => $data_tbs->harga_produk,
                            'subtotal'         => $data_tbs->subtotal,
                            'potongan'         => $data_tbs->potongan,
                            'warung_id'        => Auth::user()->id_warung,
                            'created_at'       => $data_penjualan_pos->created_at,
                            'updated_at'       => $data_penjualan_pos->updated_at,
                        ]);

                    }
                } else {

                    $detail_penjualan = DetailPenjualanPos::create([
                        'id_penjualan_pos' => $id,
                        'no_faktur'        => $data_penjualan_pos->no_faktur,
                        'satuan_id'        => $data_tbs->satuan_id,
                        'id_produk'        => $data_tbs->id_produk,
                        'jumlah_produk'    => $data_tbs->jumlah_produk,
                        'harga_produk'     => $data_tbs->harga_produk,
                        'subtotal'         => $data_tbs->subtotal,
                        'potongan'         => $data_tbs->potongan,
                        'warung_id'        => Auth::user()->id_warung,
                        'created_at'       => $data_penjualan_pos->created_at,
                        'updated_at'       => $data_penjualan_pos->updated_at,
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
        $produk     = explode("|", $request->produk);
        $id_produk  = $produk[0];
        $satuan_id  = $produk[4];
        $session_id = session()->getId();

        $harga_jual = $this->cekHargaProduk($produk);

        if ($harga_jual == '' || $harga_jual == 0) {

            $respons['harga_jual'] = $harga_jual;
            return response()->json($respons);

        } else {

            $data_tbs = EditTbsPenjualan::where('id_produk', $id_produk)
                ->where('id_penjualan_pos', $id)->where('warung_id', Auth::user()->id_warung);

//JIKA PRODUK YG DIPILIH SUDAH ADA DI TBS
            if ($data_tbs->count() > 0) {

                $jumlah_produk = $data_tbs->first()->jumlah_produk + $request->jumlah_produk;

                $subtotal_edit = ($jumlah_produk * $data_tbs->first()->harga_produk) - $data_tbs->first()->potongan;

                $data_tbs->update(['jumlah_produk' => $jumlah_produk, 'subtotal' => $subtotal_edit]);

                $subtotal = $request->jumlah_produk * $data_tbs->first()->harga_produk;

                $respons['subtotal'] = $subtotal;
                return response()->json($respons);

            } else {
                $penjualan    = PenjualanPos::find($id);
                $subtotal     = $request->jumlah_produk * $harga_jual;
                $tbspenjualan = EditTbsPenjualan::create([
                    'id_penjualan_pos' => $id,
                    'no_faktur'        => $penjualan->no_faktur,
                    'session_id'       => $session_id,
                    'satuan_id'        => $satuan_id,
                    'id_produk'        => $id_produk,
                    'jumlah_produk'    => $request->jumlah_produk,
                    'harga_produk'     => $harga_jual,
                    'subtotal'         => $subtotal,
                    'warung_id'        => Auth::user()->id_warung,
                ]);

                $respons['subtotal'] = $subtotal;

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

            $respons['subtotal'] = $subtotal;

            return response()->json($respons);
        }

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

        $detail_penjualan = DetailPenjualanPos::with('produk')->where('id_penjualan_pos', $penjualan['id'])->get();
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

        return view('penjualan.cetak_kecil', ['penjualan' => $penjualan, 'detail_penjualan' => $detail_penjualan, 'subtotal' => $subtotal, 'nama_pelanggan' => $nama_pelanggan, 'potongan' => $potongan, 'setting_aplikasi' => $setting_aplikasi])->with(compact('html'));
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
    public function downloadExcel(Request $request, $session_id)
    {

        $data_tbs_penjualan_pos = TbsPenjualan::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung);

        Excel::create('Data Export Penjualan', function ($excel) use ($request, $data_tbs_penjualan_pos) {
            // Set property
            $excel->sheet('Data Export Penjualan', function ($sheet) use ($request, $data_tbs_penjualan_pos) {

                $row = 1;
                $sheet->row($row, [
                    'Session Id',
                    'Satuan Id',
                    'Id Produk',
                    'Jumlah Produk',
                    'Harga Produk',
                    'Subtotal',
                    'Tax',
                    'Potongan',
                    'Warung Id',
                    'Created By',
                    'Updated By',
                    'Created At',
                    'Updated At',
                    'Ppn',
                    'Tax Include',
                ]);

                foreach ($data_tbs_penjualan_pos->get() as $data_tbs_penjualan_poss) {

                    $sheet->row(++$row, [
                        $data_tbs_penjualan_poss->session_id,
                        $data_tbs_penjualan_poss->satuan_id,
                        $data_tbs_penjualan_poss->id_produk,
                        $data_tbs_penjualan_poss->jumlah_produk,
                        $data_tbs_penjualan_poss->harga_produk,
                        $data_tbs_penjualan_poss->subtotal,
                        $data_tbs_penjualan_poss->tax,
                        $data_tbs_penjualan_poss->potongan,
                        $data_tbs_penjualan_poss->warung_id,
                        $data_tbs_penjualan_poss->created_by,
                        $data_tbs_penjualan_poss->updated_by,
                        $data_tbs_penjualan_poss->created_at,
                        $data_tbs_penjualan_poss->updated_at,
                        null,
                        0,

                    ]);

                }

            });
        })->download('xls');
    }

    //DOWNLOAD EXCEL - LAPORAN LABA KOTOR /PELANGGAN
    public function downloadExcelPenjualan(Request $request, $id_penjualan)
    {

        $data_tbs_penjualan_pos = DetailPenjualanPos::select('detail_penjualan_pos.no_faktur', 'satuans.nama_satuan', 'barangs.kode_barang', 'detail_penjualan_pos.jumlah_produk', 'detail_penjualan_pos.harga_produk', 'detail_penjualan_pos.subtotal', 'detail_penjualan_pos.tax', 'detail_penjualan_pos.potongan', 'detail_penjualan_pos.warung_id', 'detail_penjualan_pos.created_by', 'detail_penjualan_pos.updated_by', 'detail_penjualan_pos.created_at', 'detail_penjualan_pos.updated_at')->leftJoin('barangs', 'detail_penjualan_pos.id_produk', '=', 'barangs.id')->leftJoin('satuans', 'detail_penjualan_pos.satuan_id', '=', 'satuans.id')->where('detail_penjualan_pos.id_penjualan_pos', $id_penjualan)->where('detail_penjualan_pos.warung_id', Auth::user()->id_warung);

        Excel::create('Data Export Penjualan', function ($excel) use ($request, $data_tbs_penjualan_pos) {
            // Set property
            $excel->sheet('Data Export Penjualan', function ($sheet) use ($request, $data_tbs_penjualan_pos) {

                $row = 1;
                $sheet->row($row, [
                    'Session Id',
                    'Satuan Id',
                    'Id Produk',
                    'Jumlah Produk',
                    'Harga Produk',
                    'Subtotal',
                    'Tax',
                    'Potongan',
                    'Warung Id',
                    'Created By',
                    'Updated By',
                    'Created At',
                    'Updated At',
                    'Ppn',
                    'Tax Include',
                ]);

                foreach ($data_tbs_penjualan_pos->get() as $data_tbs_penjualan_poss) {

                    $sheet->row(++$row, [
                        $data_tbs_penjualan_poss->no_faktur,
                        $data_tbs_penjualan_poss->nama_satuan,
                        $data_tbs_penjualan_poss->kode_barang,
                        $data_tbs_penjualan_poss->jumlah_produk,
                        $data_tbs_penjualan_poss->harga_produk,
                        $data_tbs_penjualan_poss->subtotal,
                        $data_tbs_penjualan_poss->tax,
                        $data_tbs_penjualan_poss->potongan,
                        $data_tbs_penjualan_poss->warung_id,
                        $data_tbs_penjualan_poss->created_by,
                        $data_tbs_penjualan_poss->updated_by,
                        $data_tbs_penjualan_poss->created_at,
                        $data_tbs_penjualan_poss->updated_at,
                        null,
                        0,
                    ]);

                }

            });
        })->download('xls');
    }

}
