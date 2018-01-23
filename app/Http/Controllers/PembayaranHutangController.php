<?php

namespace App\Http\Controllers;

use App\Kas;
use App\PembayaranHutang;
use App\SettingAplikasi;
use App\Suplier;
use App\TbsPembayaranHutang;
use App\DetailPembayaranHutang;
use App\TransaksiHutang;
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
                'total'            => $pembayaranhutangs->PemisahTotal,
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
        $tbs_pembayaran_hutang = TbsPembayaranHutang::dataTbsPembayaranHutang($session_id)->paginate(10);
        $array         = array();

        foreach ($tbs_pembayaran_hutang as $tbs_pembayaran_hutangs) {
                $sisa_hutang = $tbs_pembayaran_hutangs->subtotal_hutang - $tbs_pembayaran_hutangs->jumlah_bayar;
            array_push($array, [
                'id'                => $tbs_pembayaran_hutangs->id_tbs_pembayaran_hutang,
                'no_faktur_pembelian'  => $tbs_pembayaran_hutangs->no_faktur_pembelian,
                'suplier'          => $tbs_pembayaran_hutangs->nama_suplier,
                'jatuh_tempo'      => $tbs_pembayaran_hutangs->jatuh_tempo,
                'subtotal_hutang'      => $tbs_pembayaran_hutangs->subtotal_hutang,
                'hutang'           => $tbs_pembayaran_hutangs->hutang,
                'potongan'         => $tbs_pembayaran_hutangs->potongan,
                'jumlah_bayar'     => $tbs_pembayaran_hutangs->jumlah_bayar,
                'sisa_hutang'      => $sisa_hutang,
            ]);
        }
        $url     = '/pembayaran-hutang/view-tbs-pembayaran-hutang';
        $respons = $this->dataPagination($tbs_pembayaran_hutang, $array,$url); 
        return response()->json($respons); 
    }
         public function pencarianTbsPembayaranHutang(Request $request)
    {
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;
        $search = $request->search;
        $tbs_pembayaran_hutang = TbsPembayaranHutang::cariTbsPembayaranHutang($request, $session_id)->paginate(10);;
        $array         = array();

        foreach ($tbs_pembayaran_hutang as $tbs_pembayaran_hutangs) {
                $sisa_hutang = $tbs_pembayaran_hutangs->subtotal_hutang - $tbs_pembayaran_hutangs->jumlah_bayar;
            array_push($array, [
                'id'                => $tbs_pembayaran_hutangs->id_tbs_pembayaran_hutang,
                'no_faktur_pembelian'  => $tbs_pembayaran_hutangs->no_faktur_pembelian,
                'suplier'          => $tbs_pembayaran_hutangs->nama_suplier,
                'jatuh_tempo'      => $tbs_pembayaran_hutangs->jatuh_tempo,
                'subtotal_hutang'      => $tbs_pembayaran_hutangs->subtotal_hutang,
                'hutang'           => $tbs_pembayaran_hutangs->hutang,
                'potongan'         => $tbs_pembayaran_hutangs->potongan,
                'jumlah_bayar'     => $tbs_pembayaran_hutangs->jumlah_bayar,
                'sisa_hutang'      => $sisa_hutang,
            ]);
        }
        $url     = '/pembayaran-hutang/view-tbs-pembayaran-hutang';
        $respons = $this->dataPaginationPencarianData($tbs_pembayaran_hutang, $array,$url,$search); 
        return response()->json($respons); 
    }

        //VIEW DAN PENCARIAN dataSupplierHutang
     public function dataSupplierHutang($id)
    {
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;
        $id_suplier     = $id;
        $data_supplier_hutang = Pembelian::where('suplier_id', $id_suplier)->where('warung_id',$user_warung)->orderBy('id', 'desc')->paginate(10);
        $array         = array();
        foreach ($data_supplier_hutang as $data_supplier_hutangs) {
            array_push($array, [
                'id_pembelian'             => $data_supplier_hutangs->id,
                'no_faktur'                 => $data_supplier_hutangs->no_faktur,
                'total'                     => $data_supplier_hutangs->total,
                'nilai_kredit'              => $data_supplier_hutangs->nilai_kredit,
                'tanggal_jatuh_tempo'       => $data_supplier_hutangs->JatuhTempo,
                'waktu'                     => $data_supplier_hutangs->Waktu,
                'status_pembelian'          => $data_supplier_hutangs->status_pembelian
            ]);
        }
        $url     = '/pembayaran-hutang/data-suplier-hutang';
        $respons = $this->dataPagination($data_supplier_hutang, $array,$url); 
        return response()->json($respons); 
    }
    public function pencarianDataSupplierHutang(Request $request,$id)
    {
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;
        $id_suplier = $id;
        $search = $request->search;
        $data_supplier_hutang = Pembelian::where('suplier_id', $id_suplier)->where('warung_id',$user_warung)
        ->where(function ($query) use ($request) {
        $query->orWhere('no_faktur', 'LIKE', $request->search . '%')
         ->orWhere('nilai_kredit', 'LIKE', $request->search . '%');
        })->orderBy('id', 'desc')->paginate(10);

        $array         = array();
        foreach ($data_supplier_hutang as $data_supplier_hutangs) {
            array_push($array, [
                'id_pembelian'             => $data_supplier_hutangs->id,
                'no_faktur'                 => $data_supplier_hutangs->no_faktur,
                'total'                     => $data_supplier_hutangs->total,
                'nilai_kredit'              => $data_supplier_hutangs->nilai_kredit,
                'tanggal_jatuh_tempo'       => $data_supplier_hutangs->JatuhTempo,
                'waktu'                     => $data_supplier_hutangs->Waktu,
                'status_pembelian'          => $data_supplier_hutangs->status_pembelian
            ]);
        }
        $url     = '/pembayaran-hutang/data-suplier-hutang';
        $respons = $this->dataPaginationPencarianData($data_supplier_hutang, $array,$url,$search); 
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
    //INSERT TBS
    public function prosesTbsPembayaranHutang(Request $request)
    {
        $session_id = session()->getId();
        $pembelian   = Pembelian::find($request->id_pembelian);
        $data_tbs   = TbsPembayaranHutang::where('no_faktur_pembelian', $request->no_faktur)
            ->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung);
        $data_suplier_tbs   = TbsPembayaranHutang::select('suplier_id')->where('no_faktur_pembelian', $request->no_faktur)
            ->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->get();    
            
         $subtotal_hutang       = $request->nilai_kredit  - $request->potongan;
        //JIKA FAKTUR YG DIPILIH SUDAH ADA DI TBS
            if ($data_tbs->count() > 0  ) {
                return 0;
            }
             else {
                    $tbs_pembayaran_piutang = TbsPembayaranHutang::create([
                            'session_id'          => $session_id,
                            'no_faktur_pembelian' => $request->no_faktur,
                            'jatuh_tempo'         => $pembelian->tanggal_jt_tempo,
                            'hutang'             => $request->nilai_kredit,
                            'potongan'            => $request->potongan,
                            'subtotal_hutang'      => $subtotal_hutang,
                            'jumlah_bayar'        => $request->jumlah_bayar,
                            'suplier_id'        => $pembelian->suplier_id,
                            'warung_id'           => Auth::user()->id_warung,
                    ]);
                    $respons['jumlah_bayar'] = $request->jumlah_bayar;
                    return response()->json($respons);
                }
    }
        public function prosesHapusTbsPembayaranHutang($id)
    {
        if (!TbsPembayaranHutang::destroy($id)) {
            return 0;
        } else {
            return response(200);
        }
    }
        public function prosesEditTbsPembayaranHutang(Request $request)
    {
        $tbs_pembayaran_hutang = TbsPembayaranHutang::find($request->id_tbs);
        $subtotal               = $request->nilai_kredit - $request->potongan;
        $tbs_pembayaran_hutang->update(['potongan' => $request->potongan, 'subtotal_hutang' => $subtotal, 'jumlah_bayar' => $request->jumlah_bayar]);
        $respons['jumlah_bayar'] = $request->jumlah_bayar;

        return response()->json($respons);
    }
    //PROSES BATAL 
    public function proses_batal_transaksi_pembayaran_hutang()
    {
        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            $session_id         = session()->getId();
            $data_tbs_pembelian = TbsPembayaranHutang::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->delete();
        }
    }

    public function dataKas()
    {
        $kas = Kas::select('id', 'nama_kas', 'default_kas')->where('warung_id', Auth::user()->id_warung)->where('status_kas', 1)->get();
        return response()->json($kas);
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
        $no_faktur  = PembayaranHutang::no_faktur($warung_id);

        $this->validate($request, [
            'kas'     => 'required',
            'tanggal' => 'required',
        ]);

        $data_pembayaran_hutang = TbsPembayaranHutang::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung);

        if ($data_pembayaran_hutang->count() == 0) {
            return 0;
        } else {
            //INSERT PEMBAYARAN hutang
            $pembayaran_hutang = PembayaranHutang::create([
                'no_faktur_pembayaran' => $no_faktur,
                'total'                => $request->subtotal,
                'cara_bayar'           => $request->kas,
                'suplier_id'           => $data_pembayaran_hutang->first()->suplier_id,
                'keterangan'           => $request->keterangan,
                'warung_id'            => $warung_id,
            ]);

            // INSERT DETAIL PEMBAYARAN hutang
            foreach ($data_pembayaran_hutang->get() as $data_tbs) {

                $detail_pembayaran_hutang = DetailPembayaranHutang::create([
                    'no_faktur_pembayaran' => $no_faktur,
                    'no_faktur_pembelian'  => $data_tbs->no_faktur_pembelian,
                    'jatuh_tempo'          => $data_tbs->jatuh_tempo,
                    'hutang'              => $data_tbs->hutang,
                    'potongan'             => $data_tbs->potongan,
                    'jumlah_bayar'         => $data_tbs->jumlah_bayar,
                    'suplier_id'         => $data_tbs->suplier_id,
                    'warung_id'            => $data_tbs->warung_id,
                ]);

                // INSERT TRANSAKSI hutang TIDAK DIBUAT DI OBSERVER KARENA DI OBSERVER ID pembelian DI ANGGAP NULL
                $id_pembelian  = Pembelian::select('id')->where('no_faktur', $data_tbs->no_faktur_pembelian)->first();
                $transaksi_hutang = TransaksiHutang::create([
                    'no_faktur'       => $no_faktur,
                    'id_transaksi'    => $id_pembelian->id,
                    'jenis_transaksi' => 'Pembayaran Hutang',
                    'jumlah_keluar'   => $data_tbs->jumlah_bayar,
                    'suplier_id'    => $data_tbs->suplier_id,
                    'warung_id'       => $data_tbs->warung_id,
                ]);
            }

            //HAPUS TBS PEMBAYARAN hutang
            $data_pembayaran_hutang->delete();
            DB::commit();

            $respons['respons_pembayaran'] = $pembayaran_hutang->id_pembayaran_hutang;

            return response()->json($respons);
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
