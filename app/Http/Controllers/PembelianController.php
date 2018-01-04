<?php

namespace App\Http\Controllers;

use App\Barang;use App\DetailPembelian;
use App\EditTbsPembelian;
use App\Kas;
use App\Pembelian;
use App\Suplier;
use App\TbsPembelian;
use App\TransaksiKas;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Html\Builder;

class PembelianController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('user-must-warung');
    }
    public function index(Request $request, Builder $htmlBuilder)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            return view('pembelian.index')->with(compact('html'));
        }
    }

    public function view()
    {
        $pembelian = Pembelian::select('pembelians.id as id', 'pembelians.no_faktur as no_faktur', 'supliers.nama_suplier as nama_suplier', 'pembelians.created_at as created_at', 'pembelians.status_pembelian as status_pembelian', 'pembelians.total as total')->leftJoin('supliers', 'pembelians.suplier_id', '=', 'supliers.id')
            ->where('pembelians.warung_id', Auth::user()->id_warung)->orderBy('pembelians.id')->paginate(10);
        $array = array();
        foreach ($pembelian as $pembelians) {
            array_push($array, [
                'id'               => $pembelians->id,
                'no_faktur'        => $pembelians->no_faktur,
                'waktu'            => $pembelians->getWaktuAttribute(),
                'suplier'          => $pembelians->nama_suplier,
                'status_pembelian' => $pembelians->status_pembelian,
                'total'            => $pembelians->getTotalSeparator()]);
        }

        //DATA PAGINATION
        $respons['current_page']   = $pembelian->currentPage();
        $respons['data']           = $array;
        $respons['first_page_url'] = url('/pembelian/view?page=' . $pembelian->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $pembelian->lastPage();
        $respons['last_page_url']  = url('/pembelian/view?page=' . $pembelian->lastPage());
        $respons['next_page_url']  = $pembelian->nextPageUrl();
        $respons['path']           = url('/pembelian/view');
        $respons['per_page']       = $pembelian->perPage();
        $respons['prev_page_url']  = $pembelian->previousPageUrl();
        $respons['to']             = $pembelian->perPage();
        $respons['total']          = $pembelian->total();
        //DATA PAGINATION
        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {

        $search = $request->search;

        $pembelian = Pembelian::select('pembelians.id as id', 'pembelians.no_faktur as no_faktur', 'supliers.nama_suplier as nama_suplier', 'pembelians.created_at as created_at', 'pembelians.status_pembelian as status_pembelian', 'pembelians.total as total')->leftJoin('supliers', 'pembelians.suplier_id', '=', 'supliers.id')->where('pembelians.warung_id', Auth::user()->id_warung)->orderBy('pembelians.id')
            ->where(function ($query) use ($search) {
                // search
                $query->where('pembelians.status_pembelian', 'LIKE', $search . '%')
                    ->orWhere('pembelians.no_faktur', 'LIKE', $search . '%')
                    ->orWhere('pembelians.total', 'LIKE', $search . '%');
            })->paginate(10);

        $array = array();
        foreach ($pembelian as $pembelians) {
            array_push($array, [
                'id'               => $pembelians->id,
                'no_faktur'        => $pembelians->no_faktur,
                'waktu'            => $pembelians->getWaktuAttribute(),
                'suplier'          => $pembelians->nama_suplier,
                'status_pembelian' => $pembelians->status_pembelian,
                'total'            => $pembelians->total]);
        }

        //DATA PAGINATION
        $respons['current_page']   = $pembelian->currentPage();
        $respons['data']           = $array;
        $respons['first_page_url'] = url('/pembelian/view?page=' . $pembelian->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $pembelian->lastPage();
        $respons['last_page_url']  = url('/pembelian/view?page=' . $pembelian->lastPage());
        $respons['next_page_url']  = $pembelian->nextPageUrl();
        $respons['path']           = url('/pembelian/view');
        $respons['per_page']       = $pembelian->perPage();
        $respons['prev_page_url']  = $pembelian->previousPageUrl();
        $respons['to']             = $pembelian->perPage();
        $respons['total']          = $pembelian->total();
        //DATA PAGINATION
        return response()->json($respons);
    }

    //VIEW DAN PENCARIAN TBS PEMBELIAN
    public function viewTbsPembelian()
    {
        $session_id  = session()->getId();
        $no_faktur = '';
        $user_warung = Auth::user()->id_warung;

        $sum_subtotal = EditTbsPembelian::select(DB::raw('SUM(subtotal) as subtotal'))->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->first();
        $subtotal     = $sum_subtotal->subtotal;

        $kas_default = Kas::where('warung_id', Auth::user()->id_warung)->where('default_kas', 1)->count();
        $kas_pilih   = Kas::where('warung_id', Auth::user()->id_warung)->where('default_kas', 1)->first();

        $tbs_pembelian = TbsPembelian::select('tbs_pembelians.id_tbs_pembelian AS id_tbs_pembelian', 'tbs_pembelians.jumlah_produk AS jumlah_produk', 'barangs.nama_barang AS nama_barang', 'barangs.kode_barang AS kode_barang', 'tbs_pembelians.id_produk AS id_produk', 'tbs_pembelians.harga_produk AS harga_produk', 'tbs_pembelians.potongan AS potongan', 'tbs_pembelians.tax AS tax', 'tbs_pembelians.subtotal AS subtotal', 'tbs_pembelians.ppn AS ppn')->leftJoin('barangs', 'barangs.id', '=', 'tbs_pembelians.id_produk')->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->orderBy('id_tbs_pembelian', 'desc')->paginate(10);
        $array         = array();

        foreach ($tbs_pembelian as $tbs_pembelians) {

            $potongan_persen        = ($tbs_pembelians->potongan / ($tbs_pembelians->jumlah_produk * $tbs_pembelians->harga_produk)) * 100;
            $subtotal_tbs           = $tbs_pembelians->PemisahSubtotal;
            $harga_pemisah          = $tbs_pembelians->PemisahHarga;
            $nama_produk_title_case = $tbs_pembelians->TitleCaseBarang;
            $jumlah_produk          = $tbs_pembelians->PemisahJumlah;

            $ppn = TbsPembelian::select('ppn')->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->where('ppn', '!=', '')->limit(1);
            if ($ppn->count() > 0) {
                $ppn_produk = $ppn->first()->ppn;
                if ($tbs_pembelians->tax == 0) {
                    $tax_persen = 0;
                } else {
                    if ($tbs_pembelians->ppn == "Include") {
                        $tax_kembali = $tbs_pembelians->subtotal - $tbs_pembelians->tax;
                        //tax untuk mendapatkan 1,1
                        $tax_format = $tbs_pembelians->subtotal / $tax_kembali - 1;
                        $tax_persen = $tax_format * 100;

                    } else if ($tbs_pembelians->ppn == "Exclude") {
                        $tax_persen = ($tbs_pembelians->tax * 100) / ($tbs_pembelians->jumlah_produk * $tbs_pembelians->harga_produk - $tbs_pembelians->potongan);
                    }
                }
            } else {
                $ppn_produk = "";
                $tax_persen = 0;
            }

            array_push($array, [
                'id_tbs_pembelian'       => $tbs_pembelians->id_tbs_pembelian,
                'nama_produk'            => $nama_produk_title_case,
                'kode_produk'            => $tbs_pembelians->produk->kode_barang,
                'harga_produk'           => $tbs_pembelians->harga_produk,
                'harga_pemisah'          => $tbs_pembelians->PemisahHarga,
                'jumlah_produk'          => $tbs_pembelians->jumlah_produk,
                'jumlah_produk_pemisah'  => $jumlah_produk,
                'potongan'               => $tbs_pembelians->potongan,
                'potongan_persen'        => $potongan_persen,
                'tax'                    => $tbs_pembelians->tax,
                'ppn_produk'             => $ppn_produk,
                'tax_persen'             => $tax_persen,
                'kas_default'            => $kas_default,
                'kas_pilih'              => $kas_pilih,
                'subtotal'              => $tbs_pembelians->subtotal,
                'subtotal_tbs'           => $subtotal_tbs,
                'subtotal_number_format' => $subtotal,
            ]);
        }

        $url     = '/pembelian/view-tbs-pembelian';
        $respons = $this->paginationData($tbs_pembelian, $array, $kas_default, $subtotal,$no_faktur, $url);

        return response()->json($respons);
    }

    public function pencarianTbsPembelian(Request $request)
    {
        $session_id  = session()->getId();
        $no_faktur = '';
        $user_warung = Auth::user()->id_warung;

        $sum_subtotal = TbsPembelian::select(DB::raw('SUM(subtotal) as subtotal'))->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->first();
        $subtotal     = $sum_subtotal->subtotal;

        $kas_default = Kas::where('warung_id', Auth::user()->id_warung)->where('default_kas', 1)->count();
        $kas_pilih   = Kas::where('warung_id', Auth::user()->id_warung)->where('default_kas', 1)->first();

        $tbs_pembelian = TbsPembelian::select('tbs_pembelians.id_tbs_pembelian AS id_tbs_pembelian', 'tbs_pembelians.jumlah_produk AS jumlah_produk', 'barangs.nama_barang AS nama_barang', 'barangs.kode_barang AS kode_barang', 'tbs_pembelians.id_produk AS id_produk', 'tbs_pembelians.harga_produk AS harga_produk', 'tbs_pembelians.potongan AS potongan', 'tbs_pembelians.tax AS tax', 'tbs_pembelians.subtotal AS subtotal', 'tbs_pembelians.ppn AS ppn')->leftJoin('barangs', 'barangs.id', '=', 'tbs_pembelians.id_produk')->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)
            ->where(function ($query) use ($request) {

                $query->orWhere('barangs.nama_barang', 'LIKE', $request->search . '%')
                    ->orWhere('barangs.kode_barang', 'LIKE', $request->search . '%');

            })->orderBy('id_tbs_pembelian', 'desc')->paginate(10);

        $array = array();

        foreach ($tbs_pembelian as $tbs_pembelians) {

            $potongan_persen        = ($tbs_pembelians->potongan / ($tbs_pembelians->jumlah_produk * $tbs_pembelians->harga_produk)) * 100;
            $subtotal_tbs           = $tbs_pembelians->PemisahSubtotal;
            $harga_pemisah          = $tbs_pembelians->PemisahHarga;
            $nama_produk_title_case = $tbs_pembelians->TitleCaseBarang;
            $jumlah_produk          = $tbs_pembelians->PemisahJumlah;

            $ppn = TbsPembelian::select('ppn')->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->where('ppn', '!=', '')->limit(1);
            if ($ppn->count() > 0) {
                $ppn_produk = $ppn->first()->ppn;
                if ($tbs_pembelians->tax == 0) {
                    $tax_persen = 0;
                } else {
                    if ($tbs_pembelians->ppn == "Include") {
                        $tax_kembali = $tbs_pembelians->subtotal - $tbs_pembelians->tax;
                        //tax untuk mendapatkan 1,1
                        $tax_format = $tbs_pembelians->subtotal / $tax_kembali - 1;
                        $tax_persen = $tax_format * 100;

                    } else if ($tbs_pembelians->ppn == "Exclude") {
                        $tax_persen = ($tbs_pembelians->tax * 100) / ($tbs_pembelians->jumlah_produk * $tbs_pembelians->harga_produk - $tbs_pembelians->potongan);
                    }

                }
            } else {
                $ppn_produk = "";
                $tax_persen = 0;
            }

            array_push($array, [
                'id_tbs_pembelian'       => $tbs_pembelians->id_tbs_pembelian,
                'nama_produk'            => $nama_produk_title_case,
                'kode_produk'            => $tbs_pembelians->produk->kode_barang,
                'harga_produk'           => $tbs_pembelians->harga_produk,
                'harga_pemisah'          => $tbs_pembelians->PemisahHarga,
                'jumlah_produk'          => $tbs_pembelians->jumlah_produk,
                'jumlah_produk_pemisah'  => $jumlah_produk,
                'potongan'               => $tbs_pembelians->potongan,
                'potongan_persen'        => $potongan_persen,
                'tax'                    => $tbs_pembelians->tax,
                'ppn_produk'             => $ppn_produk,
                'tax_persen'             => $tax_persen,
                'kas_pilih'              => $kas_pilih,
                'subtotal'              => $tbs_pembelians->subtotal,
                'subtotal_tbs'           => $subtotal_tbs,
                'subtotal_number_format' => $subtotal,
            ]);
        }

        $url     = '/pembelian/pencarian-tbs-pembelian';
        $search  = $request->search;
        $respons = $this->paginationPencarianData($tbs_pembelian, $array, $kas_default, $subtotal,$no_faktur, $url, $search);

        return response()->json($respons);
    }

    public function viewEditTbsPembelian($id)
    {
        $pembelian   = Pembelian::find($id);
        $no_faktur = $pembelian->no_faktur;
        $session_id  = session()->getId();
        $user_warung = Auth::user()->id_warung;

        $sum_subtotal = EditTbsPembelian::select(DB::raw('SUM(subtotal) as subtotal'))->where('no_faktur', $pembelian->no_faktur)->where('warung_id', Auth::user()->id_warung)->first();
        $subtotal     = $sum_subtotal->subtotal;

        $kas_default = Kas::where('warung_id', Auth::user()->id_warung)->where('default_kas', 1)->count();
        $kas_pilih   = Kas::where('warung_id', Auth::user()->id_warung)->where('default_kas', 1)->first();

        $tbs_pembelian = EditTbsPembelian::select('edit_tbs_pembelians.id_edit_tbs_pembelians AS id_edit_tbs_pembelian', 'edit_tbs_pembelians.jumlah_produk AS jumlah_produk', 'barangs.nama_barang AS nama_barang', 'barangs.kode_barang AS kode_barang', 'edit_tbs_pembelians.id_produk AS id_produk', 'edit_tbs_pembelians.harga_produk AS harga_produk', 'edit_tbs_pembelians.potongan AS potongan', 'edit_tbs_pembelians.tax AS tax', 'edit_tbs_pembelians.subtotal AS subtotal', 'edit_tbs_pembelians.ppn AS ppn','edit_tbs_pembelians.no_faktur AS no_faktur')->leftJoin('barangs', 'barangs.id', '=', 'edit_tbs_pembelians.id_produk')->where('edit_tbs_pembelians.no_faktur', $pembelian->no_faktur)->where('edit_tbs_pembelians.warung_id', Auth::user()->id_warung)->orderBy('edit_tbs_pembelians.id_edit_tbs_pembelians', 'desc')->paginate(10);
        $array         = array();

        foreach ($tbs_pembelian as $tbs_pembelians) {

            $potongan_persen        = ($tbs_pembelians->potongan / ($tbs_pembelians->jumlah_produk * $tbs_pembelians->harga_produk)) * 100;
            $subtotal_tbs           = $tbs_pembelians->PemisahSubtotal;
            $harga_pemisah          = $tbs_pembelians->PemisahHarga;
            $nama_produk_title_case = $tbs_pembelians->TitleCaseBarang;
            $jumlah_produk          = $tbs_pembelians->PemisahJumlah;

            $ppn = EditTbsPembelian::select('ppn')->where('no_faktur', $tbs_pembelians->no_faktur)->where('warung_id', Auth::user()->id_warung)->where('ppn', '!=', '')->limit(1);
            if ($ppn->count() > 0) {
                $ppn_produk = $ppn->first()->ppn;
                if ($tbs_pembelians->tax == 0) {
                    $tax_persen = 0;
                } else {
                    if ($tbs_pembelians->ppn == "Include") {
                        $tax_kembali = $tbs_pembelians->subtotal - $tbs_pembelians->tax;
                        //tax untuk mendapatkan 1,1
                        $tax_format = $tbs_pembelians->subtotal / $tax_kembali - 1;
                        $tax_persen = $tax_format * 100;

                    } else if ($tbs_pembelians->ppn == "Exclude") {
                        $tax_persen = ($tbs_pembelians->tax * 100) / ($tbs_pembelians->jumlah_produk * $tbs_pembelians->harga_produk - $tbs_pembelians->potongan);
                    }
                }
            } else {
                $ppn_produk = "";
                $tax_persen = 0;
            }

            array_push($array, [
                'id_tbs_pembelian'       => $tbs_pembelians->id_edit_tbs_pembelian,
                'nama_produk'            => $nama_produk_title_case,
                'kode_produk'            => $tbs_pembelians->produk->kode_barang,
                'harga_produk'           => $tbs_pembelians->harga_produk,
                'harga_pemisah'          => $tbs_pembelians->PemisahHarga,
                'jumlah_produk'          => $tbs_pembelians->jumlah_produk,
                'jumlah_produk_pemisah'  => $jumlah_produk,
                'potongan'               => $tbs_pembelians->potongan,
                'potongan_persen'        => $potongan_persen,
                'tax'                    => $tbs_pembelians->tax,
                'ppn_produk'             => $ppn_produk,
                'tax_persen'             => $tax_persen,
                'kas_default'            => $kas_default,
                'kas_pilih'              => $kas_pilih,
                'subtotal'              => $tbs_pembelians->subtotal,
                'subtotal_tbs'           => $subtotal_tbs,
                'subtotal_number_format' => $subtotal,
            ]);
        }

        $url     = '/pembelian/view-edit-tbs-pembelian/' . $id;
        $respons = $this->paginationData($tbs_pembelian, $array, $kas_default,$subtotal, $no_faktur, $url);

        return response()->json($respons);
    }

    public function pencarianEditTbsPembelian(Request $request, $id)
    {
        $pembelian   = Pembelian::find($id);
        $no_faktur = $pembelian->no_faktur;
        $session_id  = session()->getId();
        $user_warung = Auth::user()->id_warung;

        $sum_subtotal = EditTbsPembelian::select(DB::raw('SUM(subtotal) as subtotal'))->where('no_faktur', $pembelian->no_faktur)->where('warung_id', Auth::user()->id_warung)->first();
        $subtotal     = $sum_subtotal->subtotal;

        $kas_default = Kas::where('warung_id', Auth::user()->id_warung)->where('default_kas', 1)->count();
        $kas_pilih   = Kas::where('warung_id', Auth::user()->id_warung)->where('default_kas', 1)->first();

        $tbs_pembelian = EditTbsPembelian::select('edit_tbs_pembelians.id_edit_tbs_pembelians AS id_edit_tbs_pembelian', 'edit_tbs_pembelians.jumlah_produk AS jumlah_produk', 'barangs.nama_barang AS nama_barang', 'barangs.kode_barang AS kode_barang', 'edit_tbs_pembelians.id_produk AS id_produk', 'edit_tbs_pembelians.harga_produk AS harga_produk', 'edit_tbs_pembelians.potongan AS potongan', 'edit_tbs_pembelians.tax AS tax', 'edit_tbs_pembelians.subtotal AS subtotal', 'edit_tbs_pembelians.ppn AS ppn','edit_tbs_pembelians.no_faktur AS no_faktur')->leftJoin('barangs', 'barangs.id', '=', 'edit_tbs_pembelians.id_produk')->where('edit_tbs_pembelians.no_faktur', $pembelian->no_faktur)->where('edit_tbs_pembelians.warung_id', Auth::user()->id_warung)
            ->where(function ($query) use ($request) {

                $query->orWhere('barangs.nama_barang', 'LIKE', $request->search . '%')
                    ->orWhere('barangs.kode_barang', 'LIKE', $request->search . '%');

            })->orderBy('edit_tbs_pembelians.id_edit_tbs_pembelians', 'desc')->paginate(10);

        $array = array();

        foreach ($tbs_pembelian as $tbs_pembelians) {

            $potongan_persen        = ($tbs_pembelians->potongan / ($tbs_pembelians->jumlah_produk * $tbs_pembelians->harga_produk)) * 100;
            $subtotal_tbs           = $tbs_pembelians->PemisahSubtotal;
            $harga_pemisah          = $tbs_pembelians->PemisahHarga;
            $nama_produk_title_case = $tbs_pembelians->TitleCaseBarang;
            $jumlah_produk          = $tbs_pembelians->PemisahJumlah;

            $ppn = EditTbsPembelian::select('ppn')->where('no_faktur', $pembelian->no_faktur)->where('warung_id', Auth::user()->id_warung)->where('ppn', '!=', '')->limit(1);
            if ($ppn->count() > 0) {
                $ppn_produk = $ppn->first()->ppn;
                if ($tbs_pembelians->tax == 0) {
                    $tax_persen = 0;
                } else {
                    if ($tbs_pembelians->ppn == "Include") {
                        $tax_kembali = $tbs_pembelians->subtotal - $tbs_pembelians->tax;
                        //tax untuk mendapatkan 1,1
                        $tax_format = $tbs_pembelians->subtotal / $tax_kembali - 1;
                        $tax_persen = $tax_format * 100;

                    } else if ($tbs_pembelians->ppn == "Exclude") {
                        $tax_persen = ($tbs_pembelians->tax * 100) / ($tbs_pembelians->jumlah_produk * $tbs_pembelians->harga_produk - $tbs_pembelians->potongan);
                    }

                }
            } else {
                $ppn_produk = "";
                $tax_persen = 0;
            }

            array_push($array, [
                'id_tbs_pembelian'       => $tbs_pembelians->id_edit_tbs_pembelian,
                'nama_produk'            => $nama_produk_title_case,
                'kode_produk'            => $tbs_pembelians->produk->kode_barang,
                'harga_produk'           => $tbs_pembelians->harga_produk,
                'harga_pemisah'          => $tbs_pembelians->PemisahHarga,
                'jumlah_produk'          => $tbs_pembelians->jumlah_produk,
                'jumlah_produk_pemisah'  => $jumlah_produk,
                'potongan'               => $tbs_pembelians->potongan,
                'potongan_persen'        => $potongan_persen,
                'tax'                    => $tbs_pembelians->tax,
                'ppn_produk'             => $ppn_produk,
                'tax_persen'             => $tax_persen,
                'kas_pilih'              => $kas_pilih,
                'subtotal'              => $tbs_pembelians->subtotal,
                'subtotal_tbs'           => $subtotal_tbs,
                'subtotal_number_format' => $subtotal,
            ]);
        }

        $url     = '/pembelian/pencarian-edit-tbs-pembelian/' . $id;
        $search  = $request->search;
        $respons = $this->paginationPencarianData($tbs_pembelian, $array, $kas_default, $subtotal,$no_faktur, $url, $search);

        return response()->json($respons);
    }
    //END VIEW DAN PENCARIAN TBS PEMBELIAN

    public function paginationData($pembelian, $array, $kas_default, $subtotal,$no_faktur, $url)
    {

        //DATA PAGINATION
        $respons['current_page']   = $pembelian->currentPage();
        $respons['data']           = $array;
        $respons['kas_default']    = $kas_default;
        $respons['subtotal']       = $subtotal;
        $respons['no_faktur']      = $no_faktur;
        $respons['first_page_url'] = url($url . '?page=' . $pembelian->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $pembelian->lastPage();
        $respons['last_page_url']  = url($url . '?page=' . $pembelian->lastPage());
        $respons['next_page_url']  = $pembelian->nextPageUrl();
        $respons['path']           = url($url);
        $respons['per_page']       = $pembelian->perPage();
        $respons['prev_page_url']  = $pembelian->previousPageUrl();
        $respons['to']             = $pembelian->perPage();
        $respons['total']          = $pembelian->total();
        //DATA PAGINATION

        return $respons;
    }
    public function paginationPencarianData($pembelian, $array, $kas_default, $subtotal,$no_faktur, $url, $search)
    {
        //DATA PAGINATION
        $respons['current_page']   = $pembelian->currentPage();
        $respons['data']           = $array;
        $respons['kas_default']    = $kas_default;
        $respons['subtotal']       = $subtotal;
        $respons['no_faktur']      = $no_faktur;
        $respons['first_page_url'] = url($url . '?page=' . $pembelian->firstItem() . '&search=' . $search);
        $respons['from']           = 1;
        $respons['last_page']      = $pembelian->lastPage();
        $respons['last_page_url']  = url($url . '?page=' . $pembelian->lastPage() . '&search=' . $search);
        $respons['next_page_url']  = $pembelian->nextPageUrl();
        $respons['path']           = url($url);
        $respons['per_page']       = $pembelian->perPage();
        $respons['prev_page_url']  = $pembelian->previousPageUrl();
        $respons['to']             = $pembelian->perPage();
        $respons['total']          = $pembelian->total();
        //DATA PAGINATION

        return $respons;
    }

    public function pilih_suplier()
    {
        $suplier = Suplier::select('id', 'nama_suplier')->where('warung_id', Auth::user()->id_warung)->get();
        return response()->json($suplier);
    }

    public function detailView(Request $request)
    {
        $detail_pembelian = DetailPembelian::with(['produk'])->where('warung_id', Auth::user()->id_warung)->where('no_faktur', $request->no_faktur)->get();
        $array            = array();
        foreach ($detail_pembelian as $detail_pembelians) {
            array_push($array, [
                'no_faktur'       => $detail_pembelians->no_faktur,
                'nama_produk'     => $detail_pembelians->TitleCaseBarang,
                'jumlah_produk'   => $detail_pembelians->PemisahJumlah,
                'harga_produk'    => $detail_pembelians->PemisahHarga,
                'potongan_produk' => $detail_pembelians->PemisahPotongan,
                'pajak_produk'    => $detail_pembelians->PemisahTax,
                'subtotal'        => $detail_pembelians->PemisahSubtotal,
            ]);
        }

        $respons['data'] = $array;

        return $respons;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Builder $htmlBuilder)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            // form pembelian
            $session_id   = session()->getId();
            $sum_subtotal = TbsPembelian::select(DB::raw('SUM(subtotal) as subtotal'))->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->first();
            $subtotal     = number_format($sum_subtotal->subtotal, 2, ',', '.');
            if ($request->ajax()) {

                $tbs_pembelian = TbsPembelian::with(['produk'])->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->get();
                return Datatables::of($tbs_pembelian)->addColumn('action', function ($TbsPembelian) {

                    $pesan_alert = 'Anda Yakin Ingin Menghapus Produk "' . $TbsPembelian->TitleCaseBarang . '" ?';
                    return view('pembelian._hapus_produk', [
                        'model'           => $TbsPembelian,
                        'form_url'        => route('pembelian.hapus_tbs_pembelian', $TbsPembelian->id_tbs_pembelian),
                        'confirm_message' => $pesan_alert,
                    ]);
                })
                    ->editColumn('data_produk_tbs', function ($data_produk_tbs) {

                        return $data_produk_tbs->produk->kode_barang . ' - ' . $data_produk_tbs->TitleCaseBarang;
                    })
                    ->editColumn('jumlah_produk', function ($produk_tbs) {
                        return "<a href='#edit-jumlah' align='right' id='edit_jumlah_produk' class='edit-jumlah' data-id='$produk_tbs->id_tbs_pembelian' data-nama='$produk_tbs->TitleCaseBarang'><p align='right'>" . $produk_tbs->PemisahJumlah . "</p></a>";
                    })
                    ->editColumn('harga_produk', function ($produk) {

                        return "<a href='#edit-harga' align='right' id='edit_harga_produk' class='edit-harga' data-id='$produk->id_tbs_pembelian'  data-nama='$produk->TitleCaseBarang'><p align='right'>" . $produk->PemisahHarga . "</p></a>";
                    })
                    ->editColumn('potongan', function ($produk) {

                        $potongan_persen = ($produk->potongan / ($produk->jumlah_produk * $produk->harga_produk)) * 100;
                        return "<a href='#edit-potongan' id='edit_potongan' class='edit-potongan' data-id='$produk->id_tbs_pembelian' data-nama='$produk->TitleCaseBarang' data-jumlah='$produk->jumlah_produk' data-harga='$produk->harga_produk'><p align='right'>" . round($produk->potongan, 2) . " | " . round($potongan_persen, 2) . "%</p></a>";
                    })
                    ->editColumn('tax', function ($produk) use ($session_id) {
                        $ppn = TbsPembelian::select('ppn')->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->where('ppn', '!=', '')->limit(1);
                        if ($ppn->count() > 0) {
                            $ppn_produk = $ppn->first()->ppn;
                            if ($produk->tax == 0) {
                                $tax_persen = 0;
                            } else {

                                $tax_persen = ($produk->tax * 100) / ($produk->jumlah_produk * $produk->harga_produk - $produk->potongan);
                            }
                        } else {
                            $ppn_produk = "";
                            $tax_persen = 0;
                        }
                        return "<a href='#edit-tax'id='edit_tax_produk' class='edit-tax' data-id='$produk->id_tbs_pembelian'  data-jumlah='$produk->jumlah_produk' data-potongan='$produk->potongan' data-harga='$produk->harga_produk' data-ppn='$ppn_produk' data-nama='$produk->TitleCaseBarang'><p align='right'>" . round($produk->tax, 2) . " | " . round($tax_persen, 2) . "%</p></a>";
                    })
                    ->editColumn('subtotal', function ($produk) {
                        return "<p id='table-subtotal' align='right'>" . $produk->PemisahSubtotal . "</p>";
                    })->make(true);

            }

            $html = $htmlBuilder
                ->addColumn(['data' => 'data_produk_tbs', 'name' => 'data_produk_tbs', 'title' => 'Produk', 'orderable' => false, 'searchable' => false])
                ->addColumn(['data' => 'jumlah_produk', 'name' => 'jumlah_produk', 'title' => 'Jumlah'])
                ->addColumn(['data' => 'harga_produk', 'name' => 'harga_produk', 'title' => 'Harga'])
                ->addColumn(['data' => 'potongan', 'name' => 'potongan', 'title' => 'Potongan'])
                ->addColumn(['data' => 'tax', 'name' => 'tax', 'title' => 'Pajak'])
                ->addColumn(['data' => 'subtotal', 'name' => 'subtotal', 'title' => 'Subtotal'])
                ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Hapus', 'orderable' => false, 'searchable' => false]);

            $kas_default = Kas::where('warung_id', Auth::user()->id_warung)->where('default_kas', 1);

            return view('pembelian.create', ['subtotal_tbs' => $subtotal, 'kas_default' => $kas_default])->with(compact('html'));
        }
    }

    public function cekTbsPembelian(Request $request)
    {
        $session_id = session()->getId(); // SESSION ID
        // CEK TBS PEMBELIAN
        $data_tbs = TbsPembelian::where('id_produk', $request->id)
            ->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung);
        return $data_tbs->count();
    }

    //PROSES TAMBAH TBS PEMBELIAN
    public function proses_tambah_tbs_pembelian(Request $request)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {

            $session_id = session()->getId();
            $barang     = Barang::select('nama_barang', 'satuan_id')->where('id', $request->id_produk_tbs)->where('id_warung', Auth::user()->id_warung)->first();
            // SUBTOTAL = JUMLAH * HARGA
            $subtotal = $request->jumlah_produk * $request->harga_produk;
            // INSERT TBS PEMBELIAN
            $Insert_tbspembelian = TbsPembelian::create([
                'id_produk'     => $request->id_produk_tbs,
                'session_id'    => $session_id,
                'jumlah_produk' => $request->jumlah_produk,
                'harga_produk'  => $request->harga_produk,
                'subtotal'      => $subtotal,
                'satuan_id'     => $barang->satuan_id,
                'warung_id'     => Auth::user()->id_warung,
            ]);
        }
    }

//PROSES EDIT JUMLAH TBS PEMBELIAN
    public function edit_jumlah_tbs_pembelian(Request $request)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            // SELECT  TBS PEMBELIAN
            $tbs_pembelian = TbsPembelian::find($request->id_tbs_pembelian);
            // JIKA TAX/ PAJAKK EDIT TBS PEMBELIAN == 0
            if ($tbs_pembelian->tax == 0) {
                $tax_produk = 0;
            } else {
                // TAX PERSEN = (TAX TBS PEMBELIAN * 100) / (JUMLAH PRODUK * HARGA - POTONGAN)
                $tax = ($tbs_pembelian->tax * 100) / ($request->jumlah_edit_produk * $tbs_pembelian->harga_produk - $tbs_pembelian->potongan); // TAX DALAM BENTUK PERSEN
                // TAX PRODUK = (HARGA * JUMLAH - POTONGAN) * TAX /100
                $tax_produk = (($tbs_pembelian->harga_produk * $request->jumlah_edit_produk) - $tbs_pembelian->potongan) * $tax / 100;
            }

            if ($tbs_pembelian->ppn == 'Include') {
                // JIKA PPN INCLUDE MAKA PAJAK TIDAK MEMPENGARUHI SUBTOTAL
                $subtotal = ($tbs_pembelian->harga_produk * $request->jumlah_edit_produk) - $tbs_pembelian->potongan;
            } elseif ($tbs_pembelian->ppn == 'Exclude') {
                // JIKA PPN EXCLUDE MAKA PAJAK MEMPENGARUHI SUBTOT
                $subtotal = (($tbs_pembelian->harga_produk * $request->jumlah_edit_produk) - $tbs_pembelian->potongan) + $tax_produk;
            } else {
                $subtotal = ($tbs_pembelian->harga_produk * $request->jumlah_edit_produk) - $tbs_pembelian->potongan;
            }
// UPDATE JUMLAH PRODUK, SUBTOTAL, DAN TAX
            $tbs_pembelian->update(['jumlah_produk' => $request->jumlah_edit_produk, 'subtotal' => $subtotal, 'tax' => $tax_produk]);
            $nama_barang = $tbs_pembelian->TitleCaseBarang; // TITLE CASH

        }
    }

//PROSES EDIT HARGA TBS PEMBELIAN
    public function edit_harga_tbs_pembelian(Request $request)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            // SELECT  TBS PEMBELIAN
            $tbs_pembelian = TbsPembelian::find($request->id_harga);

// JIKA POTONGAN == 0
            if ($tbs_pembelian->potongan == 0) {
                $potongan_produk = 0;
            } else {
                // POTONGA PERSEN = POTONGAN / (JUMLAH * HARGA) * 100
                $potongan_persen = ($tbs_pembelian->potongan / ($tbs_pembelian->jumlah_produk * $request->harga_edit_produk)) * 100;
                // POTONGAN PRODUK = HARGA * JUMLAH * POTONGAN PERSEN /100
                $potongan_produk = ($request->harga_edit_produk * $tbs_pembelian->jumlah_produk) * $potongan_persen / 100;
            }

// JIKA PAJAK == 0
            if ($tbs_pembelian->tax == 0) {
                $tax_produk = 0;
            } else {
// TAX PERSEN =  (TAX * 100) / (JUMLAH * HARGA - POTONGAN )
                $tax = ($tbs_pembelian->tax * 100) / ($tbs_pembelian->jumlah_produk * $request->harga_edit_produk - $potongan_produk);
// TAX PRODUK = ((HARGA * JUMLAH) - POTONGAN) * TAX PERSEN / 100
                $tax_produk = (($request->harga_edit_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk) * $tax / 100;
            }

            if ($tbs_pembelian->ppn == 'Include') {
                // JIKA PPN INCLUDE MAKA PAJAK TIDAK MEMPENGARUHI SUBTOTAL
                $subtotal = ($request->harga_edit_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk;
            } elseif ($tbs_pembelian->ppn == 'Exclude') {
                // JIKA PPN EXCLUDE MAKA PAJAK MEMPENGARUHI SUBTOTAL
                $subtotal = (($request->harga_edit_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk) + $tax_produk;
            } else {
                $subtotal = ($request->harga_edit_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk;
            }

            // UPDATE HARGA, SUBTOTAL, POTONGAN, TAX
            $tbs_pembelian->update(['harga_produk' => $request->harga_edit_produk, 'subtotal' => $subtotal, 'potongan' => $potongan_produk, 'tax' => $tax_produk]);
            $nama_barang = $tbs_pembelian->TitleCaseBarang; // TITLE CASH
        }
    }

    //PROSES CEK PERSEN MELEBIHI BATAS
    public function cek_persen_potongan_pembelian(Request $request)
    {
        // SELECT EDIT TBS PEMBELIAN
        $tbs_pembelian = TbsPembelian::find($request->id_potongan);
        $potongan      = substr_count($request->potongan_edit_produk, '%'); // UNTUK CEK APAKAH ADA STRING "%"
        // JIKA TIDAK ADA
        if ($potongan == 0) {
            $potongan_persen = 0;
        } else {
            $potongan_persen = filter_var($request->potongan_edit_produk, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); //  PISAH STRING BERDASRAKAN TANDA "%"
        }

        if ($potongan_persen > 100) {
            return $persen_alert = 1;
        } else {
            return $persen_alert = 0;
        }

    }

//PROSES EDIT HARGA TBS PEMBELIAN
    public function edit_potongan_tbs_pembelian(Request $request)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            // SELECT EDIT TBS PEMBELIAN
            $tbs_pembelian = TbsPembelian::find($request->id_potongan);
            $potongan      = substr_count($request->potongan_edit_produk, '%'); // UNTUK CEK APAKAH ADA STRING "%"
            // JIKA TIDAK ADA
            if ($potongan == 0) {
                // FILTER ANGKA DESIMAL
                $potongan_produk = filter_var($request->potongan_edit_produk, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); // POTONGAN TIDAK DALAM BENTUK NOMINAL
                $potongan_persen = 0;
            } else {
                // JIKA ADA
                // FILTER ANGKA DESIMAL
                $potongan_persen = filter_var($request->potongan_edit_produk, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); //  PISAH STRING BERDASRAKAN TANDA "%"
                // POTONGA PRODUK =  (HARGA * JUMLAH ) * POTONGAN PERSEN / 100;
                $potongan_produk = ($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) * $potongan_persen / 100;
            }

            if ($potongan_produk == '') {
                $potongan_produk = 0;
            }

            if ($potongan_persen > 100) {
                    
            } else {

                // JIKA TIDAK ADA PAJAK
                if ($tbs_pembelian->tax == 0) {
                    $tax_produk = 0;
                } else {
                    // TAX PERSEN =  (TAX * 100) / (JUMLAH * HARGA - POTONGAN )
                    $tax = ($tbs_pembelian->tax * 100) / ($tbs_pembelian->jumlah_produk * $tbs_pembelian->harga_produk - $potongan_produk);
                    // TAX PRODUK = ((HARGA * JUMLAH) - POTONGAN) * TAX PERSEN / 100
                    $tax_produk = (($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk) * $tax / 100;
                }

                if ($tbs_pembelian->ppn == 'Include') {
                    // JIKA PPN INCLUDE MAKA PAJAK TIDAK MEMPENGARUHI SUBTOTAL
                    $subtotal = ($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk;
                } elseif ($tbs_pembelian->ppn == 'Exclude') {
                    // JIKA PPN EXCLUDE MAKA PAJAK MEMPENGARUHI SUBTOTAL
                    $subtotal = (($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk) + $tax_produk;
                } else {
                    $subtotal = ($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk;
                }

                // UPDATE POTONGAN, SUBTOTAL, TAX
                $tbs_pembelian->update(['potongan' => $potongan_produk, 'subtotal' => $subtotal, 'tax' => $tax_produk]);
                $nama_barang = $tbs_pembelian->TitleCaseBarang; // TITLE CASH

            }

        }
    }

//PROSES CEK PERSEN TAX  MELEBIHI BATAS
    public function cek_persen_tax_pembelian(Request $request)
    {
        // SELECT EDIT TBS PEMBELIAN
        $tbs_pembelian = TbsPembelian::find($request->id_tax);
        $tax           = substr_count($request->tax_edit_produk, '%'); // UNTUK CEK APAKAH ADA STRING "%"
        // JIKA TIDAK ADA
        if ($tax == 0) {
            $tax_persen = 0;
        } else {
            // JIKA ADA
            $tax_persen = filter_var($request->tax_edit_produk, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); //  PISAH STRING BERDASRAKAN TANDA "%"
        }

        if ($tax_persen > 100) {
            return $persen_alert = 1;
        } else {
            return $persen_alert = 0;
        }

    }

    public function editTaxTbsPembelian(Request $request)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            // SELECT EDIT  TBS PEMBELIAN
            $tbs_pembelian = TbsPembelian::find($request->id_tax);
            $tax           = substr_count($request->tax_edit_produk, '%'); // UNTUK CEK APAKAH ADA STRING "%"
            // JIKA TIDAK ADA
            if ($tax == 0) {
                $tax_produk = filter_var($request->tax_edit_produk, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); // TAX DAALAM BENTUK NOMINAL
                $tax_persen = 0;
            } else {
                // JIKA ADA
                $tax_persen = filter_var($request->tax_edit_produk, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); //  PISAH STRING BERDASRAKAN TANDA "%"
                // TAX PRODUK = ((HARGA * JUMLAH) - POTONGAN) * TAX PERSEN / 100
                if ($request->ppn_produk == 'Include') {
                    $default_tax              = 1;
                    $subtotal_kurang_potongan = (($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $tbs_pembelian->potongan);
                    $hasil_tax                = $default_tax + ($tax_persen / 100);
                    $hasil_tax2               = $subtotal_kurang_potongan / $hasil_tax;
                    $tax_produk               = $subtotal_kurang_potongan - $hasil_tax2;
                } elseif ($request->ppn_produk == 'Exclude') {
                    $tax_produk = (($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $tbs_pembelian->potongan) * $tax_persen / 100;
                }
            }

            if ($tax_produk == '') {
                $tax_produk = 0;
            }

            if ($request->ppn_produk == 'Include') {
                // JIKA PPN INCLUDE MAKA PAJAK TIDAK MEMPENGARUHI SUBTOTAL
                $subtotal = ($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $tbs_pembelian->potongan;
            } elseif ($request->ppn_produk == 'Exclude') {
                // JIKA PPN EXCLUDE MAKA PAJAK MEMPENGARUHI SUBTOTAL
                $subtotal = (($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $tbs_pembelian->potongan) + $tax_produk;
            }
            // UPDATE SUBTOTAL, TAX, PPN
            $tbs_pembelian->update(['subtotal' => $subtotal, 'tax' => $tax_produk, 'ppn' => $request->ppn_produk]);
            $nama_barang = $tbs_pembelian->TitleCaseBarang; // TITLE CASH

        }
    }

    //PROSES HAPUS TBS PEMBELIAN
    public function hapus_tbs_pembelian($id)
    {
        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            TbsPembelian::destroy($id);
        }
    }

//PROSES BATAL TBS PEMBELIAN
    public function proses_batal_transaksi_pembelian()
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            $session_id         = session()->getId();
            $data_tbs_pembelian = TbsPembelian::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->delete();

            return response(200);
        }
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
            //START TRANSAKSI
            DB::beginTransaction();
            $warung_id  = Auth::user()->id_warung;
            $session_id = session()->getId();
            $user       = Auth::user()->id;
            $no_faktur  = Pembelian::no_faktur($warung_id);

            //INSERT DETAIL PEMBELIAN
            $data_produk_pembelian = TbsPembelian::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung);

            // INSERT DETAIL PEMBELIAN
            foreach ($data_produk_pembelian->get() as $data_tbs_pembelian) {
                $barang = Barang::select('harga_beli')->where('id', $data_tbs_pembelian->id_produk)->where('id_warung', Auth::user()->id_warung);
                if ($barang->first()->harga_beli != $data_tbs_pembelian->harga_produk) {
                    $barang->update(['harga_beli' => $data_tbs_pembelian->harga_produk]);
                }
                $detail_pembelian = DetailPembelian::create([
                    'no_faktur'     => $no_faktur,
                    'satuan_id'     => $data_tbs_pembelian->satuan_id,
                    'id_produk'     => $data_tbs_pembelian->id_produk,
                    'jumlah_produk' => $data_tbs_pembelian->jumlah_produk,
                    'harga_produk'  => $data_tbs_pembelian->harga_produk,
                    'subtotal'      => $data_tbs_pembelian->subtotal,
                    'tax'           => $data_tbs_pembelian->tax,
                    'potongan'      => $data_tbs_pembelian->potongan,
                    'ppn'           => $data_tbs_pembelian->ppn,
                    'warung_id'     => Auth::user()->id_warung,
                ]);

            }

            //INSERT PEMBELIAN
            if ($request->keterangan == "") {
                $keterangan = "-";
            } else {
                $keterangan = $request->keterangan;
            }

            if ($request->pembayaran == '') {
                $pembayaran = 0;
            } else {
                $pembayaran = $request->pembayaran;
            }
            if ($request->kembalian == '') {
                $kembalian = 0;
            } else {
                $kembalian = $request->kembalian;
            }
            if ($pembayaran < $request->total_akhir) {
                # code...
                $status_pembelian = 'Hutang';
            } else {
                $status_pembelian = 'Tunai';
            }

            $pembelian = Pembelian::create([
                'no_faktur'        => $no_faktur,
                'total'            => $request->total_akhir,
                'suplier_id'       => $request->suplier,
                'status_pembelian' => $status_pembelian,
                'potongan'         => $request->potongan,
                'tunai'            => $pembayaran,
                'kembalian'        => $kembalian,
                'kredit'           => $request->kredit,
                'nilai_kredit'     => $request->kredit,
                'cara_bayar'       => $request->cara_bayar,
                'status_beli_awal' => $status_pembelian,
                'tanggal_jt_tempo' => $request->jatuh_tempo,
                'keterangan'       => $request->keterangan,
                'ppn'              => $request->ppn,
                'warung_id'        => Auth::user()->id_warung,
            ]);

            //HAPUS TBS PEMBELIAN
            $data_produk_pembelian->delete();
            DB::commit();

        }
    }

    public function total_kas(Request $request)
    {
        $session_id            = session()->getId();
        $total_kas             = TransaksiKas::total_kas($request);
        $data_produk_pembelian = TbsPembelian::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->count();

        $respons['total_kas']             = $total_kas;
        $respons['data_produk_pembelian'] = $data_produk_pembelian;

        return $respons;
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
    public function proses_form_edit($id)
    {
        $session_id            = session()->getId();
        $data_pembelian        = Pembelian::find($id);
        $data_produk_pembelian = DetailPembelian::where('no_faktur', $data_pembelian->no_faktur)->where('warung_id', Auth::user()->id_warung);

        $hapus_semua_edit_tbs_pembelian = EditTbsPembelian::where('no_faktur', $data_pembelian->no_faktur)->where('warung_id', Auth::user()->id_warung)
            ->delete();

        foreach ($data_produk_pembelian->get() as $data_tbs) {
            $detail_pembelian = EditTbsPembelian::create([
                'session_id'    => $session_id,
                'no_faktur'     => $data_tbs->no_faktur,
                'id_produk'     => $data_tbs->id_produk,
                'satuan_id'     => $data_tbs->satuan_id,
                'jumlah_produk' => $data_tbs->jumlah_produk,
                'harga_produk'  => $data_tbs->harga_produk,
                'subtotal'      => $data_tbs->subtotal,
                'tax'           => $data_tbs->tax,
                'potongan'      => $data_tbs->potongan,
                'ppn'           => $data_tbs->ppn,
                'warung_id'     => Auth::user()->id_warung,
            ]);
        }
        return response(200);
    }

    public function ambilFakturPembelian($id)
    {
        //
        return Pembelian::find($id);
    }

    public function edit(Request $request, $id, Builder $htmlBuilder)
    {
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

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            if (!Pembelian::destroy($id)) {
                return 0;
            } else {
                return response(200);
            }
        }
    }
}
