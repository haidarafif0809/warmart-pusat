<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Role;
use App\Komunitas;
use App\KomunitasPenggiat;
use App\KomunitasCustomer;
use App\Customer;
use Session;

class UbahProfilController extends Controller
{
    public function ubah_profil()
    {
        //  
        $user = Auth::user();
        $komunitas = Komunitas::with(['kelurahan','warung','komunitas_penggiat'])->find($user->id);
        $otoritas = Role::where('id','!=',3)->where('id','!=',4)->where('id','!=',5)->pluck('display_name','id');
        $customer = Customer::find($user->id)->first();

        if ($customer->tgl_lahir == "" AND $customer->tgl_lahir == NULL) { 
        	$tanggal = "";
        }else{
      		$tanggal = $komunitas->tgl_lahir; 
        }

        $komunitas_customer = KomunitasCustomer::where('user_id',$user->id)->first();
        return view('ubah_profil',['user'=>$user,'otoritas'=>$otoritas,'komunitas'=>$komunitas, 'tanggal'=>$tanggal,'komunitas_customer'=>$komunitas_customer,'customer'=>$customer]);
    }


    public function proses_ubah_profil(Request $request, $id)
    { 
        $this->validate($request, [ 
            'name' => 'required', 
            'no_telp' => 'required|unique:users,no_telp,'.$id,
            'email' => 'required|unique:users,email,'.$id, 
            'alamat' => 'required',
        ]);

  			if ($request['id_ubah_profil'] == 1) {
  				
		         $user = User::where('id',$id)->update([
		                'name'  => $request->name,
		                'no_telp' => $request->no_telp,
		                'email' => $request->email,
		                'alamat'=> $request->alamat   
		            ]);

  			}elseif ($request['id_ubah_profil'] == 2) {
  				
			         //insert
			        $komunitas = Komunitas::where('id',$id)->update([
			            'email' =>$request->email,
			            'name' =>$request->name,
			            'no_telp' =>$request->no_telp,
			            'alamat' =>$request->alamat,
			            'wilayah' =>$request->kelurahan,
			            'nama_bank' =>$request['nama_bank'],
			            'no_rekening' =>$request['no_rekening'],
			            'an_rekening' =>$request['an_rekening'],
			            'id_warung' =>$request['id_warung'],
			        ]); 

			        $cek_komunitas_penggiat = KomunitasPenggiat::where('komunitas_id',$id)->count();
 
 					if ($cek_komunitas_penggiat == 0) {
				        if ($request['nama_penggiat'] != "" AND $request['alamat_penggiat'] != ""){
						            $komunitaspenggiat = KomunitasPenggiat::create([
						            'nama_penggiat' =>$request->nama_penggiat,
						            'alamat_penggiat'  =>$request->alamat_penggiat,
						            'komunitas_id'=>$id      
						            ]);
	            			KomunitasPenggiat::where('komunitas_id',$id)->update(['nama_penggiat' =>$request['nama_penggiat'],'alamat_penggiat'  =>$request['alamat_penggiat']]);  
				        } 
 					}elseif ($cek_komunitas_penggiat == 1){
				        if ($request['nama_penggiat'] != "" AND $request['alamat_penggiat'] != ""){ 
	            			KomunitasPenggiat::where('komunitas_id',$id)->update(['nama_penggiat' =>$request['nama_penggiat'],'alamat_penggiat'  =>$request['alamat_penggiat']]);  
				        }  
 					}



  			}elseif ($request['id_ubah_profil'] == 3) {

			        Customer::find($id)->update([
			            'name'              => $request->name,
			            'email'             => $request->email, 
			            'alamat'            => $request->alamat,
			            'no_telp'           => $request->no_telp,
			            'tgl_lahir'         => $request['tgl_lahir'],
			            'wilayah'           => $request['kelurahan'],
			        ]);
			        if ($request['komunitas'] != "") {
				        //hapus komunitas sebelumnya, masukkan komunitas baru
				        KomunitasCustomer::where('user_id',$id)->delete();
				        if (isset($request['komunitas'])) {
				        
				        KomunitasCustomer::create(['user_id' =>$id ,'komunitas_id' => $request['komunitas']]);
				        }
			        }
  			}
		          

	        Session::flash("flash_notification", [
	            "level"     => "success",
	            "message"   => "Profil Berhasil Di Ubah"
	        ]);
  
       return back();
    } 	
}
