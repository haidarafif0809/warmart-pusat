<?php

namespace App\Http\Controllers;

use App\SettingPromo;
use Auth;
use Illuminate\Http\Request;


class SettingPromoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

        public function view()
    {
        $settingpromo = SettingPromo::with('barang')->orderBy('id_setting_promo', 'desc')->paginate(10);
        $array             = array();

        foreach ($settingpromo as $settingpromos) {
            array_push($array, ['settingpromo' => $settingpromos]);
        }

        $url     = '/daftar-topos/view';
        $respons = $this->paginationData($settingpromo, $array, $url);

        return response()->json($respons);
    }


        public function paginationData($settingpromo, $array, $url)
    {

        //DATA PAGINATION
        $respons['current_page']   = $settingpromo->currentPage();
        $respons['data']           = $array;
        $respons['first_page_url'] = url($url . '?page=' . $settingpromo->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $settingpromo->lastPage();
        $respons['last_page_url']  = url($url . '?page=' . $settingpromo->lastPage());
        $respons['next_page_url']  = $settingpromo->nextPageUrl();
        $respons['path']           = url($url);
        $respons['per_page']       = $settingpromo->perPage();
        $respons['prev_page_url']  = $settingpromo->previousPageUrl();
        $respons['to']             = $settingpromo->perPage();
        $respons['total']          = $settingpromo->total();
        //DATA PAGINATION

        return $respons;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
