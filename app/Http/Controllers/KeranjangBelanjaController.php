<?php

namespace App\Http\Controllers;

use App\Barang;
use App\KeranjangBelanja;
use Auth;
use DB;
use Jenssegers\Agent\Agent;
use OpenGraph;
use SEOMeta;

class KeranjangBelanjaController extends Controller
{
    //

    public function daftar_belanja()
    {

        $this->seo();
        $agent = new Agent();

        $keranjang_belanjaan = KeranjangBelanja::with(['produk', 'pelanggan'])->where('id_pelanggan', Auth::user()->id)->get();
        $cek_belanjaan       = $keranjang_belanjaan->count();

        $jumlah_produk = KeranjangBelanja::select([DB::raw('IFNULL(SUM(jumlah_produk),0) as total_produk')])->where('id_pelanggan', Auth::user()->id)->first();

        //MEANMPILKAN PRODUK BELANJAAN DAN SUBTUTALNYA
        $produk_belanjaan_dan_subtotal = $this->tampilanProdukKeranjangBelanja($keranjang_belanjaan);
        $subtotal                      = number_format($produk_belanjaan_dan_subtotal['subtotal'], 0, ',', '.');
        $produk_belanjaan              = $produk_belanjaan_dan_subtotal['produk_belanjaan'];

        return view('layouts.keranjang_belanja', ['keranjang_belanjaan' => $keranjang_belanjaan, 'cek_belanjaan' => $cek_belanjaan, 'agent' => $agent, 'produk_belanjaan' => $produk_belanjaan, 'jumlah_produk' => $jumlah_produk, 'subtotal' => $subtotal]);

    }

    public function hapus_produk_keranjang_belanjaan($id)
    {

        // jika gagal hapus
        if (!KeranjangBelanja::destroy($id)) {
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }

    public function tambah_jumlah_produk_keranjang_belanjaan($id)
    {
        $produk = KeranjangBelanja::find($id);
        $produk->jumlah_produk += 1;
        $produk->save();

        return redirect()->back();
    }

    public function kurang_jumlah_produk_keranjang_belanjaan($id)
    {
        $produk = KeranjangBelanja::find($id);
        $produk->jumlah_produk -= 1;
        $produk->save();

        return redirect()->back();

    }

    public function tambah_produk_keranjang_belanjaan($id)
    {

        $pelanggan = Auth::user()->id;

        $datakeranjang_belanjaan = KeranjangBelanja::where('id_pelanggan', $pelanggan)->Where('id_produk', $id);
        $jumlah_produk           = $datakeranjang_belanjaan->first();

        if ($datakeranjang_belanjaan->count() > 0) {

            $datakeranjang_belanjaan->update(['jumlah_produk' => $jumlah_produk->jumlah_produk + 1]);

        } else {

            $produk = KeranjangBelanja::create(['id_produk' => $id, 'id_pelanggan' => $pelanggan, 'jumlah_produk' => '1']);
        }
        return redirect()->back();

    }

    public function fotoProduk($keranjang_belanjaans)
    {
        if ($keranjang_belanjaans->produk->foto != null) {
            $foto_produk = '<img src="foto_produk/' . $keranjang_belanjaans->produk->foto . '">';
        } else {
            $foto_produk = '<img src="image/foto_default.png">';
        }
        return $foto_produk;
    }

    public function tombolKurangiProduk($keranjang_belanjaans)
    {

        $agent = new Agent();
        if ($agent->isMobile()) {

            if ($keranjang_belanjaans->jumlah_produk == 1) {
                $tombolKurangiProduk = '<a class="btn btn-xs" disabled="true">-</a>';
            } else {
                $tombolKurangiProduk = '<a href=" ' . url('/keranjang-belanja/kurang-jumlah-produk-keranjang-belanja/' . $keranjang_belanjaans->id_keranjang_belanja . '') . '" class="btn btn-xs">-</a>';
            }

        } else {

            if ($keranjang_belanjaans->jumlah_produk == 1) {
                $tombolKurangiProduk = '
                <a class="btn btn-round btn-info btn-xs"   style="background-color: #01573e" disabled="true"> <i class="material-icons">remove</i> </a>';
            } else {
                $tombolKurangiProduk = '
                <a href=" ' . url('/keranjang-belanja/kurang-jumlah-produk-keranjang-belanja/' . $keranjang_belanjaans->id_keranjang_belanja . '') . '" class="btn btn-round btn-info btn-xs"   style="background-color: #01573e"> <i class="material-icons">remove</i></a>';
            }
        }

        return $tombolKurangiProduk;
    }

    public function tombolTambahiProduk($sisa_stok, $keranjang_belanjaans)
    {

        $agent = new Agent();
        if ($agent->isMobile()) {
            if ($sisa_stok <= 0 && $keranjang_belanjaans->produk->hitung_stok == 1) {
                $tombolTambahiProduk = '<a class="btn btn-xs" disabled="true">+</a>';
            } else {
                $tombolTambahiProduk = '
                <a href=" ' . url('/keranjang-belanja/tambah-jumlah-produk-keranjang-belanja/' . $keranjang_belanjaans->id_keranjang_belanja . '') . '" class="btn btn-xs">+</a>';
            }
        } else {
            if ($sisa_stok <= 0 && $keranjang_belanjaans->produk->hitung_stok == 1) {
                $tombolTambahiProduk = '
                <a class="btn btn-round btn-info btn-Fxs"   style="background-color: #01573e" disabled="true"> <i class="material-icons">add</i> </a>';
            } else {
                $tombolTambahiProduk = '
                <a href=" ' . url('/keranjang-belanja/tambah-jumlah-produk-keranjang-belanja/' . $keranjang_belanjaans->id_keranjang_belanja . '') . '" class="btn btn-round btn-info btn-xs"   style="background-color: #01573e"> <i class="material-icons">add</i> </a>';
            }
        }

        return $tombolTambahiProduk;
    }

    public function cardProdukBelanjaan($harga_produk, $sisa_stok, $keranjang_belanjaans, $subtotal_produk)
    {

        $agent = new Agent();
        if ($agent->isMobile()) {

            $produk_belanjaan = '
            <div class="card" style="margin-bottom: 1px; margin-top: 1px;">
                <div class="row">
                    <div class="col-md-12">

                        <div class="row">
                            <div class="col-xs-4" style="padding-right:0px">
                                <div class="img-container" style="margin:10px;">';
            $produk_belanjaan .= $this->fotoProduk($keranjang_belanjaans);
            $produk_belanjaan .= '</div>
                                </div>

                                <div class="col-xs-2" style="padding-left:0px; padding-right:0px; padding-top:23px">
                                    <b><a href="' . url('detail-produk/' . $keranjang_belanjaans->id_produk . '') . '" style="font-size: 12px;">' . $this->namaProduk($keranjang_belanjaans->produk->nama_barang) . '</a></b><br>
                                    <b style="color:red">' . number_format($harga_produk, 0, ',', '.') . '</b>
                                    <p style="font-size: 10px; margin-top:10px">' . $keranjang_belanjaans->produk->warung->name . '</p>
                                </div>

                                <div class="col-xs-3" style="padding-left:0px; padding-right:0px">
                                    <center>
                                        <div class="btn-group">';
            //tombol kurangi produk
            $produk_belanjaan .= $this->tombolKurangiProduk($keranjang_belanjaans);
            $produk_belanjaan .= $this->tombolTambahiProduk($sisa_stok, $keranjang_belanjaans);

            $produk_belanjaan .= '
                                        </div><br>
                                        <b>' . $keranjang_belanjaans->jumlah_produk . ' </b><br>
                                        <div class="btn-group">

                                            <button id="btnHapusProduk" data-nama="' . title_case($keranjang_belanjaans->produk->nama_barang) . '" data-id="' . $keranjang_belanjaans->id_keranjang_belanja . '" class="btn btn-danger btn-xs"><i class="material-icons">delete</i></button>
                                        </div></center>
                                    </div>
                                    <div class="col-md-2" style="padding-top:43px">
                                        <b>' . number_format($subtotal_produk, 0, ',', '.') . '</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';

        } else {

            $produk_belanjaan = '

                    <div class="card-content" style="padding-left: 5px; padding-top: 1px; padding-bottom: 1px; padding-right: 1px;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="img-container"  style="padding-left: 5px; padding-top: 1px; padding-bottom: 1px; padding-right: 1px; width:75px;">';
            $produk_belanjaan .= $this->fotoProduk($keranjang_belanjaans);
            $produk_belanjaan .= '
                                                </div>
                                            </div>
                                            <div class="col-sm-8">
                                                <h6><a href="' . url('detail-produk/' . $keranjang_belanjaans->id_produk . '') . '">' . $this->namaProduk($keranjang_belanjaans->produk->nama_barang) . '</a> </h6>
                                                <p><small> ' . $keranjang_belanjaans->produk->warung->name . '</small></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <h6 align="right"><b>Rp. ' . number_format($harga_produk, 0, ',', '.') . '</b></h6>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="btn-group">';

            //tombol kurangi produk
            $produk_belanjaan .= $this->tombolKurangiProduk($keranjang_belanjaans);

            $produk_belanjaan .= ' <a class="btn btn-round btn-info btn-xs"   style="background-color: #01573e"><font style="font-size: 11.5px;">' . $keranjang_belanjaans->jumlah_produk . ' </font></a>';
            //tombol tambahi
            $produk_belanjaan .= $this->tombolTambahiProduk($sisa_stok, $keranjang_belanjaans);

            $produk_belanjaan .= '
                                        </div><br>
                                        <button id="btnHapusProduk" data-id="' . $keranjang_belanjaans->id_keranjang_belanja . '" data-nama="' . title_case($keranjang_belanjaans->produk->nama_barang) . '" class="btn btn-danger btn-xs">Hapus</button>

                                    </div>


                                    <div class="col-md-2">
                                        <h6 align="right"><b>Rp. ' . number_format($subtotal_produk, 0, ',', '.') . '</b></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><hr style="margin-top: 0px; margin-bottom: 0px;">

                    ';

        }

        return $produk_belanjaan;
    }

    public function tampilanProdukKeranjangBelanja($keranjang_belanjaan)
    {
        $subtotal         = 0;
        $produk_belanjaan = "";
        foreach ($keranjang_belanjaan as $keranjang_belanjaans) {

            $barang = Barang::where('id', $keranjang_belanjaans->id_produk);
            //jika barang yang di keranjang ternyata sudah dihapus warung
            if ($barang->count() == 0) {
                KeranjangBelanja::where('id_produk', $keranjang_belanjaans->id_produk)->delete();
            } else {
                $sisa_stok       = $barang->first()->stok - $keranjang_belanjaans->jumlah_produk;
                $harga_produk    = $keranjang_belanjaans->produk->harga_jual;
                $subtotal_produk = $keranjang_belanjaans->produk->harga_jual * $keranjang_belanjaans->jumlah_produk;
                //card produk belanjaan
                $produk_belanjaan .= $this->cardProdukBelanjaan($harga_produk, $sisa_stok, $keranjang_belanjaans, $subtotal_produk);
                $subtotal += $subtotal_produk;
            }
        }

        return array('produk_belanjaan' => $produk_belanjaan, 'subtotal' => $subtotal);
    }

    public function seo()
    {
        SEOMeta::setTitle('War-Mart.id');
        SEOMeta::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
        SEOMeta::setCanonical('https://war-mart.id');
        SEOMeta::addKeyword(['warmart', 'warung', 'marketplace', 'toko online', 'belanja', 'lazada']);

        OpenGraph::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
        OpenGraph::setTitle('War-Mart.id');
        OpenGraph::setUrl('https://war-mart.id');
        OpenGraph::addProperty('type', 'articles');
    }

    public function namaProduk($nama)
    {

        $agent = new Agent();
        if ($agent->isMobile()) {

            if (strlen(strip_tags($nama)) <= 20) {

                $nama_produk = title_case(strip_tags($nama));
            } else {

                $nama_produk = title_case('' . strip_tags(substr($nama, 0, 21)) . '...');
            }

        } else {

            if (strlen(strip_tags($nama)) <= 33) {

                $nama_produk = title_case(strip_tags($nama));
            } else {

                $nama_produk = title_case('' . strip_tags(substr($nama, 0, 30)) . '...');
            }
        }

        return $nama_produk;
    }
}
