<?php

namespace App\Http\Controllers;

use App\DetailPesananPelanggan;
use App\KeranjangBelanja;
use App\PesananPelanggan;
use Auth;
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

        $pesanan_pelanggan  = PesananPelanggan::where('id_pelanggan', Auth::user()->id)->orderBy('id', 'desc')->paginate(10);
        $cek_pesanan        = $pesanan_pelanggan->count();
        $pagination_pesanan = $pesanan_pelanggan->links();

        //MEANMPILKAN PRODUK PESANAN VERSI MOBILE
        $produk_pesanan_mobile = '';
        foreach ($pesanan_pelanggan as $pesanan_pelanggans) {

            $produk_pesanan_mobile .= '
			<div class="card">
			<div class="col-sm-6">
			<b>Pesanan : <a href="' . url('pesanan-detail/' . $pesanan_pelanggans->id . '') . '">#' . $pesanan_pelanggans->id . '</a></b>
			</div>
			<div class="col-sm-6">
			Di pesan pada : ' . $pesanan_pelanggans->created_at . '
			</div><hr style="margin-bottom: 0px;margin-top: 1px">
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
			<td><a href="' . url('pesanan-detail/' . $pesanan_pelanggans->id . '') . '">#' . $pesanan_pelanggans->id . '</a></td>
			<td>' . $pesanan_pelanggans->created_at . '</td>
			<td>Rp. ' . number_format($pesanan_pelanggans->subtotal, 0, ',', '.') . '</td>';
            if ($pesanan_pelanggans->konfirmasi_pesanan == 0) {
                $produk_pesanan_komputer .= '<td><b  style="color:red">Belum Di Konfirmasi</b></td>';
            } elseif ($pesanan_pelanggans->konfirmasi_pesanan == 1) {
                $produk_pesanan_komputer .= '<td><b  style="color:orange">Sudah Diterima Warung</b></td>';
            } elseif ($pesanan_pelanggans->konfirmasi_pesanan == 2) {
                $produk_pesanan_komputer .= '<td><b  style="color:#01573e">Selesai</b></td>';
            } elseif ($pesanan_pelanggans->konfirmasi_pesanan == 3) {
                $produk_pesanan_komputer .= '<td><b  style="color:red">Batal</b></td>';
            }
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

        $pesanan_pelanggan        = PesananPelanggan::where('id_pelanggan', Auth::user()->id)->where('id', $id)->first();
        $detail_pesanan_pelanggan = DetailPesananPelanggan::with(['produk', 'pelanggan', 'pesanan_pelanggan'])->where('id_pelanggan', Auth::user()->id)->where('id_pesanan_pelanggan', $pesanan_pelanggan->id)->get();

        $status_pesanan = '';
        if ($pesanan_pelanggan->konfirmasi_pesanan == 0) {
            $status_pesanan .= '<td><b  style="color:red">Belum Di Konfirmasi</b></td>';
        } elseif ($pesanan_pelanggan->konfirmasi_pesanan == 1) {
            $status_pesanan .= '<td><b  style="color:orange">Sudah Diterima Warung</b></td>';
        } elseif ($pesanan_pelanggan->konfirmasi_pesanan == 2) {
            $status_pesanan .= '<td><b  style="color:#01573e">Selesai</b></td>';
        } elseif ($pesanan_pelanggan->konfirmasi_pesanan == 3) {
            $produk_pesanan_mobile .= '<td><b  style="color:red">Batal</b></td>';
        }

        return view('layouts.detail_pesanan_pelanggan', ['detail_pesanan_pelanggan' => $detail_pesanan_pelanggan, 'pesanan_pelanggan' => $pesanan_pelanggan, 'cek_belanjaan' => $cek_belanjaan, 'agent' => $agent, 'logo_warmart' => $logo_warmart, 'user' => $user, 'status_pesanan' => $status_pesanan]);
    }
}
