<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use SEOMeta;
use OpenGraph;
use Twitter;
use App\User;
use App\Barang;
use App\Error;
use Auth;
use App\TransaksiKas;
use DB;
use App\Hpp;


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

        return view('layouts.landing_page');
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
