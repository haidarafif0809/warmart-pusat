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

	public function view()
	{
		$data_settings = TemaWarna::where('warung_id', Auth::user()->id_warung)->orderBy('id', 'asc')->paginate(10);

		$data_agent = new Agent();
		if ($data_agent->isMobile()) {
			$agent = 0;
		} else {
			$agent = 1;
		}

		$array = [];
		foreach ($data_settings as $data_setting) {
			array_push($array, ['tema' => $data_setting, 'agent' => $agent]);
		}

		return response()->json($array);
	}
}
