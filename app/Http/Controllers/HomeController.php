<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use SEOMeta;
use OpenGraph;
use Twitter;
use Auth;
use App\Barang;
use App\Warung;
use App\KategoriBarang;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        SEOMeta::setTitle('War-Mart.id');
        SEOMeta::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
        SEOMeta::setCanonical('https://war-mart.id');
        SEOMeta::addKeyword(['warmart', 'warung', 'marketplace','toko online','belanja','lazada']);

        OpenGraph::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
        OpenGraph::setTitle('War-Mart.id');
        OpenGraph::setUrl('https://war-mart.id');
        OpenGraph::addProperty('type', 'articles');

        //MENAMPILKAN PRODUK ACAK
        $data_produk = Barang::select(['id','kode_barang', 'kode_barcode', 'nama_barang', 'harga_jual', 'foto', 'deskripsi_produk', 'kategori_barang_id', 'id_warung'])->inRandomOrder()->paginate(4);
        $kategori_produk = KategoriBarang::select(['id','nama_kategori_barang'])->get();
        $daftar_produk = $this->listProduk($data_produk);        

        return view('layouts.landing_page', ['daftar_produk' => $daftar_produk]);

    }

    public function listProduk($data_produk){
     $daftar_produk = "";
     $produk_pagination = $data_produk->links();
     foreach ($data_produk as $produks) {

        $warung = Warung::select(['name'])->where('id', $produks->id_warung)->first();

        $daftar_produk .= '<div class="col-md-3">
        <div class="card card-product card-plain no-shadow" data-colored-shadow="false">
           <div class="card-image">';

            if ($produks->foto != NULL) {
               $daftar_produk .= '<img src="./foto_produk/'.$produks->foto.'">';
           }
           else{
            $daftar_produk .= '<img src="./image/foto_default.png">';
        }

        $daftar_produk .= '</div>
        <div class="card-content">
           <a href="#">
               <h5 class="card-title">'.$produks->nama.'</h5>
           </a>';

           if ($produks->deskripsi_produk != "") {
               $daftar_produk .= '<p class="description">'.strip_tags(substr($produks->deskripsi_produk, 0, 60)).'..</p>';
           }
           else{
            $daftar_produk .= '<p class="description">Tidak Ada Deskripsi</p>
            <p class="description">Untuk Produk Ini</p>';
        }
        $daftar_produk .= '<div class="footer">
        <div class="price-container">
            <span class="price" style="color:red;">'.$produks->rupiah.'</span>
        </div>
    </div>
</div>

<a class="description"><i class="material-icons">store</i>  '.$warung->name.' </a>
</div>
</div>';
}
return $daftar_produk;
}

public function index_home()
{
    SEOMeta::setTitle('War-Mart.id');
    SEOMeta::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
    SEOMeta::setCanonical('https://war-mart.id');
    SEOMeta::addKeyword(['warmart', 'warung', 'marketplace','toko online','belanja','lazada']);

    OpenGraph::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
    OpenGraph::setTitle('War-Mart.id');
    OpenGraph::setUrl('https://war-mart.id');
    OpenGraph::addProperty('type', 'articles');

    if (Auth::user()->tipe_user == 3) {
        return redirect()->route('daftar_produk.index');
    }
    else{
        return view('layouts.landing_page');
    }

}

public function dashboard()
{
    return view('home');
}

public function sms(){

        $client = new Client(); //GuzzleHttp\Client
        $result = $client->get('https://reguler.zenziva.net/apps/smsapi.php?userkey=k9d4p8&passkey=afifmaulana&nohp=081222498686&pesan=isi%20pesan');

        return $result->getBody(); 
    }
}
