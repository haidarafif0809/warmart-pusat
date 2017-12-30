<?php

namespace App\Http\Controllers;

use App\Barang;
use App\DetailPenjualanPos;
use App\Kas;
use App\PenjualanPos;
use App\TbsPenjualan;
use App\TransaksiKas;
use App\TransaksiPiutang;
use App\User;
use App\EditTbsPenjualan;
use Auth;
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
        $array     = array();
        foreach ($pelanggan as $pelanggans) {
            array_push($array, [
                'id'             => $pelanggans->id,
                'nama_pelanggan' => $pelanggans->name]);
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

        //DATA PAGINATION
        $respons['current_page']   = $penjualan->currentPage();
        $respons['data']           = $array;
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


    public function view()
    {
        $user_warung   = Auth::user()->id_warung;
        $penjualan = PenjualanPos::with(['pelanggan','kas','user_edit','user_buat'])->where('warung_id', $user_warung)->orderBy('id', 'desc')->paginate(10);
        $array         = array();

        foreach ($penjualan as $penjualans) {
            array_push($array, [
                'id' => $penjualans->id,
                'waktu'    => $penjualans->Waktu,
                'pelanggan'     => $penjualans->pelanggan->name,
                'status_penjualan'         => $penjualans->status_penjualan,
                'total'         => $penjualans->total,
                'kas'   => $penjualans->kas->nama_kas,
                'potongan' => $penjualans->potongan,
                'tunai' => $penjualans->tunai,
                'kembalian' => $penjualans->kembalian,
                'piutang' => $penjualans->nilai_kredit,
                'jatuh_tempo' => $penjualans->JatuhTempo,
                'user_buat' => $penjualans->user_buat->name,
                'user_edit' => $penjualans->user_edit->name,
                'waktu_edit' => $penjualans->WaktuEdit
            ]);
        }

        $url     = '/penjualan/view';
        $respons = $this->paginationData($penjualan, $array, $url);

        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {
        $user_warung   = Auth::user()->id_warung;
        $penjualan = PenjualanPos::select('penjualan_pos.id AS id', 'penjualan_pos.status_penjualan AS status_penjualan','penjualan_pos.total AS total','users.name AS pelanggan',DB::raw('DATE_FORMAT(penjualan_pos.created_at, "%d/%m/%Y %H:%i:%s") as waktu_jual'))
        ->leftJoin('users', 'users.id', '=', 'penjualan_pos.pelanggan_id')
        ->where('warung_id', $user_warung)
        ->where(function ($query) use ($request) {

            $query->orWhere('penjualan_pos.id', 'LIKE', $request->search . '%')
            ->orWhere('penjualan_pos.status_penjualan', 'LIKE', $request->search . '%')
            ->orWhere('penjualan_pos.total', 'LIKE', $request->search . '%')
            ->orWhere('penjualan_pos.created_at', 'LIKE', $request->search . '%')
            ->orWhere('users.name', 'LIKE', $request->search . '%');

        })->orderBy('penjualan_pos.id', 'desc')->paginate(10);

        $array = array();
        foreach ($penjualan as $penjualans) {

            array_push($array, [
                'id' => $penjualans['id'],
                'waktu'    => $penjualans['waktu_jual'],
                'pelanggan'         => $penjualans['pelanggan'],
                'status_penjualan'     => $penjualans['status_penjualan'],
                'total'         => $penjualans['total']
            ]);
        }

        $url    = '/penjualan/pencarian';
        $search = $request->search;

        $respons = $this->paginationPencarianData($penjualan, $array, $url, $search);

        return response()->json($respons);
    }


    public function viewDetailPenjualan($id)
    {
        $user_warung   = Auth::user()->id_warung;
        $detail_penjualan = DetailPenjualanPos::with(['produk'])->where('warung_id', $user_warung)->where('id_penjualan_pos', $id)->orderBy('id_detail_penjualan_pos', 'desc')->paginate(10);
        $array         = array();

        foreach ($detail_penjualan as $detail_penjualans) {

            $potongan_persen = ($detail_penjualans->potongan / ($detail_penjualans->jumlah_produk * $detail_penjualans->harga_produk)) * 100;

            if ($detail_penjualans->potongan > 0) {
                $potongan = number_format($detail_penjualans->potongan, 0, ',', '.') . ",00 (" . round($potongan_persen, 2) . "%)";
            } else {
                $potongan = number_format($detail_penjualans->potongan, 2, ',', '.');
            }

            array_push($array, [
                'id_detail_penjualan_pos' => $detail_penjualans->id_detail_penjualan_pos,
                'id_penjualan_pos' => $id,
                'nama_produk'      => $detail_penjualans->NamaProduk,
                'kode_produk'      => $detail_penjualans->produk->kode_barang,
                'jumlah_produk'    => $detail_penjualans->jumlah_produk,
                'harga_produk'     => $detail_penjualans->harga_produk,
                'potongan'         => $potongan,
                'subtotal'         => $detail_penjualans->subtotal,
                'produk'           => $detail_penjualans->id_produk . "|" . $detail_penjualans->NamaProduk . "|" . $detail_penjualans->harga_produk]);
        }

        $url     = '/penjualan/view-detail-penjualan/'.$id;
        $respons = $this->paginationData($detail_penjualan, $array, $url);

        return response()->json($respons);
    }

    public function pencarianDetailPenjualan(Request $request,$id)
    {
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;
        $detail_penjualan = DetailPenjualanPos::select('detail_penjualan_pos.id_detail_penjualan_pos AS id_detail_penjualan_pos', 'detail_penjualan_pos.jumlah_produk AS jumlah_produk', 'barangs.nama_barang AS nama_barang', 'barangs.kode_barang AS kode_barang', 'detail_penjualan_pos.id_produk AS id_produk', 'detail_penjualan_pos.potongan AS potongan', 'detail_penjualan_pos.subtotal AS subtotal', 'detail_penjualan_pos.harga_produk AS harga_produk')
        ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualan_pos.id_produk')
        ->where('warung_id', $user_warung)->where('detail_penjualan_pos.id_penjualan_pos', $id)
        ->where(function ($query) use ($request) {

            $query->orWhere('barangs.kode_barang', 'LIKE', $request->search . '%')
            ->orWhere('barangs.nama_barang', 'LIKE', $request->search . '%')
            ->orWhere('detail_penjualan_pos.id_penjualan_pos', 'LIKE', $request->search . '%');

        })->orderBy('detail_penjualan_pos.id_detail_penjualan_pos', 'desc')->paginate(10);

        $array = array();
        foreach ($detail_penjualan as $detail_penjualans) {
            $potongan_persen = ($detail_penjualans['potongan'] / ($detail_penjualans['jumlah_produk'] * $detail_penjualans['harga_produk'])) * 100;

            if ($detail_penjualans['potongan'] > 0) {
                $potongan = number_format($detail_penjualans['potongan'], 0, ',', '.') . ",00 (" . round($potongan_persen, 2) . "%)";
            } else {
                $potongan = number_format($detail_penjualans['potongan'], 2, ',', '.');
            }

            array_push($array, [
                'id_detail_penjualan_pos' => $detail_penjualans['id_detail_penjualan_pos'],
                'id_penjualan_pos' => $id,
                'nama_produk'      => title_case($detail_penjualans['nama_barang']),
                'kode_produk'      => $detail_penjualans['kode_barang'],
                'jumlah_produk'    => $detail_penjualans['jumlah_produk'],
                'potongan'         => $potongan,
                'harga_produk'     => $detail_penjualans['harga_produk'],
                'subtotal'         => $detail_penjualans['subtotal'],
                'produk'           => $detail_penjualans['id_produk'] . "|" . title_case($detail_penjualans['nama_barang']) . "|" . $detail_penjualans->harga_jual]);
        }

        $url    = '/penjualan/pencarian-detail-penjualan/'.$id;
        $search = $request->search;

        $respons = $this->paginationPencarianData($detail_penjualan, $array, $url, $search);

        return response()->json($respons);
    }
    public function viewTbsPenjualan()
    {
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;
        $tbs_penjualan = TbsPenjualan::with(['produk'])->where('warung_id', $user_warung)->where('session_id', $session_id)->orderBy('id_tbs_penjualan', 'desc')->paginate(10);
        $array         = array();

        foreach ($tbs_penjualan as $tbs_penjualans) {
            $potongan_persen = ($tbs_penjualans->potongan / ($tbs_penjualans->jumlah_produk * $tbs_penjualans->harga_produk)) * 100;

            if ($tbs_penjualans->potongan > 0) {
                $potongan = number_format($tbs_penjualans->potongan, 0, ',', '.') . ",00 (" . round($potongan_persen, 2) . "%)";
            } else {
                $potongan = $tbs_penjualans->potongan;
            }

            array_push($array, [
                'id_tbs_penjualan' => $tbs_penjualans->id_tbs_penjualan,
                'nama_produk'      => $tbs_penjualans->NamaProduk,
                'kode_produk'      => $tbs_penjualans->produk->kode_barang,
                'jumlah_produk'    => $tbs_penjualans->jumlah_produk,
                'harga_produk'     => $tbs_penjualans->harga_produk,
                'potongan'         => $potongan,
                'subtotal'         => $tbs_penjualans->subtotal,
                'produk'           => $tbs_penjualans->id_produk . "|" . $tbs_penjualans->NamaProduk . "|" . $tbs_penjualans->produk->harga_jual]);
        }

        $url     = '/penjualan/view-tbs-penjualan';
        $respons = $this->paginationData($tbs_penjualan, $array, $url);

        return response()->json($respons);
    }

    public function pencarianTbsPenjualan(Request $request)
    {
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;
        $tbs_penjualan = TbsPenjualan::select('tbs_penjualans.id_tbs_penjualan AS id_tbs_penjualan', 'tbs_penjualans.jumlah_produk AS jumlah_produk', 'barangs.nama_barang AS nama_barang', 'barangs.kode_barang AS kode_barang', 'tbs_penjualans.id_produk AS id_produk', 'tbs_penjualans.potongan AS potongan', 'tbs_penjualans.subtotal AS subtotal', 'tbs_penjualans.harga_produk AS harga_produk','barangs.harga_jual AS harga_jual')
        ->leftJoin('barangs', 'barangs.id', '=', 'tbs_penjualans.id_produk')
        ->where('warung_id', $user_warung)->where('session_id', $session_id)
        ->where(function ($query) use ($request) {

            $query->orWhere('barangs.kode_barang', 'LIKE', $request->search . '%')
            ->orWhere('barangs.nama_barang', 'LIKE', $request->search . '%');

        })->orderBy('tbs_penjualans.id_tbs_penjualan', 'desc')->paginate(10);

        $array = array();
        foreach ($tbs_penjualan as $tbs_penjualans) {
            $potongan_persen = ($tbs_penjualans['potongan'] / ($tbs_penjualans['jumlah_produk'] * $tbs_penjualans['harga_produk'])) * 100;

            if ($tbs_penjualans['potongan'] > 0) {
                $potongan = number_format($tbs_penjualans['potongan'], 0, ',', '.') . ",00 (" . round($potongan_persen, 2) . "%)";
            } else {
                $potongan = $tbs_penjualans['potongan'];
            }

            array_push($array, [
                'id_tbs_penjualan' => $tbs_penjualans['id_tbs_penjualan'],
                'nama_produk'      => title_case($tbs_penjualans['nama_barang']),
                'kode_produk'      => $tbs_penjualans['kode_barang'],
                'jumlah_produk'    => $tbs_penjualans['jumlah_produk'],
                'potongan'         => $potongan,
                'harga_produk'     => $tbs_penjualans['harga_produk'],
                'subtotal'         => $tbs_penjualans['subtotal'],
                'produk'           => $tbs_penjualans['id_produk'] . "|" . title_case($tbs_penjualans['nama_barang']) . "|" . $tbs_penjualans['harga_jual']
            ]);
        }

        $url    = '/penjualan/pencarian-tbs-penjualan';
        $search = $request->search;

        $respons = $this->paginationPencarianData($tbs_penjualan, $array, $url, $search);

        return response()->json($respons);
    }

    public function viewEditTbsPenjualan($id)
    {
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;
        $tbs_penjualan = EditTbsPenjualan::with(['produk'])->where('warung_id', $user_warung)->where('id_penjualan_pos', $id)->orderBy('id_edit_tbs_penjualans', 'desc')->paginate(10);
        $array         = array();


        foreach ($tbs_penjualan as $tbs_penjualans) {
            $potongan_persen = ($tbs_penjualans->potongan / ($tbs_penjualans->jumlah_produk * $tbs_penjualans->harga_produk)) * 100;

            if ($tbs_penjualans->potongan > 0) {
                $potongan = number_format($tbs_penjualans->potongan, 0, ',', '.') . ",00 (" . round($potongan_persen, 2) . "%)";
            } else {
                $potongan = $tbs_penjualans->potongan;
            }

            array_push($array, [
                'id_edit_tbs_penjualans' => $tbs_penjualans->id_edit_tbs_penjualans,
                'id_penjualan_pos' => $tbs_penjualans->id_penjualan_pos,
                'nama_produk'      => $tbs_penjualans->NamaProduk,
                'kode_produk'      => $tbs_penjualans->produk->kode_barang,
                'jumlah_produk'    => $tbs_penjualans->jumlah_produk,
                'harga_produk'     => $tbs_penjualans->harga_produk,
                'potongan'         => $potongan,
                'subtotal'         => $tbs_penjualans->subtotal,
                'produk'           => $tbs_penjualans->id_produk . "|" . $tbs_penjualans->NamaProduk . "|" . $tbs_penjualans->produk->harga_jual]);
        }

        $url     = '/penjualan/view-edit-tbs-penjualan/'.$id;
        $respons = $this->paginationData($tbs_penjualan, $array, $url);

        return response()->json($respons);
    }



    public function pencarianEditTbsPenjualan(Request $request,$id)
    {
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;
        $tbs_penjualan = EditTbsPenjualan::select('edit_tbs_penjualans.id_edit_tbs_penjualans AS id_edit_tbs_penjualans', 'edit_tbs_penjualans.jumlah_produk AS jumlah_produk', 'barangs.nama_barang AS nama_barang', 'barangs.kode_barang AS kode_barang', 'edit_tbs_penjualans.id_produk AS id_produk', 'edit_tbs_penjualans.potongan AS potongan', 'edit_tbs_penjualans.subtotal AS subtotal', 'edit_tbs_penjualans.harga_produk AS harga_produk','barangs.harga_jual AS harga_jual')
        ->leftJoin('barangs', 'barangs.id', '=', 'edit_tbs_penjualans.id_produk')
        ->where('warung_id', $user_warung)->where('id_penjualan_pos', $id)
        ->where(function ($query) use ($request) {

            $query->orWhere('barangs.kode_barang', 'LIKE', $request->search . '%')
            ->orWhere('barangs.nama_barang', 'LIKE', $request->search . '%');

        })->orderBy('edit_tbs_penjualans.id_edit_tbs_penjualans', 'desc')->paginate(10);

        $array = array();
        foreach ($tbs_penjualan as $tbs_penjualans) {
            $potongan_persen = ($tbs_penjualans['potongan'] / ($tbs_penjualans['jumlah_produk'] * $tbs_penjualans['harga_produk'])) * 100;

            if ($tbs_penjualans['potongan'] > 0) {
                $potongan = number_format($tbs_penjualans['potongan'], 0, ',', '.') . ",00 (" . round($potongan_persen, 2) . "%)";
            } else {
                $potongan = $tbs_penjualans['potongan'];
            }

            array_push($array, [
                'id_edit_tbs_penjualans' => $tbs_penjualans['id_edit_tbs_penjualans'],
                'id_penjualan_pos' => $id,
                'nama_produk'      => title_case($tbs_penjualans['nama_barang']),
                'kode_produk'      => $tbs_penjualans['kode_barang'],
                'jumlah_produk'    => $tbs_penjualans['jumlah_produk'],
                'potongan'         => $potongan,
                'harga_produk'     => $tbs_penjualans['harga_produk'],
                'subtotal'         => $tbs_penjualans['subtotal'],
                'produk'           => $tbs_penjualans['id_produk'] . "|" . title_case($tbs_penjualans['nama_barang']) . "|" . $tbs_penjualans['harga_jual']
            ]);
        }

        $url    = '/penjualan/pencarian-edit-tbs-penjualan/'.$id;
        $search = $request->search;

        $respons = $this->paginationPencarianData($tbs_penjualan, $array, $url, $search);

        return response()->json($respons);
    }


    public function prosesTambahTbsPenjualan(Request $request)
    {
        $produk     = explode("|", $request->produk);
        $id_produk  = $produk[0];
        $harga_jual = $produk[3];
        $satuan_id  = $produk[4];
        $session_id = session()->getId();

        $data_tbs = TbsPenjualan::where('id_produk', $id_produk)
        ->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)
        ->count();

//JIKA PRODUK YG DIPILIH SUDAH ADA DI TBS
        if ($data_tbs > 0) {

            return 0;

        } else {

            $subtotal     = $request->jumlah_produk * $harga_jual;
            $tbspenjualan = TbsPenjualan::create([
                'session_id'    => $session_id,
                'satuan_id'     => $satuan_id,
                'id_produk'     => $id_produk,
                'jumlah_produk' => $request->jumlah_produk,
                'harga_produk'  => $harga_jual,
                'subtotal'      => $subtotal,
                'warung_id'     => Auth::user()->id_warung,
            ]);

            $respons['subtotal'] = $subtotal;

            return response()->json($respons);
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

    public function proses_batal_penjualan(){

        $session_id           = session()->getId();
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

    public function cekDataTbsPenjualan()
    {

        $session_id    = session()->getId();
        $tbs_penjualan = TbsPenjualan::select([DB::raw('SUM(subtotal) as subtotal')])->where('session_id', $session_id);

        if ($tbs_penjualan->first()->subtotal == null or $tbs_penjualan->first()->subtotal == '') {
            $subtotal = 0;
        } else {
            $subtotal = $tbs_penjualan->first()->subtotal;
        }

        $respons['subtotal'] = $subtotal;

        return response()->json($respons);
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
                'jenis_transaksi' => 'PenjualanPos',
                'jumlah_masuk'    => $penjualan->kredit,
                'pelanggan_id'    => $penjualan->pelanggan_id,
                'warung_id'       => $penjualan->warung_id]);
        }

        foreach ($data_produk_penjualan->get() as $data_tbs) {

            if ($data_tbs->produk->hitung_stok == 1) {

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
            }
            else{

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
       return response(200);

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
    $session_id             = session()->getId();
    $data_produk_penjualan = DetailPenjualanPos::where('id_penjualan_pos', $id)->where('warung_id', Auth::user()->id_warung);

    $hapus_semua_edit_tbs_penjualan = EditTbsPenjualan::where('id_penjualan_pos', $id)->where('warung_id', Auth::user()->id_warung)->delete();
    foreach ($data_produk_penjualan->get() as $data_tbs) {
        $detail_penjualan = EditTbsPenjualan::create([
            'session_id'    => $session_id,
            'id_penjualan_pos'  => $id,
            'no_faktur'     => $data_tbs->no_faktur,
            'id_produk'     => $data_tbs->id_produk,
            'satuan_id'     => $data_tbs->satuan_id,
            'jumlah_produk' => $data_tbs->jumlah_produk,
            'harga_produk' => $data_tbs->harga_produk,
            'subtotal' => $data_tbs->subtotal,            
            'tax' => $data_tbs->tax,
            'potongan' => $data_tbs->potongan,
            'warung_id'     => Auth::user()->id_warung,
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
    $user             = Auth::user()->id;

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
            DB::rollBack();
            return 2;
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
            'tanggal_jt_tempo' => $request->jatuh_tempo
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

        if ($data_tbs->produk->hitung_stok == 1) {


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
                ]);

            } 
        }else{

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
        ]);
           
       }
   }

   $data_produk_penjualan_pos = EditTbsPenjualan::where('id_penjualan_pos', $id)->where('warung_id', Auth::user()->id_warung)->delete();
   DB::commit();
   return response(200);
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
public function prosesTambahEditTbsPenjualan(Request $request,$id)
{
    $produk     = explode("|", $request->produk);
    $id_produk  = $produk[0];
    $harga_jual = $produk[3];
    $satuan_id  = $produk[4];
    $session_id = session()->getId();

    $data_tbs = EditTbsPenjualan::where('id_produk', $id_produk)
    ->where('id_penjualan_pos', $id)->where('warung_id', Auth::user()->id_warung)
    ->count();

//JIKA PRODUK YG DIPILIH SUDAH ADA DI TBS
    if ($data_tbs > 0) {

        return 0;

    } else {
        $penjualan = PenjualanPos::find($id);
        $subtotal     = $request->jumlah_produk * $harga_jual;
        $tbspenjualan = EditTbsPenjualan::create([
            'id_penjualan_pos'    => $id,
            'no_faktur'    => $penjualan->no_faktur,
            'session_id'    => $session_id,
            'satuan_id'     => $satuan_id,
            'id_produk'     => $id_produk,
            'jumlah_produk' => $request->jumlah_produk,
            'harga_produk'  => $harga_jual,
            'subtotal'      => $subtotal,
            'warung_id'     => Auth::user()->id_warung,
        ]);

        $respons['subtotal'] = $subtotal;

        return response()->json($respons);
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

public function proses_batal_edit_penjualan($id){

    $data_tbs_penjualan = EditTbsPenjualan::where('id_penjualan_pos', $id)->where('warung_id', Auth::user()->id_warung)->delete();

    return response(200);
}

}
