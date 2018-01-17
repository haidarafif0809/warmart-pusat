<?php

namespace App\Http\Controllers;

use App\PembayaranPiutang;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;

class PembayaranPiutangController extends Controller
{
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
            return response(200);
        }
    }

    public function dataPagination($pembayaran_piutang, $array_pembayaran_piutang)
    {

        $respons['current_page']   = $pembayaran_piutang->currentPage();
        $respons['data']           = $array_pembayaran_piutang;
        $respons['first_page_url'] = url('/pembayaran-piutang/view?page=' . $pembayaran_piutang->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $pembayaran_piutang->lastPage();
        $respons['last_page_url']  = url('/pembayaran-piutang/view?page=' . $pembayaran_piutang->lastPage());
        $respons['next_page_url']  = $pembayaran_piutang->nextPageUrl();
        $respons['path']           = url('/pembayaran-piutang/view');
        $respons['per_page']       = $pembayaran_piutang->perPage();
        $respons['prev_page_url']  = $pembayaran_piutang->previousPageUrl();
        $respons['to']             = $pembayaran_piutang->perPage();
        $respons['total']          = $pembayaran_piutang->total();

        return $respons;
    }

    public function view()
    {
        $pembayaran_piutang = PembayaranPiutang::select('pembayaran_piutangs.id_pembayaran_piutang as id', 'pembayaran_piutangs.no_faktur_pembayaran as no_faktur', 'pembayaran_piutangs.created_at as waktu', 'pembayaran_piutangs.total as total', 'kas.nama_kas as nama_kas', 'users.name as petugas', 'pembayaran_piutangs.keterangan as keterangan')->leftJoin('kas', 'pembayaran_piutangs.cara_bayar', '=', 'kas.id')->leftJoin('users', 'pembayaran_piutangs.created_by', '=', 'users.id')
            ->where('pembayaran_piutangs.warung_id', Auth::user()->id_warung)
            ->orderBy('pembayaran_piutangs.id_pembayaran_piutang')->paginate(10);

        $array_pembayaran_piutang = array();
        foreach ($pembayaran_piutang as $pembayaran_piutangs) {
            array_push($array_pembayaran_piutang, [
                'id'         => $pembayaran_piutangs->id,
                'no_faktur'  => $pembayaran_piutangs->no_faktur,
                'waktu'      => $pembayaran_piutangs->waktu,
                'total'      => $pembayaran_piutangs->getTotalSeparator(),
                'kas'        => $pembayaran_piutangs->nama_kas,
                'keterangan' => $pembayaran_piutangs->keterangan,
                'user_buat'  => $pembayaran_piutangs->petugas,
            ]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($pembayaran_piutang, $array_pembayaran_piutang);
        return response()->json($respons);
    }
}
