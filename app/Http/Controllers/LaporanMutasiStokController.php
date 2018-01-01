<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanMutasiStokController extends Controller
{
	public function __construct()
	{
		$this->middleware('user-must-warung');
	}
}
