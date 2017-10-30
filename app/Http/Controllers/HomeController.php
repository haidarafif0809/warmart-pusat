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
use App\User;
use App\Error;
use App\TransaksiKas;
use DB;
use App\Hpp;
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
    
    //MENAMPILKAN PRODUK ACAK
    $data_produk = Barang::select(['id','kode_barang', 'kode_barcode', 'nama_barang', 'harga_jual', 'foto', 'deskripsi_produk', 'kategori_barang_id', 'id_warung'])->inRandomOrder()->paginate(4);
    $kategori_produk = KategoriBarang::select(['id','nama_kategori_barang'])->get();
    $daftar_produk = $this->listProduk($data_produk);   

    if (Auth::user()->tipe_user == 3) {
        return redirect()->route('daftar_produk.index');
    }
    else{
        return view('layouts.landing_page', ['daftar_produk' => $daftar_produk]);
    }

}

public function dashboard()
{
        //DASBOARD ADMIN
    $jumlah_komunitas = User::where('tipe_user','2')->count();
    $jumlah_customer = User::where('tipe_user','3')->count();
    $jumlah_warung = User::where('tipe_user','4')->count();
    $jumlah_warung_tervalidasi = User::where('tipe_user','4')->where('konfirmasi_admin','1')->count();
    $jumlah_komunitas_tervalidasi = User::where('tipe_user','2')->where('konfirmasi_admin','1')->count();
    $produk = Barang::count();
    $error_log = Error::count();

        //DASBOARD WARUNG
    $user_warung = Auth::user()->id_warung;
    $produk_warung = Barang::where('id_warung',$user_warung)->count();
    $transaksi_kas = TransaksiKas::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as jumlah_kas')])->where('warung_id',$user_warung)->first();
    $jumlah_kas_masuk = TransaksiKas::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) as total_kas_masuk')])->where('warung_id',$user_warung)->first();
    $jumlah_kas_keluar = TransaksiKas::select([DB::raw('IFNULL(SUM(jumlah_keluar),0) as total_kas_keluar')])->where('warung_id',$user_warung)->first();

    $stok_masuk = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) as jumlah_item_masuk')])->where('warung_id', $user_warung)->first(); 
    $stok_keluar = Hpp::select([DB::raw('IFNULL(SUM(jumlah_keluar),0) as jumlah_item_keluar')])->where('warung_id', $user_warung)->first(); 
    $nila_masuk = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_masuk')])->where('jenis_hpp',1)->where('warung_id',$user_warung)->first();
    $nila_keluar = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_keluar')])->where('jenis_hpp',2)->where('warung_id',$user_warung)->first();
    $prose_total_persedian = $nila_masuk->total_masuk - $nila_keluar->total_keluar;
    $total_persedian = number_format($prose_total_persedian,0,',','.');

    return view('home',['jumlah_komunitas'=>$jumlah_komunitas,'jumlah_customer'=>$jumlah_customer,'jumlah_warung'=>$jumlah_warung,'jumlah_warung_tervalidasi'=>$jumlah_warung_tervalidasi,'jumlah_komunitas_tervalidasi'=>$jumlah_komunitas_tervalidasi,'produk'=>$produk,'error_log'=>$error_log,'produk_warung'=>$produk_warung,'transaksi_kas'=>$transaksi_kas,'jumlah_kas_masuk'=>$jumlah_kas_masuk,'jumlah_kas_keluar'=>$jumlah_kas_keluar,'stok_masuk'=>$stok_masuk,'stok_keluar'=>$stok_keluar,'total_persedian'=>$total_persedian]);
}

public function sms(){

        $client = new Client(); //GuzzleHttp\Client
        $result = $client->get('https://reguler.zenziva.net/apps/smsapi.php?userkey=k9d4p8&passkey=afifmaulana&nohp=081222498686&pesan=isi%20pesan');

        return $result->getBody(); 
    }
}
