<?php

namespace App\Http\Controllers;

use App\DetailPembayaranPiutang;
use App\EditTbsPembayaranPiutang;
use App\Kas;
use App\PembayaranPiutang;
use App\PenjualanPos;
use App\SettingAplikasi;
use App\TbsPembayaranPiutang;
use App\TransaksiKas;
use App\TransaksiPiutang;
use App\Warung;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Html\Builder;
use Laratrust;
use Excel;
use PHPExcel_Style_Fill;

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

    public function dataKas()
    {
        $kas = Kas::select('id', 'nama_kas', 'default_kas')->where('warung_id', Auth::user()->id_warung)->where('status_kas', 1)->get();
        return response()->json($kas);
    }

    public function dataPiutang()
    {
        $penjualan_piutang = TransaksiPiutang::dataPenjualanPiutang()->get();
        $array             = array();
        foreach ($penjualan_piutang as $penjualan_piutangs) {
            if ($penjualan_piutangs->pelanggan_id == 0) {
                $nama_pelanggan = "Umum";
            } else {
                $nama_pelanggan = $penjualan_piutangs->name;
            }

            array_push($array, [
                'id'                  => $penjualan_piutangs->id,
                'no_faktur_penjualan' => $penjualan_piutangs->no_faktur,
                'pelanggan_id'        => $penjualan_piutangs->pelanggan_id,
                'piutang'             => $penjualan_piutangs->sisa_piutang,
                'jatuh_tempo'         => $penjualan_piutangs->tanggal_jt_tempo,
                'nama_pelanggan'      => $nama_pelanggan,
            ]);
        }

        return response()->json($array);
    }

    public function getDataFakturPiutang($id)
    {
        $penjualan_piutang = TransaksiPiutang::dataPenjualanPiutangPerFaktur($id)->first();
        
        return response()->json($penjualan_piutang);
    }

    public function dataPagination($pembayaran_piutang, $array_pembayaran_piutang, $link_view)
    {

        $respons['current_page']   = $pembayaran_piutang->currentPage();
        $respons['data']           = $array_pembayaran_piutang;
        $respons['otoritas']       = $this->otoritasPembayaranPiutang();
        $respons['first_page_url'] = url('/pembayaran-piutang/' . $link_view . '?page=' . $pembayaran_piutang->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $pembayaran_piutang->lastPage();
        $respons['last_page_url']  = url('/pembayaran-piutang/' . $link_view . '?page=' . $pembayaran_piutang->lastPage());
        $respons['next_page_url']  = $pembayaran_piutang->nextPageUrl();
        $respons['path']           = url('/pembayaran-piutang/' . $link_view . '');
        $respons['per_page']       = $pembayaran_piutang->perPage();
        $respons['prev_page_url']  = $pembayaran_piutang->previousPageUrl();
        $respons['to']             = $pembayaran_piutang->perPage();
        $respons['total']          = $pembayaran_piutang->total();

        return $respons;
    }

    public function foreachPembayaranPiutang($pembayaran_piutang)
    {
        $array_pembayaran_piutang = array();
        foreach ($pembayaran_piutang as $pembayaran_piutangs) {
            array_push($array_pembayaran_piutang, [
                'id'         => $pembayaran_piutangs->id,
                'no_faktur'  => $pembayaran_piutangs->no_faktur,
                'waktu'      => $pembayaran_piutangs->Waktu,
                'waktu_edit' => $pembayaran_piutangs->WaktuEdit,
                'total'      => $pembayaran_piutangs->getTotalSeparator(),
                'kas'        => $pembayaran_piutangs->nama_kas,
                'keterangan' => $pembayaran_piutangs->keterangan,
                'user_buat'  => $pembayaran_piutangs->petugas,
            ]);
        }

        return $array_pembayaran_piutang;
    }

    public function foreachTbs($pembayaran_piutang, $jenis_tbs)
    {
        $array_pembayaran_piutang = array();
        foreach ($pembayaran_piutang as $pembayaran_piutangs) {

            if ($pembayaran_piutangs->pelanggan_id == 0) {
                $pelanggan = 'Umum';
            } else {
                $pelanggan = $pembayaran_piutangs->name;
            }

            if ($jenis_tbs == 1) {
                // JIKA JENIS TBS == 1, MAKA ambil "id_tbs_pembayaran_piutang"
                $id_tbs = $pembayaran_piutangs->id_tbs_pembayaran_piutang;
            } else {
// JIKA JENIS TBS == 2, MAKA ambil "id_edit_tbs_pembayaran_piutang"
                $id_tbs = $pembayaran_piutangs->id_edit_tbs_pembayaran_piutang;
            }

            $sisa_piutang = $pembayaran_piutangs->subtotal_piutang - $pembayaran_piutangs->jumlah_bayar;

            array_push($array_pembayaran_piutang, [
                'no_faktur_penjualan'       => $pembayaran_piutangs->no_faktur_penjualan,
                'jatuh_tempo'               => $pembayaran_piutangs->jatuh_tempo,
                'piutang'                   => $pembayaran_piutangs->piutang,
                'potongan'                  => $pembayaran_piutangs->potongan,
                'total'                     => $pembayaran_piutangs->subtotal_piutang,
                'jumlah_bayar'              => $pembayaran_piutangs->jumlah_bayar,
                'sisa_piutang'              => $sisa_piutang,
                'pelanggan'                 => $pelanggan,
                'id_tbs_pembayaran_piutang' => $id_tbs,
                'id_penjualan'              => $pembayaran_piutangs->id_penjualan,
            ]);
        }

        return $array_pembayaran_piutang;
    }

    public function foreachDetail($pembayaran_piutang)
    {
        $array_pembayaran_piutang = array();
        foreach ($pembayaran_piutang as $detail_pembayaran_piutangs) {

            if ($detail_pembayaran_piutangs->pelanggan_id == 0) {
                $pelanggan = 'Umum';
            } else {
                $pelanggan = $detail_pembayaran_piutangs->name;
            }
            $subtotal_piutang = $detail_pembayaran_piutangs->piutang - $detail_pembayaran_piutangs->potongan;
            $sisa_piutang     = $subtotal_piutang - $detail_pembayaran_piutangs->jumlah_bayar;

            array_push($array_pembayaran_piutang, [
                'no_faktur_penjualan'       => $detail_pembayaran_piutangs->no_faktur_penjualan,
                'jatuh_tempo'               => $detail_pembayaran_piutangs->jatuh_tempo,
                'piutang'                   => $detail_pembayaran_piutangs->piutang,
                'potongan'                  => $detail_pembayaran_piutangs->potongan,
                'total'                     => $subtotal_piutang,
                'jumlah_bayar'              => $detail_pembayaran_piutangs->jumlah_bayar,
                'sisa_piutang'              => $sisa_piutang,
                'pelanggan'                 => $pelanggan,
                'id_detail_pembayaran_piutang' => $detail_pembayaran_piutangs->id_detail_pembayaran_piutang,
            ]);
        }

        return $array_pembayaran_piutang;
    }

    public function fakturPembayaran($id)
    {
        $pembayaran_piutang = PembayaranPiutang::select('no_faktur_pembayaran')
        ->where('id_pembayaran_piutang', $id)
        ->where('warung_id', Auth::user()->id_warung)->first();
        $no_faktur = $pembayaran_piutang->no_faktur_pembayaran;

        return $no_faktur;
    }

    public function tanggalSql($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "Y-m-d");
        return $date_format;
    }

    public function view()
    {
        $pembayaran_piutang = PembayaranPiutang::dataPembayaranPiutang()->paginate(10);

        $array_pembayaran_piutang = $this->foreachPembayaranPiutang($pembayaran_piutang);
        $link_view                = 'view';

        //DATA PAGINATION
        $respons = $this->dataPagination($pembayaran_piutang, $array_pembayaran_piutang, $link_view);
        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {
        $pembayaran_piutang = PembayaranPiutang::cariPembayaranPiutang($request)->paginate(10);

        $array_pembayaran_piutang = $this->foreachPembayaranPiutang($pembayaran_piutang);
        $link_view                = 'view';

        //DATA PAGINATION
        $respons = $this->dataPagination($pembayaran_piutang, $array_pembayaran_piutang, $link_view);
        return response()->json($respons);
    }

    //INSERT TBS
    public function prosesTbsPembayaranPiutang(Request $request)
    {
        $session_id = session()->getId();
        $data_tbs   = TbsPembayaranPiutang::where('no_faktur_penjualan', $request->no_faktur_penjualan)
        ->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->count();

        //JIKA FAKTUR YG DIPILIH SUDAH ADA DI TBS
        if ($data_tbs > 0) {

            return 0;

        } else {
            $subtotal_piutang = $request->piutang - $request->potongan;

            $tbs_pembayaran_piutang = TbsPembayaranPiutang::create([
                'session_id'          => $session_id,
                'no_faktur_penjualan' => $request->no_faktur_penjualan,
                'jatuh_tempo'         => $request->jatuh_tempo,
                'piutang'             => $request->piutang,
                'potongan'            => $request->potongan,
                'jumlah_bayar'        => $request->jumlah_bayar,
                'subtotal_piutang'    => $subtotal_piutang,
                'pelanggan_id'        => $request->pelanggan_id,
                'warung_id'           => Auth::user()->id_warung,
            ]);

            $respons['jumlah_bayar'] = $request->jumlah_bayar;

            return response()->json($respons);
        }
    }

    public function viewTbs()
    {
        $session_id         = session()->getId();
        $pembayaran_piutang = TbsPembayaranPiutang::dataTbsPembayaranPiutang($session_id)->paginate(10);
        $jenis_tbs          = 1;

        $array_pembayaran_piutang = $this->foreachTbs($pembayaran_piutang, $jenis_tbs);
        $link_view                = 'view-tbs-pembayaran-piutang';

        //DATA PAGINATION
        $respons = $this->dataPagination($pembayaran_piutang, $array_pembayaran_piutang, $link_view);
        return response()->json($respons);
    }

    public function pencarianTbs(Request $request)
    {
        $session_id         = session()->getId();
        $pembayaran_piutang = TbsPembayaranPiutang::cariTbsPembayaranPiutang($request, $session_id)->paginate(10);
        $jenis_tbs          = 1;

        $array_pembayaran_piutang = $this->foreachTbs($pembayaran_piutang, $jenis_tbs);
        $link_view                = 'view-tbs-pembayaran-piutang';

        //DATA PAGINATION
        $respons = $this->dataPagination($pembayaran_piutang, $array_pembayaran_piutang, $link_view);
        return response()->json($respons);
    }

    public function prosesHapusTbsPembayaranPiutang($id)
    {
        if (!TbsPembayaranPiutang::destroy($id)) {
            return 0;
        } else {
            return response(200);
        }
    }

    public function prosesEditPotonganTbsPembayaranPiutang(Request $request)
    {
        $tbs_pembayaran_piutang = TbsPembayaranPiutang::find($request->id_tbs);
        $subtotal               = $request->piutang - $request->potongan;

        $tbs_pembayaran_piutang->update(['potongan' => $request->potongan, 'subtotal_piutang' => $subtotal, 'jumlah_bayar' => $request->jumlah_bayar]);
        $respons['jumlah_bayar'] = $request->jumlah_bayar;

        return response()->json($respons);
    }

    public function store(Request $request)
    {
        //START TRANSAKSI
        DB::beginTransaction();
        $warung_id  = Auth::user()->id_warung;
        $session_id = session()->getId();
        $user       = Auth::user()->id;
        $no_faktur  = PembayaranPiutang::no_faktur($warung_id);

        $this->validate($request, [
            'kas'     => 'required',
            'tanggal' => 'required',
        ]);

        $data_pembayaran_piutang = TbsPembayaranPiutang::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung);

        if ($data_pembayaran_piutang->count() == 0) {
            return 0;
        } else {
            //INSERT PEMBAYARAN PIUTANG
            $jam                = date("h:i:s");
            $pembayaran_piutang = PembayaranPiutang::create([
                'no_faktur_pembayaran' => $no_faktur,
                'total'                => $request->subtotal,
                'cara_bayar'           => $request->kas,
                'keterangan'           => $request->keterangan,
                'warung_id'            => $warung_id,
                'created_at'           => $this->tanggalSql($request->tanggal) . " " . $jam,
                'updated_at'           => $this->tanggalSql($request->tanggal) . " " . $jam,
            ]);

            // INSERT DETAIL PEMBAYARAN PIUTANG
            foreach ($data_pembayaran_piutang->get() as $data_tbs) {

                $detail_pembayaran_piutang = DetailPembayaranPiutang::create([
                    'no_faktur_pembayaran' => $no_faktur,
                    'no_faktur_penjualan'  => $data_tbs->no_faktur_penjualan,
                    'jatuh_tempo'          => $data_tbs->jatuh_tempo,
                    'piutang'              => $data_tbs->piutang,
                    'potongan'             => $data_tbs->potongan,
                    'jumlah_bayar'         => $data_tbs->jumlah_bayar,
                    'pelanggan_id'         => $data_tbs->pelanggan_id,
                    'warung_id'            => $data_tbs->warung_id,
                    'created_at'           => $this->tanggalSql($request->tanggal) . " " . $jam,
                    'updated_at'           => $this->tanggalSql($request->tanggal) . " " . $jam,
                ]);

                // INSERT TRANSAKSI PIUTANG TIDAK DIBUAT DI OBSERVER KARENA DI OBSERVER ID PENJUALAN DI ANGGAP NULL
                $id_penjualan_pos  = PenjualanPos::select('id')->where('no_faktur', $data_tbs->no_faktur_penjualan)->first();
                $transaksi_piutang = TransaksiPiutang::create([
                    'no_faktur'       => $no_faktur,
                    'id_transaksi'    => $id_penjualan_pos->id,
                    'jenis_transaksi' => 'Pembayaran Piutang',
                    'jumlah_keluar'   => $data_tbs->jumlah_bayar + $data_tbs->potongan,
                    'pelanggan_id'    => $data_tbs->pelanggan_id,
                    'warung_id'       => $data_tbs->warung_id,
                ]);

                //INSERT TRANSAKSI KAS
                $transaksi_kas = TransaksiKas::create([
                    'no_faktur'       => $no_faktur,
                    'jenis_transaksi' => 'Pembayaran Piutang',
                    'jumlah_masuk'    => $data_tbs->jumlah_bayar + $data_tbs->potongan,
                    'kas'             => $request->kas,
                    'warung_id'       => $data_tbs->warung_id,
                ]);
            }

            //HAPUS TBS PEMBAYARAN PIUTANG
            $data_pembayaran_piutang->delete();
            DB::commit();

            $respons['respons_pembayaran'] = $pembayaran_piutang->id_pembayaran_piutang;

            return response()->json($respons);
        }
    }

    public function prosesBatalPembayaranPiutang()
    {
        $session_id         = session()->getId();
        $data_tbs_penjualan = TbsPembayaranPiutang::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->delete();

        return response(200);
    }

    public function destroy($id)
    {
        //START TRANSAKSI
        DB::beginTransaction();
        if (!PembayaranPiutang::destroy($id)) {
            DB::rollBack();
            return 0;
        } else {
            DB::commit();
            return response(200);
        }
    }

    public function viewDetail($id)
    {
        $user_warung = Auth::user()->id_warung;
        //AMBIL NO FAKTUR
        $no_faktur          = $this->fakturPembayaran($id);
        $pembayaran_piutang = DetailPembayaranPiutang::dataDetailPembayaranPiutang($user_warung, $no_faktur)->paginate(10);

        $array_pembayaran_piutang = $this->foreachDetail($pembayaran_piutang);
        $link_view                = 'view-detail-pembayaran-piutang/' . $id;

        //DATA PAGINATION
        $respons = $this->dataPagination($pembayaran_piutang, $array_pembayaran_piutang, $link_view);
        return response()->json($respons);
    }

    public function pencarianDetail(Request $request, $id)
    {
        $user_warung = Auth::user()->id_warung;
        //AMBIL NO FAKTUR
        $no_faktur          = $this->fakturPembayaran($id);
        $pembayaran_piutang = DetailPembayaranPiutang::cariDetailPembayaranPiutang($request, $user_warung, $no_faktur)->paginate(10);

        $array_pembayaran_piutang = $this->foreachDetail($pembayaran_piutang);
        $link_view                = 'pencarian-detail-pembayaran-piutang/' . $id;

        //DATA PAGINATION
        $respons = $this->dataPagination($pembayaran_piutang, $array_pembayaran_piutang, $link_view);
        return response()->json($respons);
    }

//PROSES EDIT PEMBAYARAN PIUTANG
    public function edit($id)
    {
        $session_id = session()->getId();
        $no_faktur  = $this->fakturPembayaran($id);

        //PILIH DATA DETAIL PEMBAYARAN PIUTANG
        $detail_pembayaran_piutang = DetailPembayaranPiutang::where('no_faktur_pembayaran', $no_faktur)
        ->where('warung_id', Auth::user()->id_warung);

        //HAPUS DATA DI EDIT TBS
        $hapus_semua_edit_tbs_pembayaran_piutang = EditTbsPembayaranPiutang::where('no_faktur_pembayaran', $no_faktur)
        ->where('warung_id', Auth::user()->id_warung)->delete();

        foreach ($detail_pembayaran_piutang->get() as $data_tbs) {
            $subtotal_piutang = $data_tbs->piutang - $data_tbs->potongan;

            $detail_penjualan = EditTbsPembayaranPiutang::create([
                'session_id'           => $session_id,
                'no_faktur_pembayaran' => $data_tbs->no_faktur_pembayaran,
                'subtotal_piutang'     => $subtotal_piutang,
                'no_faktur_penjualan'  => $data_tbs->no_faktur_penjualan,
                'jatuh_tempo'          => $data_tbs->jatuh_tempo,
                'piutang'              => $data_tbs->piutang,
                'potongan'             => $data_tbs->potongan,
                'jumlah_bayar'         => $data_tbs->jumlah_bayar,
                'pelanggan_id'         => $data_tbs->pelanggan_id,
                'warung_id'            => Auth::user()->id_warung,
            ]);
        }

        return response(200);
    }

    public function viewTbsEdit($id)
    {
        $session_id = session()->getId();
        //AMBIL NO FAKTUR
        $no_faktur          = $this->fakturPembayaran($id);
        $pembayaran_piutang = EditTbsPembayaranPiutang::dataEditTbsPembayaranPiutang($session_id, $no_faktur)->paginate(10);
        $jenis_tbs          = 2;

        $array_pembayaran_piutang = $this->foreachTbs($pembayaran_piutang, $jenis_tbs);
        $link_view                = 'view-edit-tbs-pembayaran-piutang';

        //DATA PAGINATION
        $respons = $this->dataPagination($pembayaran_piutang, $array_pembayaran_piutang, $link_view);
        return response()->json($respons);
    }

    public function pencarianTbsEdit(Request $request, $id)
    {
        $session_id = session()->getId();
        //AMBIL NO FAKTUR
        $no_faktur          = $this->fakturPembayaran($id);
        $pembayaran_piutang = EditTbsPembayaranPiutang::cariEditTbsPembayaranPiutang($request, $session_id, $no_faktur)->paginate(10);
        $jenis_tbs          = 2;

        $array_pembayaran_piutang = $this->foreachTbs($pembayaran_piutang, $jenis_tbs);
        $link_view                = 'view-edit-tbs-pembayaran-piutang';

        //DATA PAGINATION
        $respons = $this->dataPagination($pembayaran_piutang, $array_pembayaran_piutang, $link_view);
        return response()->json($respons);
    }

    //INSERT EDIT TBS
    public function prosesEditTbsPembayaranPiutang(Request $request, $id)
    {
        $session_id = session()->getId();
        //AMBIL NO FAKTUR
        $no_faktur = $this->fakturPembayaran($id);

        $data_tbs = EditTbsPembayaranPiutang::where('no_faktur_penjualan', $request->no_faktur_penjualan)
        ->where('session_id', $session_id)
        ->where('no_faktur_pembayaran', $no_faktur)
        ->where('warung_id', Auth::user()->id_warung)->count();

        //JIKA FAKTUR YG DIPILIH SUDAH ADA DI TBS
        if ($data_tbs > 0) {

            return 0;

        } else {
            $subtotal_piutang = $request->piutang - $request->potongan;

            $tbs_pembayaran_piutang = EditTbsPembayaranPiutang::create([
                'session_id'           => $session_id,
                'no_faktur_pembayaran' => $no_faktur,
                'no_faktur_penjualan'  => $request->no_faktur_penjualan,
                'jatuh_tempo'          => $request->jatuh_tempo,
                'piutang'              => $request->piutang,
                'potongan'             => $request->potongan,
                'jumlah_bayar'         => $request->jumlah_bayar,
                'subtotal_piutang'     => $subtotal_piutang,
                'pelanggan_id'         => $request->pelanggan_id,
                'warung_id'            => Auth::user()->id_warung,
            ]);

            $respons['jumlah_bayar'] = $request->jumlah_bayar;

            return response()->json($respons);
        }
    }

    public function prosesHapusEditTbsPembayaranPiutang($id)
    {
        if (!EditTbsPembayaranPiutang::destroy($id)) {
            return 0;
        } else {
            return response(200);
        }
    }

    public function updateEditTbsPembayaranPiutang(Request $request)
    {
        $tbs_pembayaran_piutang = EditTbsPembayaranPiutang::find($request->id_tbs);
        $subtotal               = $request->piutang - $request->potongan;

        $tbs_pembayaran_piutang->update(['potongan' => $request->potongan, 'subtotal_piutang' => $subtotal, 'jumlah_bayar' => $request->jumlah_bayar]);
        $respons['jumlah_bayar'] = $request->jumlah_bayar;

        return response()->json($respons);
    }

    public function prosesBatalEditPembayaranPiutang($id)
    {
        //AMBIL NO FAKTUR
        $no_faktur          = $this->fakturPembayaran($id);
        $data_tbs_penjualan = EditTbsPembayaranPiutang::where('no_faktur_pembayaran', $no_faktur)->where('warung_id', Auth::user()->id_warung)->delete();

        return response(200);
    }

    public function editPembayaranPiutang($id)
    {
        $pembayaran_piutang = PembayaranPiutang::find($id);
        return response()->json($pembayaran_piutang);
    }

    public function update(Request $request, $id)
    {
//START TRANSAKSI
        DB::beginTransaction();

        $pembayaran_piutang = PembayaranPiutang::find($id);

//HAPUS DETAIL PEMBAYARAN PIUTANG
        $detail_pembayaran_piutang = DetailPembayaranPiutang::where('no_faktur_pembayaran', $pembayaran_piutang->no_faktur_pembayaran)
        ->where('warung_id', Auth::user()->id_warung)->get();
        foreach ($detail_pembayaran_piutang as $data_detail) {

            if (!$hapus_detail = DetailPembayaranPiutang::destroy($data_detail->id_detail_pembayaran_piutang)) {
                //DI BATALKAN PROSES NYA
                $respons['respons'] = 0;
                DB::rollBack();
                return response()->json($respons);
            }

            $hapus_transaksi_piutang = TransaksiPiutang::where('no_faktur', $pembayaran_piutang->no_faktur_pembayaran)->delete();

            $hapus_transaksi_kas = TransaksiKas::where('no_faktur', $pembayaran_piutang->no_faktur_pembayaran)->delete();
        }

//INSERT DETAIL PEMBAYARAN PIUTANG
        $tbs_pembayaran_piutang = EditTbsPembayaranPiutang::where('no_faktur_pembayaran', $pembayaran_piutang->no_faktur_pembayaran)->where('warung_id', Auth::user()->id_warung);

        if ($tbs_pembayaran_piutang->count() == 0) {
            DB::rollBack();
            return 0;
        } else {
//UPDATE PEMBAYARAN
            $jam = date("h:i:s");
            $pembayaran_piutang->update([
                'total'      => $request->subtotal,
                'cara_bayar' => $request->kas,
                'keterangan' => $request->keterangan,
                'created_at' => $this->tanggalSql($request->tanggal) . " " . $jam,
                'updated_at' => $this->tanggalSql($request->tanggal) . " " . $jam,
            ]);

            // INSERT DETAIL PEMBAYARAN PIUTANG
            foreach ($tbs_pembayaran_piutang->get() as $data_tbs) {

                $detail_pembayaran_piutang = DetailPembayaranPiutang::create([
                    'no_faktur_pembayaran' => $pembayaran_piutang->no_faktur_pembayaran,
                    'no_faktur_penjualan'  => $data_tbs->no_faktur_penjualan,
                    'jatuh_tempo'          => $data_tbs->jatuh_tempo,
                    'piutang'              => $data_tbs->piutang,
                    'potongan'             => $data_tbs->potongan,
                    'jumlah_bayar'         => $data_tbs->jumlah_bayar,
                    'pelanggan_id'         => $data_tbs->pelanggan_id,
                    'warung_id'            => $data_tbs->warung_id,
                    'created_at'           => $this->tanggalSql($request->tanggal) . " " . $jam,
                    'updated_at'           => $this->tanggalSql($request->tanggal) . " " . $jam,
                ]);

                // INSERT TRANSAKSI PIUTANG TIDAK DIBUAT DI OBSERVER KARENA DI OBSERVER ID PENJUALAN DI ANGGAP NULL
                $id_penjualan_pos  = PenjualanPos::select('id')->where('no_faktur', $data_tbs->no_faktur_penjualan)->first();
                $transaksi_piutang = TransaksiPiutang::create([
                    'no_faktur'       => $pembayaran_piutang->no_faktur_pembayaran,
                    'id_transaksi'    => $id_penjualan_pos->id,
                    'jenis_transaksi' => 'Pembayaran Piutang',
                    'jumlah_keluar'   => $data_tbs->jumlah_bayar + $data_tbs->potongan,
                    'pelanggan_id'    => $data_tbs->pelanggan_id,
                    'warung_id'       => $data_tbs->warung_id,
                ]);

                //INSERT TRANSAKSI KAS
                $transaksi_kas = TransaksiKas::create([
                    'no_faktur'       => $pembayaran_piutang->no_faktur_pembayaran,
                    'jenis_transaksi' => 'Pembayaran Piutang',
                    'jumlah_masuk'    => $data_tbs->jumlah_bayar + $data_tbs->potongan,
                    'kas'             => $request->kas,
                    'warung_id'       => $data_tbs->warung_id,
                ]);
            }

            $tbs_pembayaran_piutang = EditTbsPembayaranPiutang::where('no_faktur_pembayaran', $pembayaran_piutang->no_faktur_pembayaran)
            ->where('warung_id', Auth::user()->id_warung)->delete();

            DB::commit();

            $respons['respon_piutang'] = $id;
            return response()->json($respons);
        }

    }

    public function cekSubtotalTbsPembayaranPiutang($jenis_tbs){
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;

        $TbsPembayaranPiutang = new TbsPembayaranPiutang();
        $subtotal      = $TbsPembayaranPiutang->subtotalTbs($user_warung,$session_id);
        $respons['subtotal'] = $subtotal;

        return response()->json($respons);
    }

    public function cetakUlang($id)
    {
        //SETTING APLIKASI
        $user_warung      = Auth::user()->id_warung;
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();
        $data_warung      = Warung::where('id', $user_warung)->first();

        $pembayaran_piutang = PembayaranPiutang::dataPembayaranPiutang()->where('pembayaran_piutangs.id_pembayaran_piutang', $id)->first();

        $detail_pembayaran_piutang = DetailPembayaranPiutang::dataDetailPembayaranPiutang($user_warung, $pembayaran_piutang->no_faktur)->get();

        $subtotal = 0;
        foreach ($detail_pembayaran_piutang as $detail_pembayaran_piutangs) {
            $subtotal += $detail_pembayaran_piutangs->jumlah_bayar;
        }

        return view('pembayaran_piutang.cetak_besar',
            [
                'pembayaran_piutang'        => $pembayaran_piutang,
                'detail_pembayaran_piutang' => $detail_pembayaran_piutang,
                'data_warung'               => $data_warung,
                'petugas'                   => Auth::user()->name,
                'setting_aplikasi'          => $setting_aplikasi,
                'subtotal'                  => $subtotal,
            ])->with(compact('html'));
    }

    public function otoritasPembayaranPiutang(){

        if (Laratrust::can('tambah_pembayaran_piutang')) {
            $tambah_pembayaran_piutang = 1;
        }else{
            $tambah_pembayaran_piutang = 0;            
        }
        if (Laratrust::can('edit_pembayaran_piutang')) {
            $edit_pembayaran_piutang = 1;
        }else{
            $edit_pembayaran_piutang = 0;            
        }
        if (Laratrust::can('hapus_pembayaran_piutang')) {
            $hapus_pembayaran_piutang = 1;
        }else{
            $hapus_pembayaran_piutang = 0;            
        }
        $respons['tambah_pembayaran_piutang'] = $tambah_pembayaran_piutang;
        $respons['edit_pembayaran_piutang'] = $edit_pembayaran_piutang;
        $respons['hapus_pembayaran_piutang'] = $hapus_pembayaran_piutang;

        return response()->json($respons);
    }

     //DOWNLAOD TEMPLATE
    public function downloadTemplate()
    {
        Excel::create('Template Import Pembayaran Piutang', function ($excel) {
            // Set the properties
            $excel->setTitle('Template Import Pembayaran Piutang');
            $excel->sheet('Template Import Pemb. Piutang', function ($sheet) {

                $kolomWajib = ['A', 'B', 'C', 'D'];
                foreach($kolomWajib as $kolom) {
                    $sheet->getStyle($kolom . '1')->applyFromArray(array(
                        'fill' => array(
                            'type'  => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '90CAF9'),
                        ),
                    ));
                }

                $row = 1;
                $sheet->row($row, [
                    'No Transaksi',
                    // 'Pelanggan',
                    // 'Tanggal JT',
                    'Piutang',
                    'Potongan',
                    // 'Subtotal',
                    'Pembayaran',
                    // 'Sisa'
                ]);
                $sheet->row(++$row, [
                    '4',
                    // 'Umum',
                    // '21/06/2018',
                    '16500',
                    '0',
                    // '16500',
                    '16500',
                    // '0'
                ]);
            });
        })->export('xlsx');
    }

    public function importExcel(Request $request)
    {

        function _lowerWithTrim($arg) {
            return strtolower(trim($arg));
        }

        $warung_id = Auth::user()->id_warung;
        
        // ambil file yang baru diupload
        $excel = $request->file('excel');

        // baca sheet pertama
        $excels = Excel::selectSheetsByIndex(0)->load($excel, function ($reader) {
        })->get();
        
        $no         = 1;  
        $pembayaranPiutangList = PembayaranPiutang::select('pembayaran_piutangs.no_faktur_pembayaran as no_transaksi')->get();
        // $dataKas = Kas::select('id', 'nama_kas')->get();
        // $arrayKas = [];
        // return response($dataKas);

        // membuat array data customer yang sudah ada di database
        $customersDataInDB = [];
        $customersDataInDB['no_transaksi'] = [];
        
        foreach($pembayaranPiutangList as $val) {

            if (_lowerWithTrim($val->no_transaksi) != '')
                $customersDataInDB['no_transaksi'][] = _lowerWithTrim($val->no_transaksi);
        }
        // return response($customersDataInDB);

        // membuat array data kas untuk validasi kas
        // foreach ($dataKas as $data) {
        //     $arrayKas[_lowerWithTrim($data->nama_kas)] = $data->id;
        // }
        // return response((string) array_key_exists('ks warung', $arrayKas));
        // array_key_exists

        $errorMessages = [];
        $baris = 1;
        $arr = [];
        $kolom = '';
        $arrPenjualanPos = [];
        $arrDetailPenjualanPiutang = [];
        $PenjualanPos = PenjualanPos::select()->get();
        $detailPembayaranPiutang = DetailPembayaranPiutang::select('no_faktur_penjualan')->get();

            foreach($PenjualanPos as $data) {
                $arrPenjualanPos[$data->id] = $data;
            }
        foreach($detailPembayaranPiutang as $data) {
            $arrDetailPenjualanPiutang[] = $data;
        }
        // return response($arrPenjualanPos);

        // perulangan untuk menghandle error
        foreach ($excels as $row) {

            // validasi untuk data yang sudah ada di database
            /*if (in_array(_lowerWithTrim($row['no_transaksi']), $customersDataInDB['no_transaksi'])) {
                if (in_array(_lowerWithTrim($row['no_transaksi']), $customersDataInDB['no_transaksi'])) {
                    $kolom .= 'No Transaksi';
                }

                $errorMessages[] = 'Baris ke ' . $baris . ': ' . $kolom . ' sudah ada.';

                // kosongkan variable
                $kolom = '';
                $arr = [];
            }*/

            // return $arrDetailPenjualanPiutang;

            // validasi no transaksi yang tidak ada di database
            if (!empty($row['no_transaksi']) && !array_key_exists((int)$row['no_transaksi'], $arrPenjualanPos)) {
                $errorMessages[] = 'Baris ke ' . $baris . ': No Transaksi ' . $row['no_transaksi'] . ' tidak ada.';
            } else {
                // validasi data yang sudah ada
                if (in_array($arrPenjualanPos[$row['no_transaksi']]->no_faktur, $arrDetailPenjualanPiutang))
                    $errorMessages[] = 'Baris ke ' . $baris . ': No Transaksi ' . $row['no_transaksi'] . ' sudah tidak ada.';
            }


            // validasi untuk data wajib yang kosong
            if (empty($row['no_transaksi']) || empty($row['piutang']) || empty($row['potongan']) && $row['potongan'] != 0 || empty($row['pembayaran'])) {
                if (empty($row['no_transaksi'])) {
                    $kolom .= 'No Transaksi';
                    $arr[] = ' ';
                }
                if (empty($row['piutang'])) {
                    if (count($arr) == 0)
                        $kolom .= 'Piutang';
                    else
                        $kolom .= ', Piutang';

                    $arr[] = ' ';
                }
                if (empty($row['potongan']) && $row['potongan'] != 0) {
                    if (count($arr) == 0)
                        $kolom .= 'Potongan';
                    else
                        $kolom .= ', Potongan';

                    $arr[] = ' ';
                }
                if (empty($row['pembayaran'])) {
                    if (count($arr) == 0)
                        $kolom .= 'Pembayaran';
                    else
                        $kolom .= ', Pembayaran';

                    $arr[] = ' ';
                }
                // return response($row['potongan']);

                $errorMessages[] = 'Baris ke ' . $baris . ': ' . $kolom . ' tidak boleh kosong.';
                // kosongkan variable
                $kolom = '';
                $arr = [];
            }

            // validasi data kas


            $baris++;
        }

        // jika ada error kirim pesan error sebagai respon dan cegah penginsertan data
        if (count($errorMessages) > 0) {

            // pisahkan masing2 pesan dengan baris baru agar nantinya terlihat rapih di swal
            $errorMessages = implode('<br>', $errorMessages);
            $data['errors'] = $errorMessages;
            return response()->json($data);
        }

        // perulangan untuk insert data customer
        $no = 0;
        foreach ($excels as $row) {            
            $session_id = session()->getId();
            $subtotal_piutang = $row['piutang'] - $row['potongan'];

            $tbs_pembayaran_piutang = TbsPembayaranPiutang::create([
                'session_id'          => $session_id,
                'no_faktur_penjualan' => $arrPenjualanPos[$row['no_transaksi']]->no_faktur,
                'jatuh_tempo'         => $arrPenjualanPos[$row['no_transaksi']]->tanggal_jt_tempo,
                'piutang'             => $row['piutang'],
                'potongan'            => $row['potongan'],
                'jumlah_bayar'        => $row['pembayaran'],
                'subtotal_piutang'    => $subtotal_piutang,
                'pelanggan_id'        => $arrPenjualanPos[$row['no_transaksi']]->pelanggan_id,
                'warung_id'           => Auth::user()->id_warung,
            ]);

            $no++;
        }

        // Hitung Jumlah Produk Yang Diimport
        $data['jumlah_data'] = $no;
        return response()->json($data);
    }
}
