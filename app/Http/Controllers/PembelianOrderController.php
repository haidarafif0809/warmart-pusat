<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Suplier;
use App\TbsPembelianOrder;
use App\DetailPembelianOrder;
use App\PembelianOrder;
use App\Barang;
use App\SatuanKonversi;
use Illuminate\Support\Facades\DB;
use Auth;

class PembelianOrderController extends Controller
{

    // DATA SUPLIER
    public function dataSuplier()
    {
        $suplier = Suplier::select('id', 'nama_suplier')
        ->where('warung_id', Auth::user()->id_warung)->get();
        return response()->json($suplier);
    }

    public function editSatuan($request, $db){

        $satuan_konversi = explode("|", $request->satuan_produk);
        $harga_produk = Barang::select('harga_beli')->find($satuan_konversi[6])->harga_beli;

        $harga_beli = $harga_produk * ($satuan_konversi[3] * $satuan_konversi[4]);

        $edit_tbs_penjualan = $db::find($request->id_tbs);

        $subtotal = ($edit_tbs_penjualan->jumlah_produk * $harga_beli) - $edit_tbs_penjualan->potongan;

        $edit_tbs_penjualan->update(['satuan_id' => $satuan_konversi[0], 'harga_produk' => $harga_beli, 'subtotal' => $subtotal]);

        $respons['harga_produk'] = $harga_beli;
        $respons['nama_satuan']     = $satuan_konversi[1];
        $respons['satuan_id']     = $satuan_konversi[0];
        $respons['subtotal']     = $subtotal;

        return $harga_produk;
    }

    // SATUAN KONVERSI
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

    // DATA PAGINTION
    public function dataPagination($pembelian_orders, $array, $no_faktur, $url)
    {

        //DATA PAGINATION
        $respons['current_page']   = $pembelian_orders->currentPage();
        $respons['data']           = $array;
        $respons['no_faktur']      = $no_faktur;
        $respons['first_page_url'] = url($url . '?page=' . $pembelian_orders->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $pembelian_orders->lastPage();
        $respons['last_page_url']  = url($url . '?page=' . $pembelian_orders->lastPage());
        $respons['next_page_url']  = $pembelian_orders->nextPageUrl();
        $respons['path']           = url($url);
        $respons['per_page']       = $pembelian_orders->perPage();
        $respons['prev_page_url']  = $pembelian_orders->previousPageUrl();
        $respons['to']             = $pembelian_orders->perPage();
        $respons['total']          = $pembelian_orders->total();
        //DATA PAGINATION

        return $respons;
    }


    // VIEW TBS PEMBELIAN
    public function viewTbsPembelian()
    {
        $session_id  = session()->getId();
        $no_faktur   = '';
        $user_warung = Auth::user()->id_warung;

        $tbs_pembelian_orders = TbsPembelianOrder::dataTransaksiTbsPembelianOrder($session_id, $user_warung)
        ->orderBy('tbs_pembelian_orders.id_tbs_pembelian_order', 'desc')->paginate(10);
        $array = array();

        foreach ($tbs_pembelian_orders as $tbs_pembelian_order) {

            $potongan_persen        = ($tbs_pembelian_order->potongan / ($tbs_pembelian_order->jumlah_produk * $tbs_pembelian_order->harga_produk)) * 100;

            $ppn = TbsPembelianOrder::select('ppn')->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->where('ppn', '!=', '')->limit(1);

            if ($ppn->count() > 0) {

                $ppn_produk = $ppn->first()->ppn;
                if ($tbs_pembelian_order->tax == 0) {
                    $tax_persen = 0;
                } else {
                    if ($tbs_pembelian_order->ppn == "Include") {
                        $tax_kembali = $tbs_pembelian_order->subtotal - $tbs_pembelian_order->tax;
                        //tax untuk mendapatkan 1,1
                        $tax_format = $tbs_pembelian_order->subtotal / $tax_kembali - 1;
                        $tax_persen = $tax_format * 100;

                    } else if ($tbs_pembelian_order->ppn == "Exclude") {
                        $tax_persen = ($tbs_pembelian_order->tax * 100) / ($tbs_pembelian_order->jumlah_produk * $tbs_pembelian_order->harga_produk - $tbs_pembelian_order->potongan);
                    }
                }
            } else {
                $ppn_produk = "";
                $tax_persen = 0;
            }

            array_push($array, [
                'data_tbs'            => $tbs_pembelian_order,
                'nama_satuan'            => strtoupper($tbs_pembelian_order->nama_satuan),
                'potongan_persen'        => $potongan_persen,
                'ppn_produk'             => $ppn_produk,
                'tax_persen'             => $tax_persen,
                ]);
        }

        $url     = '/pembelian-order/view-tbs-pembelian';
        $respons = $this->dataPagination($tbs_pembelian_orders, $array, $no_faktur, $url);

        return response()->json($respons);
    }



    public function pencarianTbsPembelian(Request $request)
    {
        $session_id  = session()->getId();
        $no_faktur   = '';
        $user_warung = Auth::user()->id_warung;

        $tbs_pembelian_orders = TbsPembelianOrder::dataTransaksiTbsPembelianOrder($session_id, $user_warung)
        ->where(function ($query) use ($request) {

            $query->orWhere('barangs.nama_barang', 'LIKE', '%'. $request->search . '%')
            ->orWhere('barangs.kode_barang', 'LIKE', '%'. $request->search . '%');

        })->orderBy('id_tbs_pembelian_order', 'desc')->paginate(10);
        $array = array();

        foreach ($tbs_pembelian_orders as $tbs_pembelian_order) {

            $potongan_persen        = ($tbs_pembelian_order->potongan / ($tbs_pembelian_order->jumlah_produk * $tbs_pembelian_order->harga_produk)) * 100;

            $ppn = TbsPembelianOrder::select('ppn')->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->where('ppn', '!=', '')->limit(1);

            if ($ppn->count() > 0) {

                $ppn_produk = $ppn->first()->ppn;
                if ($tbs_pembelian_order->tax == 0) {
                    $tax_persen = 0;
                } else {
                    if ($tbs_pembelian_order->ppn == "Include") {
                        $tax_kembali = $tbs_pembelian_order->subtotal - $tbs_pembelian_order->tax;
                        //tax untuk mendapatkan 1,1
                        $tax_format = $tbs_pembelian_order->subtotal / $tax_kembali - 1;
                        $tax_persen = $tax_format * 100;

                    } else if ($tbs_pembelian_order->ppn == "Exclude") {
                        $tax_persen = ($tbs_pembelian_order->tax * 100) / ($tbs_pembelian_order->jumlah_produk * $tbs_pembelian_order->harga_produk - $tbs_pembelian_order->potongan);
                    }
                }
            } else {
                $ppn_produk = "";
                $tax_persen = 0;
            }

            array_push($array, [
                'data_tbs'            => $tbs_pembelian_order,
                'nama_satuan'            => strtoupper($tbs_pembelian_order->nama_satuan),
                'potongan_persen'        => $potongan_persen,
                'ppn_produk'             => $ppn_produk,
                'tax_persen'             => $tax_persen,
                ]);
        }

        $url     = '/pembelian-order/view-tbs-pembelian';
        $respons = $this->dataPagination($tbs_pembelian_orders, $array, $no_faktur, $url);

        return response()->json($respons);
    }



    //PROSES TAMBAH TBS PEMBELIAN ORDER
    public function proses_tambah_tbs_pembelian(Request $request)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {

            $session_id = session()->getId();
            $data_tbs   = TbsPembelianOrder::where('id_produk', $request->id_produk_tbs)
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
                $Insert_tbspembelian = TbsPembelianOrder::create([
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
            $tbs_pembelian_order = TbsPembelianOrder::find($request->id_tbs_pembelian);
            // JIKA TAX/ PAJAKK EDIT TBS PEMBELIAN == 0
            if ($tbs_pembelian_order->tax == 0) {
                $tax_produk = 0;
            } else {
                // TAX PERSEN = (TAX TBS PEMBELIAN * 100) / (JUMLAH PRODUK * HARGA - POTONGAN)
                $tax = ($tbs_pembelian_order->tax * 100) / ($request->jumlah_edit_produk * $tbs_pembelian_order->harga_produk - $tbs_pembelian_order->potongan); // TAX DALAM BENTUK PERSEN
                // TAX PRODUK = (HARGA * JUMLAH - POTONGAN) * TAX /100
                $tax_produk = (($tbs_pembelian_order->harga_produk * $request->jumlah_edit_produk) - $tbs_pembelian_order->potongan) * $tax / 100;
            }

            if ($tbs_pembelian_order->ppn == 'Include') {
                // JIKA PPN INCLUDE MAKA PAJAK TIDAK MEMPENGARUHI SUBTOTAL
                $subtotal = ($tbs_pembelian_order->harga_produk * $request->jumlah_edit_produk) - $tbs_pembelian_order->potongan;
            } elseif ($tbs_pembelian_order->ppn == 'Exclude') {
                // JIKA PPN EXCLUDE MAKA PAJAK MEMPENGARUHI SUBTOT
                $subtotal = (($tbs_pembelian_order->harga_produk * $request->jumlah_edit_produk) - $tbs_pembelian_order->potongan) + $tax_produk;
            } else {
                $subtotal = ($tbs_pembelian_order->harga_produk * $request->jumlah_edit_produk) - $tbs_pembelian_order->potongan;
            }
            // UPDATE JUMLAH PRODUK, SUBTOTAL, DAN TAX
            $tbs_pembelian_order->update(['jumlah_produk' => $request->jumlah_edit_produk, 'subtotal' => $subtotal, 'tax' => $tax_produk]);

            $respons['subtotal'] = $subtotal;

            return response()->json($respons);

        }
    }

    public function editSatuanTbsPembelian(Request $request){

        $db = 'App\TbsPembelianOrder';
        $respons = $this->editSatuan($request, $db);

        return response()->json($respons);
    }

    //PROSES EDIT HARGA TBS PEMBELIAN
    public function edit_harga_tbs_pembelian(Request $request)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            // SELECT  TBS PEMBELIAN
            $tbs_pembelian = TbsPembelianOrder::find($request->id_harga);

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
        $tbs_pembelian = TbsPembelianOrder::find($request->id_potongan);
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

    // PROSES EDIT POTONGAN TBS ORDER PEMBELIAN
    public function edit_potongan_tbs_pembelian(Request $request)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            // SELECT EDIT TBS PEMBELIAN
            $tbs_pembelian = TbsPembelianOrder::find($request->id_potongan);
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

            if ($potongan_persen <= 100){

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


    public function editTaxTbsPembelian(Request $request)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            // SELECT TBS PEMBELIAN ORDER
            $tbs_pembelian = TbsPembelianOrder::find($request->id_tax);
            $tax           = substr_count($request->tax_edit_produk, '%'); // UNTUK CEK APAKAH ADA STRING "%"
            // JIKA TIDAK ADA
            if ($tax == 0) {
                if ($request->ppn_produk == 'Exclude') {
                    $tax_produk  = filter_var($request->tax_edit_produk, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); // TAX DALAM BENTUK NOMINAL
                    $tax_include = 0;
                } else {
                    $tax_produk  = 0;
                    $tax_include = filter_var($request->tax_edit_produk, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); // TAX DALAM BENTUK NOMINAL;
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

            $respons['subtotal'] = $subtotal;

            return response()->json($tbs_pembelian);

        }
    }

    //PROSES HAPUS TBS PEMBELIAN ORDER
    public function hapus_tbs_pembelian($id)
    {
        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            $tbs_pembelian = TbsPembelianOrder::find($id);

            $respons['subtotal'] = $tbs_pembelian->subtotal;

            $tbs_pembelian->delete();

            return response()->json($respons);

        }
    }

    //PROSES BATAL TBS PEMBELIAN ORDER
    public function proses_batal_transaksi_pembelian()
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            $session_id         = session()->getId();
            $data_tbs_pembelian = TbsPembelianOrder::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->delete();

            return response(200);
        }
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
            //START TRANSAKSI
            DB::beginTransaction();
            $warung_id  = Auth::user()->id_warung;
            $session_id = session()->getId();
            $user       = Auth::user()->id;
            $no_faktur  = PembelianOrder::no_faktur($warung_id);

            //INSERT DETAIL PEMBELIAN
            $data_produk_pembelian_order = TbsPembelianOrder::where('session_id', $session_id)->where('warung_id', $warung_id);

            // INSERT DETAIL PEMBELIAN
            foreach ($data_produk_pembelian_order->get() as $data_tbs_pembelian_order) {

                $detail_pembelian = DetailPembelianOrder::create([
                    'no_faktur_order'  => $no_faktur,
                    'id_produk'        => $data_tbs_pembelian_order->id_produk,
                    'jumlah_produk'    => $data_tbs_pembelian_order->jumlah_produk,
                    'satuan_id'        => $data_tbs_pembelian_order->satuan_id,
                    'satuan_dasar'     => $data_tbs_pembelian_order->satuan_dasar,
                    'harga_produk'     => $data_tbs_pembelian_order->harga_produk,
                    'subtotal'         => $data_tbs_pembelian_order->subtotal,
                    'tax'              => $data_tbs_pembelian_order->tax,
                    'potongan'         => $data_tbs_pembelian_order->potongan,
                    'status_harga'     => $data_tbs_pembelian_order->status_harga,
                    'warung_id'        => $warung_id,
                    ]);
            }

            $pembelian = PembelianOrder::create([
                'no_faktur_order'   => $no_faktur,
                'suplier_id'        => $request->suplier,
                'total'             => $request->subtotal,
                'keterangan'        => $request->keterangan,
                'status_order'      => 1, // Diorder
                'warung_id'         => $warung_id,
                ]);


            //HAPUS TBS PEMBELIAN ORDER
            $data_produk_pembelian_order->delete();
            DB::commit();

            $respons['respons_pembelian'] = $pembelian->id;
            return response()->json($respons);

        }
    }



    public function view()
    {
        //SELECT SEMUA TRASNSAKSI PEMBELIAN ORDER
        $no_faktur = '';
        $data_pembelian_order = PembelianOrder::dataTransaksiPembelianOrder()->paginate(10);
        //PERULANGAN
        $array_pembelian = array();
        foreach ($data_pembelian_order as $pembelian_order) {
            array_push($array_pembelian, [
                'data'          => $pembelian_order,
                'status_order'  => $pembelian_order->Status
                ]);
        }

        $url     = '/pembelian-order/view';
        //DATA PAGINATION
        $respons = $this->dataPagination($data_pembelian_order, $array_pembelian, $no_faktur, $url);

        return response()->json($respons);
    }


//VIEW DETAIL PEMBELIAN ORDER & PENCARIAN
    public function viewDetailPembelianOrder($id)
    {
        $warung_id = Auth::user()->id_warung;
        $pembelian = PembelianOrder::find($id);

        $data_detailOrder = DetailPembelianOrder::detailPembelianOrder($warung_id, $pembelian->no_faktur_order)->paginate(10);

        $array_order = [];
        foreach ($data_detailOrder as $detail_order) {
            array_push($array_order, [
                'detail_order'=> $detail_order,
                'nama_produk'=> $detail_order->NamaProduk,
                ]);
        }

        $url     = '/pembelian-order/view-detail-pembelian-order';
        //DATA PAGINATION
        $respons = $this->dataPagination($data_detailOrder, $array_order, $pembelian->no_faktur_order, $url);


        return response()->json($respons);
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
