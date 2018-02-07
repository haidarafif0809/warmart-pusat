<?php

namespace App\Http\Controllers;

use App\Kas;
use App\PembayaranHutang;
use App\SettingAplikasi;
use App\Suplier;
use App\TbsPembayaranHutang;
use App\EditTbsPembayaranHutang;
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
        $pembayaranhutang = PembayaranHutang::datapembayaranHutang()->paginate(10);
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
         public function pencarian(Request $request)
    {
        $search = $request->search;
        $pembayaranhutang = PembayaranHutang::datacariPembayaranHutang($request)->paginate(10);
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
        $respons = $this->dataPaginationPencarianData($pembayaranhutang, $array,$url,$search); 
        return response()->json($respons); 
    }

    //VIEW DAN PENCARIAN Tbs Pembayaran Hutang
     public function viewTbsPembayaranHutang()
    {
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;
        $tbs_pembayaran_hutang = TbsPembayaranHutang::dataTbsPembayaranHutang($session_id)->paginate(10);
        $jenis_tbs          = 1;

        $array         = $this->foreachTbs($tbs_pembayaran_hutang, $jenis_tbs);

        $url     = '/pembayaran-hutang/view-tbs-pembayaran-hutang';
        $respons = $this->dataPagination($tbs_pembayaran_hutang, $array,$url); 
        return response()->json($respons); 
    }
         public function pencarianTbsPembayaranHutang(Request $request)
    {
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;
        $search = $request->search;
        $tbs_pembayaran_hutang = TbsPembayaranHutang::cariTbsPembayaranHutang($request, $session_id)->paginate(10);
        $jenis_tbs          = 1;

        $array         = $this->foreachTbs($tbs_pembayaran_hutang,$jenis_tbs);

        $url     = '/pembayaran-hutang/view-tbs-pembayaran-hutang';
        $respons = $this->dataPaginationPencarianData($tbs_pembayaran_hutang, $array,$url,$search); 
        return response()->json($respons); 
    }

    //VIEW DAN PENCARIAN EDIT TBS PEMBAYARAN HUTANG 
     public function viewTbsEdit($id)
    {
        $session_id = session()->getId();
        //AMBIL NO FAKTUR
        $no_faktur          = $this->fakturPembayaran($id);
        $pembayaran_piutang = EditTbsPembayaranHutang::dataEditTbsPembayaranHutang($session_id, $no_faktur)->paginate(10);
        $jenis_tbs          = 2;

        $array_pembayaran_piutang = $this->foreachTbs($pembayaran_piutang, $jenis_tbs);
        $link_view                = '/pembayaran-hutang/view-edit-tbs-pembayaran-hutang';

        //DATA PAGINATION
        $respons = $this->dataPagination($pembayaran_piutang, $array_pembayaran_piutang, $link_view);
        return response()->json($respons);
    }

    public function pencarianTbsEdit(Request $request, $id)
    {
        $session_id = session()->getId();
        $search = $request->search;
        //AMBIL NO FAKTUR
        $no_faktur          = $this->fakturPembayaran($id);
        $pembayaran_piutang = EditTbsPembayaranHutang::dataCariEditTbsPembayaranHutang($request, $session_id, $no_faktur)->paginate(10);
        $jenis_tbs          = 2;

        $array_pembayaran_piutang = $this->foreachTbs($pembayaran_piutang, $jenis_tbs);
        $link_view                = '/pembayaran-hutang/view-edit-tbs-pembayaran-hutang';

        //DATA PAGINATION
        $respons = $this->dataPaginationPencarianData($pembayaran_piutang, $array_pembayaran_piutang, $link_view,$search);
        return response()->json($respons);
    }


        //VIEW DAN PENCARIAN dataSupplierHutang
     public function dataSupplierHutang($id)
    {
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;
        $id_suplier     = $id;         

        $data_supplier_hutang = TransaksiHutang::getDataPembelianHutang($id_suplier)->paginate(10);
        $data_supplier_hutang_get = TransaksiHutang::getDataPembelianHutang($id_suplier)->having('sisa_hutang', '>', 0)->get();

        $array         = array();
        foreach ($data_supplier_hutang_get as $data_supplier_hutangs) {
            array_push($array, [
                'id_pembelian'             => $data_supplier_hutangs->id,
                'no_faktur'                 => $data_supplier_hutangs->no_faktur,
                'total'                     => $data_supplier_hutangs->total,
                'nilai_kredit'              => $data_supplier_hutangs->sisa_hutang,
                'tanggal_jatuh_tempo'       => $data_supplier_hutangs->tanggal_jt_tempo,
                'waktu'                     => $data_supplier_hutangs->Waktu
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
        $data_supplier_hutang = TransaksiHutang::getDataCariPembelianHutang($id_suplier,$request)->paginate(10);
        $data_supplier_hutang_get = TransaksiHutang::getDataCariPembelianHutang($id_suplier,$request)->having('sisa_hutang', '>', 0)->get();

        $array         = array();
        foreach ($data_supplier_hutang_get as $data_supplier_hutangs) {
            array_push($array, [
                'id_pembelian'             => $data_supplier_hutangs->id,
                'no_faktur'                 => $data_supplier_hutangs->no_faktur,
                'total'                     => $data_supplier_hutangs->total,
                'nilai_kredit'              => $data_supplier_hutangs->sisa_hutang,
                'tanggal_jatuh_tempo'       => $data_supplier_hutangs->tanggal_jt_tempo,
                'waktu'                     => $data_supplier_hutangs->Waktu
            ]);
        }
        $url     = '/pembayaran-hutang/data-suplier-hutang';
        $respons = $this->dataPaginationPencarianData($data_supplier_hutang, $array,$url,$search); 
        return response()->json($respons); 
    }

      public function viewDetail($id)
    {
        $data_pembayaran_hutang = PembayaranHutang::find($id);
        $no_faktur_pembayaran = $data_pembayaran_hutang->no_faktur_pembayaran;
        $user_warung        = Auth::user()->id_warung;
        $pembayaran_hutang = DetailPembayaranHutang::dataDetailPembayaranHutang($no_faktur_pembayaran)->paginate(10);
        $array_pembayaran_hutang = $this->foreachDetail($pembayaran_hutang);
        $link_view                = 'view-detail-pembayaran-hutang/' . $id;

        //DATA PAGINATION
        $respons = $this->dataPagination($pembayaran_hutang, $array_pembayaran_hutang, $link_view);
        return response()->json($respons);
    }

    public function pencarianDetail(Request $request, $id)
    {
        $data_pembayaran_hutang = PembayaranHutang::find($id);
        $no_faktur_pembayaran = $data_pembayaran_hutang->no_faktur_pembayaran;
        $user_warung        = Auth::user()->id_warung;
        $pembayaran_hutang = DetailPembayaranHutang::cariDetailPembayaranHutang($request, $no_faktur_pembayaran)->paginate(10);
        $search = $request->search;
        $array_pembayaran_hutang = $this->foreachDetail($pembayaran_hutang);
        $link_view                = 'pencarian-detail-pembayaran-hutang/' . $id;

        //DATA PAGINATION
        $respons = $this->dataPaginationPencarianData($pembayaran_hutang, $array_pembayaran_hutang, $link_view,$search);
        return response()->json($respons);
    }


        public function foreachDetail($pembayaran_hutang)
    {
        $array_pembayaran_hutang = array();
        foreach ($pembayaran_hutang as $detail_pembayaran_hutangs) {

            $sisa_hutang     = $detail_pembayaran_hutangs->subtotal_hutang - $detail_pembayaran_hutangs->jumlah_bayar;

            array_push($array_pembayaran_hutang, [
                'no_faktur_pembelian'       => $detail_pembayaran_hutangs->no_faktur_pembelian,
                'jatuh_tempo'               => $detail_pembayaran_hutangs->jatuh_tempo,
                'hutang'                   => $detail_pembayaran_hutangs->hutang,
                'potongan'                  => $detail_pembayaran_hutangs->potongan,
                'total'                     => $detail_pembayaran_hutangs->subtotal_hutang,
                'jumlah_bayar'              => $detail_pembayaran_hutangs->jumlah_bayar,
                'sisa_hutang'              => $sisa_hutang,
                'suplier'                 => $detail_pembayaran_hutangs->nama_suplier,
                'id_detail_pembayaran_hutang' => $detail_pembayaran_hutangs->id_detail_pembayaran_hutang,
            ]);
        }

        return $array_pembayaran_hutang;
    }

        public function foreachTbs($tbs_pembayaran_hutang,$jenis_tbs)
    {
        $array_pembayaran_hutang  = array();

        foreach ($tbs_pembayaran_hutang as $tbs_pembayaran_hutangs) {

            if ($jenis_tbs == 1) {
                // JIKA JENIS TBS == 1, MAKA ambil "id_tbs_pembayaran_hutang"
                $id_tbs = $tbs_pembayaran_hutangs->id_tbs_pembayaran_hutang;
            } else {
            // JIKA JENIS TBS == 2, MAKA ambil "id_edit_tbs_pembayaran_hutang"
                $id_tbs = $tbs_pembayaran_hutangs->id_edit_tbs_pembayaran_hutang;
            }

                $sisa_hutang = $tbs_pembayaran_hutangs->subtotal_hutang - $tbs_pembayaran_hutangs->jumlah_bayar;
            array_push($array_pembayaran_hutang, [
                'id'                => $id_tbs,
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

        return $array_pembayaran_hutang;
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
        $data_supplier_hutang = TransaksiHutang::dataPembelianHutang()->get();
        $array  = array();
        foreach ($data_supplier_hutang as $data_supplier_hutangs) {
            array_push($array, [
                'id'          => $data_supplier_hutangs->suplier_id,
                'nama_suplier'      => $data_supplier_hutangs->nama_suplier
            ]);
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
        $data_suplier_tbs   = TbsPembayaranHutang::select('suplier_id')
            ->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->get();    
            
         $subtotal_hutang       = $request->nilai_kredit  - $request->potongan;
        //JIKA FAKTUR YG DIPILIH SUDAH ADA DI TBS
            if ($data_tbs->count() > 0) {
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
        //INSERT TBS
    public function prosesTbsEditPembayaranHutang(Request $request,$id)
    {
        $session_id = session()->getId();
        $pembelian   = Pembelian::find($request->id_pembelian);
        $data_tbs   = EditTbsPembayaranHutang::where('no_faktur_pembelian', $request->no_faktur)
            ->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung);
        $data_suplier_tbs   = EditTbsPembayaranHutang::select('suplier_id')->where('no_faktur_pembelian', $request->no_faktur)
            ->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->get();    
            
         $subtotal_hutang       = $request->nilai_kredit  - $request->potongan;
        //JIKA FAKTUR YG DIPILIH SUDAH ADA DI TBS
            if ($data_tbs->count() > 0  ) {
                return 0;
            }
             else {
                    $tbs_pembayaran_piutang = EditTbsPembayaranHutang::create([
                            'session_id'          => $session_id,
                            'no_faktur_pembelian' => $request->no_faktur,
                            'no_faktur_pembayaran' => $request->no_faktur_pembayaran,
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

    //hapus tbs pembayaran hutang
        public function prosesHapusTbsPembayaranHutang($id)
    {
        if (!TbsPembayaranHutang::destroy($id)) {
            return 0;
        } else {
            return response(200);
        }
    }
     //hapus tbs edit pembayaran hutang
     public function prosesHapusEditTbsPembayaranHutang($id)
    {
        if (!EditTbsPembayaranHutang::destroy($id)) {
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
        public function prosesUpdateTbsEditPembayaranHutang(Request $request)
    {
        $tbs_pembayaran_hutang = EditTbsPembayaranHutang::find($request->id_tbs);
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
            $jam                = date("h:i:s");
            $pembayaran_hutang = PembayaranHutang::create([
                'no_faktur_pembayaran' => $no_faktur,
                'total'                => $request->subtotal,
                'cara_bayar'           => $request->kas,
                'keterangan'           => $request->keterangan,
                'warung_id'            => $warung_id,
                'created_at' => $this->tanggalSql($request->tanggal) . " " . $jam,
                'updated_at' => $this->tanggalSql($request->tanggal) . " " . $jam,
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
                    'suplier_id'            => $data_tbs->suplier_id,
                    'warung_id'            => $data_tbs->warung_id,
                    'subtotal_hutang'       => $data_tbs->subtotal_hutang,
                    'created_at' => $this->tanggalSql($request->tanggal) . " " . $jam,
                    'updated_at' => $this->tanggalSql($request->tanggal) . " " . $jam,

                ]);

                // INSERT TRANSAKSI hutang TIDAK DIBUAT DI OBSERVER KARENA DI OBSERVER ID pembelian DI ANGGAP NULL
                $id_pembelian  = Pembelian::select('id')->where('no_faktur', $data_tbs->no_faktur_pembelian)->first();
                $transaksi_hutang = TransaksiHutang::create([
                    'no_faktur'       => $no_faktur,
                    'id_transaksi'    => $id_pembelian->id,
                    'jenis_transaksi' => 'Pembayaran Hutang',
                    'jumlah_keluar'   => $data_tbs->jumlah_bayar + $data_tbs->potongan,
                    'suplier_id'    => $data_tbs->suplier_id,
                    'warung_id'       => $data_tbs->warung_id,
                ]);

               //INSERT transaksi hutang
                $create_transaksi_kas = TransaksiKas::create([ 
                'no_faktur'         => $no_faktur, 
                'jenis_transaksi'   =>'Pembayaran Hutang' , 
                'jumlah_keluar'     => $data_tbs->jumlah_bayar + $data_tbs->potongan, 
                'kas'               => $request->kas, 
                'warung_id'         => $data_tbs->warung_id]);  

            }

            //HAPUS TBS PEMBAYARAN hutang
            $data_pembayaran_hutang->delete();
            DB::commit();

            $respons['respons_pembayaran'] = $pembayaran_hutang->id_pembayaran_hutang;

            return response()->json($respons);
        }
    }

     public function tanggalSql($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "Y-m-d");
        return $date_format;
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
//PROSES EDIT PEMBAYARAN HUTANG
    public function edit($id)
    {
        $session_id = session()->getId();
        $no_faktur  = $this->fakturPembayaran($id);

        //PILIH DATA DETAIL PEMBAYARAN HUTANG
        $detail_pembayaran_piutang = DetailPembayaranHutang::where('no_faktur_pembayaran', $no_faktur)
            ->where('warung_id', Auth::user()->id_warung);

        //HAPUS DATA DI EDIT TBS
        $hapus_semua_edit_tbs_pembayaran_piutang = EditTbsPembayaranHutang::where('no_faktur_pembayaran', $no_faktur)
            ->where('warung_id', Auth::user()->id_warung)->delete();

        foreach ($detail_pembayaran_piutang->get() as $data_tbs) {
            $subtotal_hutang = $data_tbs->hutang - $data_tbs->potongan;

            $detail_pembelian = EditTbsPembayaranHutang::create([
                'session_id'           => $session_id,
                'no_faktur_pembayaran' => $data_tbs->no_faktur_pembayaran,
                'subtotal_hutang'     => $subtotal_hutang,
                'no_faktur_pembelian'  => $data_tbs->no_faktur_pembelian,
                'jatuh_tempo'          => $data_tbs->jatuh_tempo,
                'hutang'              => $data_tbs->hutang,
                'potongan'             => $data_tbs->potongan,
                'jumlah_bayar'         => $data_tbs->jumlah_bayar,
                'suplier_id'         => $data_tbs->suplier_id,
                'warung_id'            => Auth::user()->id_warung,
            ]);
        }

        return response(200);
    }

        public function fakturPembayaran($id)
    {
        $pembayaran_hutang = PembayaranHutang::select('no_faktur_pembayaran')
            ->where('id_pembayaran_hutang', $id)
            ->where('warung_id', Auth::user()->id_warung)->first();
        $no_faktur = $pembayaran_hutang->no_faktur_pembayaran;

        return $no_faktur;
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
        //START TRANSAKSI
        DB::beginTransaction();

        $pembayaran_hutang = PembayaranHutang::find($id);

//HAPUS DETAIL PEMBAYARAN PIUTANG
        $detail_pembayaran_hutang = DetailPembayaranHutang::where('no_faktur_pembayaran', $pembayaran_hutang->no_faktur_pembayaran)
            ->where('warung_id', Auth::user()->id_warung)->get();
        foreach ($detail_pembayaran_hutang as $data_detail) {
            if (!$hapus_detail = DetailPembayaranHutang::destroy($data_detail->id_detail_pembayaran_hutang)) {
                //DI BATALKAN PROSES NYA
                $respons['respons'] = 0;
                DB::rollBack();
                return response()->json($respons);
            }
            $hapus_transaksi_hutang = TransaksiHutang::where('no_faktur', $pembayaran_hutang->no_faktur_pembayaran)->delete();
            $hapus_transaksi_kas = TransaksiKas::where('no_faktur', $pembayaran_hutang->no_faktur_pembayaran)->delete();
        }

//INSERT DETAIL PEMBAYARAN PIUTANG
        $tbs_pembayaran_hutang = EditTbsPembayaranHutang::where('no_faktur_pembayaran', $pembayaran_hutang->no_faktur_pembayaran)->where('warung_id', Auth::user()->id_warung);

        if ($tbs_pembayaran_hutang->count() == 0) {
            DB::rollBack();
            return 0;
        } else {
//UPDATE PEMBAYARAN
            $jam                = date("h:i:s");
            $pembayaran_hutang->update([
                'total'      => $request->subtotal,
                'cara_bayar' => $request->kas,
                'keterangan' => $request->keterangan,
                'created_at' => $this->tanggalSql($request->tanggal) . " " . $jam,
                'updated_at' => $this->tanggalSql($request->tanggal) . " " . $jam,
            ]);

            // INSERT DETAIL PEMBAYARAN PIUTANG
            foreach ($tbs_pembayaran_hutang->get() as $data_tbs) {

                $detail_pembayaran_hutang = DetailPembayaranHutang::create([
                    'no_faktur_pembayaran' => $pembayaran_hutang->no_faktur_pembayaran,
                    'no_faktur_pembelian'  => $data_tbs->no_faktur_pembelian,
                    'jatuh_tempo'          => $data_tbs->jatuh_tempo,
                    'hutang'              => $data_tbs->hutang,
                    'potongan'             => $data_tbs->potongan,
                    'jumlah_bayar'         => $data_tbs->jumlah_bayar,
                    'suplier_id'         => $data_tbs->suplier_id,
                    'warung_id'            => $data_tbs->warung_id,
                     'subtotal_hutang'       => $data_tbs->subtotal_hutang,
                    'created_at'           => $this->tanggalSql($request->tanggal) . " " . $jam,
                    'updated_at'           => $this->tanggalSql($request->tanggal) . " " . $jam,
                ]);

                // INSERT TRANSAKSI PIUTANG TIDAK DIBUAT DI OBSERVER KARENA DI OBSERVER ID PENJUALAN DI ANGGAP NULL
                $id_pembelian  = Pembelian::select('id')->where('no_faktur', $data_tbs->no_faktur_pembelian)->first();
                $transaksi_hutang = TransaksiHutang::create([
                    'no_faktur'       => $pembayaran_hutang->no_faktur_pembayaran,
                    'id_transaksi'    => $id_pembelian->id,
                    'jenis_transaksi' => 'Pembayaran Hutang',
                    'jumlah_keluar'   => $data_tbs->jumlah_bayar + $data_tbs->potongan,
                    'suplier_id'    => $data_tbs->suplier_id,
                    'warung_id'       => $data_tbs->warung_id,
                ]);

                //INSERT TRANSAKSI KAS
                $transaksi_kas = TransaksiKas::create([
                    'no_faktur'       => $pembayaran_hutang->no_faktur_pembayaran,
                    'jenis_transaksi' => 'Pembayaran Hutang',
                    'jumlah_masuk'    => $data_tbs->jumlah_bayar + $data_tbs->potongan,
                    'kas'             => $request->kas,
                    'warung_id'       => $data_tbs->warung_id,
                ]);
            }

            $tbs_pembayaran_hutang = EditTbsPembayaranHutang::where('no_faktur_pembayaran', $pembayaran_hutang->no_faktur_pembayaran)
                ->where('warung_id', Auth::user()->id_warung)->delete();

            DB::commit();

            $respons['respon_hutang'] = $id;
            return response()->json($respons);
        }
    }

    public function cekSubtotalTbsPembayaranHutang($jenis_tbs){
    $session_id    = session()->getId();
    $user_warung   = Auth::user()->id_warung;
    if ($jenis_tbs == 1) {
            $TbsPembayaranHutang = new TbsPembayaranHutang();
            $subtotal      = $TbsPembayaranHutang->subtotalTbs($user_warung,$session_id);
            $respons['subtotal'] = $subtotal;
    }else if ($jenis_tbs == 2){
             $TbsPembayaranHutang = new EditTbsPembayaranHutang();
            $subtotal      = $TbsPembayaranHutang->subtotalTbs($user_warung,$session_id);
            $respons['subtotal'] = $subtotal;
    }

    return response()->json($respons);
    }

        public function cekDataTbsPembayaranHutang($id)
    {
        $user_warung   = Auth::user()->id_warung;
        $pembayaran_hutang    = PembayaranHutang::find($id);
        $TbsPembayaranHutang = new EditTbsPembayaranHutang();
        $subtotal      = $TbsPembayaranHutang->subtotalTbs($user_warung,$pembayaran_hutang->no_faktur_pembayaran);
        $respons['subtotal'] = $subtotal;
        return response()->json([
            "subtotal" => $subtotal,
            "pembayaran_hutang"     => $pembayaran_hutang->toArray(),
        ]);
    }
        public function prosesBatalEditPembayaranHutang($id)
    {
        //AMBIL NO FAKTUR
        $no_faktur          = $this->fakturPembayaran($id);
        $data_tbs_pembayaran_hutang = EditTbsPembayaranHutang::where('no_faktur_pembayaran', $no_faktur)->where('warung_id', Auth::user()->id_warung)->delete();

        return response(200);
    }
        public function cekSupplierDouble(){
        $session_id    = session()->getId();
        $data_suplier_tbs   = TbsPembayaranHutang::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung);
        return response()->json([
            "data_supplier" => $data_suplier_tbs->first(),
            "data_tbs"      => $data_suplier_tbs->count(),
        ]);
    }

    public function cekSupplierDoubleEdit(Request $request){
        $session_id    = session()->getId();
        $data_suplier_tbs   = EditTbsPembayaranHutang::where('no_faktur_pembayaran', $request->no_faktur_pembayaran)->where('warung_id', Auth::user()->id_warung);
        return response()->json([
            "data_supplier" => $data_suplier_tbs->first(),
            "data_tbs"      => $data_suplier_tbs->count(),
        ]);
    }
        public function total_kas(Request $request)
    {
        $session_id            = session()->getId();
        $total_kas             = TransaksiKas::total_kas($request);
        $data_tbs_pembayaran_hutang = TbsPembayaranHutang::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->count();

        $respons['total_kas']             = $total_kas;
        $respons['data_tbs_pembayaran_hutang'] = $data_tbs_pembayaran_hutang;

        return $respons;
    }
         public function total_kas_edit(Request $request)
    {
        $session_id            = session()->getId();
        $total_kas             = TransaksiKas::total_kas($request);
        $data_tbs_pembayaran_hutang = EditTbsPembayaranHutang::where('no_faktur_pembayaran', $request->no_faktur)->where('warung_id', Auth::user()->id_warung)->count();

        $respons['total_kas']             = $total_kas;
        $respons['data_tbs_pembayaran_hutang'] = $data_tbs_pembayaran_hutang;

        return $respons;
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

        if (!PembayaranHutang::destroy($id)) {
            DB::rollBack();
            return 0;
        } else {
            DB::commit();
            return response(200);
        }
    }

           public function cetakBesarPembayaranHutang($id)
    {
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        $pembayaran_hutang = PembayaranHutang::QueryCetak($id)->first();

        $detail_pembayaran_hutang = DetailPembayaranHutang::dataDetailPembayaranHutang($pembayaran_hutang->no_faktur)->get();
        $terbilang        = $this->kekata($pembayaran_hutang->total);
        $subtotal         = 0;
        foreach ($detail_pembayaran_hutang as $detail_pembayaran_hutangs) {
            $subtotal += $detail_pembayaran_hutangs->jumlah_bayar;

        }

        return view('pembayaran_hutang.cetak_besar', ['pembayaran_hutang' => $pembayaran_hutang, 'detail_pembayaran_hutang' => $detail_pembayaran_hutang, 'subtotal' => $subtotal, 'terbilang' => $terbilang,'setting_aplikasi' => $setting_aplikasi])->with(compact('html'));
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
}
