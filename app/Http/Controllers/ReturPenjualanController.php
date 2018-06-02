<?php

namespace App\Http\Controllers;

use App\DetailReturPenjualan;
use App\Kas;
use App\ReturPenjualan;
use App\PenjualanPos;
use App\Penjualan;
use App\SettingAplikasi;
use App\TbsReturPenjualan;
use App\TransaksiKas;
use App\Warung;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Html\Builder;
use Laratrust;


class ReturPenjualanController extends Controller
{
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

        public function dataPagination($retur_penjualan, $array_retur_penjualan, $link_view)
    {

        $respons['current_page']   = $retur_penjualan->currentPage();
        $respons['data']           = $array_retur_penjualan;
        $respons['first_page_url'] = url('/retur-penjualan/' . $link_view . '?page=' . $retur_penjualan->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $retur_penjualan->lastPage();
        $respons['last_page_url']  = url('/retur-penjualan/' . $link_view . '?page=' . $retur_penjualan->lastPage());
        $respons['next_page_url']  = $retur_penjualan->nextPageUrl();
        $respons['path']           = url('/retur-penjualan/' . $link_view . '');
        $respons['per_page']       = $retur_penjualan->perPage();
        $respons['prev_page_url']  = $retur_penjualan->previousPageUrl();
        $respons['to']             = $retur_penjualan->perPage();
        $respons['total']          = $retur_penjualan->total();

        return $respons;
    }

        public function dataPaginationPencarianData($retur_penjualan, $array, $link_view, $search)
    {
        //DATA PAGINATION
        $respons['current_page']   = $retur_penjualan->currentPage();
        $respons['data']           = $array;
        $respons['first_page_url'] = url('/retur-penjualan/' . $link_view . '?page=' . $retur_penjualan->firstItem() . '&search=' . $search);
        $respons['from']           = 1;
        $respons['last_page']      = $retur_penjualan->lastPage();
        $respons['last_page_url']  = url('/retur-penjualan/' . $link_view . '?page=' . $retur_penjualan->lastPage() . '&search=' . $search);
        $respons['next_page_url']  = $retur_penjualan->nextPageUrl();
        $respons['path']           = url('/retur-penjualan/'. $link_view);
        $respons['per_page']       = $retur_penjualan->perPage();
        $respons['prev_page_url']  = $retur_penjualan->previousPageUrl();
        $respons['to']             = $retur_penjualan->perPage();
        $respons['total']          = $retur_penjualan->total();
        //DATA PAGINATION
        return $respons;
    }


        public function view()
    {
        $retur_penjualan = ReturPenjualan::dataReturPenjualan()->paginate(10);

        $array_retur_penjualan = $this->foreachReturPenjualan($retur_penjualan);
        $link_view                = 'view';

        //DATA PAGINATION
        $respons = $this->dataPagination($retur_penjualan, $array_retur_penjualan, $link_view);
        return response()->json($respons);
    }

    public function pencarian(Request $request){
        $retur_penjualan = ReturPenjualan::pencariandataReturPenjualan($request)->paginate(10);

        $array_retur_penjualan = $this->foreachReturPenjualan($retur_penjualan);
        $link_view                = 'view';

        //DATA PAGINATION
        $respons = $this->dataPagination($retur_penjualan, $array_retur_penjualan, $link_view);
        return response()->json($respons);
    }

        public function foreachReturPenjualan($retur_penjualan)
    {
        $array_retur_penjualan = array();
        foreach ($retur_penjualan as $retur_penjualans) {
            array_push($array_retur_penjualan, [
                'id'         => $retur_penjualans->id,
                'no_faktur'  => $retur_penjualans->no_faktur,
                'pelanggan'  => $retur_penjualans->pelanggan,
                'waktu'      => $retur_penjualans->Waktu,
                'waktu_edit' => $retur_penjualans->WaktuEdit,
                'total'      => $retur_penjualans->TotalSeparator,
                'kas'        => $retur_penjualans->nama_kas,
                'keterangan' => $retur_penjualans->keterangan,
                'user_buat'  => $retur_penjualans->petugas,
            ]);
        }

        return $array_retur_penjualan;
    }

        public function foreachTbs($tbs_retur_penjualan, $jenis_tbs)
    {
        $array_retur_penjualan = array();

        foreach ($tbs_retur_penjualan as $tbs_retur_penjualans) {

            if ($jenis_tbs == 1) {
                // JIKA JENIS TBS == 1, MAKA ambil "id_tbs_retur_penjualan"
                $id_tbs = $tbs_retur_penjualans->id_tbs_retur_penjualan;
            } else {
                // JIKA JENIS TBS == 2, MAKA ambil "id_edit_tbs_retur_penjualan"
                $id_tbs = $tbs_retur_penjualans->id_edit_tbs_retur_penjualan;
            }

            $potongan = $this->tampilPotongan($tbs_retur_penjualans->potongan, $tbs_retur_penjualans->jumlah_retur, $tbs_retur_penjualans->harga_produk);

            array_push($array_retur_penjualan, [
                'id'                  => $id_tbs,
                'no_faktur_penjualan' => $tbs_retur_penjualans->no_faktur_penjualan,
                'id_produk'           => $tbs_retur_penjualans->id_produk,
                'kode_barang'         => $tbs_retur_penjualans->kode_barang,
                'nama_barang'         => title_case($tbs_retur_penjualans->nama_barang),
                'jumlah_jual'         => $tbs_retur_penjualans->jumlah_jual,
                'jumlah_retur'        => $tbs_retur_penjualans->jumlah_retur,
                'satuan'              => $tbs_retur_penjualans->satuan,
                'id_satuan'              => $tbs_retur_penjualans->id_satuan,
                'harga_produk'        => $tbs_retur_penjualans->harga_produk,
                'potongan'            => $potongan,
                'subtotal'            => $tbs_retur_penjualans->subtotal,

            ]);
        }

        return $array_retur_penjualan;
    }

    //VIEW DAN PENCARIAN Tbs retur Penjualan
    public function viewTbsReturPenjualan()
    {
        $session_id            = session()->getId();
        $user_warung           = Auth::user()->id_warung;
        $tbs_retur_penjualan   = TbsReturPenjualan::dataTbsReturPenjualan($session_id)->paginate(10);
        $jenis_tbs             = 1;

        $array = $this->foreachTbs($tbs_retur_penjualan, $jenis_tbs);

        $url     = 'view-tbs-retur-penjualan';
        $respons = $this->dataPagination($tbs_retur_penjualan, $array, $url);
        return response()->json($respons);
    }

        public function pencarianTbsReturPenjualan(Request $request)
    {
        $session_id            = session()->getId();
        $user_warung           = Auth::user()->id_warung;
        $search                = $request->search;
        $tbs_retur_penjualan   = TbsReturPenjualan::cariTbsReturPenjualan($request, $session_id)->paginate(10);
        $jenis_tbs             = 1;

        $array = $this->foreachTbs($tbs_retur_penjualan, $jenis_tbs);

        $url     = 'view-tbs-retur-penjualan';
        $respons = $this->dataPaginationPencarianData($tbs_retur_penjualan, $array, $url, $search);
        return response()->json($respons);
    }

    //VIEW DAN PENCARIAN dataSupplierHutang
    public function dataPelangganRetur($id,$jenis_penjualan)
    {
        $session_id  = session()->getId();
        $user_warung = Auth::user()->id_warung;
        $id_pelanggan  = $id;

        if ($jenis_penjualan == 0) {
            $data_retur_penjualan     = PenjualanPos::getDataPenjualanRetur($id_pelanggan)->paginate(10);
        }else{
            $data_retur_penjualan     = Penjualan::getDataPenjualanRetur($id_pelanggan)->paginate(10);
        }
        

        $array = array();
        foreach ($data_retur_penjualan as $data_retur_penjualans) {
            array_push($array, [
                'id_penjualan'        => $data_retur_penjualans->id_penjualan,
                'id_produk'           => $data_retur_penjualans->id_produk, 
                'kode_barang'         => $data_retur_penjualans->kode_barang,
                'nama_barang'         => title_case($data_retur_penjualans->nama_barang),
                'jumlah_jual'         => $data_retur_penjualans->jumlah_jual,
                'jumlah_produk'       => $data_retur_penjualans->jumlah_produk,
                'satuan'              => $data_retur_penjualans->nama_satuan,
                'id_satuan'           => $data_retur_penjualans->satuan_id,
                'harga_produk'        => $data_retur_penjualans->harga_produk,
                'subtotal'            => $data_retur_penjualans->subtotal,
                'waktu'               => $data_retur_penjualans->Waktu,
            ]);
        }
        $url     = 'data-pelanggan-retur/'.$id_pelanggan;
        $respons = $this->dataPagination($data_retur_penjualan, $array, $url);
        return response()->json($respons);
    }
        public function pencarianPelangganRetur(Request $request, $id)
    {
        $session_id  = session()->getId();
        $user_warung = Auth::user()->id_warung;
        $id_pelanggan  = $id;
        $search = $request->search;
         if ($jenis_penjualan == 0) {
            $data_retur_penjualan     = PenjualanPos::getDataCariPenjualanRetur($id_pelanggan,$request)->paginate(10);
        }else{
            $data_retur_penjualan     = Penjualan::getDataCariPenjualanRetur($id_pelanggan,$request)->paginate(10);
        }

        $array = array();
        foreach ($data_retur_penjualan as $data_retur_penjualans) {
            array_push($array, [
                'id_penjualan'        => $data_retur_penjualans->id_penjualan,
                'kode_barang'         => $data_retur_penjualans->kode_barang,
                'nama_barang'         => title_case($data_retur_penjualans->nama_barang),
                'jumlah_jual'         => $data_retur_penjualans->jumlah_jual,
                'jumlah_produk'       => $data_retur_penjualans->jumlah_produk,
                'id_satuan'           => $data_retur_penjualans->satuan_id,
                'satuan'              => $data_retur_penjualans->nama_satuan,
                'harga_produk'        => $data_retur_penjualans->harga_produk,
                'subtotal'            => $data_retur_penjualans->subtotal,
                'waktu'               => $data_retur_penjualans->Waktu,
            ]);
        }
        $url     = 'data-pelanggan-retur/'.$id_pelanggan;
        $respons = $this->dataPaginationPencarianData($data_retur_penjualan, $array, $url, $search);
        return response()->json($respons);
    }
        //INSERT TBS
    public function prosesTbsReturPenjualan(Request $request)
    {
        $session_id = session()->getId();
        $data_satuan = explode("|", $request->satuan_produk); 
        $data_tbs   = TbsReturPenjualan::where('no_faktur_penjualan', $request->id_penjualan)->where('id_produk', $request->id_produk)
        ->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung);


        $subtotal = $request->jumlah_retur * $request->harga_produk;
        //JIKA FAKTUR YG DIPILIH SUDAH ADA DI TBS
        if ($data_tbs->count() > 0) {
                $subtotal_lama = $data_tbs->first()->subtotal;

                $jumlah_retur = $data_tbs->first()->jumlah_retur + $request->jumlah_retur;

                $subtotal_edit = ($jumlah_retur * $request->harga_produk) - $data_tbs->first()->potongan;

                $data_tbs->update(['jumlah_retur' => $jumlah_retur, 'subtotal' => $subtotal_edit, 'harga_produk' => $request->harga_produk, 'id_satuan' => $data_satuan[0], 'satuan_dasar' => $data_satuan[2]]);

                $subtotal = $jumlah_retur * $request->harga_produk;

                $respons['status']        = 1;
                $respons['subtotal_lama'] = $subtotal_lama;
                $respons['subtotal']      = $subtotal;
                return response()->json($respons);

        } else {
            $tbs_retur_penjualan = TbsReturPenjualan::create([
                'session_id'          => $session_id,
                'no_faktur_penjualan' => $request->id_penjualan,
                'id_produk'           => $request->id_produk,
                'harga_produk'        => $request->harga_produk,
                'jumlah_retur'        => $request->jumlah_retur,
                'jumlah_jual'         => $request->jumlah_jual,
                'subtotal'            => $subtotal,
                'id_satuan'           => $data_satuan[0], 
                'satuan_dasar'        => $data_satuan[2],
                'warung_id'           => Auth::user()->id_warung,
                'id_pelanggan'        => $request->pelanggan,
            ]);
            $respons['subtotal'] = $subtotal;
            return response()->json($respons);
        }
    }
        public function cekSubtotalTbsReturPenjualan($jenis_tbs)
    {
        $session_id  = session()->getId();
        $user_warung = Auth::user()->id_warung;
        if ($jenis_tbs == 1) {
            $TbsReturPenjualan = new TbsReturPenjualan();
            $subtotal            = $TbsReturPenjualan->subtotalTbs($user_warung, $session_id);
            $respons['subtotal'] = $subtotal;
        } else if ($jenis_tbs == 2) {
            
        }

        return response()->json($respons);
    }

        public function cekPelangganDouble()
    {
        $session_id       = session()->getId();
        $data_pelanggan_tbs = TbsReturPenjualan::cekPelangganReturPenjualan($session_id);
        return response()->json([
            "data_pelanggan" => $data_pelanggan_tbs->first(),
            "data_tbs"      => $data_pelanggan_tbs->count(),
        ]);
    }

        public function editJumlahReturTbs(Request $request) { 
 
        if (Auth::user()->id_warung == '') { 
            Auth::logout(); 
            return response()->view('error.403'); 
        } else { 
            $tbs_retur_penjualan = TbsReturPenjualan::find($request->id_tbs); 
 
            if ($tbs_retur_penjualan->tax == 0) { 
                $tax_produk = 0; 
            } else { 
                // TAX PRODUK = (HARGA * JUMLAH RETUR - POTONGAN) * TAX /100 
                $tax_produk = (($tbs_retur_penjualan->harga_produk * $request->jumlah_retur) - $tbs_retur_penjualan->potongan) * $tax / 100; 
 
                // TAX PERSEN = (TAX TBS PEMBELIAN * 100) / (HARGA * JUMLAH RETUR - POTONGAN) 
                $tax = ($tbs_retur_penjualan->tax * 100) / $tax_produk; 
            } 
 
            if ($tbs_retur_penjualan->ppn == 'Include') { 
                // JIKA PPN INCLUDE MAKA PAJAK TIDAK MEMPENGARUHI SUBTOTAL 
                $subtotal = ($tbs_retur_penjualan->harga_produk * $request->jumlah_retur) - $tbs_retur_penjualan->potongan; 
            } elseif ($tbs_retur_penjualan->ppn == 'Exclude') { 
                // JIKA PPN EXCLUDE MAKA PAJAK MEMPENGARUHI SUBTOTOTAL 
                $subtotal = (($tbs_retur_penjualan->harga_produk * $request->jumlah_retur) - $tbs_retur_penjualan->potongan) + $tax_produk; 
            } else { 
                $subtotal = ($tbs_retur_penjualan->harga_produk * $request->jumlah_retur) - $tbs_retur_penjualan->potongan; 
            } 
 
            // UPDATE JUMLAH RETUR, SUBTOTAL, DAN TAX 
            $tbs_retur_penjualan->update(['jumlah_retur' => $request->jumlah_retur, 'subtotal' => $subtotal, 'tax' => $tax_produk]); 
            $respons['subtotal'] = $subtotal; 
 
            return response()->json($respons); 
        } 
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

        public function editPotongan(Request $request) 
    { 
        $tbs_retur_penjualan = TbsReturPenjualan::find($request->id_tbs); 
 
        $total = $tbs_retur_penjualan->jumlah_retur * $tbs_retur_penjualan->harga_produk; 
 
        $potongan_produk = $this->cekPotongan($request->potongan_produk, $tbs_retur_penjualan->harga_produk, $tbs_retur_penjualan->jumlah_retur); 
 
        if ($potongan_produk == '') { 
 
            $respons['status'] = 0; 
 
            return response()->json($respons); 
 
        } else if ($potongan_produk > $total) { 
 
            $respons['status'] = 1; 
 
            return response()->json($respons); 
 
        } else { 
            $subtotal = ($tbs_retur_penjualan->jumlah_retur * $tbs_retur_penjualan->harga_produk) - $potongan_produk; 
 
            $tbs_retur_penjualan->update(['potongan' => $potongan_produk, 'subtotal' => $subtotal]); 
 
            $potongan            = $this->tampilPotongan($potongan_produk, $tbs_retur_penjualan->jumlah_retur, $tbs_retur_penjualan->harga_produk); 
            $respons['potongan'] = $potongan; 
            $respons['subtotal'] = $subtotal; 
 
            return response()->json($respons); 
        } 
    }

        public function editSatuan($request, $db){

            $satuan_konversi = explode("|", $request->satuan_produk);
            $edit_tbs_penjualan = $db::find($request->id_tbs);

            $subtotal = ($edit_tbs_penjualan->jumlah_retur * $satuan_konversi[5]) - $edit_tbs_penjualan->potongan;

            $edit_tbs_penjualan->update(['id_satuan' => $satuan_konversi[0], 'harga_produk' => $satuan_konversi[5], 'subtotal' => $subtotal]);

            $respons['harga_produk'] = $satuan_konversi[5];
            $respons['nama_satuan']     = $satuan_konversi[1];
            $respons['satuan_id']     = $satuan_konversi[0];
            $respons['subtotal']     = $subtotal;

            return $respons;
        }

        public function editSatuanTbs(Request $request){

            $db = 'App\TbsReturPenjualan';
            $respons = $this->editSatuan($request, $db);

            return response()->json($respons);
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
 
    public function prosesBatalReturPenjualan()
        {

            $session_id         = session()->getId();
            $data_tbs_retur_penjualan = TbsReturPenjualan::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->delete();

            return response(200);
        }

        //hapus tbs tbs retur penjualan
    public function prosesHapusTbsReturPenjualan($id)
    {
        if (!TbsReturPenjualan::destroy($id)) {
            return 0;
        } else {
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
        // START TRANSAKSI
            DB::beginTransaction();
            $warung_id  = Auth::user()->id_warung;
            $session_id = session()->getId();
            $no_faktur  = ReturPenjualan::no_faktur($warung_id);

            $tbs_retur = TbsReturPenjualan::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung);

            if ($tbs_retur->count() == 0) {

                return $tbs_retur->count();

            } else {
            //INSERT RETUR PEMBELIAN
                $pelanggan   = TbsReturPenjualan::select('id_pelanggan')->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->first()->id_pelanggan;
                $total = $request->total_akhir;

                $retur = ReturPenjualan::create([
                    'no_faktur_retur'   => $no_faktur,
                    'id_pelanggan'        => $pelanggan,
                    'total'             => $total < 0 ? 0 : $total,
                    'total_bayar'       => $request->total_akhir,
                    'potongan'          => $request->potongan_faktur,
                    'id_kas'             => $request->kas,
                    'warung_id'         => $warung_id,
                    ]);

                TransaksiKas::create([
                    'no_faktur'       => $no_faktur,
                    'jenis_transaksi' => 'Retur Penjualan',
                    'jumlah_keluar'    => $total < 0 ? 0 : $total,
                    'kas'             => $request->kas,
                    'warung_id'       => $warung_id
                    ]);

                // /*Jika Retur Pembelian, menggunakan fitur potong hutang*/
                // if ($request->potong_hutang != '' || $request->potong_hutang != 0) {

                //     if ($total < 0) {
                //         $subtotal_akhir = $request->total_akhir;
                //     }else{
                //         $subtotal_akhir = $request->potong_hutang;
                //     }

                //     while ($subtotal_akhir > 0) {

                //         foreach ($request['faktur_hutang'] as $faktur_hutang) {

                //             $id_pembelian = Pembelian::select('id')->where('no_faktur', $faktur_hutang)->first()->id;
                //             $sisa_hutang = TransaksiHutang::getDataPembelianHutangFaktur($faktur_hutang)->having('sisa_hutang', '>', 0)->first()->sisa_hutang;

                //             if ($subtotal_akhir == $sisa_hutang) {

                //                 TransaksiHutang::create([
                //                     'no_faktur'       => $no_faktur,
                //                     'id_transaksi'    => $id_pembelian,
                //                     'jenis_transaksi' => 'Retur Pembelian',
                //                     'jumlah_keluar'   => $subtotal_akhir,
                //                     'suplier_id'      => $supplier,
                //                     'warung_id'       => $warung_id,
                //                     ]);
                                
                //                 $subtotal_akhir = 0;
                //             }elseif ($subtotal_akhir > $sisa_hutang) {

                //                 TransaksiHutang::create([
                //                     'no_faktur'       => $no_faktur,
                //                     'id_transaksi'    => $id_pembelian,
                //                     'jenis_transaksi' => 'Retur Pembelian',
                //                     'jumlah_keluar'   => $sisa_hutang,
                //                     'suplier_id'      => $supplier,
                //                     'warung_id'       => $warung_id,
                //                     ]);
                                
                //                 $subtotal_akhir = $subtotal_akhir - $sisa_hutang;
                //             }elseif ($subtotal_akhir < $sisa_hutang) {

                //                 TransaksiHutang::create([
                //                     'no_faktur'       => $no_faktur,
                //                     'id_transaksi'    => $id_pembelian,
                //                     'jenis_transaksi' => 'Retur Pembelian',
                //                     'jumlah_keluar'   => $subtotal_akhir,
                //                     'suplier_id'      => $supplier,
                //                     'warung_id'       => $warung_id,
                //                     ]);
                                
                //                 $subtotal_akhir = 0;
                //             }
                //         } /*END FOREACH*/
                //     } /*END WHILE*/
                // }

                foreach ($tbs_retur->get() as $data_tbs) {



                    if ($data_tbs->id_satuan != $data_tbs->satuan_dasar) {

                        $jumlah_konversi = SatuanKonversi::select('jumlah_konversi')->where('warung_id', Auth::user()->id_warung)
                        ->where('id_produk', $data_tbs->id_produk)
                        ->where('id_satuan', $data_tbs->id_satuan)->first()->jumlah_konversi;

                        $jumlah_dasar = SatuanKonversi::select('jumlah_konversi')->where('id_satuan', $data_tbs->satuan_dasar);
                        if ($jumlah_dasar->count() > 0) {
                            $jumlah_konversi_dasar = intval($data_tbs->jumlah_retur) * (intval($jumlah_dasar->first()->jumlah_konversi) * intval($jumlah_konversi));
                        } else {
                            $jumlah_konversi_dasar = intval($data_tbs->jumlah_retur) * intval($jumlah_konversi);
                        }

                    }

                        // INSERT DETAIL
                        $detail = DetailReturPenjualan::create([
                            'no_faktur_retur'   => $no_faktur,
                            'no_faktur_penjualan' => $data_tbs->no_faktur_penjualan,
                            'id_produk'         => $data_tbs->id_produk,
                            'jumlah_retur'     => $data_tbs->jumlah_retur,
                            'jumlah_jual'     => $data_tbs->jumlah_jual,
                            'id_satuan'         => $data_tbs->id_satuan,
                            'satuan_dasar'      => $data_tbs->satuan_dasar,
                            'harga_produk'      => $data_tbs->harga_produk,
                            'subtotal'          => $data_tbs->subtotal,
                            'potongan'          => $data_tbs->potongan,
                            'id_pelanggan'      => $data_tbs->id_pelanggan,
                            'warung_id'         => $data_tbs->warung_id,
                            'created_at'        => $retur->created_at,
                            ]);
                   
                }

            }
                // HAPUS TBS
            $tbs_retur->delete();

            DB::commit();

            $respons['respons_retur'] = $retur->id;
            return response()->json($respons);
        }
    }

        // VIEW DETAIL
    public function viewDetail($id)
    {
        $warung_id = Auth::user()->id_warung;
        $retur_penjualan = ReturPenjualan::find($id);

        $detail_returs = DetailReturPenjualan::dataDetailRetur($retur_penjualan->no_faktur_retur)->paginate(10);

        $array = [];
        foreach ($detail_returs as $detail_retur) {
            array_push($array, [
                'detail_retur'=> $detail_retur,
                ]);
        }

        $url     = '/retur-pembelian/view-tbs';
        $respons = $this->dataPagination($detail_returs, $array, $retur_penjualan->no_faktur_retur, $url);

        return response()->json($respons);
    }

    // PENCARIAN DETAIL
    public function pencarianDetail(Request $request, $id)
    {
        $warung_id = Auth::user()->id_warung;
        $search = $request->search;
        $retur_penjualan = ReturPenjualan::find($id);

        $detail_returs = DetailReturPenjualan::dataDetailRetur($retur_penjualan->no_faktur_retur)
        ->where(function ($query) use ($search) {
            $query->orwhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
            ->orwhere('satuans.nama_satuan', 'LIKE', '%' . $search . '%');
        })->paginate(10);

        $array = [];
        foreach ($detail_returs as $detail_retur) {
            array_push($array, [
                'detail_retur'=> $detail_retur,
                ]);
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
        //START TRANSAKSI
        DB::beginTransaction();

        if (!ReturPenjualan::destroy($id)) {
            DB::rollBack();
            return 0;
        } else {
            DB::commit();
            return response(200);
        }
    }
}
