<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserWarung;
use App\Role;
use App\SettingAplikasi;
use Auth;
use Excel;
use Illuminate\Support\Facades\DB;
use Session;

class DaftarUserWarungController extends Controller
{

	public function paginationData($user, $user_array,  $url)
	{
        //DATA PAGINATION
		$respons['current_page']   = $user->currentPage();
		$respons['data']           = $user_array;
		$respons['first_page_url'] = url($url . '?page=' . $user->firstItem());
		$respons['from']           = 1;
		$respons['last_page']      = $user->lastPage();
		$respons['last_page_url']  = url($url . '?page=' . $user->lastPage());
		$respons['next_page_url']  = $user->nextPageUrl();
		$respons['path']           = url($url);
		$respons['per_page']       = $user->perPage();
		$respons['prev_page_url']  = $user->previousPageUrl();
		$respons['to']             = $user->perPage();
		$respons['total']          = $user->total();
        //DATA PAGINATION

		return $respons;
	}
	public function paginationPencarianData($user, $url, $search)
	{
        //DATA PAGINATION
		$respons['current_page']   = $user->currentPage();
		$respons['data']           = $user;
		$respons['first_page_url'] = url($url . '?page=' . $user->firstItem() . '&search=' . $search);
		$respons['from']           = 1;
		$respons['last_page']      = $user->lastPage();
		$respons['last_page_url']  = url($url . '?page=' . $user->lastPage() . '&search=' . $search);
		$respons['next_page_url']  = $user->nextPageUrl();
		$respons['path']           = url($url);
		$respons['per_page']       = $user->perPage();
		$respons['prev_page_url']  = $user->previousPageUrl();
		$respons['to']             = $user->perPage();
		$respons['total']          = $user->total();
        //DATA PAGINATION

		return $respons;
	}

	public function view()
	{
		$user_warung = Auth::user()->id_warung;
		$user = UserWarung::with(['otoritas'])->where('id_warung', $user_warung)->where('tipe_user', 4)->orderBy('id', 'desc')->paginate(10);
		$user_array = array();
		foreach ($user as $users) {
            # code...
			$role_users = Role::where('id', $users->otoritas->role_id)->first();
			array_push($user_array, ['role_user' => $role_users->display_name, 'user' => $users]);
		}
		$url = "daftar-user-warung/view";
		return $this->paginationData($user,$user_array,  $url);
	}
	public function pencarian(Request $request)
	{
		$user_warung = Auth::user()->id_warung;
		return  UserWarung::where('id_warung', $user_warung)->where('tipe_user', 4)
		->where(function ($query) use ($request) {
			$query->orWhere('name', 'LIKE', $request->search . '%')
			->orWhere('email', 'LIKE', $request->search . '%')
			->orWhere('alamat', 'LIKE', $request->search . '%')
			->orWhere('no_telp', 'LIKE', $request->search . '%');

		})
		->orderBy('id', 'desc')->paginate(10);
	}

	public function otoritas_user()
	{
		$otoritas = Role::where('id', '!=', 3)->where('id', '!=', 4)->where('id', '!=', 5)->get();
		return response()->json($otoritas);
	}

	public function store(Request $request)
	{
		        //START TRANSAKSI
		DB::beginTransaction();
	        // // proses tambah user
		$this->validate($request, [
			'name'    => 'required',
			'email'   => 'nullable|unique:users,email',
			'alamat'  => 'required',
			'no_telp' => 'required|without_spaces|unique:users,no_telp,',
			'role_id' => 'required',
		]);

            //SETTING APLIKASI
		$setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();
            //APP WARMART == 1
		if ($setting_aplikasi->tipe_aplikasi == 0) {
			$konfirmasi_admin = 0;
		} else {
			$konfirmasi_admin = 1;
		}
		$user_warung = Auth::user()->id_warung;
		$kode_verifikasi = rand(1111, 9999);
            //USER WARUNG
		$user = UserWarung::create([
			'name'              => $request->name,
			'email'              => $request->email,
			'password'          => bcrypt($request->password),
			'alamat'            => $request->alamat,
			'no_telp'           => $request->no_telp,
			'id_warung'         => $user_warung,
			'tipe_user'         => 4,
			'status_konfirmasi' => 0,
			'kode_verifikasi'   => $kode_verifikasi,
			'konfirmasi_admin'  => $konfirmasi_admin,
		]);
		$user->verifyEmail();
		$role_baru = Role::where('id', $request->role_id)->first();
		$user->attachRole($role_baru->id);

		DB::commit();
	}
}
