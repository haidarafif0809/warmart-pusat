<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SettingFixel;
use Auth;

class SettingFixelController extends Controller
{
	public function view()
	{
		$google = SettingFixel::select('id_pixel')->where('fixel','Google')->where('warung_id',Auth::user()->id_warung);
		$facebook = SettingFixel::select('id_pixel')->where('fixel','Facebook')->where('warung_id',Auth::user()->id_warung);
		if ($google->count() > 0) {
			$respons['google'] = $google->first()->id_pixel;
		}else{
			$respons['google'] = 0;
		}

		if ($facebook->count() > 0) {
			$respons['facebook'] = $facebook->first()->id_pixel;
		}else{
			$respons['facebook'] = 0;
		}

		return $respons;
	}
	public function simpanSetting(Request $request){

		SettingFixel::where('fixel','Google')->where('warung_id',Auth::user()->id_warung)->delete();
		SettingFixel::where('fixel','Facebook')->where('warung_id',Auth::user()->id_warung)->delete();
		SettingFixel::create(['fixel'=> 'Google','id_pixel' => $request->google,'warung_id'=>Auth::user()->id_warung,'logo'=>'google.png']);
		SettingFixel::create(['fixel'=> 'Facebook','id_pixel' => $request->facebook,'warung_id'=>Auth::user()->id_warung,'logo'=>'facebook.jpg']);

	}
}
