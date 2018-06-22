<?php

namespace App\Http\Controllers;

use App\DetailPesananPelanggan;
use App\KeranjangBelanja;
use App\PesananPelanggan;
use App\SettingAplikasi;
use App\SettingPembedaAplikasi;
use App\Warung;
use App\User;
use Auth;
use Mail;
use Indonesia;
use Jenssegers\Agent\Agent;
use OpenGraph;
use SEOMeta;
use Illuminate\Http\Request;

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

        $cek_belanjaan = KeranjangBelanja::where('id_pelanggan', Auth::user()->id)->count();
        
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        $pesanan_pelanggan  = PesananPelanggan::with('warung')->where('id_pelanggan', Auth::user()->id)->orderBy('id', 'desc')->paginate(10);
        $cek_pesanan        = $pesanan_pelanggan->count();
        $pagination_pesanan = $pesanan_pelanggan->links();
        //MEANMPILKAN PRODUK PESANAN VERSI KOMPUTER
        $produk_pesanan_komputer = '';
        $produk_pesanan_mobile = '';
        if ($agent->isMobile()) {
        //MEANMPILKAN PRODUK PESANAN VERSI MOBILE
            foreach ($pesanan_pelanggan as $pesanan_pelanggans) {

                $produk_pesanan_mobile .= '
                <div class="card">
                <div class="col-sm-6">
                <b>Pesanan : <a href="' . url('pesanan-detail?xasq=' . $pesanan_pelanggans->id . '') . '">#' . $pesanan_pelanggans->id . '</a></b>
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
                } elseif ($pesanan_pelanggans->konfirmasi_pesanan == 4) {
                    $produk_pesanan_mobile .= '<td><b  style="color:orange">Batal Pelanggan</b></td>';
                }
                if ($setting_aplikasi->tipe_aplikasi == 0) {
                    $produk_pesanan_mobile .= '<br>Warung : <a href="' . url('halaman-warung/' . $pesanan_pelanggans->id_warung . '') . '"><b>' . $pesanan_pelanggans->warung->name . '</b></a>';
                }

                $produk_pesanan_mobile .= '
                <a href="' . url('pesanan-detail?xasq=' . $pesanan_pelanggans->id . '') . '" style="background-color: #01573e" class="btn btn-block">Detail Pesanan</a>
                </div>
                </div>';
            }
        }else{
            foreach ($pesanan_pelanggan as $pesanan_pelanggans) {

                $produk_pesanan_komputer .= '
                <tr  style="margin-top:0px;margin-bottom: 0px;">
                <td><a href="' . url('pesanan-detail?xasq=' . $pesanan_pelanggans->id . '') . '"><b>#' . $pesanan_pelanggans->id . '</b></a></td>
                <td><b>' . $pesanan_pelanggans->WaktuPesan . '</b></td>
                <td class="text-right"><b class="text-right">Rp. ' . number_format($pesanan_pelanggans->subtotal, 0, ',', '.') . '</b></td>';
                if ($pesanan_pelanggans->konfirmasi_pesanan == 0) {
                    $produk_pesanan_komputer .= '<td class="text-center"><b  style="color:red">Belum Di Konfirmasi</b></td>';
                } elseif ($pesanan_pelanggans->konfirmasi_pesanan == 1) {
                    $produk_pesanan_komputer .= '<td class="text-center"><b  style="color:orange">Sudah Diterima Warung</b></td>';
                } elseif ($pesanan_pelanggans->konfirmasi_pesanan == 2) {
                    $produk_pesanan_komputer .= '<td class="text-center"><b  style="color:#01573e">Selesai</b></td>';
                } elseif ($pesanan_pelanggans->konfirmasi_pesanan == 3) {
                    $produk_pesanan_komputer .= '<td class="text-center"><b  style="color:red">Batal</b></td>';
                } elseif ($pesanan_pelanggans->konfirmasi_pesanan == 4) {
                    $produk_pesanan_komputer .= '<td class="text-center"><b  style="color:orange">Batal Pelanggan</b></td>';
                }
                if ($setting_aplikasi->tipe_aplikasi == 0) {
                    $produk_pesanan_komputer .= '<td><a href="' . url('halaman-warung/' . $pesanan_pelanggans->id_warung . '') . '"><b>' . $pesanan_pelanggans->warung->name . '</b></a></td>';
                }
                $produk_pesanan_komputer .= '</tr>';
            }
        }



        return view('layouts.pesanan_pelanggan', ['produk_pesanan_mobile' => $produk_pesanan_mobile, 'produk_pesanan_komputer' => $produk_pesanan_komputer, 'cek_belanjaan' => $cek_belanjaan,'cek_pesanan' => $cek_pesanan, 'pagination_pesanan' => $pagination_pesanan, 'setting_aplikasi' => $setting_aplikasi]);
    }

    public function detailPesananPelanggan(Request $request)
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

        $cek_belanjaan = KeranjangBelanja::where('id_pelanggan', Auth::user()->id)->count();

        $pesanan_pelanggan        = PesananPelanggan::with('warung')->where('id_pelanggan', Auth::user()->id)->where('id', $request->xasq)->first();
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
        } elseif ($pesanan_pelanggan->konfirmasi_pesanan == 4) {
            $status_pesanan .= '<td><b  style="color:orange">Batal Pelanggan</b></td>';
        }
        if ($pesanan_pelanggan->metode_pembayaran == 'TRANSFER') {
            $layanan_kurir = explode(" | ", $pesanan_pelanggan->layanan_kurir);
            $service = $layanan_kurir[2];
            $waktu_pengiriman = $layanan_kurir[1] ." Hari";
        }else{
            $service = "Bayar di Tempat";
            $waktu_pengiriman = "-";
        }
        $lokasi_warung = Indonesia::allVillages()->where('id', $pesanan_pelanggan->warung->wilayah)->first();

        return view('layouts.detail_pesanan_pelanggan', ['detail_pesanan_pelanggan' => $detail_pesanan_pelanggan, 'pesanan_pelanggan' => $pesanan_pelanggan, 'cek_belanjaan' => $cek_belanjaan, 'agent' => $agent,'status_pesanan' => $status_pesanan, 'lokasi_warung' => $lokasi_warung->name, 'pagination' => $pagination, 'service'=>$service, 'waktu_pengiriman'=>$waktu_pengiriman]);
    }

    public function batalPesananPelanggan($id)
    {
        PesananPelanggan::where('id', $id)->update(['konfirmasi_pesanan' => '4']);
        return redirect()->back();
    }

    public function lanjutPesananPelanggan($id)
    {
        PesananPelanggan::where('id', $id)->update(['konfirmasi_pesanan' => '0']);
        return redirect()->back();
    }

    public function cekBatasWaktuTransfer() {
        $address_app = SettingPembedaAplikasi::select('warung_id')->where('app_address', url('/'))->first();

        $waktu_dibuat = PesananPelanggan::where([
            ['konfirmasi_pesanan', '=', 0],
            ['id_warung', '=', $address_app->warung_id]
        ])->get();

        $res['telat 11 jam'] = 0;
        $res['telat 11 jam 30 menit'] = 0;

        foreach($waktu_dibuat as $wd) {

            $pesanan_pelanggan = PesananPelanggan::select('id', 'id_pelanggan', 'updated_at', 'id_warung', 'nama_pemesan', 'no_telp_pemesan', 'alamat_pemesan', 'jumlah_produk', 'subtotal', 'metode_pembayaran', 'biaya_kirim', 'kode_unik_transfer')->whereId($wd->id)->first();

            $data_warung = Warung::select('name', 'alamat', 'no_telpon', 'email')->whereId($wd->id_warung)->first();

            $user = User::select('email')->whereId($wd->id_pelanggan)->first();

            $produk_pesanan = DetailPesananPelanggan::with('produk')->where('id_pesanan_pelanggan', $wd->id)->get();

            $waktu = time() - strtotime($wd->created_at);

            // mendapatkan angka jam dan menit [jam, menit]
            $waktu = [(ceil(($waktu / 3600) - 1)), ((($waktu / 60) % 60))];
            // return $waktu;

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
