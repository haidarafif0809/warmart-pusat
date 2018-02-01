<?php

namespace App\Http\Controllers;

use App\DetailPesananPelanggan;
use App\KeranjangBelanja;
use App\PesananPelanggan;
use App\Warung;
use Auth;
use DB;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use OpenGraph;
use SEOMeta;
use Indonesia;

class PemesananController extends Controller
{

    public function selesaikanPemesanan()
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

        $keranjang_belanja = KeranjangBelanja::with(['produk', 'pelanggan'])->where('id_pelanggan', Auth::user()->id);

        $cek_belanjaan = $keranjang_belanja->count();
        //PERINTAH PAGINATION
        if ($agent->isMobile()) {

            $keranjang_belanjaan = $keranjang_belanja->paginate(4);
        } else {

            $keranjang_belanjaan = $keranjang_belanja->paginate(8);
        }
        $pagination = $keranjang_belanjaan->links();

        $jumlah_produk = KeranjangBelanja::select([DB::raw('IFNULL(SUM(jumlah_produk),0) as total_produk')])->where('id_pelanggan', Auth::user()->id)->first();
        //FOTO WARMART
        $logo_warmart = "" . asset('/assets/img/examples/warmart_logo.png') . "";

        $subtotal = 0;
        $berat_barang = 0;
        foreach ($keranjang_belanja->get() as $keranjang_belanjaans) {
            $harga_produk = $keranjang_belanjaans->produk->harga_jual * $keranjang_belanjaans->jumlah_produk;
            $subtotal     = $subtotal += $harga_produk;
            $berat_barang     = $berat_barang += $keranjang_belanjaans->produk->berat;
        }

        $user = Auth::user();
        $id_warung = $keranjang_belanja->first()->produk->id_warung;
        $warung = Warung::find($id_warung);
        $kabupaten = Indonesia::findCity($warung->kabupaten);
        $nama_kabupaten = $kabupaten->name;

        return view('layouts.selesaikan_pemesanan', ['pagination' => $pagination, 'keranjang_belanjaan' => $keranjang_belanjaan, 'cek_belanjaan' => $cek_belanjaan, 'agent' => $agent, 'jumlah_produk' => $jumlah_produk, 'logo_warmart' => $logo_warmart, 'subtotal' => $subtotal, 'user' => $user,'berat_barang'=>$berat_barang,'kabupaten'=>$nama_kabupaten]);
    }

    public function prosesSelesaikanPemesanan(Request $request)
    {
        //START TRANSAKSI
        DB::beginTransaction();

        // QUERY LENGKAPNYA ADA DI scopeKeranjangBelanjaPelanggan di model Keranjang Belanja
        $keranjang_belanjaan = KeranjangBelanja::KeranjangBelanjaPelanggan()->get();

        $id_user = Auth::user()->id;

        $cek_pesanan = 0; // BUAT VARIABEL CEK PESANAN YANG KITA SET  0
        foreach ($keranjang_belanjaan as $key => $keranjang_belanjaans) {

            $id_warung = $keranjang_belanjaans['id_warung'];

            // $key adalah urutan perulangan di foreach

            // jika perulangan yg pertama maka proses dibawah akan dijalan kan
            if ($key == 0) {

                // QUERY LENGKAPMNYA ADA DI scopeHitungTotalPesanan di mmodel Keranjang Belanja
                $query_hitung_total = KeranjangBelanja::HitungTotalPesanan($id_warung)->first();

                // INSERT KE PESANAN PELANGGAN
                $pesanan_pelanggan = PesananPelanggan::create([
                    'id_pelanggan'    => $id_user,
                    'nama_pemesan'    => $request->name,
                    'no_telp_pemesan' => $request->no_telp,
                    'alamat_pemesan'  => $request->alamat,
                    'jumlah_produk'   => $query_hitung_total['total_produk'],
                    'subtotal'        => $query_hitung_total['total_pesanan'],
                    'id_warung'       => $id_warung,
                ]);

                // UBAH NILAI VARIABEL CEK PESANAN JADI ID WARUNG
                $cek_pesanan = $id_warung;

                // ID PESANAN PELANGGAN
                $id_pesanan_pelanggan = $pesanan_pelanggan->id;

                // SELECT WARUNG
                $warung = Warung::find($id_warung);

                // AMBIL NOMOR TELPON WARUNG
                $nomor_tujuan = $warung->no_telpon;

                // KIRIM SMS KE WARUNG
                $this->kirimSmsKeWarung($nomor_tujuan, $id_pesanan_pelanggan);
            }

            // JIKA CEK PESANAN TIDAK SAMA DENGAN NOL DAN CEK PESANAN TIDAK SAMA DENGAN ID WARUNG
            if ($cek_pesanan != 0 and $cek_pesanan != $id_warung) {

                // QUERY LENGKAPMNYA ADA DI scopeHitungTotalPesanan di mmodel Keranjang Belanja
                $query_hitung_total = KeranjangBelanja::HitungTotalPesanan($id_warung)->first();

                // INSERT PESANAN PELANGGAN
                $pesanan_pelanggan = PesananPelanggan::create([
                    'id_pelanggan'    => $id_user,
                    'nama_pemesan'    => $request->name,
                    'no_telp_pemesan' => $request->no_telp,
                    'alamat_pemesan'  => $request->alamat,
                    'jumlah_produk'   => $query_hitung_total['total_produk'],
                    'subtotal'        => $query_hitung_total['total_pesanan'],
                    'id_warung'       => $id_warung,
                ]);

                // UBAH NILAI VARIABEL CEK PESANAN JADI ID WARUNG
                $cek_pesanan = $id_warung;

                // ID PESANAN PELANGGAN
                $id_pesanan_pelanggan = $pesanan_pelanggan->id;

                // SELECT WARUNG
                $warung = Warung::find($id_warung);

                // AMBIL NOMOR TELPON WARUNG
                $nomor_tujuan = $warung->no_telpon;

                // KIRIM SMS KE WARUNG
                $this->kirimSmsKeWarung($nomor_tujuan, $id_pesanan_pelanggan);

            }

            // INSERT KE DETAIL PESANAN PELANGGAN
            DetailPesananPelanggan::create([
                'id_pesanan_pelanggan' => $pesanan_pelanggan->id,
                'id_produk'            => $keranjang_belanjaans['id_produk'],
                'id_pelanggan'         => $id_user,
                'harga_produk'         => $keranjang_belanjaans['harga_jual'],
                'jumlah_produk'        => $keranjang_belanjaans['jumlah_produk'],
            ]);

            // HAPUS KERANJANG BELANJA
            KeranjangBelanja::destroy($keranjang_belanjaans['id_keranjang_belanja']);

        }

        DB::commit();

        return redirect()->route('daftar_produk.index');

    }

    public function kirimSmsKeWarung($nomor_tujuan, $id_pesanan_pelanggan)
    {

        $userkey   = env('USERKEY');
        $passkey   = env('PASSKEY');
        $isi_pesan = urlencode('Warmart: Assalamualaikum, Ada Pesanan baru Silakan Cek war-mart.id/detail-pesanan-warung/' . $id_pesanan_pelanggan);

        if (env('STATUS_SMS') == 1) {
            $client = new Client(); //GuzzleHttp\Client
            $result = $client->get('https://reguler.zenziva.net/apps/smsapi.php?userkey=' . $userkey . '&passkey=' . $passkey . '&nohp=' . $nomor_tujuan . '&pesan=' . $isi_pesan . '');

        }

    }

    public function dataProvinsi(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/city?province=",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: f038d4bff2cc5732df792e9b97cae16d"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
      } else {
          echo $response;
      }


  }

  public function dataKota(Request $request){
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.rajaongkir.com/starter/city?province=".$request->id_provinsi,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "key: f038d4bff2cc5732df792e9b97cae16d"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
  } else {
      echo $response;
  }
}

public function hitungOngkir(Request $request){

    $origin = $request->kota_pengirim;
    $destination = $request->kota_tujuan;
    $weight = $request->berat_barang;
    $courier = $request->kurir;

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "origin=".$origin."&destination=".$destination."&weight=".$weight."&courier=".$courier,//origin = ID kota atau kabupaten asal, destination = ID kota atau kabupaten tujuan, weight = Berat kiriman dalam gram,courier = Kode kurir: jne, pos, tiki. cek lengkapnya  https://rajaongkir.com/dokumentasi/starter
      CURLOPT_HTTPHEADER => array(
        "content-type: application/x-www-form-urlencoded",
        "key: f038d4bff2cc5732df792e9b97cae16d"
    ),
  ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
  } else {
      echo $response;
  }
}

}
