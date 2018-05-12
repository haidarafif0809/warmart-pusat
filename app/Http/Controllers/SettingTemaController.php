<?php

namespace App\Http\Controllers;

use Auth;
use App\TemaWarna;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;

class SettingTemaController extends Controller
{
	public function __construct()
	{
		$this->middleware('user-must-warung');
	}

	public function dataPagination($data_settings, $array)
	{

		$respons['current_page']   = $data_settings->currentPage();
		$respons['data']           = $array;
		$respons['first_page_url'] = url('/tema/view?page=' . $data_settings->firstItem());
		$respons['from']           = 1;
		$respons['last_page']      = $data_settings->lastPage();
		$respons['last_page_url']  = url('/tema/view?page=' . $data_settings->lastPage());
		$respons['next_page_url']  = $data_settings->nextPageUrl();
		$respons['path']           = url('/tema/view');
		$respons['per_page']       = $data_settings->perPage();
		$respons['prev_page_url']  = $data_settings->previousPageUrl();
		$respons['to']             = $data_settings->perPage();
		$respons['total']          = $data_settings->total();

		return $respons;
	}

	public function view()
	{
		$data_settings = TemaWarna::where('warung_id', Auth::user()->id_warung)->orderBy('id', 'asc')->paginate(10);

		$array = [];
		foreach ($data_settings as $data_setting) {
			array_push($array, ['tema' => $data_setting]);
		}
        //DATA PAGINATION
		$respons = $this->dataPagination($data_settings, $array);

		return $respons;
	}

	public function pencarian(Request $request)
	{
		$data_settings = TemaWarna::where('warung_id', Auth::user()->id_warung)
		->where('nama_tema', 'LIKE', "%$request->search%")->orderBy('id', 'asc')->paginate(10);

		$array = [];
		foreach ($data_settings as $data_setting) {
			array_push($array, ['tema' => $data_setting]);
		}
        //DATA PAGINATION
		$respons = $this->dataPagination($data_settings, $array);

		return $respons;
	}

	public function store(Request $request){
		$this->validate($request, [
			'nama_tema' => 'required',
			]);

		if ($request->default_tema == 1) {

			$tema_terpakai = TemaWarna::where('default_tema', $request->default_tema)
			->where('warung_id', Auth::user()->id_warung)->update([
				'default_tema' => 0,
				]);

			TemaWarna::create([
				'nama_tema'		=> $request->nama_tema,
				'kode_tema'		=> $request->kode_tema,
				'header_tema'	=> $request->header_tema,
				'default_tema'	=> $request->default_tema,
				'warung_id'		=> Auth::user()->id_warung
				]);

		} else {

			TemaWarna::create([
				'nama_tema'		=> $request->nama_tema,
				'kode_tema'		=> $request->kode_tema,
				'header_tema'	=> $request->header_tema,
				'default_tema'	=> $request->default_tema,
				'warung_id'		=> Auth::user()->id_warung
				]);
		}
	}

	public function ubahTema($id, $default_tema)
	{
		if ($default_tema == 1){
			$tema = TemaWarna::where('id', $id)->update([
				'default_tema' => 0,
				]);
		}
		else{
			$tema = TemaWarna::where('default_tema', 1)->update([
				'default_tema' => 0,
				]);
			$tema = TemaWarna::where('id', $id)->update([
				'default_tema' => 1,
				]);
		}

		return response($default_tema);
	}

	public function destroy($id)
	{
		$tema = TemaWarna::destroy($id);
		return response(200);
	}
}
