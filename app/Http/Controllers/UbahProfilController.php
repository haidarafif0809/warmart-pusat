<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Role;
use App\Komunitas;
use App\KomunitasPenggiat;
use App\KomunitasCustomer;
use Jenssegers\Agent\Agent;
use App\Customer;
use App\UserWarung; 
use App\BankWarung;
use App\BankKomunitas;
use Session;
use App\KeranjangBelanja;  

class UbahProfilController extends Controller
{   
//UBAH PROFIL USER PELANGGAN
	public function ubah_profil_pelanggan() {
    	//PILIH USER -> LOGIN
		$user = Auth::user();
		//FOTO WARMART
		$logo_warmart = "".asset('/assets/img/examples/warmart_logo.png')."";
        //PELANGGAN, WARUNG, KOMUNITAS
		$pelanggan = Customer::select(['id','email','password','name', 'alamat', 'wilayah', 'no_telp','tgl_lahir','tipe_user', 'status_konfirmasi'])->where('id', $user->id)->first();
		$komunitas_pelanggan = KomunitasCustomer::where('user_id',$user->id)->first();

		$keranjang_belanjaan = KeranjangBelanja::with(['produk','pelanggan'])->where('id_pelanggan',Auth::user()->id)->get();
		$cek_belanjaan = $keranjang_belanjaan->count();  
		return view('ubah_profil.ubah_profil_pelanggan',['user' => $pelanggan, 'pelanggan' => $pelanggan, 'komunitas_pelanggan' => $komunitas_pelanggan, 'cek_belanjaan' => $cek_belanjaan, 'logo_warmart' => $logo_warmart]);
	}

//UBAH PROFIL USER PELANGGAN
	public function proses_ubah_profil_pelanggan(Request $request) {
		//VALIDASI
		$this->validate($request, [ 
			'name' 		=> 'required', 
			'no_telp' 	=> 'required|unique:users,no_telp,'.$request->id,
			'email' 	=> 'unique:users,email,'.$request->id, 
			'alamat' 	=> 'required',
			]);
		//UPDATE USER PELANGGAN
		Customer::find($request->id)->update([
			'name'              => $request->name,
			'email'             => $request->email, 
			'alamat'            => $request->alamat,
			'no_telp'           => $request->no_telp,
			'tgl_lahir'         => $request->tgl_lahir,
			]);

		//JIKA SEBELUMNYA SUDAH ADA DI KOMUNITAS
		if ($request['komunitas'] != "") {
			//HAPUS KOMUNITAS LAMA
			KomunitasCustomer::where('user_id',$request->id)->delete();
			//INSERT KOMUNITAS BARU
			if (isset($request['komunitas'])) {
				KomunitasCustomer::create(['user_id' =>$request->id ,'komunitas_id' => $request['komunitas']]);
			}
		}

		return redirect()->route('daftar_produk.index');
	}	

//UBAH PROFIL USER WARUNG
	public function ubah_profil_warung() {
    	//PILIH USER -> LOGIN
		$user = Auth::user(); 
		$user_warung = UserWarung::with(['kelurahan'])->find($user->id);
		return view('ubah_profil.ubah_profil_warung')->with(compact('user_warung','user')); 
	}

//UBAH PROFIL USER WARUNG
	public function proses_ubah_profil_warung(Request $request) {
		//VALIDASI
		$this->validate($request, [
			'name'      => 'required',
			'alamat'    => 'required',
			'kelurahan' => 'required', 
			'email'     => 'required|without_spaces|unique:users,email,'.$request->id,
			'no_telp'   => 'required|without_spaces|unique:users,no_telp,'.$request->id,
			]);

         //UPDATE USER WARUNG
		$user_warung = UserWarung::where('id',$request->id)->update([
			'name'      => $request->name,
			'email'     => $request->email, 
			'no_telp'     => $request->no_telp, 
			'alamat'    => $request->alamat,
			'wilayah'   => $request->kelurahan, 
			]);


		Session::flash("flash_notification", [
			"level"     => "success",
			"message"   => "Profil Berhasil Di Ubah"
			]);

		return redirect()->back();
	}	 

//UBAH PROFIL USER KOMUNITAS
	public function ubah_profil_komunitas() {
    	//PILIH USER -> LOGIN
		$user = Auth::user(); 
		$komunitas = Komunitas::with(['kelurahan','warung','komunitas_penggiat','bank_komunitas'])->find($user->id); 

		return view('ubah_profil.ubah_profil_komunitas')->with(compact('user','komunitas')); 
	}

//UBAH PROFIL USER PELANGGAN
	public function proses_ubah_profil_komunitas(Request $request) {

        //end masukan data bank komunitas
		//VALIDASI 
		$this->validate($request, [
			'email'     => 'required|without_spaces|unique:users,email,'. $request->id,
			'name'      => 'required|unique:users,name,'. $request->id,
			'alamat'    => 'required',
			'kelurahan' => 'required',
			'no_telp'   => 'required|without_spaces|unique:users,no_telp,'. $request->id,
			'nama_bank' => 'required',
			'no_rekening' => 'required',
			'an_rekening' => 'required',
			'id_warung' => 'required',
			]);

         //insert
		$komunitas = Komunitas::where('id',$request->id)->update([
			'email' =>$request->email,
			'name' =>$request->name,
			'alamat' =>$request->alamat,
			'wilayah' =>$request->kelurahan,
			'no_telp' =>$request->no_telp,
			'id_warung' =>$request->id_warung,
			]);

		$cek_komunitas_penggiat = KomunitasPenggiat::where('komunitas_id',$request->id)->count(); 

         //masukan data penggiat komunitas
		if ($cek_komunitas_penggiat == 0) {
			$komunitaspenggiat = KomunitasPenggiat::create([
				'nama_penggiat' =>$request->name_penggiat,
				'alamat_penggiat'  =>$request->alamat_penggiat,
				'komunitas_id'=>$request->id 
				]);
		}else{
			if ($request->name_penggiat != "" AND $request->alamat_penggiat != ""){
				$komunitaspenggiat = KomunitasPenggiat::where('komunitas_id',$request->id)->update([
					'nama_penggiat' =>$request->name_penggiat,
					'alamat_penggiat'  =>$request->alamat_penggiat
					]);
			} 
		} 

		$cek_bank_komunitas = BankKomunitas::where('komunitas_id',$request->id)->count(); 
         //masukan data bank komunitas 
		if ($cek_bank_komunitas == 0) {
			$bankkomunitas = BankKomunitas::create([
				'nama_bank' =>$request->nama_bank,
				'no_rek'    =>$request->no_rekening,
				'atas_nama' =>$request->an_rekening ,
				'komunitas_id'=>$request->id              
				]);  
		}else{
			if ($request->nama_bank != "" AND $request->no_rekening != "" AND $request->an_rekening != "" ){
				$bankkomunitas = BankKomunitas::where('komunitas_id',$request->id)->update([
					'nama_bank' =>$request->nama_bank,
					'no_rek'    =>$request->no_rekening,
					'atas_nama' =>$request->an_rekening              
					]);
			} 

		}

		Session::flash("flash_notification", [
			"level"     => "success",
			"message"   => "Profil Berhasil Di Ubah"
			]);

		return redirect()->back();
	}	 

//UBAH PROFIL USER ADMIN
	public function ubah_profil_admin() {
    	//PILIH USER -> LOGIN
		$user = Auth::user(); 
		$user_admin = UserWarung::find($user->id);
		return view('ubah_profil.ubah_profil_admin')->with(compact('user_admin','user')); 
	}

//UBAH PROFIL USER ADMIN
	public function proses_ubah_profil_admin(Request $request) {
		//VALIDASI
		$this->validate($request, [
			'name'      => 'required',
			'email'     => 'required|without_spaces|unique:users,email,'.$request->id,
			'no_telp'   => 'required|without_spaces|unique:users,no_telp,'.$request->id,
			'alamat'    => 'required', 
			]);

         //UPDATE USER ADMIN
		$user_warung = User::where('id',$request->id)->update([
			'name'      => $request->name,
			'email'     => $request->email, 
			'no_telp'     => $request->no_telp, 
			'alamat'    => $request->alamat, 
			]);


		Session::flash("flash_notification", [
			"level"     => "success",
			"message"   => "Profil Berhasil Di Ubah"
			]);

		return redirect()->back();
	}	
}
