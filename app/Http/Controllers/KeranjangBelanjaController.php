<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;  
use SEOMeta;
use OpenGraph;
use Twitter;
use App\KeranjangBelanja;
use Jenssegers\Agent\Agent;

class KeranjangBelanjaController extends Controller
{
    //
	public function daftar_belanja()
	{
		SEOMeta::setTitle('War-Mart.id');
		SEOMeta::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
		SEOMeta::setCanonical('https://war-mart.id');
		SEOMeta::addKeyword(['warmart', 'warung', 'marketplace','toko online','belanja','lazada']);

		OpenGraph::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
		OpenGraph::setTitle('War-Mart.id');
		OpenGraph::setUrl('https://war-mart.id');
		OpenGraph::addProperty('type', 'articles');

		$keranjang_belanjaan = KeranjangBelanja::with(['produk','pelanggan']);

		$agent = new Agent();
		$cek_belanjaan = $keranjang_belanjaan->count();
		return view('layouts.keranjang_belanja',['keranjang_belanjaan'=>$keranjang_belanjaan,'cek_belanjaan'=>$cek_belanjaan,'agent'=>$agent]);

	}
}
