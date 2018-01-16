<?php

namespace App\Http\Controllers;

use App\Kas;
use App\PembayaranHutang;
use App\SettingAplikasi;
use App\Suplier;
use App\TbsPembayaranHutang;
use App\TransaksiKas;
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

        //DATA PAGINATION
        $respons['current_page']   = $pembayaranhutang->currentPage();
        $respons['data']           = $array;
        $respons['first_page_url'] = url('/pembayaranhutang/view?page=' . $pembayaranhutang->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $pembayaranhutang->lastPage();
        $respons['last_page_url']  = url('/pembayaranhutang/view?page=' . $pembayaranhutang->lastPage());
        $respons['next_page_url']  = $pembayaranhutang->nextPageUrl();
        $respons['path']           = url('/pembayaranhutang/view');
        $respons['per_page']       = $pembayaranhutang->perPage();
        $respons['prev_page_url']  = $pembayaranhutang->previousPageUrl();
        $respons['to']             = $pembayaranhutang->perPage();
        $respons['total']          = $pembayaranhutang->total();
        //DATA PAGINATION
        return response()->json($respons);
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
