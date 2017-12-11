<?php

namespace App\Http\Controllers;

use App\DetailPenjualan;
use App\DetailPesananPelanggan;
use App\Hpp;
use App\Penjualan;
use App\PesananPelanggan;
use App\TransaksiKas;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
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

    public function dataPagination($data_pesanan_warung, $array_pesanan_warung)
    {

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
        $data_pesanan_warung  = PesananPelanggan::where('id_warung', Auth::user()->id_warung)->orderBy('id', 'desc')->paginate(10);
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
        $respons = $this->dataPagination($data_pesanan_warung, $array_pesanan_warung);
        return response()->json($respons);
    }

    public function detailView($id)
    {
        $data_pesanan = PesananPelanggan::where('id_warung', Auth::user()->id_warung)->where('id', $id)->first();
        return response()->json($data_pesanan);
    }

    public function pencarian(Request $request)
    {
        $search              = $request->search;
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
        $respons = $this->dataPagination($data_pesanan_warung, $array_pesanan_warung);
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
            $agent         = new Agent();
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

        $detail_pesanan_pelanggan = DetailPesananPelanggan::where('id_pesanan_pelanggan', $request->id_pesanan)->get();

        $jumlah_masuk = 0;
        foreach ($detail_pesanan_pelanggan as $detail_pesanan_pelanggans) {
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

        //PROSES MEMBUAT TRANSAKSI KAS
        TransaksiKas::create(['no_faktur' => $penjualan->id, 'jenis_transaksi' => 'penjualan', 'jumlah_masuk' => $jumlah_masuk, 'kas' => $request->id_kas, 'warung_id' => $id_warung]);

        PesananPelanggan::where('id', $request->id_pesanan)->update(['konfirmasi_pesanan' => '2']);

        DB::commit();
        return redirect()->back();
    }

    public function batalkanPesananWarung($id)
    {
        //START TRANSAKSI
        DB::beginTransaction();
        PesananPelanggan::where('id', $id)->update(['konfirmasi_pesanan' => '3']);
        $penjualan = Penjualan::where('id_pesanan', $id);
        if ($penjualan->count() != 0) {
            # code...
            DetailPenjualan::where('id_penjualan', $penjualan->first()->id)->delete();
            TransaksiKas::where('no_faktur', $penjualan->first()->id)->delete();
            Hpp::where('no_faktur', $penjualan->first()->id)->where('jenis_hpp', 2)->delete();
            $penjualan->delete();
        }

        DB::commit();

        return redirect()->back();
    }

    public function batalkanKonfirmasiPesananWarung($id)
    {
        PesananPelanggan::where('id', $id)->update(['konfirmasi_pesanan' => '0']);
        return redirect()->back();
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

}
