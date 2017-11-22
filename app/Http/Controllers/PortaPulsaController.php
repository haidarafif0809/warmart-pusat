<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HargaPulsa;
class PortaPulsaController extends Controller
{
    //

	public function cekDdeposit(){
		$data = array( 
			'inquiry' => 'S', // konstan
		);
		$result = $this->client($data);
		echo $result;
	}

	public function cekHargaPulsa($pilihan){
		$data = array( 
'inquiry' => 'HARGA', // konstan
'code' => $pilihan, // pilihan: pln, pulsa, game
);	
		$result = $this->client($data);
		echo $result;

	}

	public function statusTransaksi($trx_id){
		$data = array( 
'inquiry' => 'STATUS', // konstan
'trxid_api' => 'xxxxx', // Trxid atau Reffid dari sisi client saat transaksi pengisian
);
		$result = $this->client($data);
		echo $result;

	}

	public function isiPulsa ($trxid,$no_hp,$kode_produk,$beli_ke){
		$data = array( 
'inquiry' => 'I', // konstan
'code' => $kode_produk, // kode produk
'phone' => $no_hp, // nohp pembeli
'trxid_api' => $trxid, // Trxid / Reffid dari sisi client
'no' => $beli_ke, // untuk isi lebih dari 1x dlm sehari, isi urutan 1,2,3,4,dst
);
		$result = $this->client($data);
		echo $result;
	}

	public function perbaruiDataHargaPulsa(){

		HargaPulsa::truncate();

		$data = array( 
'inquiry' => 'HARGA', // konstan
'code' => "PULSA", // pilihan: pln, pulsa, game
);	
		$result = $this->client($data);
		$data_harga = json_decode($result);
		$harga_pulsa = $data_harga->message;
		for ($i=0; $i < count($data_harga->message) ; $i++) { 
			HargaPulsa::create(['code' => $harga_pulsa[$i]->code,'description' => $harga_pulsa[$i]->description,'price' => $harga_pulsa[$i]->price,'status' => $harga_pulsa[$i]->status]);
		}
		$response['status'] = 'sukses';
		return response($response);
	}


	public function client($data){
		$url = 'http://portalpulsa.com/api/connect/';

		$header = array(
			'portal-userid: P39737',
	'portal-key: 23cf060e1868fa4c951c1583297ad1b0', // lihat hasil autogenerate di member area
	'portal-secret: e29dc007864fb178d01a920b1adcabc48e5e901d633329dc8e019b66ede0a209', // lihat hasil autogenerate di member area
);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);

		return $result;

	}

	public function callbale(){
		if($_SERVER['REMOTE_ADDR']=='172.104.161.223'){ 
		// memastikan data terikirim dari server portalpulsa
			

		}
	}
}
