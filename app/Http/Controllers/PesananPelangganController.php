<?php

namespace App\Http\Controllers;

use App\DetailPesananPelanggan;
use App\KeranjangBelanja;
use App\PesananPelanggan;
use Auth;
use Indonesia;
use Jenssegers\Agent\Agent;
use OpenGraph;
use SEOMeta;

class PesananPelangganController extends Controller
{
    public function pesananPelanggan()
    {

        SEOMeta::setTitle('War-Mart.id');
        SEOMeta::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
        SEOMeta::setCanonical('https://war-mart.id');
        SEOMeta::addKeyword(['warmart', 'warung', 'marketplace', 'toko online', 'belanja', 'lazada']);

        OpenGraph::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
        OpenGraph::setTitle('War-Mart.id');
        OpenGraph::setUrl('https://war-mart.id');
        OpenGraph::addProperty('type', 'articles');
        $agent = new Agent();

        $keranjang_belanjaan = KeranjangBelanja::with(['produk', 'pelanggan'])->where('id_pelanggan', Auth::user()->id)->get();
        $cek_belanjaan       = $keranjang_belanjaan->count();
        $logo_warmart        = "" . asset('/assets/img/examples/warmart_logo.png') . "";
        $user                = Auth::user();

        $pesanan_pelanggan  = PesananPelanggan::with('warung')->where('id_pelanggan', Auth::user()->id)->orderBy('id', 'desc')->paginate(10);
        $cek_pesanan        = $pesanan_pelanggan->count();
        $pagination_pesanan = $pesanan_pelanggan->links();

        //MEANMPILKAN PRODUK PESANAN VERSI MOBILE
        $produk_pesanan_mobile = '';
        foreach ($pesanan_pelanggan as $pesanan_pelanggans) {

            $produk_pesanan_mobile .= '
            <div class="card">
                <div class="col-sm-6">
                    <b>Pesanan : <a href="' . url('pesanan-detail/' . $pesanan_pelanggans->id . '') . '">#' . $pesanan_pelanggans->id . '</a></b>
                </div><hr style="margin-bottom: 0px;margin-top: 1px">
                <div class="col-sm-6">
                    Waktu Pesan : ' . $pesanan_pelanggans->WaktuPesan . '
                </div>
                <div class="container">
                    <a> Jumlah  : ' . $pesanan_pelanggans->jumlah_produk . '<a><br>
                        Total &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Rp. ' . number_format($pesanan_pelanggans->subtotal, 0, ',', '.') . '<br>
                        Status &nbsp;&nbsp;: ';

            if ($pesanan_pelanggans->konfirmasi_pesanan == 0) {
                $produk_pesanan_mobile .= '<b  style="color:red">Belum Di Konfirmasi</b>';
            } elseif ($pesanan_pelanggans->konfirmasi_pesanan == 1) {
                $produk_pesanan_mobile .= '<b  style="color:orange">Sudah Diterima Warung</b>';
            } elseif ($pesanan_pelanggans->konfirmasi_pesanan == 2) {
                $produk_pesanan_mobile .= '<b  style="color:#01573e">Selesai</b>';
            } elseif ($pesanan_pelanggans->konfirmasi_pesanan == 3) {
                $produk_pesanan_mobile .= '<b  style="color:red">Batal</b>';
            }

            $produk_pesanan_mobile .= '<br>Warung : <a href="' . url('halaman-warung/' . $pesanan_pelanggans->id_warung . '') . '"><b>' . $pesanan_pelanggans->warung->name . '</b></a>';
            $produk_pesanan_mobile .= '
                        <a href="' . url('pesanan-detail/' . $pesanan_pelanggans->id . '') . '" style="background-color: #01573e" class="btn btn-block">Detail Pesanan</a>
                    </div>
                </div>';
        }

        //MEANMPILKAN PRODUK PESANAN VERSI KOMPUTER
        $produk_pesanan_komputer = '';
        foreach ($pesanan_pelanggan as $pesanan_pelanggans) {

            $produk_pesanan_komputer .= '
                <tr  style="margin-top:0px;margin-bottom: 0px;">
                    <td><a href="' . url('pesanan-detail/' . $pesanan_pelanggans->id . '') . '"><b>#' . $pesanan_pelanggans->id . '</b></a></td>
                    <td><b>' . $pesanan_pelanggans->WaktuPesan . '</b></td>
                    <td><b>Rp. ' . number_format($pesanan_pelanggans->subtotal, 0, ',', '.') . '</b></td>';
            if ($pesanan_pelanggans->konfirmasi_pesanan == 0) {
                $produk_pesanan_komputer .= '<td><b  style="color:red">Belum Di Konfirmasi</b></td>';
            } elseif ($pesanan_pelanggans->konfirmasi_pesanan == 1) {
                $produk_pesanan_komputer .= '<td><b  style="color:orange">Sudah Diterima Warung</b></td>';
            } elseif ($pesanan_pelanggans->konfirmasi_pesanan == 2) {
                $produk_pesanan_komputer .= '<td><b  style="color:#01573e">Selesai</b></td>';
            } elseif ($pesanan_pelanggans->konfirmasi_pesanan == 3) {
                $produk_pesanan_komputer .= '<td><b  style="color:red">Batal</b></td>';
            }
            $produk_pesanan_komputer .= '<td><a href="' . url('halaman-warung/' . $pesanan_pelanggans->id_warung . '') . '"><b>' . $pesanan_pelanggans->warung->name . '</b></a></td>';
            $produk_pesanan_komputer .= '</tr>';
        }

        return view('layouts.pesanan_pelanggan', ['produk_pesanan_mobile' => $produk_pesanan_mobile, 'produk_pesanan_komputer' => $produk_pesanan_komputer, 'cek_belanjaan' => $cek_belanjaan, 'agent' => $agent, 'logo_warmart' => $logo_warmart, 'user' => $user, 'cek_pesanan' => $cek_pesanan, 'pagination_pesanan' => $pagination_pesanan]);
    }

    public function detailPesananPelanggan($id)
    {

        SEOMeta::setTitle('War-Mart.id');
        SEOMeta::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
        SEOMeta::setCanonical('https://war-mart.id');
        SEOMeta::addKeyword(['warmart', 'warung', 'marketplace', 'toko online', 'belanja', 'lazada']);

        OpenGraph::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
        OpenGraph::setTitle('War-Mart.id');
        OpenGraph::setUrl('https://war-mart.id');
        OpenGraph::addProperty('type', 'articles');
        $agent = new Agent();

        $keranjang_belanjaan = KeranjangBelanja::with(['produk', 'pelanggan'])->where('id_pelanggan', Auth::user()->id)->get();
        $cek_belanjaan       = $keranjang_belanjaan->count();
        $logo_warmart        = "" . asset('/assets/img/examples/warmart_logo.png') . "";
        $user                = Auth::user();

        $pesanan_pelanggan        = PesananPelanggan::with('warung')->where('id_pelanggan', Auth::user()->id)->where('id', $id)->first();
        $detail_pesanan_pelanggan = DetailPesananPelanggan::with(['produk', 'pelanggan', 'pesanan_pelanggan'])->where('id_pelanggan', Auth::user()->id)->where('id_pesanan_pelanggan', $pesanan_pelanggan->id)->paginate(10);
        //PERINTAH PAGINATION
        $pagination = $detail_pesanan_pelanggan->links();

        $status_pesanan = '';
        if ($pesanan_pelanggan->konfirmasi_pesanan == 0) {
            $status_pesanan .= '<td><b  style="color:red">Belum Di Konfirmasi</b></td>';
        } elseif ($pesanan_pelanggan->konfirmasi_pesanan == 1) {
            $status_pesanan .= '<td><b  style="color:orange">Sudah Diterima Warung</b></td>';
        } elseif ($pesanan_pelanggan->konfirmasi_pesanan == 2) {
            $status_pesanan .= '<td><b  style="color:#01573e">Selesai</b></td>';
        } elseif ($pesanan_pelanggan->konfirmasi_pesanan == 3) {
            $status_pesanan .= '<td><b  style="color:red">Batal</b></td>';
        }

        $lokasi_warung = Indonesia::allVillages()->where('id', $pesanan_pelanggan->warung->wilayah)->first();

        return view('layouts.detail_pesanan_pelanggan', ['detail_pesanan_pelanggan' => $detail_pesanan_pelanggan, 'pesanan_pelanggan' => $pesanan_pelanggan, 'cek_belanjaan' => $cek_belanjaan, 'agent' => $agent, 'logo_warmart' => $logo_warmart, 'user' => $user, 'status_pesanan' => $status_pesanan, 'lokasi_warung' => $lokasi_warung->name, 'pagination' => $pagination]);
    }

}
