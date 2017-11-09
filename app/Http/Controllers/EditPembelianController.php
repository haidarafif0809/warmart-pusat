<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder; 
use Yajra\Datatables\Datatables; 
use Illuminate\Support\Facades\DB;  
use App\Pembelian;  
use App\DetailPembelian;   
use App\Barang;   
use App\EditTbsPembelian;
use App\Kas;   
use Session; 
use Auth; 
use Laratrust; 

class EditPembelianController extends Controller
{
	public function __construct() 
	{ 
		$this->middleware('user-must-warung'); 
	} 

    //PROSES TAMBAH TBS PEMBELIAN 
	public function proses_tambah_tbs_pembelian(Request $request){ 

		if (Auth::user()->id_warung == '') {
			Auth::logout();
			return response()->view('error.403');
		}else{
		//VALIDATE
			$this->validate($request, [ 
				'id_produk_tbs'     => 'required|numeric', 
				'jumlah_produk' => 'required|numeric|digits_between:1,15', 
			]); 

		$no_faktur = $request->no_faktur; // NO FAKTUR
		$session_id = session()->getId();  // SESSION ID

		// CEK EDIT TBS PEMBELIAN
		$data_tbs = EditTbsPembelian::where('id_produk', $request->id_produk_tbs) 
		->where('no_faktur', $no_faktur)->where('warung_id', Auth::user()->id_warung); 

		// SELECT PRODUK
		$barang = Barang::select('nama_barang','satuan_id')->where('id',$request->id_produk_tbs)->where('id_warung',Auth::user()->id_warung)->first(); 
//JIKA PRODUK YG DIPILIH SUDAH ADA DI TBS 
		if ($data_tbs->count() > 0) { 

			$pesan_alert =  
			'<div class="container-fluid"> 
			<div class="alert-icon"> 
			<i class="material-icons">warning</i> 
			</div> 
			<b>Warning : Produk "'.$barang->nama_barang.'" Sudah Ada, Silakan Pilih Produk Lain !</b> 
			</div>'; 

			Session::flash("flash_notification", [ 
				"level"   =>"warning", 
				"message" => $pesan_alert 
			]);  

			return redirect()->back(); 
		} 
		else{ 

			$pesan_alert =  
			'<div class="container-fluid"> 
			<div class="alert-icon"> 
			<i class="material-icons">check</i> 
			</div> 
			<b>Berhasil Menambah Produk "'.$barang->nama_barang.'"</b> 
			</div>'; 

			// SUBTOTAL = JUMLAH * HARGA
			$subtotal = $request->jumlah_produk * $request->harga_produk; 
			// INSERT EDIT TBS PEMBELIAN
			$Insert_tbspembelian = EditTbsPembelian::create([ 
				'id_produk'     => $request->id_produk_tbs,               
				'no_faktur'    => $no_faktur,             
				'session_id'    => $session_id, 
				'jumlah_produk' => $request->jumlah_produk, 
				'harga_produk'  => $request->harga_produk, 
				'subtotal'      => $subtotal, 
				'satuan_id'     => $barang->satuan_id, 
				'warung_id'     => Auth::user()->id_warung                                                                                                        
			]); 

			Session::flash("flash_notification", [ 
				"level"     =>"success", 
				"message"   => $pesan_alert 
			]); 
			return redirect()->back(); 
		} 
	}
} 

//PROSES EDIT JUMLAH TBS PEMBELIAN 
public function edit_jumlah_tbs_pembelian(Request $request){ 

	if (Auth::user()->id_warung == '') {
		Auth::logout();
		return response()->view('error.403');
	}else{
		// SELECT EDIT TBS PEMBELIAN
		$tbs_pembelian = EditTbsPembelian::find($request->id_tbs_pembelian); 
		// JIKA TAX/ PAJAKK EDIT TBS PEMBELIAN == 0
		if ($tbs_pembelian->tax == 0) { 
			$tax_produk = 0; 
		}else{ 

			// TAX PERSEN = (TAX TBS PEMBELIAN * 100) / (JUMLAH PRODUK * HARGA - POTONGAN)
			$tax = ($tbs_pembelian->tax * 100) / ($request->jumlah_edit_produk * $tbs_pembelian->harga_produk - $tbs_pembelian->potongan); // TAX DALAM BENTUK PERSEN
			// TAX PRODUK = (HARGA * JUMLAH - POTONGAN) * TAX /100
			$tax_produk = (($tbs_pembelian->harga_produk * $request->jumlah_edit_produk) - $tbs_pembelian->potongan) * $tax / 100; 
		} 

		if ($tbs_pembelian->ppn == 'Include') { // JIKA PPN INCLUDE MAKA PAJAK TIDAK MEMPENGARUHI SUBTOTAL
			$subtotal = ($tbs_pembelian->harga_produk * $request->jumlah_edit_produk) - $tbs_pembelian->potongan; 
		}elseif ($tbs_pembelian->ppn == 'Exclude') {  // JIKA PPN EXCLUDE MAKA PAJAK MEMPENGARUHI SUBTOTAL
			$subtotal = (($tbs_pembelian->harga_produk * $request->jumlah_edit_produk) - $tbs_pembelian->potongan) + $tax_produk; 
		}else{ 
			$subtotal = ($tbs_pembelian->harga_produk * $request->jumlah_edit_produk) - $tbs_pembelian->potongan; 
		} 

		// UPDATE JUMLAH PRODUK, SUBTOTAL, DAN TAX
		$tbs_pembelian->update(['jumlah_produk' => $request->jumlah_edit_produk,'subtotal'=>$subtotal,'tax'=>$tax_produk]); 
		$nama_barang = $tbs_pembelian->TitleCaseBarang; // TITLE CASH

		$pesan_alert =  
		'<div class="container-fluid"> 
		<div class="alert-icon"> 
		<i class="material-icons">check</i> 
		</div> 
		<b>Berhasil Mengubah Jumlah Produk "'.$nama_barang.'"</b> 
		</div>'; 

		Session::flash("flash_notification", [ 
			"level"     => "success", 
			"message"   => $pesan_alert 
		]); 

		return redirect()->back(); 
	}
} 

//PROSES EDIT HARGA TBS PEMBELIAN 
public function edit_harga_tbs_pembelian(Request $request){ 

	if (Auth::user()->id_warung == '') {
		Auth::logout();
		return response()->view('error.403');
	}else{
		// SELECT EDIT TBS PEMBELIAN
		$tbs_pembelian = EditTbsPembelian::find($request->id_harga); 

		// JIKA POTONGAN == 0
		if ($tbs_pembelian->potongan == 0) { 
			$potongan_produk = 0; 
		}else{ 
			// POTONGA PERSEN = POTONGAN / (JUMLAH * HARGA) * 100
			$potongan_persen = ($tbs_pembelian->potongan / ($tbs_pembelian->jumlah_produk * $request->harga_edit_produk)) * 100; 
			// POTONGAN PRODUK = HARGA * JUMLAH * POTONGAN PERSEN /100 
			$potongan_produk = ($request->harga_edit_produk * $tbs_pembelian->jumlah_produk) * $potongan_persen / 100; 
		} 

		// JIKA PAJAK == 0
		if ($tbs_pembelian->tax == 0) { 
			$tax_produk = 0; 
		}else{ 
			// TAX PERSEN =  (TAX * 100) / (JUMLAH * HARGA - POTONGAN )
			$tax = ($tbs_pembelian->tax * 100) / ($tbs_pembelian->jumlah_produk * $request->harga_edit_produk - $potongan_produk); 
			// TAX PROSUK = ((HARGA * JUMLAH) - POTONGAN) * TAX PERSEN / 100
			$tax_produk = (($request->harga_edit_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk) * $tax / 100; 
		} 

		if ($tbs_pembelian->ppn == 'Include') { // JIKA PPN INCLUDE MAKA PAJAK TIDAK MEMPENGARUHI SUBTOTAL
			$subtotal = ($request->harga_edit_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk; 
		}elseif ($tbs_pembelian->ppn == 'Exclude') { // JIKA PPN EXCLUDE MAKA PAJAK MEMPENGARUHI SUBTOTAL
			$subtotal = (($request->harga_edit_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk) + $tax_produk; 
		}else{ 
			$subtotal = ($request->harga_edit_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk; 
		} 

		// UPDATE HARGA, SUBTOTAL, POTONGAN, TAX
		$tbs_pembelian->update(['harga_produk' => $request->harga_edit_produk,'subtotal'=>$subtotal,'potongan'=>$potongan_produk,'tax'=>$tax_produk]); 
		$nama_barang = $tbs_pembelian->TitleCaseBarang; // TITLE CASH

		$pesan_alert =  
		'<div class="container-fluid"> 
		<div class="alert-icon"> 
		<i class="material-icons">check</i> 
		</div> 
		<b>Berhasil Mengubah Harga Produk "'.$nama_barang.'"</b> 
		</div>'; 

		Session::flash("flash_notification", [ 
			"level"     => "success", 
			"message"   => $pesan_alert 
		]); 

		return redirect()->back(); 
	}
} 


//PROSES EDIT HARGA TBS PEMBELIAN 
public function edit_potongan_tbs_pembelian(Request $request){ 

	if (Auth::user()->id_warung == '') {
		Auth::logout();
		return response()->view('error.403');
	}else{
		// SELECT EDIT TBS PEMBELIAN
		$tbs_pembelian = EditTbsPembelian::find($request->id_potongan); 
    $potongan = substr_count($request->potongan_edit_produk, '%'); // UNTUK CEK APAKAH ADA STRING "%" 
    // JIKA TIDAK ADA
    if ($potongan == 0) { 
    	// FILTER NUMBER FLOAT
    	$potongan_produk = filter_var($request->potongan_edit_produk, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); // POTONGAN TIDAK DALAM BENTUK NOMINAL
    	$potongan_persen = 0;
    }else{ // JIKA ADA
    	// FILTER NUMBER FLOAT
    	$potongan_persen = filter_var($request->potongan_edit_produk, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); //  PISAH STRING BERDASRAKAN TANDA "%" NYA
    	// POTONGA PRODUK =  (HARGA * JUMLAH ) * POTONGAN PERSEN / 100;
    	$potongan_produk = ($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) * $potongan_persen / 100; 
    } 
    if ($potongan_produk == '') {
    	$potongan_produk = 0;
    }
    if ($potongan_persen > 100) {
    	$pesan_alert =  
    	'<div class="container-fluid"> 
    	<div class="alert-icon"> 
    	<i class="material-icons">check</i> 
    	</div> 
    	<b>Potongan Tidak Boleh Lebih Dari 100%!</b> 
    	</div>'; 

    	Session::flash("flash_notification", [ 
    		"level"     => "success", 
    		"message"   => $pesan_alert 
    	]); 

    	return redirect()->back(); 
    }else{
			    // JIKA TIDAK ADA PAJAK 
    	if ($tbs_pembelian->tax == 0) { 
    		$tax_produk = 0; 
    	}else{ 
			// TAX PERSEN =  (TAX * 100) / (JUMLAH * HARGA - POTONGAN )
    		$tax = ($tbs_pembelian->tax * 100) / ($tbs_pembelian->jumlah_produk * $tbs_pembelian->harga_produk - $potongan_produk); 
			// TAX PRODUK = ((HARGA * JUMLAH) - POTONGAN) * TAX PERSEN / 100
    		$tax_produk = (($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk) * $tax / 100; 
    	} 

			    if ($tbs_pembelian->ppn == 'Include') {  // JIKA PPN INCLUDE MAKA PAJAK TIDAK MEMPENGARUHI SUBTOTAL
			    	$subtotal = ($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk; 
			    }elseif ($tbs_pembelian->ppn == 'Exclude') {  // JIKA PPN EXCLUDE MAKA PAJAK MEMPENGARUHI SUBTOTAL
			    	$subtotal = (($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk) + $tax_produk; 
			    }else{ 
			    	$subtotal = ($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk; 
			    } 
			// UPDATE  SUBTOTAL, POTONGAN, TAX
			    $tbs_pembelian->update(['potongan' => $potongan_produk,'subtotal'=>$subtotal,'tax'=>$tax_produk]); 
			    $nama_barang = $tbs_pembelian->TitleCaseBarang; // TITLE CASH

			    $pesan_alert =  
			    '<div class="container-fluid"> 
			    <div class="alert-icon"> 
			    <i class="material-icons">check</i> 
			    </div> 
			    <b>Berhasil Mengubah Potongan Produk "'.$nama_barang.'"</b> 
			    </div>'; 

			    Session::flash("flash_notification", [ 
			    	"level"     => "success", 
			    	"message"   => $pesan_alert 
			    ]); 

			    return redirect()->back(); 
			}
		}
	} 


	public function editTaxTbsPembelian(Request $request){ 

		if (Auth::user()->id_warung == '') {
			Auth::logout();
			return response()->view('error.403');
		}else{
	// SELECT EDIT  TBS PEMBELIAN  
			$tbs_pembelian = EditTbsPembelian::find($request->id_tax); 
    $tax = substr_count($request->tax_edit_produk, '%'); // UNTUK CEK APAKAH ADA STRING "%" 
    // JIKA TIDAK ADA
    if ($tax == 0) { 
    	$tax_produk = filter_var($request->tax_edit_produk, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);// TAX DAALAM BENTUK NOMINAL

    	$tax_persen = 0;  //  PISAH STRING BERDASRAKAN TANDA "%"
    }else{ // JIKA ADA
    	$tax_persen = filter_var($request->tax_edit_produk, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);  //  PISAH STRING BERDASRAKAN TANDA "%"
    	// TAX PRODUK = ((HARGA * JUMLAH) - POTONGAN) * TAX PERSEN / 100
    	$tax_produk = (($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $tbs_pembelian->potongan) * $tax_persen / 100; 
    } 
    if ($tax_produk == '') {
    	$tax_produk = 0;
    }

    if ($tax_persen > 100) {
    	$pesan_alert =  
    	'<div class="container-fluid"> 
    	<div class="alert-icon"> 
    	<i class="material-icons">check</i> 
    	</div> 
    	<b>Pajak Tidak Boleh Lebih Dari 100%!</b> 
    	</div>'; 

    	Session::flash("flash_notification", [ 
    		"level"     => "success", 
    		"message"   => $pesan_alert 
    	]); 

    	return redirect()->back(); 
    }else{

	    if ($request->ppn_produk == 'Include') {   // JIKA PPN INCLUDE MAKA PAJAK TIDAK MEMPENGARUHI SUBTOTAL
	    	$subtotal = ($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $tbs_pembelian->potongan; 
	    }elseif ($request->ppn_produk == 'Exclude') { // JIKA PPN EXCLUDE MAKA PAJAK MEMPENGARUHI SUBTOTAL 
	    	$subtotal = (($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $tbs_pembelian->potongan) + $tax_produk; 
	    } 

	    // UPDATE SUBTOTAL, TAX, PPN
	    $tbs_pembelian->update(['subtotal'=>$subtotal,'tax'=>$tax_produk,'ppn'=>$request->ppn_produk]); 
	    $nama_barang = $tbs_pembelian->TitleCaseBarang; // TITLE CASH

	    $pesan_alert =  
	    '<div class="container-fluid"> 
	    <div class="alert-icon"> 
	    <i class="material-icons">check</i> 
	    </div> 
	    <b>Berhasil Mengubah Pajak Produk "'.$nama_barang.'"</b> 
	    </div>'; 

	    Session::flash("flash_notification", [ 
	    	"level"     => "success", 
	    	"message"   => $pesan_alert 
	    ]); 

	    return redirect()->back(); 
	}
}
} 
//PROSES HAPUS TBS PEMBELIAN 
public function hapus_tbs_pembelian($id){ 

	if (Auth::user()->id_warung == '') {
		Auth::logout();
		return response()->view('error.403');
	}else{

		if (!EditTbsPembelian::destroy($id)) { 
			return redirect()->back(); 

		} 
		else{ 
			$pesan_alert =  
			'<div class="container-fluid"> 
			<div class="alert-icon"> 
			<i class="material-icons">check</i> 
			</div> 
			<b>Berhasil Menghapus Produk</b> 
			</div>'; 

			Session::flash("flash_notification", [ 
				"level"     => "danger", 
				"message"   => $pesan_alert 
			]); 
			return redirect()->back(); 
		} 
	}
}

//PROSES BATAL TBS PEMBELIAN 
public function proses_batal_transaksi_pembelian(Request $request){ 

	if (Auth::user()->id_warung == '') {
		Auth::logout();
		return response()->view('error.403');
	}else{
		$no_faktur = $request->no_faktur_batal;
		$data_tbs_pembelian = EditTbsPembelian::where('no_faktur', $no_faktur)->where('warung_id', Auth::user()->id_warung)->delete(); 
		$pesan_alert =  
		'<div class="container-fluid"> 
		<div class="alert-icon"> 
		<i class="material-icons">check</i> 
		</div> 
		<b>Berhasil Membatalkan Edit Pembelian</b> 
		</div>'; 

		Session::flash("flash_notification", [ 
			"level"     => "success", 
			"message"   => $pesan_alert 
		]); 
		return redirect()->route('pembelian.index'); 
	}
}  



    //PROSES SELESAI TRANSAKSI EDIT PEMBELIAN
public function prosesEditPembelian(Request $request) {

	if (Auth::user()->id_warung == '') {
		Auth::logout();
		return response()->view('error.403');
	}else{
	//START TRANSAKSI 
		DB::beginTransaction(); 
		$no_faktur = $request->no_faktur_edit;
		$session_id = session()->getId();
		$user = Auth::user()->id; 


		$data_produk_pembelian = EditTbsPembelian::where('no_faktur', $no_faktur)->where('warung_id', Auth::user()->id_warung);

		if ($data_produk_pembelian->count() == 0) {

			$pesan_alert = 
			'<div class="container-fluid">
			<div class="alert-icon">
			<i class="material-icons">error</i>
			</div>
			<b>Gagal : Belum Ada Produk Yang Diinputkan</b>
			</div>';

			Session::flash("flash_notification", [
				"level"     => "danger",
				"message"   => $pesan_alert
			]);

			return redirect()->back();
		}
		else{
			$data_detail_pembelian = DetailPembelian::where('no_faktur', $no_faktur)->where('warung_id', Auth::user()->id_warung)->get();
		//HAPUS DETAIL PEMBELIAN
			foreach ($data_detail_pembelian as $data_detail) {            

				if (!$hapus_detail = DetailPembelian::destroy($data_detail->id_detail_pembelian)) {
                //DI BATALKAN PROSES NYA
					DB::rollBack();
					return redirect()->back();
				}
			}

			foreach ($data_produk_pembelian->get() as $data_tbs) {

				$barang = Barang::select('harga_beli')->where('id',$data_tbs->id_produk)->where('id_warung',Auth::user()->id_warung); 
				if ($barang->first()->harga_beli != $data_tbs->harga_produk) {
					$barang->update(['harga_beli'=>$data_tbs->harga_produk]); 
				}
			//INSERT DETAIL PEMBELIAN
				$detail_pembelian = DetailPembelian::create([ 
					'no_faktur'         => $no_faktur, 
					'satuan_id'         => $data_tbs->satuan_id, 
					'id_produk'         => $data_tbs->id_produk, 
					'jumlah_produk'     => $data_tbs->jumlah_produk, 
					'harga_produk'      => $data_tbs->harga_produk, 
					'subtotal'          => $data_tbs->subtotal, 
					'tax'               => $data_tbs->tax, 
					'potongan'          => $data_tbs->potongan, 
					'ppn'               => $data_tbs->ppn, 
					'warung_id'         => Auth::user()->id_warung 
				]);   
			}

          //INSERT PEMBELIAN 
			if ($request->keterangan == "") { 
				$keterangan = "-"; 
			} 
			else{ 
				$keterangan = $request->keterangan; 
			} 

			if ($request->pembayaran == '') { 
				$pembayaran = 0; 
			}else{ 
				$pembayaran = $request->pembayaran; 
			} 
			if ($request->kembalian == '') { 
				$kembalian = 0; 
			}else{ 
				$kembalian = $request->kembalian; 
			} 

			$update_pembelian = Pembelian::find($request->id_pembelian)->update([			
				'total'             => str_replace('.','',$request->total_akhir), 
				'suplier_id'        => $request->suplier_id, 
				'status_pembelian'  => $request->status_pembelian, 
				'potongan'          => $request->potongan, 
				'tunai'             => $pembayaran, 
				'kembalian'         => str_replace('.','',$kembalian), 
				'kredit'            => str_replace('.','',$request->kredit), 
				'nilai_kredit'      => str_replace('.','',$request->kredit), 
				'cara_bayar'        => $request->id_cara_bayar, 
				'status_beli_awal'  => $request->status_pembelian, 
				'tanggal_jt_tempo'  => $request->jatuh_tempo, 
				'keterangan'        => $request->keterangan, 
				'ppn'               => $request->ppn
			]);

          //HAPUS TBS PEMBELIAN 
			$data_produk_pembelian->delete(); 

			$pesan_alert = 
			'<div class="container-fluid">
			<div class="alert-icon">
			<i class="material-icons">check</i>
			</div>
			<b>Sukses : Berhasil Melakukan Edit Transaksi Pembelian Faktur "'.$no_faktur.'"</b>
			</div>';

			Session::flash("flash_notification", [
				"level"     => "success",
				"message"   => $pesan_alert
			]);
			DB::commit(); 
			return redirect()->route('pembelian.index');
		}        

	}
}
}
