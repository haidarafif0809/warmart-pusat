<?php

namespace App\Http\Controllers;

use App\DetailPenjualan;
use App\DetailPenjualanPos;
use App\DetailPesananPelanggan;
use App\Hpp;
use App\Penjualan;
use App\PesananPelanggan;
use App\SettingAplikasi;
use App\TransaksiKas;
use App\User;
use App\Warung;
use App\SettingPembedaAplikasi;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Mail;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Html\Builder;

class PesananWarungController extends Controller
{
    public function __construct()
    {
        $this->middleware('user-must-warung');
    }

    public function pesananWarung(Request $request, Builder $htmlBuilder)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {

            if ($request->ajax()) {
                $pesanan_warung = PesananPelanggan::where('id_warung', Auth::user()->id_warung)->orderBy('id', 'desc');
                return Datatables::of($pesanan_warung)
                    ->addColumn('konfirmasi_pesanan', function ($konfirmasi_pesanan) {
                        $status = "";
                        if ($konfirmasi_pesanan->konfirmasi_pesanan == 0) {
                            $status .= '<b  style="color:red">Belum Di Konfirmasi</b>';
                        } elseif ($konfirmasi_pesanan->konfirmasi_pesanan == 1) {
                            $status .= '<b  style="color:orange">Sudah Konfirmasi</b>';
                        } elseif ($konfirmasi_pesanan->konfirmasi_pesanan == 2) {
                            $status .= '<b  style="color:#01573e">Selesai</b>';
                        } elseif ($konfirmasi_pesanan->konfirmasi_pesanan == 3) {
                            $status .= '<b  style="color:#01573e">Batal</b>';
                        }
                        return $status;
                    })->addColumn('subtotal', function ($subtotal) {
                    $subtotal_baru = number_format($subtotal->subtotal, 0, ',', '.');
                    return $subtotal_baru;
                })
                    ->addColumn('data_pengirim', function ($data) {
                        return view('pesanan_warung.detail_pengirim', [
                            'detail_pengirim' => $data,
                        ]);
                    })->addColumn('pemesan', function ($pemesan) {
                    $data_pemesan = "" . $pemesan->nama_pemesan . "(" . $pemesan->no_telp_pemesan . ")";
                    return $data_pemesan;
                })->make(true);
            }
            $html = $htmlBuilder
                ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'Pesanan'])
                ->addColumn(['data' => 'pemesan', 'name' => 'pemesan', 'title' => 'Pemesan', 'orderable' => false, 'searchable' => false])
                ->addColumn(['data' => 'jumlah_produk', 'name' => 'jumlah_produk', 'title' => 'Jumlah', 'orderable' => false, 'searchable' => false])
                ->addColumn(['data' => 'subtotal', 'name' => 'subtotal', 'title' => 'Total', 'orderable' => false, 'searchable' => false])
                ->addColumn(['data' => 'konfirmasi_pesanan', 'name' => 'konfirmasi_pesanan', 'title' => 'Status', 'orderable' => false, 'searchable' => false])
                ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Waktu', 'orderable' => false, 'searchable' => false])

                ->addColumn(['data' => 'data_pengirim', 'name' => 'data_pengirim', 'title' => 'Detail', 'orderable' => false, 'searchable' => false]);
            return view('pesanan_warung.index')->with(compact('html'));
        }
    }

    public function tandaPemisahTitik($angka)
    {
        return number_format($angka, 0, ',', '.');
    }

    public function dataPagination($data_pesanan_warung, $array_pesanan_warung, $agent)
    {

        $respons['agent']          = $agent;
        $respons['current_page']   = $data_pesanan_warung->currentPage();
        $respons['data']           = $array_pesanan_warung;
        $respons['first_page_url'] = url('/pesanan-warung/view?page=' . $data_pesanan_warung->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $data_pesanan_warung->lastPage();
        $respons['last_page_url']  = url('/pesanan-warung/view?page=' . $data_pesanan_warung->lastPage());
        $respons['next_page_url']  = $data_pesanan_warung->nextPageUrl();
        $respons['path']           = url('/pesanan-warung/view');
        $respons['per_page']       = $data_pesanan_warung->perPage();
        $respons['prev_page_url']  = $data_pesanan_warung->previousPageUrl();
        $respons['to']             = $data_pesanan_warung->perPage();
        $respons['total']          = $data_pesanan_warung->total();

        return $respons;
    }

    public function view()
    {
        $data_pesanan_warung = PesananPelanggan::where('id_warung', Auth::user()->id_warung)->orderBy('id', 'desc')->paginate(10);
        $data_agent          = new Agent();
        if ($data_agent->isMobile()) {
            $agent = 0;
        } else {
            $agent = 1;
        }
        $array_pesanan_warung = array();
        foreach ($data_pesanan_warung as $pesanan_warung) {
            array_push($array_pesanan_warung, [
                'id'             => $pesanan_warung->id,
                'pemesan'        => $pesanan_warung->nama_pemesan . "(" . $pesanan_warung->no_telp_pemesan . ")",
                'subtotal'       => $this->tandaPemisahTitik($pesanan_warung->subtotal),
                'pesanan_warung' => $pesanan_warung,
            ]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($data_pesanan_warung, $array_pesanan_warung, $agent);
        return response()->json($respons);
    }

    public function detailView($id)
    {
        $data_pesanan = PesananPelanggan::where('id_warung', Auth::user()->id_warung)->where('id', $id)->first();
        return response()->json($data_pesanan);
    }

    public function pencarian(Request $request)
    {
        $search     = $request->search;
        $data_agent = new Agent();
        if ($data_agent->isMobile()) {
            $agent = 0;
        } else {
            $agent = 1;
        }

        $data_pesanan_warung = PesananPelanggan::where('id_warung', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
                $query->orwhere('id', 'LIKE', '%' . $search . '%')
                    ->orWhere('nama_pemesan', 'LIKE', '%' . $search . '%')
                    ->orWhere('no_telp_pemesan', 'LIKE', '%' . $search . '%')
                    ->orWhere('alamat_pemesan', 'LIKE', '%' . $search . '%')
                    ->orWhere('created_at', 'LIKE', '%' . $search . '%');
            })->orderBy('id', 'desc')->paginate(10);
        $array_pesanan_warung = array();
        foreach ($data_pesanan_warung as $pesanan_warung) {
            array_push($array_pesanan_warung, [
                'id'             => $pesanan_warung->id,
                'pemesan'        => $pesanan_warung->nama_pemesan . "(" . $pesanan_warung->no_telp_pemesan . ")",
                'subtotal'       => $this->tandaPemisahTitik($pesanan_warung->subtotal),
                'pesanan_warung' => $pesanan_warung,
            ]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($data_pesanan_warung, $array_pesanan_warung, $agent);
        return response()->json($respons);
    }

    //VUE.JS
    public function detailPesanan(Request $request, $id)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {

            $pesanan                  = PesananPelanggan::with('pelanggan')->find($id);
            $detail_pesanan_pelanggan = DetailPesananPelanggan::with(['produk', 'pelanggan', 'pesanan_pelanggan'])->where('id_pesanan_pelanggan', $id);
            $detail_pesanan           = $detail_pesanan_pelanggan->paginate(10);

            $subtotal      = 0;
            $array_pesanan = array();
            $data_agent    = new Agent();
            if ($data_agent->isMobile()) {
                $agent = 0;
            } else {
                $agent = 1;
            }
            foreach ($detail_pesanan as $detail_pesanans) {

                $harga_produk = $detail_pesanans->produk->harga_jual * $detail_pesanans->jumlah_produk;
                $subtotal     = $subtotal += $harga_produk;

            }

            $data_pesanan = [
                'pesanan'            => $pesanan,
                'konfirmasi_pesanan' => $pesanan->konfirmasi_pesanan,
                'agent'              => $agent,
                'detail_pesanan'     => $detail_pesanan,
                'subtotal'           => $subtotal,
            ];

            $respons['current_page']   = $detail_pesanan->currentPage();
            $respons['data']           = $data_pesanan;
            $respons['first_page_url'] = url('/detail/view?page=' . $detail_pesanan->firstItem());
            $respons['from']           = 1;
            $respons['last_page']      = $detail_pesanan->lastPage();
            $respons['last_page_url']  = url('/detail/view?page=' . $detail_pesanan->lastPage());
            $respons['next_page_url']  = $detail_pesanan->nextPageUrl();
            $respons['path']           = url('/detail/view');
            $respons['per_page']       = $detail_pesanan->perPage();
            $respons['prev_page_url']  = $detail_pesanan->previousPageUrl();
            $respons['to']             = $detail_pesanan->perPage();
            $respons['total']          = $detail_pesanan->total();

            return $respons;
        }
    }

    public function detailPesananWarung(Request $request, Builder $htmlBuilder, $id)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {

            $pesanan                  = PesananPelanggan::with('pelanggan')->find($id);
            $detail_pesanan_pelanggan = DetailPesananPelanggan::with(['produk', 'pelanggan', 'pesanan_pelanggan'])->where('id_pesanan_pelanggan', $id);
            $detail_pesanan           = $detail_pesanan_pelanggan->paginate(10);
            $pagination               = $detail_pesanan->links();

            $subtotal = 0;
            foreach ($detail_pesanan_pelanggan->get() as $detail_pesanan_pelanggans) {

                $harga_produk = $detail_pesanan_pelanggans->produk->harga_jual * $detail_pesanan_pelanggans->jumlah_produk;
                $subtotal     = $subtotal += $harga_produk;

            }

            $pesanan->subtotal = $subtotal;
            $pesanan->save();

            $status_pesanan = '';
            if ($pesanan->konfirmasi_pesanan == 0) {
                $status_pesanan .= '<td><b  style="color:red">Belum Di Konfirmasi</b></td>';
            } elseif ($pesanan->konfirmasi_pesanan == 1) {
                $status_pesanan .= '<td><b  style="color:orange">Sudah Di Konfirmasi</b></td>';
            } elseif ($pesanan->konfirmasi_pesanan == 2) {
                $status_pesanan .= '<td><b  style="color:#01573e">Selesai</b></td>';
            } elseif ($pesanan->konfirmasi_pesanan == 3) {
                $status_pesanan .= '<td><b  style="color:red"> Batal</b></td>';
            }

            $agent = new Agent();

            return view('pesanan_warung.detail_pesanan', ['pesanan' => $pesanan, 'detail_pesanan' => $detail_pesanan, 'status_pesanan' => $status_pesanan, 'subtotal' => $subtotal, 'pagination' => $pagination, 'agent' => $agent]);
        }
    }

    public function konfirmasiPesananWarung($id)
    {
        PesananPelanggan::where('id', $id)->update(['konfirmasi_pesanan' => '1']);
        // return redirect()->back();
    }

    public function pesananDikonfirmasi($id)
    {

        $pesanan_pelanggan = PesananPelanggan::select('id', 'id_pelanggan', 'updated_at', 'id_warung', 'nama_pemesan', 'no_telp_pemesan', 'alamat_pemesan', 'jumlah_produk', 'subtotal', 'metode_pembayaran', 'biaya_kirim', 'kode_unik_transfer')->whereId($id)->first();

        $data_warung = Warung::select('name', 'alamat', 'no_telpon', 'email')->whereId($pesanan_pelanggan->id_warung)->first();

        $user = User::select('email')->whereId($pesanan_pelanggan->id_pelanggan)->first();

        $produk_pesanan = DetailPesananPelanggan::with('produk')->where('id_pesanan_pelanggan', $id)->get();

        $waktu_update = date("d/m/Y h:i:s", strtotime($pesanan_pelanggan->updated_at));

        Mail::send('auth.emails.email_pesanan_telah_dikonfirmasi', compact('pesanan_pelanggan', 'data_warung', 'produk_pesanan', 'waktu_update'), function ($message) use ($data_warung, $user, $pesanan_pelanggan) {

            $message->from('verifikasi@andaglos.id', $data_warung->name);
            $message->to($user->email, $pesanan_pelanggan->nama_pemesan)->subject('Pesanan telah Dikonfirmasi');

        });
    }

    public function pesananDiselesaikan($id)
    {

        $pesanan_pelanggan = PesananPelanggan::select('id', 'id_pelanggan', 'updated_at', 'id_warung', 'nama_pemesan', 'no_telp_pemesan', 'alamat_pemesan', 'jumlah_produk', 'subtotal', 'metode_pembayaran', 'biaya_kirim', 'kode_unik_transfer')->whereId($id)->first();

        $data_warung = Warung::select('name', 'alamat', 'no_telpon', 'email')->whereId($pesanan_pelanggan->id_warung)->first();

        $user = User::select('email')->whereId($pesanan_pelanggan->id_pelanggan)->first();

        $produk_pesanan = DetailPesananPelanggan::with('produk')->where('id_pesanan_pelanggan', $id)->get();

        $waktu_update = date("d/m/Y h:i:s", strtotime($pesanan_pelanggan->updated_at));

        Mail::send('auth.emails.email_pesanan_telah_diselesaikan', compact('pesanan_pelanggan', 'data_warung', 'produk_pesanan', 'waktu_update'), function ($message) use ($data_warung, $user, $pesanan_pelanggan) {

            $message->from('verifikasi@andaglos.id', $data_warung->name);
            $message->to($user->email, $pesanan_pelanggan->nama_pemesan)->subject('Pesanan telah Diselesaikan');

        });
    }

    public function selesaiKonfirmasiPesananWarung(Request $request)
    {
        //START TRANSAKSI
        DB::beginTransaction();
        $id_warung         = Auth::user()->id_warung;
        $pesanna_pelanggan = PesananPelanggan::where('id', $request->id_pesanan)->first();

        $penjualan = Penjualan::create([
            'id_kas'       => $request->id_kas,
            'id_pesanan'   => $request->id_pesanan,
            'id_pelanggan' => $pesanna_pelanggan->id_pelanggan,
            'id_warung'    => $id_warung,
            'total'        => $pesanna_pelanggan->subtotal,
        ]);

        $detail_pesanan_pelanggan = DetailPesananPelanggan::with('produk')->where('id_pesanan_pelanggan', $request->id_pesanan)->get();

        $jumlah_masuk = 0;
        foreach ($detail_pesanan_pelanggan as $detail_pesanan_pelanggans) {

            // JIKA PRODUK NYA BERKAITAN DENGAN STOK
            if ($detail_pesanan_pelanggans->produk->hitung_stok == 1) {

                // MAKA CEK DULU STOK NYA
                $detail_penjualan = new DetailPenjualanPos();
                $stok_produk      = $detail_penjualan->stok_produk($detail_pesanan_pelanggans->id_produk);
                $sisa             = $stok_produk - $detail_pesanan_pelanggans->jumlah_produk;

                // JIKA STOK NYA KURANG DARI NOL
                if ($sisa < 0) {
                    //DI BATALKAN PROSES NYA

                    $respons['respons']     = 1;
                    $respons['nama_produk'] = title_case($detail_pesanan_pelanggans->produk->nama_barang);
                    $respons['stok_produk'] = round($stok_produk, 2);
                    DB::rollBack();
                    return response()->json($respons);

                    // JIKA STOK BARANG MENCUKUPI
                } else {
                    //INSERT KE DETAIL PENJUALAN
                    $subtotal = $detail_pesanan_pelanggans->harga_produk * $detail_pesanan_pelanggans->jumlah_produk;
                    DetailPenjualan::create([
                        'id_penjualan' => $penjualan->id,
                        'id_produk'    => $detail_pesanan_pelanggans->id_produk,
                        'harga'        => $detail_pesanan_pelanggans->harga_produk,
                        'jumlah'       => $detail_pesanan_pelanggans->jumlah_produk,
                        'subtotal'     => $subtotal,
                    ]);
                    $jumlah_masuk = $jumlah_masuk + $subtotal;

                }

                // JIKA BARANG TIDAK BERKAITAN DENGAN STOK , MAKA LANGSUNG INSERT KE DETAIL PENJUALAN
            } else {

                $subtotal = $detail_pesanan_pelanggans->harga_produk * $detail_pesanan_pelanggans->jumlah_produk;
                DetailPenjualan::create([
                    'id_penjualan' => $penjualan->id,
                    'id_produk'    => $detail_pesanan_pelanggans->id_produk,
                    'harga'        => $detail_pesanan_pelanggans->harga_produk,
                    'jumlah'       => $detail_pesanan_pelanggans->jumlah_produk,
                    'subtotal'     => $subtotal,
                ]);
                $jumlah_masuk = $jumlah_masuk + $subtotal;
            }

        }

        //PROSES MEMBUAT TRANSAKSI KAS
        TransaksiKas::create(['no_faktur' => $penjualan->id, 'jenis_transaksi' => 'penjualan', 'jumlah_masuk' => $jumlah_masuk, 'kas' => $request->id_kas, 'warung_id' => $id_warung]);

        PesananPelanggan::where('id', $request->id_pesanan)->update(['konfirmasi_pesanan' => '2']);

        DB::commit();

        $respons['respons_penjualan'] = $penjualan->id_pesanan;
        return response()->json($respons);
        // return redirect()->back();
    }

    public function cetakKecil($id)
    {
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        $penjualan = Penjualan::QueryCetak($id)->first();

        if ($penjualan['id_pelanggan'] == '0') {
            $nama_pelanggan = 'Umum';
        } else {
            $nama_pelanggan = $penjualan['pelanggan'];
        }

        $detail_penjualan = DetailPenjualan::with('produk')->where('id_penjualan', $penjualan['id'])->get();
        $potongan         = $penjualan['potongan'];
        if ($potongan == null) {
            $potongan = 0;
        }
        $subtotal = 0;
        foreach ($detail_penjualan as $detail_penjualans) {
            $subtotal += $detail_penjualans->jumlah * $detail_penjualans->harga;
            $potongan += $detail_penjualans->potongan;

        }

        return view('penjualan.cetak_kecil_pesanan_selesai', ['penjualan' => $penjualan, 'detail_penjualan' => $detail_penjualan, 'subtotal' => $subtotal, 'nama_pelanggan' => $nama_pelanggan, 'potongan' => $potongan, 'setting_aplikasi' => $setting_aplikasi])->with(compact('html'));
    }

    public function cetakKecilPesanan($id)
    {
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        $penjualan = PesananPelanggan::QueryCetak($id)->first();

        if ($penjualan['id_pelanggan'] == '0') {
            $nama_pelanggan = 'Umum';
        } else {
            $nama_pelanggan = $penjualan['pelanggan'];
        }

        $detail_penjualan = DetailPesananPelanggan::with('produk')->where('id_pesanan_pelanggan', $penjualan['id'])->get();
        $subtotal         = 0;
        foreach ($detail_penjualan as $detail_penjualans) {
            $subtotal += $detail_penjualans->jumlah_produk * $detail_penjualans->harga_produk;
        }

        return view('penjualan.cetak_kecil_pesanan', ['penjualan' => $penjualan, 'detail_penjualan' => $detail_penjualan, 'subtotal' => $subtotal, 'nama_pelanggan' => $nama_pelanggan, 'setting_aplikasi' => $setting_aplikasi])->with(compact('html'));
    }

    public function batalkanPesananWarung($id)
    {
        //START TRANSAKSI
        DB::beginTransaction();
        PesananPelanggan::where('id', $id)->update([
            'konfirmasi_pesanan' => '3',
            'no_resi'            => null,
        ]);

        $penjualan = Penjualan::where('id_pesanan', $id);
        if ($penjualan->count() != 0) {
            # code...
            DetailPenjualan::where('id_penjualan', $penjualan->first()->id)->delete();
            TransaksiKas::where('no_faktur', $penjualan->first()->id)->delete();
            Hpp::where('no_faktur', $penjualan->first()->id)->where('jenis_hpp', 2)->delete();
            $penjualan->delete();
        }

        DB::commit();

        // return redirect()->back();
    }

    public function batalkanKonfirmasiPesananWarung($id)
    {
        PesananPelanggan::where('id', $id)->update(['konfirmasi_pesanan' => '0']);
        // return redirect()->back();
    }

    public function tambahProdukPesananWarung($id)
    {
        $detail_pesanan = DetailPesananPelanggan::find($id);
        $pesanan        = PesananPelanggan::with('pelanggan')->find($detail_pesanan->id_pesanan_pelanggan);
        $pesanan->subtotal += $detail_pesanan->harga_produk;
        $pesanan->save();

        $detail_pesanan->jumlah_produk += 1;
        $detail_pesanan->save();

        // return redirect()->back();
    }

    public function kurangProdukPesananWarung($id)
    {
        $detail_pesanan = DetailPesananPelanggan::find($id);
        $pesanan        = PesananPelanggan::with('pelanggan')->find($detail_pesanan->id_pesanan_pelanggan);
        $pesanan->subtotal -= $detail_pesanan->harga_produk;
        $pesanan->save();

        $detail_pesanan->jumlah_produk -= 1;
        $detail_pesanan->save();

        // return redirect()->back();
    }

    public function editJumlahPesanan(Request $request)
    {

        DetailPesananPelanggan::find($request->id)->update(['jumlah_produk' => $request->jumlah_produk]);

        // return redirect()->back();

    }

    public function tambahNoResi(Request $request)
    {
        if ($request->email) {
            // return response()->json(1);
            $pesanan_pelanggan = PesananPelanggan::select('id', 'id_pelanggan', 'updated_at', 'id_warung', 'nama_pemesan', 'no_telp_pemesan', 'alamat_pemesan', 'jumlah_produk', 'subtotal', 'kurir', 'metode_pembayaran', 'biaya_kirim', 'kode_unik_transfer', 'no_resi')->whereId($request->id_pesanan)->first();

            $data_warung = Warung::select('name', 'alamat', 'no_telpon', 'email')->whereId($pesanan_pelanggan->id_warung)->first();

            $user = User::select('email')->whereId($pesanan_pelanggan->id_pelanggan)->first();

            $produk_pesanan = DetailPesananPelanggan::with('produk')->where('id_pesanan_pelanggan', $request->id_pesanan)->get();

            $waktu_update = date("d/m/Y h:i:s", strtotime($pesanan_pelanggan->updated_at));

            Mail::send('auth.emails.email_nomor_resi', compact('pesanan_pelanggan', 'data_warung', 'produk_pesanan', 'waktu_update'), function ($message) use ($data_warung, $user, $pesanan_pelanggan) {

            $message->from('verifikasi@andaglos.id', $data_warung->name);
            $message->to($user->email, $pesanan_pelanggan->nama_pemesan)->subject('Pesanan telah Dikirim');

            });
        } else {
            PesananPelanggan::find($request->id_pesanan)->update(['no_resi' => $request->no_resi]);
        }
    }


    public function cekBatasWaktuTransfer() {
        $address_app = SettingPembedaAplikasi::select('warung_id')->where('app_address', url('/'))->first();

        $waktu_dibuat = PesananPelanggan::where([
            ['konfirmasi_pesanan', '=', 0],
            ['id_warung', '=', $address_app->warung_id]
        ])->get();

        // return $waktu_dibuat;


        $res['telat 11 jam'] = 0;
        $res['telat 11 jam 30 menit'] = 0;

        // $arr = [];
        // foreach ($waktu_dibuat as $wd) {
        //     $waktu = time() - strtotime($wd->created_at);
        //     $arr[] = [(ceil(($waktu / 3600) - 1)), ((($waktu / 60) % 60))];
        // }
        // return $arr;

        foreach($waktu_dibuat as $wd) {

            $pesanan_pelanggan = PesananPelanggan::select('id', 'id_pelanggan', 'updated_at', 'id_warung', 'nama_pemesan', 'no_telp_pemesan', 'alamat_pemesan', 'jumlah_produk', 'subtotal', 'metode_pembayaran', 'biaya_kirim', 'kode_unik_transfer')->whereId($wd->id)->first();

            $data_warung = Warung::select('name', 'alamat', 'no_telpon', 'email')->whereId($wd->id_warung)->first();

            $user = User::select('email')->whereId($wd->id_pelanggan)->first();

            $produk_pesanan = DetailPesananPelanggan::with('produk')->where('id_pesanan_pelanggan', $wd->id)->get();

            $waktu = time() - strtotime($wd->created_at);

            // mendapatkan angka jam dan menit [jam, menit]
            $waktu = [(ceil(($waktu / 3600) - 1)), ((($waktu / 60) % 60))];

            if ($waktu[0] == 11 && $waktu[1] < 30 && $wd->email_peringatan_transfer == 0) {

                PesananPelanggan::whereId($wd->id)
                    ->update([
                        'email_peringatan_transfer' => 1
                    ]);

                $msg = 'Tersisa waktu sekitar 1 jam lagi untuk mentransfer pembayaran Anda. mohon untuk segera mentrasfer agar barang segera dikirimkan.';
                $res['telat 11 jam']++;

            } else if ($waktu[0] == 11 && $waktu[1] >= 30 && $wd->email_peringatan_transfer == 1) {

                PesananPelanggan::whereId($wd->id)
                    ->update([
                        'email_peringatan_transfer' => 2
                    ]);

                $msg = 'Tersisa waktu sekitar 30 menit lagi untuk mentransfer pembayaran Anda. mohon untuk segera mentrasfer agar barang segera dikirimkan.';
                $res['telat 11 jam 30 menit']++;
            }

            if (isset($msg)) {
                Mail::send('auth.emails.batas-waktu-transfer', compact('pesanan_pelanggan', 'data_warung', 'produk_pesanan', 'msg'), function ($message) use ($data_warung, $user, $pesanan_pelanggan) {

                    $message->from('verifikasi@andaglos.id', $data_warung->name);
                    $message->to($user->email, $pesanan_pelanggan->nama_pemesan)->subject('Batas Waktu Transfer Hampir Habis!');

                });
            }
        }

        $res['hasil'] = 'selesai dieksekusi';
        return $res;
    }

}
