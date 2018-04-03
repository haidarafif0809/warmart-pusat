<?php

namespace App\Http\Controllers;

use App\BankWarung;
use App\DetailPesananPelanggan;
use App\KeranjangBelanja;
use App\LokasiPelanggan;
use App\PesananPelanggan;
use App\Role;
use App\SettingJasaPengiriman;
use App\User;
use App\Warung;
use Auth;
use DB;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Indonesia;
use Jenssegers\Agent\Agent;
use OpenGraph;
use SEOMeta;
use Session;
use App\SettingDefaultAlamatPelanggan;

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
        if (!Session::get('session_id')) {
            $session_id = session()->getId();
        } else {
            $session_id = Session::get('session_id');
        }
        if (Auth::check() == false) {
            $keranjang_belanja = KeranjangBelanja::with(['produk', 'pelanggan'])->where('session_id', $session_id);
            $jumlah_produk     = KeranjangBelanja::select([DB::raw('IFNULL(SUM(jumlah_produk),0) as total_produk')])->where('session_id', $session_id)->first();
        } else {
            $keranjang_belanja = KeranjangBelanja::with(['produk', 'pelanggan'])->where('id_pelanggan', Auth::user()->id);
            $jumlah_produk     = KeranjangBelanja::select([DB::raw('IFNULL(SUM(jumlah_produk),0) as total_produk')])->where('id_pelanggan', Auth::user()->id)->first();
        }

        $cek_belanjaan = $keranjang_belanja->count();
        //PERINTAH PAGINATION
        if ($agent->isMobile()) {

            $keranjang_belanjaan = $keranjang_belanja->paginate(20);
        } else {

            $keranjang_belanjaan = $keranjang_belanja->paginate(8);
        }
        $pagination = $keranjang_belanjaan->links();

        //FOTO WARMART
        $logo_warmart = "" . asset('/assets/img/examples/warmart_logo.png') . "";

        $subtotal     = 0;
        $berat_barang = 0;
        foreach ($keranjang_belanja->get() as $keranjang_belanjaans) {
            $harga_produk = $keranjang_belanjaans->produk->harga_jual * $keranjang_belanjaans->jumlah_produk;
            $subtotal     = $subtotal += $harga_produk;
            $berat_barang = $berat_barang += $keranjang_belanjaans->produk->berat;
        }

        // CEK LOKASI WARUNG
        $user = Auth::user();
        if ($cek_belanjaan == 0) {
            $id_warung      = 0;
            $warung         = 0;
            $kabupaten      = 0;
            $nama_kabupaten = 0;
        } else {
            $id_warung = $keranjang_belanja->first()->produk->id_warung;
            $warung    = Warung::find($id_warung);
            if ($warung->kabupaten != "") {
                $kabupaten      = Indonesia::findCity($warung->kabupaten);
                $nama_kabupaten = $kabupaten->name;
            } else {
                $kabupaten      = 0;
                $nama_kabupaten = 0;
            }
        } // END CEK LOKASI WARUNG

 // CEK LOKASI PELANGGAN
        $data_pelanggan = $this->cekLokasiPelanggan();
         // END CEK LOKASI WARUNG

        $jasa_pengirim = SettingJasaPengiriman::where('tampil_jasa_pengiriman', 1)->pluck('jasa_pengiriman', 'jasa_pengiriman');

        $bank_transfer = BankWarung::select(['setting_transfer_banks.nama_bank', 'setting_transfer_banks.id'])
        ->leftJoin('setting_transfer_banks', 'setting_transfer_banks.id', '=', 'bank_warungs.nama_bank')
        ->pluck('setting_transfer_banks.nama_bank', 'setting_transfer_banks.id');

        return view('layouts.selesaikan_pemesanan', ['pagination' => $pagination, 'keranjang_belanjaan' => $keranjang_belanjaan, 'cek_belanjaan' => $cek_belanjaan, 'agent' => $agent, 'jumlah_produk' => $jumlah_produk, 'logo_warmart' => $logo_warmart, 'subtotal' => $subtotal, 'user' => $user, 'berat_barang' => $berat_barang, 'kabupaten' => $nama_kabupaten, 'data_pelanggan' => $data_pelanggan, 'kurir' => $jasa_pengirim, 'bank' => $bank_transfer]);

    }

    public function cekLokasiPelanggan(){
         // CEK LOKASI PELANGGAN
        if (Auth::check() == false) {
            $data_pelanggan = $this->cekDefaultAlamatPelanggan();
        } else {
            $alamat_customer = LokasiPelanggan::select(['provinsi', 'kabupaten'])->where('id_pelanggan', Auth::user()->id);
            if ($alamat_customer->count() > 0) {
                $alamat                                = $alamat_customer->first();
                $data_pelanggan['provinsi_pelanggan']  = Indonesia::findProvince($alamat->provinsi)->name;
                $data_pelanggan['kabupaten_pelanggan'] = Indonesia::findCity($alamat->kabupaten)->name;
            } else {                
                $data_pelanggan = $this->cekDefaultAlamatPelanggan();
            }
        }
         // END CEK LOKASI WARUNG

        return $data_pelanggan;

    }

    public function cekDefaultAlamatPelanggan(){        
        $defaultAlamatPelanggan = SettingDefaultAlamatPelanggan::select('provinsi','kabupaten','status_aktif')->first();
        if ($defaultAlamatPelanggan->status_aktif == 1) {
            $data_pelanggan['provinsi_pelanggan']  = $defaultAlamatPelanggan->provinsi;
            $data_pelanggan['kabupaten_pelanggan'] = $defaultAlamatPelanggan->kabupaten;
        }else{
            $data_pelanggan['provinsi_pelanggan']  = Indonesia::findProvince($defaultAlamatPelanggan->provinsi)->name;
            $data_pelanggan['kabupaten_pelanggan'] = Indonesia::findCity($defaultAlamatPelanggan->kabupaten)->name;
        }

        return $data_pelanggan;
    }

    public function prosesSelesaikanPemesanan(Request $request)
    {
        //START TRANSAKSI
        DB::beginTransaction();

        if ($request->layanan_kurir != '') {
            $layanan_kurir = explode("|", $request->layanan_kurir);
            $layanan_kurir = $layanan_kurir[0] . " | " . $layanan_kurir[2] . " | " . $layanan_kurir[3];
        } else {
            $layanan_kurir = $request->layanan_kurir;
        }

        if (Auth::check() == false) {
            $session = Session::get('session_id');

            $this->validate($request, [
                'name'    => 'required',
                'email'   => 'required|without_spaces|unique:users,email|email',
                'alamat'  => 'required',
                'no_telp' => 'required|numeric|without_spaces|unique:users,no_telp',
            ]);

            $kode_verifikasi = rand(1111, 9999);
            $user            = User::create([
                'name'              => $request->name,
                'email'             => $request->email,
                'alamat'            => $request->alamat,
                'no_telp'           => $request->no_telp,
                'password'          => bcrypt('123456'),
                'tipe_user'         => 3,
                'status_konfirmasi' => 1,
                'kode_verifikasi'   => $kode_verifikasi,
            ]);

            $customerRole = Role::where('name', 'customer')->first();
            $user->attachRole($customerRole);

            $id_user             = $user->id;
            $keranjang_belanjaan = KeranjangBelanja::KeranjangBelanjaSession($session)->get();

        } else {
            // QUERY LENGKAPNYA ADA DI scopeKeranjangBelanjaPelanggan di model Keranjang Belanja
            $id_user             = Auth::user()->id;
            $keranjang_belanjaan = KeranjangBelanja::KeranjangBelanjaPelanggan()->get();

        }

        $cek_pesanan           = 0; // BUAT VARIABEL CEK PESANAN YANG KITA SET  0
        $id_pesanan            = 0;
        $arrayKeranjangBelanja = array();
        foreach ($keranjang_belanjaan as $key => $keranjang_belanjaans) {

            $kode_unik_transfer = PesananPelanggan::kodeUnikTransfer();
            $id_warung          = $keranjang_belanjaans['id_warung'];

            // $key adalah urutan perulangan di foreach

            // jika perulangan yg pertama maka proses dibawah akan dijalan kan
            if ($key == 0) {

                if (Auth::check() == false) {
                    // QUERY LENGKAPMNYA ADA DI scopeHitungTotalPesananSession di mmodel Keranjang Belanja
                    $query_hitung_total = KeranjangBelanja::HitungTotalPesananSession($id_warung, $session)->first();
                } else {
                    // QUERY LENGKAPMNYA ADA DI scopeHitungTotalPesanan di mmodel Keranjang Belanja
                    $query_hitung_total = KeranjangBelanja::HitungTotalPesanan($id_warung)->first();
                }

                if ($request->metode_pembayaran == "TRANSFER") {
                    $subtotal = (str_replace('.', '', $request->ongkos_kirim) + $query_hitung_total['total_pesanan']) + $kode_unik_transfer;
                } else {
                    $subtotal = (str_replace('.', '', $request->ongkos_kirim) + $query_hitung_total['total_pesanan']);
                }

                // INSERT KE PESANAN PELANGGAN
                $pesanan_pelanggan = PesananPelanggan::create([
                    'id_pelanggan'       => $id_user,
                    'nama_pemesan'       => $request->name,
                    'no_telp_pemesan'    => $request->no_telp,
                    'alamat_pemesan'     => $request->alamat,
                    'jumlah_produk'      => $query_hitung_total['total_produk'],
                    'subtotal'           => $subtotal,
                    'id_warung'          => $id_warung,
                    'kurir'              => $request->kurir,
                    'layanan_kurir'      => $layanan_kurir,
                    'metode_pembayaran'  => $request->metode_pembayaran,
                    'biaya_kirim'        => str_replace('.', '', $request->ongkos_kirim),
                    'bank_transfer'      => "-",
                    'kode_unik_transfer' => $kode_unik_transfer,
                ]);

                // UBAH NILAI VARIABEL CEK PESANAN JADI ID WARUNG
                $cek_pesanan = $id_warung;

                // ID PESANAN PELANGGAN
                $id_pesanan_pelanggan = $pesanan_pelanggan->id;
                $id_pesanan           = $pesanan_pelanggan->id;

                // SELECT WARUNG
                $warung = Warung::find($id_warung);

                // AMBIL NOMOR TELPON WARUNG
                $nomor_tujuan = $warung->no_telpon;
                $nama_warung  = $warung->name;

                // KIRIM SMS KE WARUNG
                $this->kirimSmsKeWarung($nomor_tujuan, $id_pesanan_pelanggan);

                $pesanan_pelanggan->kirimEmailKonfirmasiPesananKePelanggan($request, $nama_warung, $keranjang_belanjaan);

            }

            // JIKA CEK PESANAN TIDAK SAMA DENGAN NOL DAN CEK PESANAN TIDAK SAMA DENGAN ID WARUNG
            if ($cek_pesanan != 0 and $cek_pesanan != $id_warung) {

                if (Auth::check() == false) {
                    // QUERY LENGKAPMNYA ADA DI scopeHitungTotalPesananSession di mmodel Keranjang Belanja
                    $query_hitung_total = KeranjangBelanja::HitungTotalPesananSession($id_warung, $session)->first();
                } else {
                    // QUERY LENGKAPMNYA ADA DI scopeHitungTotalPesanan di mmodel Keranjang Belanja
                    $query_hitung_total = KeranjangBelanja::HitungTotalPesanan($id_warung)->first();
                }
                if ($request->metode_pembayaran == "TRANSFER") {
                    $subtotal = (str_replace('.', '', $request->ongkos_kirim) + $query_hitung_total['total_pesanan']) + $kode_unik_transfer;
                } else {
                    $subtotal = (str_replace('.', '', $request->ongkos_kirim) + $query_hitung_total['total_pesanan']);
                }

                // INSERT PESANAN PELANGGAN
                $pesanan_pelanggan = PesananPelanggan::create([
                    'id_pelanggan'       => $id_user,
                    'nama_pemesan'       => $request->name,
                    'no_telp_pemesan'    => $request->no_telp,
                    'alamat_pemesan'     => $request->alamat,
                    'jumlah_produk'      => $query_hitung_total['total_produk'],
                    'subtotal'           => $subtotal,
                    'id_warung'          => $id_warung,
                    'kurir'              => $request->kurir,
                    'layanan_kurir'      => $layanan_kurir,
                    'metode_pembayaran'  => $request->metode_pembayaran,
                    'biaya_kirim'        => str_replace('.', '', $request->ongkos_kirim),
                    'bank_transfer'      => "-",
                    'kode_unik_transfer' => $kode_unik_transfer,
                ]);

                // UBAH NILAI VARIABEL CEK PESANAN JADI ID WARUNG
                $cek_pesanan = $id_warung;

                // ID PESANAN PELANGGAN
                $id_pesanan_pelanggan = $pesanan_pelanggan->id;

                // SELECT WARUNG
                $warung = Warung::find($id_warung);

                // AMBIL NOMOR TELPON WARUNG
                $nomor_tujuan = $warung->no_telpon;
                $$nama_warung = $warung->name;

                // KIRIM SMS KE WARUNG
                $this->kirimSmsKeWarung($nomor_tujuan, $id_pesanan_pelanggan);

                $pesanan_pelanggan->kirimEmailKonfirmasiPesananKePelanggan($request, $nama_warung, $keranjang_belanjaan);
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

        if ($request->metode_pembayaran == "Bayar di Tempat") {
            return redirect()->route('daftar_produk.index');
        } else {
            return redirect()->route('info.pembayaran', ['id' => $id_pesanan, 'pelanggan' => $id_user, 'pelanggan' => $id_user, 'bank' => $request->bank]);
        }

    }

    public function halamanInfoPembayaran(Request $request)
    {
        $agent             = new Agent();
        $pesanan_pelanggan = PesananPelanggan::whereId($request->id);

        if ($pesanan_pelanggan->count() == 0) {
            return response()->view('error.404');
        } else {

            $keranjang_belanja = KeranjangBelanja::with(['produk', 'pelanggan'])->where('id_pelanggan', $request->id_pelanggan);
            $cek_belanjaan     = $keranjang_belanja->count();

            $bank = BankWarung::select(['bank_warungs.atas_nama', 'bank_warungs.no_rek', 'setting_transfer_banks.nama_bank'])->leftJoin('setting_transfer_banks', 'setting_transfer_banks.id', '=', 'bank_warungs.nama_bank')
            ->where('bank_warungs.nama_bank', $request->bank)->first();

            $waktu_daftar = date($pesanan_pelanggan->first()->created_at);
            $date         = date_create($waktu_daftar);
            date_add($date, date_interval_create_from_date_string('12 hours')); // hanya diberi waktu 12 jam
            $batas_pembayaran = date_format($date, 'Y-m-d H:i:s');

            return view('layouts.pembayaran_transfer', ['agent' => $agent, 'cek_belanjaan' => $cek_belanjaan, 'pesanan_pelanggan' => $pesanan_pelanggan->first(), 'batas_pembayaran' => $batas_pembayaran, 'bank' => $bank]);
        }

    }

    public function kirimSmsKeWarung($nomor_tujuan, $id_pesanan_pelanggan)
    {

        $userkey    = env('USERKEY');
        $passkey    = env('PASSKEY');
        $url_warung = url('/detail-pesanan-warung/' . $id_pesanan_pelanggan);
        $isi_pesan  = urlencode('Assalamualaikum, Ada Pesanan baru Silakan Cek ' . $url_warung);

        if (env('STATUS_SMS') == 1) {
            $client = new Client(); //GuzzleHttp\Client
            $result = $client->get('https://reguler.zenziva.net/apps/smsapi.php?userkey=' . $userkey . '&passkey=' . $passkey . '&nohp=' . $nomor_tujuan . '&pesan=' . $isi_pesan . '');

        }

    }

    public function dataProvinsi()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => "https://api.rajaongkir.com/starter/city?province=",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => array(
                "key: f038d4bff2cc5732df792e9b97cae16d",
            ),
        ));

        $response = curl_exec($curl);
        $err      = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }

    }

    public function dataKota(Request $request)
    {
        $curl          = curl_init();
        $jasa_pengirim = SettingJasaPengiriman::where('default_jasa_pengiriman', 1)->first()->jasa_pengiriman;

        curl_setopt_array($curl, array(
            CURLOPT_URL            => "https://api.rajaongkir.com/starter/city?province=" . $request->id_provinsi,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => array(
                "key: f038d4bff2cc5732df792e9b97cae16d",
            ),
        ));

        $response['data']  = curl_exec($curl);
        $response['kurir'] = $jasa_pengirim;
        $err               = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return response()->json($response);
        }
    }

    public function hitungOngkir(Request $request)
    {

        $origin      = $request->kota_pengirim;
        $destination = $request->kota_tujuan;
        $weight      = $request->berat_barang;
        $courier     = $request->kurir;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => "https://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => "origin=" . $origin . "&destination=" . $destination . "&weight=" . $weight . "&courier=" . $courier, //origin = ID kota atau kabupaten asal, destination = ID kota atau kabupaten tujuan, weight = Berat kiriman dalam gram,courier = Kode kurir: jne, pos, tiki. cek lengkapnya  https://rajaongkir.com/dokumentasi/starter
            CURLOPT_HTTPHEADER     => array(
                "content-type: application/x-www-form-urlencoded",
                "key: f038d4bff2cc5732df792e9b97cae16d",
            ),
        ));

        $response = curl_exec($curl);
        $err      = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    public function emailKonfirmasiPesanan()
    {
        $session_id = Session::get('session_id');

        $keranjang_belanjaan = KeranjangBelanja::select('keranjang_belanjas.id_keranjang_belanja AS id_keranjang_belanja', 'keranjang_belanjas.id_produk AS id_produk', 'keranjang_belanjas.jumlah_produk AS jumlah_produk', 'barangs.harga_jual AS harga_jual', 'barangs.id_warung AS id_warung', 'barangs.nama_barang AS nama_barang', 'barangs.foto AS foto')
        ->leftJoin('barangs', 'keranjang_belanjas.id_produk', '=', 'barangs.id')
        ->where('session_id', $session_id)->orderBy('barangs.id_warung');

        $arrayKeranjangBelanja = array();
        foreach ($keranjang_belanjaan->get() as $key => $keranjang_belanjaans) {
            array_push($arrayKeranjangBelanja, ['keranjang_belanja' => $keranjang_belanjaans]);
        }
//$arrayKeranjangBelanja[0]['keranjang_belanja']->id_produk;
        foreach ($arrayKeranjangBelanja as $arrayKeranjangBelanjas) {
            return $arrayKeranjangBelanja;
        }
    }

}
