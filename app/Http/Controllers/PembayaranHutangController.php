<?php

namespace App\Http\Controllers;

use App\Kas;
use App\PembayaranHutang;
use App\SettingAplikasi;
use App\Suplier;
use App\TbsPembayaranHutang;
use App\TransaksiKas;
use App\Pembelian;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Yajra\Datatables\Html\Builder;

class PembayaranHutangController extends Controller
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
        //
         if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            return response(200);
        }
    }


     public function view()
    {
        $pembayaranhutang = PembayaranHutang::select('pembayaran_hutangs.id_pembayaran_hutang as id', 'pembayaran_hutangs.no_faktur_pembayaran as no_faktur', 'supliers.nama_suplier as nama_suplier', 'pembayaran_hutangs.created_at as created_at', 'pembayaran_hutangs.total as total', 'kas.nama_kas as nama_kas','users.name as name','pembayaran_hutangs.keterangan as keterangan')->leftJoin('supliers', 'pembayaran_hutangs.suplier_id', '=', 'supliers.id')->leftJoin('kas', 'pembayaran_hutangs.cara_bayar', '=', 'kas.id')->leftJoin('users', 'pembayaran_hutangs.created_by', '=', 'users.id')
            ->where('pembayaran_hutangs.warung_id', Auth::user()->id_warung)->orderBy('pembayaran_hutangs.id_pembayaran_hutang')->paginate(10);
        $array = array();
        foreach ($pembayaranhutang as $pembayaranhutangs) {
            array_push($array, [
                'id'               => $pembayaranhutangs->id,
                'no_faktur'        => $pembayaranhutangs->no_faktur,
                'waktu'            => $pembayaranhutangs->Waktu,
                'suplier'          => $pembayaranhutangs->nama_suplier,
                'total'            => $pembayaranhutangs->getTotalSeparator(),
                'kas'              => $pembayaranhutangs->nama_kas,
                'keterangan'       => $pembayaranhutangs->keterangan,
                'user_buat'        => $pembayaranhutangs->name,
            ]);
        }

        $url     = '/pembayaran-hutang/view';
        $respons = $this->dataPagination($pembayaranhutang, $array,$url); 
        return response()->json($respons); 
    }

    //VIEW DAN PENCARIAN Tbs Pembayaran Hutang
     public function viewTbsPembayaranHutang()
    {
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;
        $tbs_pembayaran_hutang = TbsPembayaranHutang::with(['suplier'])->where('warung_id', $user_warung)->where('session_id', $session_id)->orderBy('id_tbs_pembayaran_hutang', 'desc')->paginate(10);
        $array         = array();

        foreach ($tbs_pembayaran_hutang as $tbs_pembayaran_hutangs) {

            array_push($array, [
                'id'                => $tbs_pembayaran_hutangs->id_tbs_pembayaran_hutang,
                'no_faktur_pembelian'  => $tbs_pembayaran_hutangs->no_faktur_pembelian,
                'suplier'          => $tbs_pembayaran_hutangs->suplier->nama,
                'jatuh_tempo'      => $tbs_pembayaran_hutangs->jatuh_tempo,
                'hutang'           => $tbs_pembayaran_hutangs->hutang,
                'potongan'         => $tbs_pembayaran_hutangs->potongan,
                'jumlah_bayar'     => $tbs_pembayaran_hutangs->jumlah_bayar
            ]);
        }
        $url     = '/pembayaran-hutang/view-tbs-pembayaran-hutang';
        $respons = $this->dataPagination($tbs_pembayaran_hutang, $array); 
        return response()->json($respons); 
    }
    public function PencarianTbsPembayaranHutang()
    {
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;
        $tbs_pembayaran_hutang = TbsPembayaranHutang::with(['suplier'])->where('warung_id', $user_warung)->where('session_id', $session_id)
        ->where(function ($query) use ($request) {
                $query->orWhere('no_faktur_pembelian', 'LIKE', $request->search . '%')
                    ->orWhere('jatuh_tempo', 'LIKE', $request->search . '%');
            })->orderBy('id_tbs_pembayaran_hutang', 'desc')->paginate(10);
        $array         = array();

        foreach ($tbs_pembayaran_hutang as $tbs_pembayaran_hutangs) {

            array_push($array, [
                'id'                => $tbs_pembayaran_hutangs->id_tbs_pembayaran_hutang,
                'no_faktur_pembelian'  => $tbs_pembayaran_hutangs->no_faktur_pembelian,
                'suplier'          => $tbs_pembayaran_hutangs->suplier->nama,
                'jatuh_tempo'      => $tbs_pembayaran_hutangs->jatuh_tempo,
                'hutang'           => $tbs_pembayaran_hutangs->hutang,
                'potongan'         => $tbs_pembayaran_hutangs->potongan,
                'jumlah_bayar'     => $tbs_pembayaran_hutangs->jumlah_bayar
            ]);
        }
        $url     = '/pembayaran-hutang/view-tbs-pembayaran-hutang';
        $respons = $this->dataPagination($tbs_pembayaran_hutang, $array); 
        return response()->json($respons); 
    }



        //VIEW DAN PENCARIAN
     public function dataSupplierHutang(Request $request)
    {
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;
        $id_suplier = $request->id;
        $data_supplier_hutang = Pembelian::where('suplier_id', $id_suplier)->orderBy('id', 'desc')->paginate(10);
        $array         = array();

        foreach ($data_supplier_hutang as $data_supplier_hutangs) {
            array_push($array, [
                'no_faktur'                 => $data_supplier_hutangs->no_faktur,
                'total'                     => $data_supplier_hutangs->total,
                'nilai_kredit'              => $data_supplier_hutangs->nilai_kredit,
                'tanggal_jatuh_tempo'       => $data_supplier_hutangs->JatuhTempo,
                'waktu'                     => $data_supplier_hutangs->Waktu,
                'status_pembelian'          => $data_supplier_hutangs->status_pembelian
            ]);
        }
        $url     = '/pembayaran-hutang/data-suplier-hutang';
        $respons = $this->dataPagination($data_supplier_hutang, $array); 
        return response()->json($respons); 
    }



        public function dataPagination($pembayaranhutang, $array,$url) 
    { 
        //DATA PAGINATION
        $respons['current_page']   = $pembayaranhutang->currentPage();
        $respons['data']           = $array;
        $respons['first_page_url'] = url($url.'?page=' . $pembayaranhutang->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $pembayaranhutang->lastPage();
        $respons['last_page_url']  = url($url.'?page=' . $pembayaranhutang->lastPage());
        $respons['next_page_url']  = $pembayaranhutang->nextPageUrl();
        $respons['path']           = url($url);
        $respons['per_page']       = $pembayaranhutang->perPage();
        $respons['prev_page_url']  = $pembayaranhutang->previousPageUrl();
        $respons['to']             = $pembayaranhutang->perPage();
        $respons['total']          = $pembayaranhutang->total();
        //DATA PAGINATION
        return $respons; 
    } 
     public function dataPaginationPencarianData($pembelian, $array,$url, $search)
    {
        //DATA PAGINATION
        $respons['current_page']   = $pembelian->currentPage();
        $respons['data']           = $array;
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

        public function pilihSuplier()
    {
        $suplier = Suplier::where('warung_id', Auth::user()->id_warung)->get();
        $array  = array();
        foreach ($suplier as $supliers) {
            array_push($array, [
                'id'          => $supliers->id,
                'nama_suplier'      => $supliers->nama_suplier]);
        }

        return response()->json($array);
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
        //
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
