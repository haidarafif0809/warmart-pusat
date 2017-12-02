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
                $pesanan_warung = PesananPelanggan::with('pelanggan')->where('id_warung', Auth::user()->id_warung)->orderBy('id', 'desc');
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
                    $data_pemesan = "" . $pemesan->pelanggan->name . "(" . $pemesan->pelanggan->no_telp . ")";
                    return $data_pemesan;
                })->make(true);
            }
            $html = $htmlBuilder
                ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'Pesanan'])
                ->addColumn(['data' => 'pemesan', 'name' => 'pemesan', 'title' => 'Pemesan', 'orderable' => false, 'searchable' => false])
                ->addColumn(['data' => 'jumlah_produk', 'name' => 'jumlah_produk', 'title' => 'Jumlah', 'orderable' => false, 'searchable' => false])
                ->addColumn(['data' => 'subtotal', 'name' => 'subtotal', 'title' => 'Total', 'orderable' => false, 'searchable' => false])
                ->addColumn(['data' => 'konfirmasi_pesanan', 'name' => 'konfirmasi_pesanan', 'title' => 'Status', 'orderable' => false, 'searchable' => false])
                ->addColumn(['data' => 'data_pengirim', 'name' => 'data_pengirim', 'title' => 'Pengiriman', 'orderable' => false, 'searchable' => false])
                ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Waktu', 'orderable' => false, 'searchable' => false]);
            return view('pesanan_warung.index')->with(compact('html'));
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

            return view('pesanan_warung.detail_pesanan', ['pesanan' => $pesanan, 'detail_pesanan' => $detail_pesanan, 'status_pesanan' => $status_pesanan, 'subtotal' => $subtotal, 'pagination' => $pagination]);
        }
    }

    public function konfirmasiPesananWarung($id)
    {
        PesananPelanggan::where('id', $id)->update(['konfirmasi_pesanan' => '1']);
        return redirect()->back();
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

        return redirect()->back();
    }

    public function kurangProdukPesananWarung($id)
    {
        $detail_pesanan = DetailPesananPelanggan::find($id);
        $pesanan        = PesananPelanggan::with('pelanggan')->find($detail_pesanan->id_pesanan_pelanggan);
        $pesanan->subtotal -= $detail_pesanan->harga_produk;
        $pesanan->save();

        $detail_pesanan->jumlah_produk -= 1;
        $detail_pesanan->save();

        return redirect()->back();
    }

}
