<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TransaksiKas;

class TransaksikasController extends Controller
{
    public function total_kas(Request $request){

    	$total_kas = TransaksiKas::total_kas($request);
    	return $total_kas;
    }
}