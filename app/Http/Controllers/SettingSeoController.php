<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SettingSeo;
use Auth;

class SettingSeoController extends Controller
{
	public function view()
	{
		$dataSEO = SettingSeo::select(['content_keyword', 'content_description'])->where('warung_id',Auth::user()->id_warung)->first();

		$respons['keyword'] = $dataSEO->content_keyword;
		$respons['deskripsi'] = $dataSEO->content_description;
		return $respons;
	}
	public function simpanSetting(Request $request){

		SettingSeo::where('warung_id',Auth::user()->id_warung)->delete();

		SettingSeo::create(['content_keyword' => $request->keyword,'content_description' => $request->deskripsi,'warung_id'=>Auth::user()->id_warung]);

	}
}
