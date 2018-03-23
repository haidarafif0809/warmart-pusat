<?php

namespace App\Http\Controllers;

use App\Barang;
use App\DetailPembelian;
use App\EditTbsPembelian;
use App\Pembelian;
use App\TransaksiHutang;
use App\TransaksiKas;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class EditPembelianController extends Controller
{
    public function __construct()
    {
        $this->middleware('user-must-warung');
    }

    //PROSES TAMBAH TBS PEMBELIAN
    public function proses_tambah_tbs_pembelian(Request $request)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            $no_faktur  = $request->no_faktur; // NO FAKTUR
            $session_id = session()->getId(); // SESSION ID

            // CEK EDIT TBS PEMBELIAN
            $data_tbs = EditTbsPembelian::where('id_produk', $request->id_produk_tbs)
                ->where('no_faktur', $no_faktur)->where('warung_id', Auth::user()->id_warung);

//JIKA PRODUK YG DIPILIH SUDAH ADA DI TBS
            if ($data_tbs->count() > 0) {

                $jumlah_produk = $data_tbs->first()->jumlah_produk + $request->jumlah_produk;

                $subtotal_edit = ($jumlah_produk * $request->harga_produk) - $data_tbs->first()->potongan;

                $data_tbs->update(['jumlah_produk' => $jumlah_produk, 'subtotal' => $subtotal_edit, 'harga_produk' => $request->harga_produk]);

                return response(200);
            } else {

                // SELECT PRODUK
                $barang = Barang::select('nama_barang', 'satuan_id')->where('id', $request->id_produk_tbs)->where('id_warung', Auth::user()->id_warung)->first();
                // SUBTOTAL = JUMLAH * HARGA
                $subtotal = $request->jumlah_produk * $request->harga_produk;
                // INSERT EDIT TBS PEMBELIAN
                $Insert_tbspembelian = EditTbsPembelian::create([
                    'id_produk'     => $request->id_produk_tbs,
                    'no_faktur'     => $no_faktur,
                    'session_id'    => $session_id,
                    'jumlah_produk' => $request->jumlah_produk,
                    'harga_produk'  => $request->harga_produk,
                    'subtotal'      => $subtotal,
                    'satuan_id'     => $request->satuan,
                    'satuan_dasar'  => $request->satuan_dasar,
                    'warung_id'     => Auth::user()->id_warung,
                ]);
                return response(200);
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
            // SELECT EDIT TBS PEMBELIAN
            $tbs_pembelian = EditTbsPembelian::find($request->id_tbs_pembelian);
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
                // JIKA PPN EXCLUDE MAKA PAJAK MEMPENGARUHI SUBTOTAL
                $subtotal = (($tbs_pembelian->harga_produk * $request->jumlah_edit_produk) - $tbs_pembelian->potongan) + $tax_produk;
            } else {
                $subtotal = ($tbs_pembelian->harga_produk * $request->jumlah_edit_produk) - $tbs_pembelian->potongan;
            }

            // UPDATE JUMLAH PRODUK, SUBTOTAL, DAN TAX
            $tbs_pembelian->update(['jumlah_produk' => $request->jumlah_edit_produk, 'subtotal' => $subtotal, 'tax' => $tax_produk]);
            $nama_barang = $tbs_pembelian->TitleCaseBarang; // TITLE CASH

            return response(200);
        }
    }

//PROSES EDIT HARGA TBS PEMBELIAN
    public function edit_harga_tbs_pembelian(Request $request)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            // SELECT EDIT TBS PEMBELIAN
            $tbs_pembelian = EditTbsPembelian::find($request->id_harga);

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
                // TAX PROSUK = ((HARGA * JUMLAH) - POTONGAN) * TAX PERSEN / 100
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

            return response(200);
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
            $tbs_pembelian = EditTbsPembelian::find($request->id_potongan);
            $potongan      = substr_count($request->potongan_edit_produk, '%'); // UNTUK CEK APAKAH ADA STRING "%"
            // JIKA TIDAK ADA
            if ($potongan == 0) {
                // FILTER NUMBER FLOAT
                $potongan_produk = filter_var($request->potongan_edit_produk, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); // POTONGAN TIDAK DALAM BENTUK NOMINAL
                $potongan_persen = 0;
            } else {
                // JIKA ADA
                // FILTER NUMBER FLOAT
                $potongan_persen = filter_var($request->potongan_edit_produk, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); //  PISAH STRING BERDASRAKAN TANDA "%" NYA
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
                // UPDATE  SUBTOTAL, POTONGAN, TAX
                $tbs_pembelian->update(['potongan' => $potongan_produk, 'subtotal' => $subtotal, 'tax' => $tax_produk]);
                $nama_barang = $tbs_pembelian->TitleCaseBarang; // TITLE CASH

                return response(200);
            }
        }
    }

    public function editTaxTbsPembelian(Request $request)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            // SELECT EDIT  TBS PEMBELIAN
            $tbs_pembelian = EditTbsPembelian::find($request->id_tax);
            $tax           = substr_count($request->tax_edit_produk, '%'); // UNTUK CEK APAKAH ADA STRING "%"
            // JIKA TIDAK ADA
            if ($tax == 0) {
                if ($request->ppn_produk == 'Exclude') {
                    $tax_produk  = filter_var($request->tax_edit_produk, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); // TAX DAALAM BENTUK NOMINAL
                    $tax_include = 0;
                } else {
                    $tax_produk  = 0;
                    $tax_include = filter_var($request->tax_edit_produk, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); // TAX DAALAM BENTUK NOMINAL
                }
                $tax_persen = 0; //  PISAH STRING BERDASRAKAN TANDA "%"
            } else {
                // JIKA ADA
                $tax_persen = filter_var($request->tax_edit_produk, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); //  PISAH STRING BERDASRAKAN TANDA "%"
                // TAX PRODUK = ((HARGA * JUMLAH) - POTONGAN) * TAX PERSEN / 100
                if ($request->ppn_produk == 'Include') {
                    $tax_produk = 0;
                    //perhitungan tax include
                    $default_tax              = 1;
                    $subtotal_kurang_potongan = (($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $tbs_pembelian->potongan);
                    $hasil_tax                = $default_tax + ($tax_persen / 100);
                    $hasil_tax2               = $subtotal_kurang_potongan / $hasil_tax;
                    $tax_include              = $subtotal_kurang_potongan - $hasil_tax2;
                    //perhitungan tax include
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
            return response(200);

        }
    }

    //PROSES CEK PERSEN MELEBIHI BATAS
    public function cek_persen_potongan_pembelian(Request $request)
    {
        // SELECT EDIT TBS PEMBELIAN
        $tbs_pembelian = EditTbsPembelian::find($request->id_potongan);
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

    //PROSES CEK PERSEN TAX  MELEBIHI BATAS
    public function cek_persen_tax_pembelian(Request $request)
    {
        // SELECT EDIT TBS PEMBELIAN
        $tbs_pembelian = EditTbsPembelian::find($request->id_tax);
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

//PROSES HAPUS TBS PEMBELIAN
    public function hapus_tbs_pembelian($id)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            EditTbsPembelian::destroy($id);
            return response(200);
        }
    }

    //PROSES BATAL TBS PEMBELIAN
    public function proses_batal_transaksi_pembelian(Request $request)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            $no_faktur          = $request->no_faktur_batal;
            $data_tbs_pembelian = EditTbsPembelian::where('no_faktur', $no_faktur)->where('warung_id', Auth::user()->id_warung)->delete();

            return response(200);
        }
    }

    public function total_kas(Request $request)
    {
        $session_id            = session()->getId();
        $total_kas             = TransaksiKas::total_kas($request);
        $data_produk_pembelian = EditTbsPembelian::where('no_faktur', $request->no_faktur)->where('warung_id', Auth::user()->id_warung);
        $kas                   = TransaksiKas::select('jumlah_keluar')->where('no_faktur', $request->no_faktur)->where('warung_id', Auth::user()->id_warung);
        if ($kas->count() == 0) {
            $jumlah_kas_lama = 0;
        } else {
            $jumlah_kas_lama = $kas->first()->jumlah_keluar;
        }

        $respons['total_kas']             = $total_kas;
        $respons['data_produk_pembelian'] = $data_produk_pembelian;
        $respons['jumlah_kas_lama']       = $jumlah_kas_lama;

        return $respons;
    }
    public function cekDataPembelian($id)
    {

        return $penjualan = Pembelian::find($id);
    }

    //PROSES SELESAI TRANSAKSI EDIT PEMBELIAN
    public function prosesEditPembelian(Request $request)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            //START TRANSAKSI
            DB::beginTransaction();
            $no_faktur  = $request->no_faktur_edit;
            $session_id = session()->getId();
            $user       = Auth::user()->id;

            $data_produk_pembelian = EditTbsPembelian::where('no_faktur', $no_faktur)->where('warung_id', Auth::user()->id_warung);

            $data_detail_pembelian = DetailPembelian::where('no_faktur', $no_faktur)->where('warung_id', Auth::user()->id_warung)->get();
            //HAPUS DETAIL PEMBELIAN

            foreach ($data_produk_pembelian->get() as $data_produk_tbs) {

                if ($data_produk_tbs->satuan_id == $data_produk_tbs->satuan_dasar) {

                    $detail_pembelian = DetailPembelian::select('harga_produk')->where('no_faktur', $data_produk_tbs->no_faktur)->where('id_produk', $data_produk_tbs->id_produk)->where('warung_id', Auth::user()->id_warung);

                    if ($detail_pembelian->count() > 0) {
                        if ($detail_pembelian->first()->harga_produk != $data_produk_tbs->harga_produk) {
                            Barang::find($data_detail->id_produk)->update(['harga_beli' => $harga_tbs->harga_produk]);
                        }
                    } else {
                        $barang = Barang::select('harga_beli')->where('id', $data_produk_tbs->id_produk)->where('id_warung', Auth::user()->id_warung);
                        if ($barang->first()->harga_beli != $data_produk_tbs->harga_produk) {
                            $barang->update(['harga_beli' => $data_produk_tbs->harga_produk]);
                        }
                    }

                }

            }

            foreach ($data_detail_pembelian as $data_detail) {

                $harga_tbs = EditTbsPembelian::select('harga_produk')->where('no_faktur', $data_detail->no_faktur)->where('id_produk', $data_detail->id_produk)->where('warung_id', Auth::user()->id_warung);
                if ($harga_tbs->count() > 0 and $harga_tbs->first()->harga_produk != $data_detail->harga_produk) {
                    Barang::find($data_detail->id_produk)->update(['harga_beli' => $harga_tbs->harga_produk]);
                }

                if (!$hapus_detail = DetailPembelian::destroy($data_detail->id_detail_pembelian)) {
                    //DI BATALKAN PROSES NYA
                    DB::rollBack();
                    return $respons['harga_tbs'] = $harga_tbs;
                }
            }

            foreach ($data_produk_pembelian->get() as $data_tbs) {
                //INSERT DETAIL PEMBELIAN
                $detail_pembelian = DetailPembelian::create([
                    'no_faktur'     => $no_faktur,
                    'satuan_id'     => $data_tbs->satuan_id,
                    'satuan_dasar'  => $data_tbs->satuan_dasar,
                    'id_produk'     => $data_tbs->id_produk,
                    'jumlah_produk' => $data_tbs->jumlah_produk,
                    'harga_produk'  => $data_tbs->harga_produk,
                    'subtotal'      => $data_tbs->subtotal,
                    'tax'           => $data_tbs->tax,
                    'tax_include'   => $data_tbs->tax_include,
                    'potongan'      => $data_tbs->potongan,
                    'ppn'           => $data_tbs->ppn,
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

            $update_pembelian = Pembelian::find($request->id_pembelian)->update([
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
            ]);

            $kas = intval($update_pembelian->tunai) - intval($update_pembelian->kembalian);
            if ($kas > 0) {

                TransaksiKas::where('no_faktur', $update_pembelian->no_faktur)->where('warung_id', Auth::user()->id_warung)->delete();
                TransaksiKas::create([
                    'no_faktur'       => $update_pembelian->no_faktur,
                    'jenis_transaksi' => 'pembelian',
                    'jumlah_keluar'   => $kas,
                    'kas'             => $update_pembelian->cara_bayar,
                    'warung_id'       => $update_pembelian->warung_id]);
            }
            if ($update_pembelian->kredit > 0) {
                TransaksiHutang::where('no_faktur', $update_pembelian->no_faktur)->where('warung_id', Auth::user()->id_warung)->delete();
                TransaksiHutang::create([
                    'no_faktur'       => $update_pembelian->no_faktur,
                    'jenis_transaksi' => 'pembelian',
                    'id_transaksi'    => $update_pembelian->id,
                    'jumlah_masuk'    => $update_pembelian->kredit,
                    'suplier_id'      => $update_pembelian->suplier_id,
                    'warung_id'       => $update_pembelian->warung_id]);
            }

            //HAPUS TBS PEMBELIAN
            $data_produk_pembelian->delete();

            DB::commit();
            return response(200);

        }
    }
}
