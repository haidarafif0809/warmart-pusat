<?php

namespace App\Http\Controllers;

use App\Barang;
use App\DetailPembelian;
use App\DetailPembelianOrder;
use App\EditTbsPembelian;
use App\Kas;
use App\Pembelian;
use App\SatuanKonversi;
use App\SettingAplikasi;
use App\Suplier;
use App\TbsPembelian;
use App\TransaksiHutang;
use App\TransaksiKas;
use App\PenerimaanProduk;
use Auth;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Validator;
use Yajra\Datatables\Html\Builder;
use Laratrust;

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

    // DATA SUPLIER PENERIMAAN
    public function suplierPenerimaan(){

        $data_penerimaan = PenerimaanProduk::select(['penerimaan_produks.id', 'penerimaan_produks.no_faktur_penerimaan', 'penerimaan_produks.suplier_id', 'penerimaan_produks.keterangan','supliers.nama_suplier'])
        ->leftJoin('supliers', 'supliers.id', '=', 'penerimaan_produks.suplier_id')
        ->where('penerimaan_produks.status_penerimaan', 1)
        ->where('penerimaan_produks.warung_id', Auth::user()->id_warung)->get();

        $array = [];

        foreach ($data_penerimaan as $penerimaan) {
            array_push($array, [
                'id_penerimaan'      => $penerimaan->id,
                'suplier_id'    => $penerimaan->suplier_id,
                'faktur_penerimaan'  => $penerimaan->no_faktur_penerimaan,
                'suplier_penerimaan' => $penerimaan->nama_suplier,
                'penerimaan'         => $penerimaan->id."|".$penerimaan->suplier_id."|".$penerimaan->no_faktur_penerimaan."|".$penerimaan->nama_suplier."|".$penerimaan->keterangan
                ]);
        }

        return response()->json($array);

    }

    public function dataPagination($data_pembelian, $array_pembelian)
    {
        $respons['current_page']   = $data_pembelian->currentPage();
        $respons['data']           = $array_pembelian;
        $respons['otoritas']      = $this->otoritasPembelian();
        $respons['first_page_url'] = url('/pembelian/view?page=' . $data_pembelian->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $data_pembelian->lastPage();
        $respons['last_page_url']  = url('/pembelian/view?page=' . $data_pembelian->lastPage());
        $respons['next_page_url']  = $data_pembelian->nextPageUrl();
        $respons['path']           = url('/pembelian/view');
        $respons['per_page']       = $data_pembelian->perPage();
        $respons['prev_page_url']  = $data_pembelian->previousPageUrl();
        $respons['to']             = $data_pembelian->perPage();
        $respons['total']          = $data_pembelian->total();

        return $respons;
    }

    public function foreachPembelian($data_pembelian)
    {
        $array_pembelian = array();
        foreach ($data_pembelian as $pembelians) {
            array_push($array_pembelian, [
                'id'               => $pembelians->id,
                'no_faktur'        => $pembelians->no_faktur,
                'waktu'            => $pembelians->Waktu,
                'suplier'          => $pembelians->nama_suplier,
                'status_pembelian' => $pembelians->status_pembelian,
                'total'            => $pembelians->getTotalSeparator(),
                'kas'              => $pembelians->nama_kas,
                'potongan'         => $pembelians->PemisahPotongan,
                'tunai'            => $pembelians->PemisahTunai,
                'kembalian'        => $pembelians->PemisahKredit,
                'jatuh_tempo'      => $pembelians->JatuhTempo,
                'user_buat'        => $pembelians->name,
                ]);
        }
        return $array_pembelian;
    }

    public function view()
    {
        //SELECT SEMUA TRASNSAKSI PEMBELIAN
        $data_pembelian = Pembelian::dataTransaksiPembelian()->paginate(10);
        //PERULANGAN
        $array_pembelian = $this->foreachPembelian($data_pembelian);
        //DATA PAGINATION
        $respons = $this->dataPagination($data_pembelian, $array_pembelian);

        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {
        //PENCARIAN TRASNSAKSI PEMBELIAN
        $search         = $request->search;
        $data_pembelian = Pembelian::dataTransaksiPembelian()->where(function ($query) use ($search) {
            $query->where('pembelians.status_pembelian', 'LIKE', '%' . $search . '%')
            ->orWhere('pembelians.no_faktur', 'LIKE', '%' . $search . '%')
            ->orWhere('pembelians.total', 'LIKE', '%' . $search . '%');
        })->paginate(10);
        //PERULANGAN
        $array_pembelian = $this->foreachPembelian($data_pembelian);
        //DATA PAGINATION
        $respons = $this->dataPagination($data_pembelian, $array_pembelian);

        return response()->json($respons);
    }

    //VIEW DAN PENCARIAN TBS PEMBELIAN
    public function viewTbsPembelian()
    {
        $session_id  = session()->getId();
        $no_faktur   = '';
        $user_warung = Auth::user()->id_warung;

        $sum_subtotal = EditTbsPembelian::select(DB::raw('SUM(subtotal) as subtotal'))->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->first();
        $subtotal     = $sum_subtotal->subtotal;

        $kas_default = Kas::where('warung_id', Auth::user()->id_warung)->where('default_kas', 1)->count();
        $kas_pilih   = Kas::where('warung_id', Auth::user()->id_warung)->where('default_kas', 1)->first();

        $tbs_pembelian = TbsPembelian::dataTransaksiTbsPembelian($session_id, $user_warung)
        ->orderBy('tbs_pembelians.id_tbs_pembelian', 'desc')->paginate(10);
        $array = array();

        foreach ($tbs_pembelian as $tbs_pembelians) {

            $potongan_persen        = ($tbs_pembelians->potongan / ($tbs_pembelians->jumlah_produk * $tbs_pembelians->harga_produk)) * 100;
            $subtotal_tbs           = $tbs_pembelians->PemisahSubtotal;
            $harga_pemisah          = $tbs_pembelians->PemisahHarga;
            $nama_produk_title_case = $tbs_pembelians->TitleCaseBarang;
            $jumlah_produk          = $tbs_pembelians->PemisahJumlah;
            $potongan_tampil        = $tbs_pembelians->PemisahPotongan;
            $tax_tampil             = $tbs_pembelians->PemisahTax;

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
                'id_produk'              => $tbs_pembelians->id_produk,
                'id_tbs_pembelian'       => $tbs_pembelians->id_tbs_pembelian,
                'nama_produk'            => $nama_produk_title_case,
                'satuan_id'              => $tbs_pembelians->satuan_id,
                'nama_satuan'            => strtoupper($tbs_pembelians->nama_satuan),
                'kode_produk'            => $tbs_pembelians->produk->kode_barang,
                'harga_produk'           => $tbs_pembelians->harga_produk,
                'harga_pemisah'          => $tbs_pembelians->PemisahHarga,
                'jumlah_produk'          => $tbs_pembelians->jumlah_produk,
                'jumlah_produk_pemisah'  => $jumlah_produk,
                'potongan'               => $potongan_tampil,
                'potongan_persen'        => $potongan_persen,
                'tax'                    => $tax_tampil,
                'ppn_produk'             => $ppn_produk,
                'tax_persen'             => $tax_persen,
                'kas_default'            => $kas_default,
                'kas_pilih'              => $kas_pilih,
                'subtotal'               => $tbs_pembelians->subtotal,
                'subtotal_tbs'           => $subtotal_tbs,
                'subtotal_number_format' => $subtotal,
                ]);
        }

        $url     = '/pembelian/view-tbs-pembelian';
        $respons = $this->paginationData($tbs_pembelian, $array, $kas_default, $subtotal, $no_faktur, $url);

        return response()->json($respons);
    }

    public function pencarianTbsPembelian(Request $request)
    {
        $session_id  = session()->getId();
        $no_faktur   = '';
        $user_warung = Auth::user()->id_warung;

        $sum_subtotal = TbsPembelian::select(DB::raw('SUM(subtotal) as subtotal'))->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->first();
        $subtotal     = $sum_subtotal->subtotal;

        $kas_default = Kas::where('warung_id', Auth::user()->id_warung)->where('default_kas', 1)->count();
        $kas_pilih   = Kas::where('warung_id', Auth::user()->id_warung)->where('default_kas', 1)->first();

        $tbs_pembelian = TbsPembelian::dataTransaksiTbsPembelian($session_id, $user_warung)
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
            $potongan_tampil        = $tbs_pembelians->PemisahPotongan;
            $tax_tampil             = $tbs_pembelians->PemisahTax;

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
                'potongan'               => $potongan_tampil,
                'potongan_persen'        => $potongan_persen,
                'tax'                    => $tax_tampil,
                'ppn_produk'             => $ppn_produk,
                'tax_persen'             => $tax_persen,
                'kas_pilih'              => $kas_pilih,
                'subtotal'               => $tbs_pembelians->subtotal,
                'subtotal_tbs'           => $subtotal_tbs,
                'subtotal_number_format' => $subtotal,
                ]);
        }

        $url     = '/pembelian/pencarian-tbs-pembelian';
        $search  = $request->search;
        $respons = $this->paginationPencarianData($tbs_pembelian, $array, $kas_default, $subtotal, $no_faktur, $url, $search);

        return response()->json($respons);
    }

    public function viewEditTbsPembelian($id)
    {
        $pembelian   = Pembelian::find($id);
        $no_faktur   = $pembelian->no_faktur;
        $session_id  = session()->getId();
        $user_warung = Auth::user()->id_warung;

        $sum_subtotal = EditTbsPembelian::select(DB::raw('SUM(subtotal) as subtotal'))->where('no_faktur', $pembelian->no_faktur)->where('warung_id', Auth::user()->id_warung)->first();
        $subtotal     = $sum_subtotal->subtotal;

        $kas_default = Kas::where('warung_id', Auth::user()->id_warung)->where('default_kas', 1)->count();
        $kas_pilih   = Kas::where('warung_id', Auth::user()->id_warung)->where('default_kas', 1)->first();

        $tbs_pembelian = EditTbsPembelian::select('edit_tbs_pembelians.id_edit_tbs_pembelians AS id_edit_tbs_pembelian', 'edit_tbs_pembelians.jumlah_produk AS jumlah_produk', 'barangs.nama_barang AS nama_barang', 'barangs.kode_barang AS kode_barang', 'edit_tbs_pembelians.id_produk AS id_produk', 'edit_tbs_pembelians.harga_produk AS harga_produk', 'edit_tbs_pembelians.potongan AS potongan', 'edit_tbs_pembelians.tax AS tax', 'edit_tbs_pembelians.subtotal AS subtotal', 'edit_tbs_pembelians.ppn AS ppn', 'edit_tbs_pembelians.no_faktur AS no_faktur', 'edit_tbs_pembelians.satuan_id AS satuan_id', 'satuans.nama_satuan')
        ->leftJoin('barangs', 'barangs.id', '=', 'edit_tbs_pembelians.id_produk')
        ->leftJoin('satuans', 'satuans.id', '=', 'edit_tbs_pembelians.satuan_id')
        ->where('edit_tbs_pembelians.no_faktur', $pembelian->no_faktur)->where('edit_tbs_pembelians.warung_id', Auth::user()->id_warung)->orderBy('edit_tbs_pembelians.id_edit_tbs_pembelians', 'desc')->paginate(10);
        $array = array();

        foreach ($tbs_pembelian as $tbs_pembelians) {

            $potongan_persen        = ($tbs_pembelians->potongan / ($tbs_pembelians->jumlah_produk * $tbs_pembelians->harga_produk)) * 100;
            $subtotal_tbs           = $tbs_pembelians->PemisahSubtotal;
            $harga_pemisah          = $tbs_pembelians->PemisahHarga;
            $nama_produk_title_case = $tbs_pembelians->TitleCaseBarang;
            $jumlah_produk          = $tbs_pembelians->PemisahJumlah;
            $potongan_tampil        = $tbs_pembelians->PemisahPotongan;
            $tax_tampil             = $tbs_pembelians->PemisahTax;

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
                'id_produk'              => $tbs_pembelians->id_produk,
                'id_tbs_pembelian'       => $tbs_pembelians->id_edit_tbs_pembelian,
                'nama_produk'            => $nama_produk_title_case,
                'kode_produk'            => $tbs_pembelians->produk->kode_barang,
                'satuan_id'              => $tbs_pembelians->satuan_id,
                'nama_satuan'            => strtoupper($tbs_pembelians->nama_satuan),
                'harga_produk'           => $tbs_pembelians->harga_produk,
                'harga_pemisah'          => $tbs_pembelians->PemisahHarga,
                'jumlah_produk'          => $tbs_pembelians->jumlah_produk,
                'jumlah_produk_pemisah'  => $jumlah_produk,
                'potongan'               => $potongan_tampil,
                'potongan_persen'        => $potongan_persen,
                'tax'                    => $tax_tampil,
                'ppn_produk'             => $ppn_produk,
                'tax_persen'             => $tax_persen,
                'kas_default'            => $kas_default,
                'kas_pilih'              => $kas_pilih,
                'subtotal'               => $tbs_pembelians->subtotal,
                'subtotal_tbs'           => $subtotal_tbs,
                'subtotal_number_format' => $subtotal,
                ]);
        }

        $url     = '/pembelian/view-edit-tbs-pembelian/' . $id;
        $respons = $this->paginationData($tbs_pembelian, $array, $kas_default, $subtotal, $no_faktur, $url);

        return response()->json($respons);
    }

    public function pencarianEditTbsPembelian(Request $request, $id)
    {
        $pembelian   = Pembelian::find($id);
        $no_faktur   = $pembelian->no_faktur;
        $session_id  = session()->getId();
        $user_warung = Auth::user()->id_warung;

        $sum_subtotal = EditTbsPembelian::select(DB::raw('SUM(subtotal) as subtotal'))->where('no_faktur', $pembelian->no_faktur)->where('warung_id', Auth::user()->id_warung)->first();
        $subtotal     = $sum_subtotal->subtotal;

        $kas_default = Kas::where('warung_id', Auth::user()->id_warung)->where('default_kas', 1)->count();
        $kas_pilih   = Kas::where('warung_id', Auth::user()->id_warung)->where('default_kas', 1)->first();

        $tbs_pembelian = EditTbsPembelian::select('edit_tbs_pembelians.id_edit_tbs_pembelians AS id_edit_tbs_pembelian', 'edit_tbs_pembelians.jumlah_produk AS jumlah_produk', 'barangs.nama_barang AS nama_barang', 'barangs.kode_barang AS kode_barang', 'edit_tbs_pembelians.id_produk AS id_produk', 'edit_tbs_pembelians.harga_produk AS harga_produk', 'edit_tbs_pembelians.potongan AS potongan', 'edit_tbs_pembelians.tax AS tax', 'edit_tbs_pembelians.subtotal AS subtotal', 'edit_tbs_pembelians.ppn AS ppn', 'edit_tbs_pembelians.no_faktur AS no_faktur')->leftJoin('barangs', 'barangs.id', '=', 'edit_tbs_pembelians.id_produk')->where('edit_tbs_pembelians.no_faktur', $pembelian->no_faktur)->where('edit_tbs_pembelians.warung_id', Auth::user()->id_warung)
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
            $potongan_tampil        = $tbs_pembelians->PemisahPotongan;
            $tax_tampil             = $tbs_pembelians->PemisahTax;

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
                'potongan'               => $potongan_tampil,
                'potongan_persen'        => $potongan_persen,
                'tax'                    => $tax_tampil,
                'ppn_produk'             => $ppn_produk,
                'tax_persen'             => $tax_persen,
                'kas_pilih'              => $kas_pilih,
                'subtotal'               => $tbs_pembelians->subtotal,
                'subtotal_tbs'           => $subtotal_tbs,
                'subtotal_number_format' => $subtotal,
                ]);
        }

        $url     = '/pembelian/pencarian-edit-tbs-pembelian/' . $id;
        $search  = $request->search;
        $respons = $this->paginationPencarianData($tbs_pembelian, $array, $kas_default, $subtotal, $no_faktur, $url, $search);

        return response()->json($respons);
    }
    //END VIEW DAN PENCARIAN TBS PEMBELIAN

//VIEW DETAIL PEMBELIAN & PENCARIAN
    public function viewDetailPembelian($id)
    {
        $pembelian   = Pembelian::find($id);
        $no_faktur   = $pembelian->no_faktur;
        $user_warung = Auth::user()->id_warung;

        $sum_subtotal = DetailPembelian::select(DB::raw('SUM(subtotal) as subtotal'))->where('no_faktur', $no_faktur)->where('warung_id', Auth::user()->id_warung)->first();
        $subtotal     = $sum_subtotal->subtotal;

        $detail_pembelian = DetailPembelian::select('detail_pembelians.id_detail_pembelian AS id_detail_pembelian', 'detail_pembelians.jumlah_produk AS jumlah_produk', 'barangs.nama_barang AS nama_barang', 'barangs.kode_barang AS kode_barang', 'detail_pembelians.id_produk AS id_produk', 'detail_pembelians.harga_produk AS harga_produk', 'detail_pembelians.potongan AS potongan', 'detail_pembelians.tax AS tax', 'detail_pembelians.subtotal AS subtotal', 'detail_pembelians.ppn AS ppn', 'detail_pembelians.no_faktur AS no_faktur', 'satuans.nama_satuan')
        ->leftJoin('barangs', 'barangs.id', '=', 'detail_pembelians.id_produk')
        ->leftJoin('satuans', 'satuans.id', '=', 'detail_pembelians.satuan_id')
        ->where('detail_pembelians.no_faktur', $pembelian->no_faktur)->where('detail_pembelians.warung_id', Auth::user()->id_warung)->orderBy('detail_pembelians.id_detail_pembelian', 'desc')->paginate(10);

        $array       = array();
        $kas_default = Kas::where('warung_id', Auth::user()->id_warung)->where('default_kas', 1)->count();

        $dataPembelian = new DetailPembelian();
        $subtotal      = $dataPembelian->subtotalTbs($user_warung, $no_faktur);

        foreach ($detail_pembelian as $detail_pembelians) {

            $potongan_persen        = ($detail_pembelians->potongan / ($detail_pembelians->jumlah_produk * $detail_pembelians->harga_produk)) * 100;
            $subtotal_tbs           = $detail_pembelians->PemisahSubtotal;
            $harga_pemisah          = $detail_pembelians->PemisahHarga;
            $nama_produk_title_case = $detail_pembelians->TitleCaseBarang;
            $jumlah_produk          = $detail_pembelians->PemisahJumlah;
            $potongan_tampil        = $detail_pembelians->PemisahPotongan;
            $tax_tampil             = $detail_pembelians->PemisahTax;

            $ppn = DetailPembelian::select('ppn')->where('no_faktur', $detail_pembelians->no_faktur)->where('warung_id', Auth::user()->id_warung)->where('ppn', '!=', '')->limit(1);
            if ($ppn->count() > 0) {
                $ppn_produk = $ppn->first()->ppn;
                if ($detail_pembelians->tax == 0) {
                    $tax_persen = 0;
                } else {
                    if ($detail_pembelians->ppn == "Include") {
                        $tax_kembali = $detail_pembelians->subtotal - $detail_pembelians->tax;
                        //tax untuk mendapatkan 1,1
                        $tax_format = $detail_pembelians->subtotal / $tax_kembali - 1;
                        $tax_persen = $tax_format * 100;

                    } else if ($detail_pembelians->ppn == "Exclude") {
                        $tax_persen = ($detail_pembelians->tax * 100) / ($detail_pembelians->jumlah_produk * $detail_pembelians->harga_produk - $detail_pembelians->potongan);
                    }
                }
            } else {
                $ppn_produk = "";
                $tax_persen = 0;
            }

            array_push($array, [
                'id_detail_pembelian'   => $detail_pembelians->id_detail_pembelian,
                'nama_produk'           => $nama_produk_title_case,
                'kode_produk'           => $detail_pembelians->produk->kode_barang,
                'nama_satuan'           => strtoupper($detail_pembelians->nama_satuan),
                'harga_produk'          => $detail_pembelians->harga_produk,
                'harga_pemisah'         => $detail_pembelians->PemisahHarga,
                'jumlah_produk'         => $detail_pembelians->jumlah_produk,
                'jumlah_produk_pemisah' => $jumlah_produk,
                'potongan'              => $potongan_tampil,
                'potongan_persen'       => $potongan_persen,
                'tax'                   => $tax_tampil,
                'ppn_produk'            => $ppn_produk,
                'tax_persen'            => $tax_persen,
                'subtotal'              => $detail_pembelians->subtotal,
                'subtotal_tbs'          => $subtotal_tbs,
                ]);
        }

        $url                 = '/pembelian/view-detail-pembelian/' . $id;
        $respons             = $this->paginationData($detail_pembelian, $array, $kas_default, $subtotal, $no_faktur, $url);
        $respons['subtotal'] = $subtotal;
        return response()->json($respons);
    }

    public function pencarianDetailPembelian(Request $request, $id)
    {
        $session_id  = session()->getId();
        $pembelian   = Pembelian::find($id);
        $no_faktur   = $pembelian->no_faktur;
        $user_warung = Auth::user()->id_warung;

        $sum_subtotal = DetailPembelian::select(DB::raw('SUM(subtotal) as subtotal'))->where('no_faktur', $no_faktur)->where('warung_id', Auth::user()->id_warung)->first();
        $subtotal     = $sum_subtotal->subtotal;

        $detail_pembelian = DetailPembelian::select('detail_pembelians.id_detail_pembelian AS id_detail_pembelian', 'detail_pembelians.jumlah_produk AS jumlah_produk', 'barangs.nama_barang AS nama_barang', 'barangs.kode_barang AS kode_barang', 'detail_pembelians.id_produk AS id_produk', 'detail_pembelians.harga_produk AS harga_produk', 'detail_pembelians.potongan AS potongan', 'detail_pembelians.tax AS tax', 'detail_pembelians.subtotal AS subtotal', 'detail_pembelians.ppn AS ppn', 'detail_pembelians.no_faktur AS no_faktur')->leftJoin('barangs', 'barangs.id', '=', 'detail_pembelians.id_produk')->where('detail_pembelians.no_faktur', $no_faktur)->where('detail_pembelians.warung_id', Auth::user()->id_warung)
        ->where(function ($query) use ($request) {

            $query->orWhere('barangs.nama_barang', 'LIKE', $request->search . '%')
            ->orWhere('barangs.kode_barang', 'LIKE', $request->search . '%');

        })->orderBy('detail_pembelians.id_detail_pembelian', 'desc')->paginate(10);
        $array       = array();
        $kas_default = Kas::where('warung_id', Auth::user()->id_warung)->where('default_kas', 1)->count();
        foreach ($detail_pembelian as $detail_pembelians) {

            $potongan_persen        = ($detail_pembelians->potongan / ($detail_pembelians->jumlah_produk * $detail_pembelians->harga_produk)) * 100;
            $subtotal_tbs           = $detail_pembelians->PemisahSubtotal;
            $harga_pemisah          = $detail_pembelians->PemisahHarga;
            $nama_produk_title_case = $detail_pembelians->TitleCaseBarang;
            $jumlah_produk          = $detail_pembelians->PemisahJumlah;
            $potongan_tampil        = $detail_pembelians->PemisahPotongan;
            $tax_tampil             = $detail_pembelians->PemisahTax;

            $ppn = DetailPembelian::select('ppn')->where('no_faktur', $detail_pembelians->no_faktur)->where('warung_id', Auth::user()->id_warung)->where('ppn', '!=', '')->limit(1);
            if ($ppn->count() > 0) {
                $ppn_produk = $ppn->first()->ppn;
                if ($detail_pembelians->tax == 0) {
                    $tax_persen = 0;
                } else {
                    if ($detail_pembelians->ppn == "Include") {
                        $tax_kembali = $detail_pembelians->subtotal - $detail_pembelians->tax;
                        //tax untuk mendapatkan 1,1
                        $tax_format = $detail_pembelians->subtotal / $tax_kembali - 1;
                        $tax_persen = $tax_format * 100;

                    } else if ($detail_pembelians->ppn == "Exclude") {
                        $tax_persen = ($detail_pembelians->tax * 100) / ($detail_pembelians->jumlah_produk * $detail_pembelians->harga_produk - $detail_pembelians->potongan);
                    }
                }
            } else {
                $ppn_produk = "";
                $tax_persen = 0;
            }

            array_push($array, [
                'id_detail_pembelian'   => $detail_pembelians->id_detail_pembelian,
                'nama_produk'           => $nama_produk_title_case,
                'kode_produk'           => $detail_pembelians->produk->kode_barang,
                'harga_produk'          => $detail_pembelians->harga_produk,
                'harga_pemisah'         => $detail_pembelians->PemisahHarga,
                'jumlah_produk'         => $detail_pembelians->jumlah_produk,
                'jumlah_produk_pemisah' => $jumlah_produk,
                'potongan'              => $potongan_tampil,
                'potongan_persen'       => $potongan_persen,
                'tax'                   => $tax_tampil,
                'ppn_produk'            => $ppn_produk,
                'tax_persen'            => $tax_persen,
                'subtotal'              => $detail_pembelians->subtotal,
                'subtotal_tbs'          => $subtotal_tbs,
                ]);
        }

        $url     = '/pembelian/pencarian-detail-pembelian/' . $id;
        $respons = $this->paginationData($detail_pembelian, $array, $kas_default, $subtotal, $no_faktur, $url);
        return response()->json($respons);
    }
//END VIEW DETAIL PEMBELIAN & PENCARIAN

    public function paginationData($pembelian, $array, $kas_default, $subtotal, $no_faktur, $url)
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
    public function paginationPencarianData($pembelian, $array, $kas_default, $subtotal, $no_faktur, $url, $search)
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

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
            $data_tbs   = TbsPembelian::where('id_produk', $request->id_produk_tbs)
            ->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung);

            if ($data_tbs->count() > 0) {

                $subtotal_lama = $data_tbs->first()->subtotal;

                $jumlah_produk = $data_tbs->first()->jumlah_produk + $request->jumlah_produk;

                $subtotal_edit = ($jumlah_produk * $request->harga_produk) - $data_tbs->first()->potongan;

                $data_tbs->update(['jumlah_produk' => $jumlah_produk, 'subtotal' => $subtotal_edit, 'harga_produk' => $request->harga_produk, 'satuan_id' => $request->satuan, 'satuan_dasar' => $request->satuan_dasar]);

                $subtotal = $jumlah_produk * $request->harga_produk;

                $respons['status']        = 1;
                $respons['subtotal_lama'] = $subtotal_lama;
                $respons['subtotal']      = $subtotal;
                return response()->json($respons);

            } else {

                $barang = Barang::select('nama_barang', 'satuan_id')->where('id', $request->id_produk_tbs)->where('id_warung', Auth::user()->id_warung)->first();
                // SUBTOTAL = JUMLAH * HARGA
                $subtotal = $request->jumlah_produk * $request->harga_produk;
                // INSERT TBS PEMBELIAN
                $Insert_tbspembelian = TbsPembelian::create([
                    'id_produk'     => $request->id_produk_tbs,
                    'session_id'    => $session_id,
                    'jumlah_produk' => $request->jumlah_produk,
                    'harga_produk'  => $request->harga_produk,
                    'subtotal'      => $subtotal,
                    'satuan_id'     => $request->satuan,
                    'satuan_dasar'  => $request->satuan_dasar,
                    'status_harga'  => $request->status_harga,
                    'warung_id'     => Auth::user()->id_warung,
                    ]);

                $respons['status']   = 0;
                $respons['subtotal'] = $subtotal;

                return response()->json($respons);

            }

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

            $respons['subtotal'] = $subtotal;

            return response()->json($respons);

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

            $respons['subtotal'] = $subtotal;

            return response()->json($respons);
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

                $respons['subtotal'] = $subtotal;

                return response()->json($respons);

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
                    $subtotal_kurang_potongan = (($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $tbs_pembelian->potongan);
                    $hasil_tax                = $default_tax + ($tax_persen / 100);
                    $hasil_tax2               = $subtotal_kurang_potongan / $hasil_tax;
                    $tax_include              = $subtotal_kurang_potongan - $hasil_tax2;
                    //perhitungan tax include
                    $tax_produk = 0;
                } elseif ($request->ppn_produk == 'Exclude') {
                    $tax_produk  = (($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $tbs_pembelian->potongan) * $tax_persen / 100;
                    $tax_include = 0;
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
            $tbs_pembelian->update(['subtotal' => $subtotal, 'tax' => $tax_produk, 'tax_include' => $tax_include, 'ppn' => $request->ppn_produk]);
            $nama_barang = $tbs_pembelian->TitleCaseBarang; // TITLE CASH

            $respons['subtotal'] = $subtotal;

            return response()->json($respons);

        }
    }

    //PROSES HAPUS TBS PEMBELIAN
    public function hapus_tbs_pembelian($id)
    {
        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            $tbs_pembelian = TbsPembelian::find($id);

            $respons['subtotal'] = $tbs_pembelian->subtotal;

            $tbs_pembelian->delete();

            return response()->json($respons);

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

            //INSERT DETAIL PEMBELIAN
            $data_produk_pembelian = TbsPembelian::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung);

            // INSERT DETAIL PEMBELIAN
            foreach ($data_produk_pembelian->get() as $data_tbs_pembelian) {
                $barang = Barang::select('harga_beli')->where('id', $data_tbs_pembelian->id_produk)->where('id_warung', Auth::user()->id_warung);

                // UPDATE HARGA BELI - MASTER PRODUK
                if ($barang->first()->harga_beli != $data_tbs_pembelian->harga_produk) {
                    if ($data_tbs_pembelian->status_harga == 1) {

                        if ($data_tbs_pembelian->satuan_id == $data_tbs_pembelian->satuan_dasar) {
                            $barang->update(['harga_beli' => $data_tbs_pembelian->harga_produk]);
                        }
                    }                        
                }

                $detail_pembelian = DetailPembelian::create([
                    'no_faktur'     => $no_faktur,
                    'satuan_id'     => $data_tbs_pembelian->satuan_id,
                    'satuan_dasar'  => $data_tbs_pembelian->satuan_dasar,
                    'id_produk'     => $data_tbs_pembelian->id_produk,
                    'jumlah_produk' => $data_tbs_pembelian->jumlah_produk,
                    'harga_produk'  => $data_tbs_pembelian->harga_produk,
                    'subtotal'      => $data_tbs_pembelian->subtotal,
                    'tax'           => $data_tbs_pembelian->tax,
                    'tax_include'   => $data_tbs_pembelian->tax_include,
                    'potongan'      => $data_tbs_pembelian->potongan,
                    'ppn'           => $data_tbs_pembelian->ppn,
                    'warung_id'     => Auth::user()->id_warung,
                    'created_at'    => $pembelian->created_at,
                    ]);
            }

            //Transaksi Hutang & kas
            $kas = intval($pembelian->tunai) - intval($pembelian->kembalian);
            if ($kas > 0) {
                TransaksiKas::create([
                    'no_faktur'       => $pembelian->no_faktur,
                    'jenis_transaksi' => 'pembelian',
                    'jumlah_keluar'   => $kas,
                    'kas'             => $pembelian->cara_bayar,
                    'warung_id'       => $pembelian->warung_id]);
            }
            if ($pembelian->kredit > 0) {
                TransaksiHutang::create([
                    'no_faktur'       => $pembelian->no_faktur,
                    'jenis_transaksi' => 'pembelian',
                    'id_transaksi'    => $pembelian->id,
                    'jumlah_masuk'    => $pembelian->kredit,
                    'suplier_id'      => $pembelian->suplier_id,
                    'warung_id'       => $pembelian->warung_id]);
            }
            //Transaksi Hutang & kas

            //HAPUS TBS PEMBELIAN
            $data_produk_pembelian->delete();
            DB::commit();

            $respons['respons_pembelian'] = $pembelian->id;
            return response()->json($respons);

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
                'satuan_dasar'  => $data_tbs->satuan_dasar,
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
    public function cetakBesar($id)
    {
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        $pembelian = Pembelian::QueryCetak($id)->first();
        if ($pembelian['suplier_id'] == '0') {
            $nama_suplier   = '-';
            $alamat_suplier = "-";
        } else {
            $nama_suplier   = $pembelian['suplier'];
            $alamat_suplier = $pembelian['alamat_suplier'];
        }

        $detail_pembelian = DetailPembelian::with('produk')->where('no_faktur', $pembelian['no_faktur'])->where('warung_id', Auth::user()->id_warung)->get();
        $terbilang        = $this->kekata($pembelian->total);
        $subtotal         = 0;
        foreach ($detail_pembelian as $detail_pembelians) {
            $subtotal += $detail_pembelians->subtotal;

        }

        return view('pembelian.cetak_besar', ['pembelian' => $pembelian, 'detail_pembelian' => $detail_pembelian, 'subtotal' => $subtotal, 'terbilang' => $terbilang, 'nama_suplier' => $nama_suplier, 'alamat_suplier' => $alamat_suplier, 'setting_aplikasi' => $setting_aplikasi])->with(compact('html'));
    }

    public function cekSubtotalTbsPembelian($jenis_tbs)
    {
        $session_id  = session()->getId();
        $user_warung = Auth::user()->id_warung;

        $TbsPembelian        = new TbsPembelian();
        $subtotal            = $TbsPembelian->subtotalTbs($user_warung, $session_id);
        $respons['subtotal'] = $subtotal;

        return response()->json($respons);
    }

    public function importExcel(Request $request)
    {
        // validasi untuk memastikan file yang diupload adalah excel
        $this->validate($request, ['excel' => 'required']);
        // ambil file yang baru diupload
        $excel = $request->file('excel');
        // baca sheet pertama
        $excels = Excel::selectSheetsByIndex(0)->load($excel, function ($reader) {
        })->get();
        $session_id = session()->getId();

        // rule untuk validasi setiap row pada file excel
        $rowRules = [
        'Kode Produk'   => 'required',
        'Jumlah Produk' => 'required',
        'Satuan Produk' => 'required',
        'Harga Produk'  => 'required',
        'Subtotal'      => 'required',
        'Tax'           => 'required',
        'Potongan'      => 'required',
        ];

        // Catat semua id buku baru
        // ID ini kita butuhkan untuk menghitung total buku yang berhasil diimport
        $produk_id  = [];
        $errors     = [];
        $lineErrors = [];
        $no         = 1;

        // looping setiap baris, mulai dari baris ke 2 (karena baris ke 1 adalah nama kolom)
        foreach ($excels as $row) {
            // Mengubah Nama Produk Menajdi lowerCase (Huruf Kecil Semua)
            $kode_produk = $row['kode_produk'];
            $db_produk   = Barang::select(['kode_barang', 'nama_barang'])->where('kode_barang', $kode_produk);

            if ($db_produk->count() === 0) {
                $errors['kode_produk'][] = [
                'line'    => $no,
                'message' => 'Kode Produk : ' . $row['kode_produk'] . ' Tidak Terdaftar di Master Data Produk.',
                ];
                $lineErrors[] = $no;
            }
            $no++;
        }

        // Perulang kedua, digunakan untuk menambahkan data produk jika tidak terjadi error.
        foreach ($excels as $row) {
            // Jika terjadi error, maka perintah dihentikan sehingga tidak ada data yg di insert ke database
            if (count($errors) > 0) {
                // Buat variable tipe array, dengan index pesanError.
                $pesan = ['pesanError' => ''];

                // Memasukan nilai error yg terjadi, kedalam variabel $pesan yg sudah kita buat tadi.
                foreach ($errors['kode_produk'] as $key => $value) {
                    if ($value['line'] == end($lineErrors)) {
                        $pesan['pesanError'] .= $value['line'] . '. ' . $value['message'];
                    } else {
                        $pesan['pesanError'] .= $value['line'] . '. ' . $value['message'] . '<br>';
                    }
                }
                return response()->json($pesan);
            }

            $db_produk = Barang::select(['id', 'satuan_id'])->where('kode_barang', $row['kode_produk'])->first();

            // Membuat validasi untuk row di excel, disini kita ubah baris yang sedang di proses menjadi array.
            $validator = Validator::make($row->toArray(), $rowRules);

            // Insert Detail Item Masuk
            $tbs_pembelian = TbsPembelian::create([
                'session_id'    => $session_id,
                'satuan_id'     => $db_produk->satuan_id,
                'satuan_dasar'  => $db_produk->satuan_id,
                'id_produk'     => $db_produk->id,
                'jumlah_produk' => $row['jumlah_produk'],
                'harga_produk'  => $row['harga_produk'],
                'subtotal'      => $row['subtotal'],
                'tax'           => $row['tax'],
                'potongan'      => $row['potongan'],
                'warung_id'     => Auth::user()->id_warung,
                ]);
        }
        // Hitung Jumlah Produk Yang Diimport
        $hitung_produk['jumlahProduk'] = $no - 1;

        return response()->json($hitung_produk);
    }

    //DOWNLAOD TEMPLATE
    public function templateExcel()
    {
        Excel::create('Tempalate Import Pembelian', function ($excel) {
            // Set property
            $excel->sheet('Tempalate Import Pembelian', function ($sheet) {
                $row = 1;
                $sheet->row($row, [
                    'Kode Produk',
                    'Jumlah Produk',
                    'Harga Produk',
                    'Subtotal',
                    'Tax',
                    'Potongan',
                    ]);

                $sheet->row(++$row, [
                    '001',
                    '20',
                    '10000',
                    '200000',
                    '0',
                    '0',
                    ]);

            });
        })->download('xls');
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


    public function otoritasPembelian(){

        if (Laratrust::can('tambah_pembelian')) {
            $tambah_pembelian = 1;
        }else{
            $tambah_pembelian = 0;            
        }
        if (Laratrust::can('edit_pembelian')) {
            $edit_pembelian = 1;
        }else{
            $edit_pembelian = 0;            
        }
        if (Laratrust::can('hapus_pembelian')) {
            $hapus_pembelian = 1;
        }else{
            $hapus_pembelian = 0;            
        }
        $respons['tambah_pembelian'] = $tambah_pembelian;
        $respons['edit_pembelian'] = $edit_pembelian;
        $respons['hapus_pembelian'] = $hapus_pembelian;

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


    public function editSatuanTbsPembelian(Request $request){

        $db = 'App\TbsPembelian';
        $respons = $this->editSatuan($request, $db);

        return response()->json($respons);
    }


    public function editSatuanEditTbsPembelian(Request $request){

        $db = 'App\EditTbsPembelian';
        $respons = $this->editSatuan($request, $db);

        return response()->json($respons);
    }

    // ORDER PEMBELIAN

    // GET PEMEBLIAN ORDER - PEMBELIAN
    public function prosesTbsOrderPembelian(Request $request){

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        }else{

            $data_orders = DetailPembelianOrder::where('no_faktur_order', $request->faktur_order)
            ->where('warung_id', Auth::user()->id_warung)->get();

            $session_id = session()->getId();
            $subtotal = 0;

            // HAPUS DATA TBS SUPLIER LAMA, JIKA TIBA TIBA SUPLIER DIUBAH
            $hapus_tbs = TbsPembelian::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)
            ->where('faktur_order', '!=', NULL)->delete();

            foreach ($data_orders as $data_order) {

                $insert_tbs = TbsPembelian::create([
                    'session_id'        => $session_id,
                    'faktur_order'      => $data_order->no_faktur_order,
                    'id_produk'         => $data_order->id_produk,
                    'jumlah_produk'     => $data_order->jumlah_produk,
                    'satuan_id'         => $data_order->satuan_id,
                    'satuan_dasar'      => $data_order->satuan_dasar,
                    'harga_produk'      => $data_order->harga_produk,
                    'subtotal'          => $data_order->subtotal,
                    'status_harga'      => $data_order->status_harga,
                    'warung_id'         => $data_order->warung_id,
                    ]);
            }


            $respons['status']   = 0;
            $respons['subtotal'] = 0;

            return response()->json($respons);
        }

    }
}
