<?php

namespace App\Http\Controllers;

use App\StokOpname;
use Illuminate\Http\Request;

class StokOpnameController extends Controller
{
    public function dataPagination($data_stok_opname, $array_stok_opname)
    {

        $respons['current_page']   = $data_stok_opname->currentPage();
        $respons['data']           = $array_stok_opname;
        $respons['first_page_url'] = url('/stok-  opname/view?page=' . $data_stok_opname->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $data_stok_opname->lastPage();
        $respons['last_page_url']  = url('/stok-  opname/view?page=' . $data_stok_opname->lastPage());
        $respons['next_page_url']  = $data_stok_opname->nextPageUrl();
        $respons['path']           = url('/stok-  opname/view');
        $respons['per_page']       = $data_stok_opname->perPage();
        $respons['prev_page_url']  = $data_stok_opname->previousPageUrl();
        $respons['to']             = $data_stok_opname->perPage();
        $respons['total']          = $data_stok_opname->total();

        return $respons;
    }

    public function view()
    {
        $data_stok_opname = StokOpname::dataStokOpname()->paginate(10);

        $array_stok_opname = array();
        foreach ($data_stok_opname as $stok_opname) {
            array_push($array_stok_opname);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($data_stok_opname, $array_stok_opname);
        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {
        $data_stok_opname = StokOpname::cariDataStokOpname($request)->paginate(10);

        $array_stok_opname = array();
        foreach ($data_stok_opname as $stok_opname) {
            array_push($array_stok_opname);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($data_stok_opname, $array_stok_opname);
        return response()->json($respons);
    }

/**
 * Display a listing of the resource.
 *
 * @return \Illuminate\Http\Response
 */
    public function index()
    {
        //
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
