<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PembelianOrder;
use App\DetailPembelianOrder;
use App\TbsPenerimaanProduk;
use App\DetailPenerimaanProduk;
use App\SettingAplikasi;
use App\PenerimaanProduk;
use Illuminate\Support\Facades\DB;
use Auth;

class PenerimaanProdukController extends Controller
{

    // DATA PAGINTION
	public function dataPagination($data_penerimaan_produks, $array, $url, $no_faktur_penerimaan)
	{

        //DATA PAGINATION
		$respons['current_page']   = $data_penerimaan_produks->currentPage();
		$respons['data']           = $array;
		$respons['no_faktur']      = $no_faktur_penerimaan;
		$respons['first_page_url'] = url($url . '?page=' . $data_penerimaan_produks->firstItem());
		$respons['from']           = 1;
		$respons['last_page']      = $data_penerimaan_produks->lastPage();
		$respons['last_page_url']  = url($url . '?page=' . $data_penerimaan_produks->lastPage());
		$respons['next_page_url']  = $data_penerimaan_produks->nextPageUrl();
		$respons['path']           = url($url);
		$respons['per_page']       = $data_penerimaan_produks->perPage();
		$respons['prev_page_url']  = $data_penerimaan_produks->previousPageUrl();
		$respons['to']             = $data_penerimaan_produks->perPage();
		$respons['total']          = $data_penerimaan_produks->total();
        //DATA PAGINATION

		return $respons;
	}

    // DATA SUPLIER ORDER
	public function suplierOrder(){

		$data_order = PembelianOrder::select(['pembelian_orders.id', 'pembelian_orders.no_faktur_order', 'pembelian_orders.suplier_id', 'pembelian_orders.keterangan','supliers.nama_suplier'])
		->leftJoin('supliers', 'supliers.id', '=', 'pembelian_orders.suplier_id')
		->where('pembelian_orders.status_order', 1)
		->where('pembelian_orders.warung_id', Auth::user()->id_warung)->get();

		$array = [];

		foreach ($data_order as $order) {
			array_push($array, [
				'id_order'		=> $order->id,
				'suplier_id'	=> $order->suplier_id,
				'faktur_order'	=> $order->no_faktur_order,
				'suplier_order'	=> $order->nama_suplier,
				'order'			=> $order->id."|".$order->suplier_id."|".$order->no_faktur_order."|".$order->nama_suplier."|".$order->keterangan
				]);
		}

		return response()->json($array);

	}


    // VIEW TBS PENERIMAAN PRODUK
	public function viewTbsPenerimaanProduk()
	{
		$session_id  = session()->getId();
		$user_warung = Auth::user()->id_warung;

		$tbs_penerimaan_produks = TbsPenerimaanProduk::dataTbsPenerimaanProduk($session_id, $user_warung)
		->orderBy('tbs_penerimaan_produks.id_tbs_penerimaan_produk', 'desc')->paginate(10);

		$array = [];
		foreach ($tbs_penerimaan_produks as $tbs_penerimaan_produk) {

			array_push($array, [
				'data_tbs'			=> $tbs_penerimaan_produk,
				'nama_satuan'       => strtoupper($tbs_penerimaan_produk->nama_satuan),
				]);
		}

		$url     = '/penerimaan-produk/view-tbs-penerimaan-produk';
		$no_faktur_penerimaan = '';
		$respons = $this->dataPagination($tbs_penerimaan_produks, $array, $url, $no_faktur_penerimaan);

		return response()->json($respons);
	}


    // PENCARIAN TBS PENERIMAAN PRODUK
	public function pencarianTbsPenerimaanProduk(Request $request)
	{
		$session_id  = session()->getId();
		$user_warung = Auth::user()->id_warung;

		$tbs_penerimaan_produks = TbsPenerimaanProduk::dataTbsPenerimaanProduk($session_id, $user_warung)
		->where(function ($query) use ($request) {

			$query->orWhere('barangs.nama_barang', 'LIKE', '%'. $request->search . '%')
			->orWhere('barangs.kode_barang', 'LIKE', '%'. $request->search . '%');

		})->orderBy('tbs_penerimaan_produks.id_tbs_penerimaan_produk', 'desc')->paginate(10);

		$array = [];
		foreach ($tbs_penerimaan_produks as $tbs_penerimaan_produk) {

			array_push($array, [
				'data_tbs'			=> $tbs_penerimaan_produk,
				'nama_satuan'       => strtoupper($tbs_penerimaan_produk->nama_satuan),
				]);
		}

		$url     = '/penerimaan-produk/view-tbs-penerimaan-produk';
		$no_faktur_penerimaan = '';
		$respons = $this->dataPagination($tbs_penerimaan_produks, $array, $url, $no_faktur_penerimaan);

		return response()->json($respons);
	}


	// GET PEMEBLIAN ORDER - PENERIMAAN ORDER
	public function prosesTbsPenerimaanProduk(Request $request){

		if (Auth::user()->id_warung == '') {
			Auth::logout();
			return response()->view('error.403');
		}else{

			$data_orders = DetailPembelianOrder::where('no_faktur_order', $request->faktur_order)
			->where('warung_id', Auth::user()->id_warung)->get();

			$session_id = session()->getId();
			$subtotal = 0;

			// HAPUS DATA TBS SUPLIER LAMA, JIKA TIBA TIBA SUPLIER DIUBAH
			$hapus_tbs = TbsPenerimaanProduk::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->delete();

			foreach ($data_orders as $data_order) {
				
				$insert_tbs = TbsPenerimaanProduk::create([
					'session_id'     	=> $session_id,
					'no_faktur_order'	=> $data_order->no_faktur_order,
					'id_produk'   		=> $data_order->id_produk,
					'jumlah_produk'		=> $data_order->jumlah_produk,
					'satuan_id' 		=> $data_order->satuan_id,
					'satuan_dasar'     	=> $data_order->satuan_dasar,
					'harga_produk'    	=> $data_order->harga_produk,
					'subtotal' 			=> $data_order->subtotal,
					'tax' 				=> $data_order->tax,
					'potongan'    		=> $data_order->potongan,
					'status_harga'		=> $data_order->status_harga,
					'warung_id'			=> $data_order->warung_id
					]);

				$subtotal = $subtotal + $data_order->subtotal;
			}


			$respons['status']   = 0;
			$respons['subtotal'] = $subtotal;

			return response()->json($respons);
		}

	}


	public function store(Request $request)
	{
		if (Auth::user()->id_warung == '') {
			Auth::logout();
			return response()->view('error.403');
		} else {
            //START TRANSAKSI
			DB::beginTransaction();
			$warung_id  = Auth::user()->id_warung;
			$session_id = session()->getId();
			$user       = Auth::user()->id;
			$no_faktur  = PenerimaanProduk::no_faktur($warung_id);

            //INSERT DETAIL PEMBELIAN
			$data_penerimaan_produk = TbsPenerimaanProduk::where('session_id', $session_id)->where('warung_id', $warung_id);

            // INSERT DETAIL PEMBELIAN
			foreach ($data_penerimaan_produk->get() as $data_tbs_penerimaan_produk) {

				$detail_penerimaan = DetailPenerimaanProduk::create([
					'no_faktur_penerimaan' => $no_faktur,
					'id_produk'        => $data_tbs_penerimaan_produk->id_produk,
					'jumlah_produk'    => $data_tbs_penerimaan_produk->jumlah_produk,
					'satuan_id'        => $data_tbs_penerimaan_produk->satuan_id,
					'satuan_dasar'     => $data_tbs_penerimaan_produk->satuan_dasar,
					'harga_produk'     => $data_tbs_penerimaan_produk->harga_produk,
					'subtotal'         => $data_tbs_penerimaan_produk->subtotal,
					'tax'              => $data_tbs_penerimaan_produk->tax,
					'potongan'         => $data_tbs_penerimaan_produk->potongan,
					'status_harga'     => $data_tbs_penerimaan_produk->status_harga,
					'warung_id'        => $warung_id,
					]);
			}

			$penerimaan = PenerimaanProduk::create([
				'no_faktur_penerimaan' => $no_faktur,
				'faktur_order'		=> $request->no_faktur,
				'suplier_id'        => $request->suplier_id,
				'total'             => $request->subtotal,
				'keterangan'        => $request->keterangan,
                'status_penerimaan' => 1, // Diterima
                'warung_id'         => $warung_id,
                ]);

			// UPDATE STATUS PEMBELIAN ORDER -> Diterima
			$pembelian_order = PembelianOrder::where('no_faktur_order', $request->no_faktur)->where('suplier_id', $request->suplier_id)->update(['status_order' => 3]);

            //HAPUS TBS PEMBELIAN ORDER
			$data_penerimaan_produk->delete();
			DB::commit();

			$respons['respons_pembelian'] = $penerimaan->id;
			return response()->json($respons);

		}
	}

    //PROSES BATAL TBS PENERIMAAN PRODUK
	public function batalPenerimaanProduk(Request $request)
	{

		if (Auth::user()->id_warung == '') {
			Auth::logout();
			return response()->view('error.403');
		} else {
			$session_id         = session()->getId();
			$data_tbs_pembelian = TbsPenerimaanProduk::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->delete();

			return response(200);
		}
	}


	public function cetakBesar($id)
	{   
		$warung_id = Auth::user()->id_warung;
        //SETTING APLIKASI
		$setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

		$data_penerimaan = PenerimaanProduk::cetakPenerimaanProduk($warung_id, $id)->first();

		$status_penerimaan = $data_penerimaan->Status;

		$data_cetak = DetailPenerimaanProduk::cetakDetailPenerimaanProduk($warung_id, $data_penerimaan->no_faktur_penerimaan)->get();

		$subtotal   = 0;
		foreach ($data_cetak as $data_cetaks) {
			$subtotal += $data_cetaks->subtotal;
		}

        // return $subtotal;
		return view('penerimaan_produk.cetak_besar', ['setting_aplikasi' => $setting_aplikasi, 'detail_orders' => $data_cetak, 'data_penerimaan' => $data_penerimaan, 'status_penerimaan' => $status_penerimaan, 'subtotal' => $subtotal])->with(compact('html'));
	}


	public function view()
	{
        //SELECT SEMUA TRASNSAKSI PENERIMAAN PRODUK
		$no_faktur = '';
		$data_penerimaan_produk = PenerimaanProduk::dataTransaksiPenerimaanProduk()->paginate(10);
        //PERULANGAN
		$array_penerimaan = array();
		foreach ($data_penerimaan_produk as $penerimaan_produk) {
			array_push($array_penerimaan, [
				'data'          => $penerimaan_produk,
				'status_penerimaan'  => $penerimaan_produk->Status
				]);
		}

		$url     = '/pembelian-order/view';
		$no_faktur_penerimaan = '';
        //DATA PAGINATION
		$respons = $this->dataPagination($data_penerimaan_produk, $array_penerimaan, $url, $no_faktur_penerimaan);

		return response()->json($respons);
	}


    // PENCARIAN TBS PENERIMAAN PRODUK
	public function pencarian(Request $request)
	{
		//SELECT SEMUA TRASNSAKSI PENERIMAAN PRODUK
		$data_penerimaan_produk = PenerimaanProduk::dataTransaksiPenerimaanProduk()
		->where(function ($query) use ($request) {

			$query->orWhere('penerimaan_produks.no_faktur_penerimaan', 'LIKE', '%'. $request->search . '%')
			->orWhere('penerimaan_produks.faktur_order', 'LIKE', '%'. $request->search . '%')
			->orWhere('supliers.nama_suplier', 'LIKE', '%'. $request->search . '%');

		})->orderBy('penerimaan_produks.id', 'desc')->paginate(10);
        //PERULANGAN
		$array_penerimaan = array();
		foreach ($data_penerimaan_produk as $penerimaan_produk) {
			array_push($array_penerimaan, [
				'data'          => $penerimaan_produk,
				'status_penerimaan'  => $penerimaan_produk->Status
				]);
		}

		$url     = '/pembelian-order/view';
		$no_faktur_penerimaan = '';
        //DATA PAGINATION
		$respons = $this->dataPagination($data_penerimaan_produk, $array_penerimaan, $url, $no_faktur_penerimaan);

		return response()->json($respons);
	}


//VIEW DETAIL PENERIMAAN PRODUK & PENCARIAN
	public function viewDetailPenerimaanProduk($id)
	{
		$warung_id = Auth::user()->id_warung;
		$penerimaan = PenerimaanProduk::find($id);

		$data_detailPenerimaan = DetailPenerimaanProduk::detailPenerimaanProduk($warung_id, $penerimaan->no_faktur_penerimaan)->paginate(10);

		$array_penerimaan = [];
		foreach ($data_detailPenerimaan as $detail_penerimaan) {
			array_push($array_penerimaan, [
				'detail_penerimaan'=> $detail_penerimaan,
				'nama_produk'=> $detail_penerimaan->NamaProduk,
				]);
		}

		$url     = '/penerimaan-produk/view-detail-penerimaan-produk';
        //DATA PAGINATION
		$respons = $this->dataPagination($data_detailPenerimaan, $array_penerimaan, $url, $penerimaan->no_faktur_penerimaan);


		return response()->json($respons);
	}

//PENCARIAN DETAIL PENERIMAAN PRODUK & PENCARIAN
	public function pencarianDetailPenerimaanProduk(Request $request, $id)
	{
		$warung_id = Auth::user()->id_warung;
		$penerimaan = PenerimaanProduk::find($id);

		$data_detailPenerimaan = DetailPenerimaanProduk::detailPenerimaanProduk($warung_id, $penerimaan->no_faktur_penerimaan)
		->where(function ($query) use ($request) {

			$query->orWhere('barangs.nama_barang', 'LIKE', '%'. $request->search . '%')
			->orWhere('barangs.kode_barang', 'LIKE', '%'. $request->search . '%');

		})->orderBy('detail_penerimaan_produks.id_detail_penerimaan', 'desc')->paginate(10);

		$array_penerimaan = [];
		foreach ($data_detailPenerimaan as $detail_penerimaan) {
			array_push($array_penerimaan, [
				'detail_penerimaan'=> $detail_penerimaan,
				'nama_produk'=> $detail_penerimaan->NamaProduk,
				]);
		}

		$url     = '/penerimaan-produk/view-detail-penerimaan-produk';
        //DATA PAGINATION
		$respons = $this->dataPagination($data_detailPenerimaan, $array_penerimaan, $url, $penerimaan->no_faktur_penerimaan);


		return response()->json($respons);
	}
}
