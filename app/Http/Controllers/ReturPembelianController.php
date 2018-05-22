<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Hpp;
use App\Barang;
use App\Suplier;
use App\Pembelian;
use App\DetailReturPembelian;
use App\TbsReturPembelian;
use App\ReturPembelian;
use App\TransaksiKas;
use App\SatuanKonversi;
use Auth;

class ReturPembelianController extends Controller
{

    public function supplier(){
        $session_id = session()->getId();

        $data_tbs   = TbsReturPembelian::select('supplier')->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung);

        if ($data_tbs->count() > 0) {
            $suplier = Suplier::select('id', 'nama_suplier')->where('id', $data_tbs->first()->supplier)->get();
        }else{
            $suplier = Suplier::select('id', 'nama_suplier')->where('warung_id', Auth::user()->id_warung)->get();
        }

        $array     = [];
        foreach ($suplier as $supliers) {
            array_push($array, [
                'id'             => $supliers->id,
                'nama_suplier' => $supliers->nama_suplier]);
        }

        return response()->json($array);

    }

    // DATA PAGINTION
    public function dataPagination($retur_pembelian, $array, $no_faktur, $url)
    {

        //DATA PAGINATION
        $respons['current_page']   = $retur_pembelian->currentPage();
        $respons['data']           = $array;
        $respons['no_faktur']      = $no_faktur;
        $respons['first_page_url'] = url($url . '?page=' . $retur_pembelian->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $retur_pembelian->lastPage();
        $respons['last_page_url']  = url($url . '?page=' . $retur_pembelian->lastPage());
        $respons['next_page_url']  = $retur_pembelian->nextPageUrl();
        $respons['path']           = url($url);
        $respons['per_page']       = $retur_pembelian->perPage();
        $respons['prev_page_url']  = $retur_pembelian->previousPageUrl();
        $respons['to']             = $retur_pembelian->perPage();
        $respons['total']          = $retur_pembelian->total();
        //DATA PAGINATION

        return $respons;
    }

    public function foreachTbs($data_tbss, $session_id, $db){

        $array = [];
        foreach ($data_tbss as $data_tbs) {

            $diskon_persen = ($data_tbs->potongan / ($data_tbs->jumlah_retur * $data_tbs->harga_produk)) * 100;

            $ppn = $db::select('ppn')->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->where('ppn', '!=', '')->limit(1);

            if ($ppn->count() > 0) {

                $ppn_produk = $ppn->first()->ppn;
                if ($data_tbs->tax == 0) {
                    $tax_persen = 0;
                } else {
                    if ($data_tbs->ppn == "Include") {
                        $tax_kembali = $data_tbs->subtotal - $data_tbs->tax;
                        //tax untuk mendapatkan 1,1
                        $tax_format = $data_tbs->subtotal / $tax_kembali - 1;
                        $tax_persen = $tax_format * 100;

                    } else if ($data_tbs->ppn == "Exclude") {
                        $tax_persen = ($data_tbs->tax * 100) / ($data_tbs->jumlah_retur * $data_tbs->harga_produk - $data_tbs->potongan);
                    }
                }
            } else {
                $ppn_produk = "";
                $tax_persen = 0;
            }

            array_push($array, [
                'data_tbs'          => $data_tbs,
                'nama_satuan'       => strtoupper($data_tbs->nama_satuan),
                'potongan_persen'   => $diskon_persen,
                'ppn_produk'        => $ppn_produk,
                'tax_persen'        => $tax_persen,
                ]);
        }

        return $array;
    }


    public function cekPotongan($potongan, $harga_produk, $jumlah_produk)
    {
        $cek_potongan = substr_count($potongan, '%'); 
        // UNTUK CEK APAKAH ADA STRING "%" atau maksudnya untuk cek apakah pot. dalam bentuk persen atau tidak

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

    // VIEW RETUR
    public function view()
    {
        $returPembelians = ReturPembelian::dataReturPembelian()->paginate(10);
        $array            = array();
        foreach ($returPembelians as $returPembelian) {
            array_push($array, [
                'id'         => $returPembelian->id,
                'no_faktur_retur'   => $returPembelian->no_faktur_retur,
                'waktu'             => $returPembelian->Waktu,
                'suplier'           => $returPembelian->nama_suplier,
                'total'             => $returPembelian->total,
                'total_bayar'       => $returPembelian->total_bayar,
                'potong_hutang'     => $returPembelian->potong_hutang,
                'potongan'     => $returPembelian->potongan,
                ]);
        }
        $no_faktur_retur = "";
        $url     = '/retur-pembelian/view';
        $respons = $this->dataPagination($returPembelians, $array, $no_faktur_retur, $url);
        return response()->json($respons);
    }

    // PENCARIAN RETUR
    public function pencarian(Request $request)
    {
        $search = $request->search;
        $returPembelians = ReturPembelian::dataReturPembelian()
        ->where(function ($query) use ($search) {
            $query->orwhere('retur_pembelians.no_faktur_retur', 'LIKE', '%' . $search . '%')
            ->orwhere('supliers.nama_suplier', 'LIKE', '%' . $search . '%');
        })->paginate(10);
        $array            = array();
        foreach ($returPembelians as $returPembelian) {
            array_push($array, [
                'id'         => $returPembelian->id,
                'no_faktur_retur'   => $returPembelian->no_faktur_retur,
                'waktu'             => $returPembelian->Waktu,
                'suplier'           => $returPembelian->nama_suplier,
                'total'             => $returPembelian->total,
                'total_bayar'       => $returPembelian->total_bayar,
                'potong_hutang'     => $returPembelian->potong_hutang,
                'potongan'     => $returPembelian->potongan,
                ]);
        }
        $no_faktur_retur = "";
        $url     = '/retur-pembelian/view';
        $respons = $this->dataPagination($returPembelians, $array, $no_faktur_retur, $url);
        return response()->json($respons);
    }

    // VIEW TBS
    public function viewTbs()
    {
        $session_id  = session()->getId();
        $no_faktur   = '';
        $user_warung = Auth::user()->id_warung;

        $tbs_retur = TbsReturPembelian::dataTransaksiTbsReturPembelian($session_id, $user_warung)
        ->orderBy('tbs_retur_pembelians.id_tbs_retur_pembelian', 'desc')->paginate(10);
        
        $db = "App\TbsReturPembelian";
        $array = $this->foreachTbs($tbs_retur, $session_id, $db);

        $url     = '/retur-pembelian/view-tbs';
        $respons = $this->dataPagination($tbs_retur, $array, $no_faktur, $url);

        return response()->json($respons);
    }

    // PENCARIAN TBS
    public function pencarianTbs(Request $request)
    {
        $session_id  = session()->getId();
        $no_faktur   = '';
        $user_warung = Auth::user()->id_warung;
        $search = $request->search;

        $tbs_retur = TbsReturPembelian::dataTransaksiTbsReturPembelian($session_id, $user_warung)
        ->where(function ($query) use ($search) {
            $query->orwhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
            ->orwhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
            ->orwhere('satuans.nama_satuan', 'LIKE', '%' . $search . '%');
        })->orderBy('tbs_retur_pembelians.id_tbs_retur_pembelian', 'desc')->paginate(10);
        
        $db = "App\TbsReturPembelian";
        $array = $this->foreachTbs($tbs_retur, $session_id, $db);

        $url     = '/retur-pembelian/view-tbs';
        $respons = $this->dataPagination($tbs_retur, $array, $no_faktur, $url);

        return response()->json($respons);
    }


    public function getSubtotal()
    {
        $session_id  = session()->getId();
        $user_warung = Auth::user()->id_warung;

        $subtotal            = TbsReturPembelian::subtotalTbs($user_warung, $session_id);
        $respons['subtotal'] = $subtotal;

        return response()->json($respons);
    }

    // VIEW DATA PEMBELIAAN
    public function dataPembelian(Request $request)
    {
        $no_faktur   = '';
        $warung_id = Auth::user()->id_warung;

        $pembelians = Pembelian::dataPembelian($request->supplier, $warung_id)        
        ->groupBy('detail_pembelians.id_produk')
        ->orderBy('pembelians.created_at', 'asc')
        ->paginate(10);
        $array = [];
        foreach ($pembelians as $pembelian) {
            $stok_produk = Hpp::stok_produk($pembelian->id_produk);

            array_push($array, [
                'pembelian'     => $pembelian,
                'stok_produk'   => $stok_produk
                ]);
        }

        $url     = '/retur-pembelian/data-pembelian';
        $respons = $this->dataPagination($pembelians, $array, $no_faktur, $url);

        return response()->json($respons);
    }

    // PENCARIAN DATA PEMBELIAN
    public function pencarianDataPembelian(Request $request)
    {
        $no_faktur   = '';
        $warung_id = Auth::user()->id_warung;        
        $search = $request->search;

        $pembelians = Pembelian::dataPembelian($request->supplier, $warung_id)
        ->where(function ($query) use ($search) {
            $query->orwhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
            ->orwhere('satuans.nama_satuan', 'LIKE', '%' . $search . '%');
        })->groupBy('detail_pembelians.id_produk')->orderBy('pembelians.created_at', 'asc')->paginate(10);

        $array = [];
        foreach ($pembelians as $pembelian) {
            $stok_produk = Hpp::stok_produk($pembelian->id_produk);

            array_push($array, [
                'pembelian'     => $pembelian,
                'stok_produk'   => $stok_produk
                ]);
        }

        $url     = '/retur-pembelian/data-pembelian';
        $respons = $this->dataPagination($pembelians, $array, $no_faktur, $url);

        return response()->json($respons);
    }


    //PROSES TAMBAH TBS RETUR PEMBELIAN
    public function prosesTbs(Request $request)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {

            $session_id = session()->getId();
            $data_satuan = explode("|", $request->satuan_produk);
            $data_tbs   = TbsReturPembelian::where('id_produk', $request->id_produk)
            ->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung);

            if ($data_tbs->count() > 0) {

                $subtotal_lama = $data_tbs->first()->subtotal;

                $jumlah_produk = $data_tbs->first()->jumlah_retur + $request->jumlah_retur;

                $subtotal_edit = ($jumlah_produk * $request->harga_produk) - $data_tbs->first()->potongan;

                $data_tbs->update(['jumlah_retur' => $jumlah_produk, 'subtotal' => $subtotal_edit, 'harga_produk' => $request->harga_produk, 'satuan_id' => $data_satuan[0], 'satuan_dasar' => $data_satuan[2]]);

                $subtotal = $jumlah_produk * $request->harga_produk;

                $respons['status']        = 1;
                $respons['subtotal_lama'] = $subtotal_lama;
                $respons['subtotal']      = $subtotal;
                return response()->json($respons);

            } else {

                // SUBTOTAL = JUMLAH * HARGA
                $subtotal = $request->jumlah_retur * $request->harga_produk;
                // INSERT TBS PEMBELIAN
                $insertTbs = TbsReturPembelian::create([
                    'id_produk'     => $request->id_produk,
                    'session_id'    => $session_id,
                    'jumlah_retur'  => $request->jumlah_retur,
                    'harga_produk'  => $request->harga_produk,
                    'subtotal'      => $subtotal,
                    'satuan_id'     => $data_satuan[0],
                    'satuan_dasar'  => $data_satuan[2],
                    'supplier'  => $request->supplier,
                    'warung_id'     => Auth::user()->id_warung,
                    ]);

                $respons['status']   = 0;
                $respons['subtotal'] = $subtotal;

                return response()->json($respons);

            }

        }
    }


    public function hapusTbs($id)
    {
        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            $tbs_retur_pembelian = TbsReturPembelian::find($id);
            $respons['subtotal'] = $tbs_retur_pembelian->subtotal;
            $tbs_retur_pembelian->delete();

            return response()->json($respons);
        }
    }

    public function editJumlahReturTbs(Request $request) {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            $tbs_retur_pembelian = TbsReturPembelian::find($request->id_tbs);

            if ($tbs_retur_pembelian->tax == 0) {
                $tax_produk = 0;
            } else {
                // TAX PRODUK = (HARGA * JUMLAH RETUR - POTONGAN) * TAX /100
                $tax_produk = (($tbs_retur_pembelian->harga_produk * $request->jumlah_retur) - $tbs_retur_pembelian->potongan) * $tax / 100;

                // TAX PERSEN = (TAX TBS PEMBELIAN * 100) / (HARGA * JUMLAH RETUR - POTONGAN)
                $tax = ($tbs_retur_pembelian->tax * 100) / $tax_produk;
            }

            if ($tbs_retur_pembelian->ppn == 'Include') {
                // JIKA PPN INCLUDE MAKA PAJAK TIDAK MEMPENGARUHI SUBTOTAL
                $subtotal = ($tbs_retur_pembelian->harga_produk * $request->jumlah_retur) - $tbs_retur_pembelian->potongan;
            } elseif ($tbs_retur_pembelian->ppn == 'Exclude') {
                // JIKA PPN EXCLUDE MAKA PAJAK MEMPENGARUHI SUBTOTOTAL
                $subtotal = (($tbs_retur_pembelian->harga_produk * $request->jumlah_retur) - $tbs_retur_pembelian->potongan) + $tax_produk;
            } else {
                $subtotal = ($tbs_retur_pembelian->harga_produk * $request->jumlah_retur) - $tbs_retur_pembelian->potongan;
            }

            // UPDATE JUMLAH RETUR, SUBTOTAL, DAN TAX
            $tbs_retur_pembelian->update(['jumlah_retur' => $request->jumlah_retur, 'subtotal' => $subtotal, 'tax' => $tax_produk]);
            $respons['subtotal'] = $subtotal;

            return response()->json($respons);
        }
    }


    public function editSatuan($request, $db){

        $satuan_konversi = explode("|", $request->satuan_produk);
        $edit_tbs_penjualan = $db::find($request->id_tbs);
        $harga_beli = Barang::select('harga_beli')->find($request->id_produk)->first()->harga_beli;

        $harga_produk = $harga_beli * ($satuan_konversi[3] * $satuan_konversi[4]);
        $subtotal = ($edit_tbs_penjualan->jumlah_produk * $harga_produk) - $edit_tbs_penjualan->potongan;

        $edit_tbs_penjualan->update(['satuan_id' => $satuan_konversi[0], 'harga_produk' => $harga_produk, 'subtotal' => $subtotal]);

        $respons['harga_produk'] = $harga_produk;
        $respons['nama_satuan']     = $satuan_konversi[1];
        $respons['satuan_id']     = $satuan_konversi[0];
        $respons['subtotal']     = $subtotal;

        return $respons;
    }


    public function editSatuanTbs(Request $request){

        $db = 'App\TbsReturPembelian';
        $respons = $this->editSatuan($request, $db);

        return response()->json($respons);
    }


    public function editPotongan(Request $request)
    {
        $tbs_retur_pembelian = TbsReturPembelian::find($request->id_tbs);

        $total = $tbs_retur_pembelian->jumlah_retur * $tbs_retur_pembelian->harga_produk;

        $potongan_produk = $this->cekPotongan($request->potongan_produk, $tbs_retur_pembelian->harga_produk, $tbs_retur_pembelian->jumlah_retur);

        if ($potongan_produk == '') {

            $respons['status'] = 0;

            return response()->json($respons);

        } else if ($potongan_produk > $total) {

            $respons['status'] = 1;

            return response()->json($respons);

        } else {
            $subtotal = ($tbs_retur_pembelian->jumlah_retur * $tbs_retur_pembelian->harga_produk) - $potongan_produk;

            $tbs_retur_pembelian->update(['potongan' => $potongan_produk, 'subtotal' => $subtotal]);

            $potongan            = $this->tampilPotongan($potongan_produk, $tbs_retur_pembelian->jumlah_retur, $tbs_retur_pembelian->harga_produk);
            $respons['potongan'] = $potongan;
            $respons['subtotal'] = $subtotal;

            return response()->json($respons);
        }
    }


    public function editTax(Request $request)
    {
        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            // SELECT EDIT TBS PEMBELIAN
            $tbs_retur_pembelian = TbsReturPembelian::find($request->id_tax);
            $tax           = substr_count($request->tax_edit_produk, '%'); // UNTUK CEK APAKAH ADA STRING "%"
            // JIKA TIDAK ADA
            if ($tax == 0) {
                if ($request->ppn_produk == 'Exclude') {
                    $tax_produk  = filter_var($request->tax_edit_produk, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); // TAX DAALAM BENTUK NOMINAL
                    $tax_include = 0;
                } else {
                    $tax_produk  = 0;
                    $tax_include = filter_var($request->tax_edit_produk, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); // TAX DAALAM BENTUK NOMINAL;
                }
                $tax_persen = 0;
            } else {
                // JIKA ADA
                $tax_persen = filter_var($request->tax_edit_produk, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); //  PISAH STRING BERDASRAKAN TANDA "%"
                // TAX PRODUK = ((HARGA * JUMLAH) - POTONGAN) * TAX PERSEN / 100
                if ($request->ppn_produk == 'Include') {
                    //perhitungan tax include
                    $default_tax              = 1;
                    $subtotal_kurang_potongan = (($tbs_retur_pembelian->harga_produk * $tbs_retur_pembelian->jumlah_retur) - $tbs_retur_pembelian->potongan);
                    $hasil_tax                = $default_tax + ($tax_persen / 100);
                    $hasil_tax2               = $subtotal_kurang_potongan / $hasil_tax;
                    $tax_include              = $subtotal_kurang_potongan - $hasil_tax2;
                    //perhitungan tax include
                    $tax_produk = 0;
                } elseif ($request->ppn_produk == 'Exclude') {
                    $tax_produk  = (($tbs_retur_pembelian->harga_produk * $tbs_retur_pembelian->jumlah_retur) - $tbs_retur_pembelian->potongan) * $tax_persen / 100;
                    $tax_include = 0;
                }
            }

            if ($tax_produk == '') {
                $tax_produk = 0;
            }

            if ($request->ppn_produk == 'Include') {
                // JIKA PPN INCLUDE MAKA PAJAK TIDAK MEMPENGARUHI SUBTOTAL
                $subtotal = ($tbs_retur_pembelian->harga_produk * $tbs_retur_pembelian->jumlah_retur) - $tbs_retur_pembelian->potongan;
            } elseif ($request->ppn_produk == 'Exclude') {
                // JIKA PPN EXCLUDE MAKA PAJAK MEMPENGARUHI SUBTOTAL
                $subtotal = (($tbs_retur_pembelian->harga_produk * $tbs_retur_pembelian->jumlah_retur) - $tbs_retur_pembelian->potongan) + $tax_produk;
            }
            // UPDATE SUBTOTAL, TAX, PPN
            $tbs_retur_pembelian->update(['subtotal' => $subtotal, 'tax' => $tax_produk, 'tax_include' => $tax_include, 'ppn' => $request->ppn_produk]);
            $nama_barang = $tbs_retur_pembelian->TitleCaseBarang; // TITLE CASH

            $respons['subtotal'] = $subtotal;

            return response()->json($respons);

        }
    }


    public function batalRetur() {
        $session_id         = session()->getId();
        $retur_pembelian = TbsReturPembelian::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->delete();

        return response(200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
        // START TRANSAKSI
            DB::beginTransaction();
            $warung_id  = Auth::user()->id_warung;
            $session_id = session()->getId();
            $no_faktur  = ReturPembelian::no_faktur($warung_id);

            $tbs_retur = TbsReturPembelian::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung);

            if ($tbs_retur->count() == 0) {

                return $tbs_retur->count();

            } else {
            //INSERT RETUR PEMBELIAN
                $total = $request->total_akhir - $request->potong_hutang;
                $retur = ReturPembelian::create([
                    'no_faktur_retur'   => $no_faktur,
                    'suplier_id'        => $tbs_retur->first()->supplier,
                    'total'             => $total,
                    'total_bayar'       => $request->total_akhir,
                    'potongan'          => $request->potongan_faktur,
                    'potong_hutang'     => $request->potong_hutang,
                    'warung_id'         => $warung_id,
                    ]);

                foreach ($tbs_retur->get() as $data_tbs) {

                    $stok_produk = Hpp::stok_produk($data_tbs->id_produk);
                    $sisa        = $stok_produk - $data_tbs->jumlah_retur;

                    if ($data_tbs->satuan_id != $data_tbs->satuan_dasar) {

                        $jumlah_konversi = SatuanKonversi::select('jumlah_konversi')->where('warung_id', Auth::user()->id_warung)
                        ->where('id_produk', $data_tbs->id_produk)
                        ->where('id_satuan', $data_tbs->satuan_id)->first()->jumlah_konversi;

                        $jumlah_dasar = SatuanKonversi::select('jumlah_konversi')->where('id_satuan', $data_tbs->satuan_dasar);
                        if ($jumlah_dasar->count() > 0) {
                            $jumlah_konversi_dasar = intval($data_tbs->jumlah_retur) * (intval($jumlah_dasar->first()->jumlah_konversi) * intval($jumlah_konversi));
                        } else {
                            $jumlah_konversi_dasar = intval($data_tbs->jumlah_retur) * intval($jumlah_konversi);
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
                    }else{
                        // INSERT DETAIL
                        $detail = DetailReturPembelian::create([
                            'no_faktur_retur'   => $no_faktur,
                            'id_produk'         => $data_tbs->id_produk,
                            'jumlah_produk'      => $data_tbs->jumlah_retur,
                            'satuan_id'         => $data_tbs->satuan_id,
                            'satuan_dasar'      => $data_tbs->satuan_dasar,
                            'harga_produk'      => $data_tbs->harga_produk,
                            'subtotal'          => $data_tbs->subtotal,
                            'potongan'          => $data_tbs->potongan,
                            'tax'               => $data_tbs->tax,
                            'tax_include'       => $data_tbs->tax_include,
                            'ppn'               => $data_tbs->ppn,
                            'supplier'          => $data_tbs->supplier,
                            'warung_id'         => $data_tbs->warung_id,
                            'created_at'        => $retur->created_at,
                            ]);
                    }
                }

                TransaksiKas::create([
                    'no_faktur'       => $no_faktur,
                    'jenis_transaksi' => 'Retur Pembelian',
                    'jumlah_masuk'    => $request->total_akhir,
                    'kas'             => $request->kas,
                    'warung_id'       => $warung_id
                    ]);
                // HAPUS TBS
                $tbs_retur->delete();

                DB::commit();

                $respons['respons_retur'] = $retur->id;
                return response()->json($respons);
            }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}