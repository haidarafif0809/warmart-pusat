<?php

namespace App\Http\Controllers;

use App\SettingFooter;
use Illuminate\Http\Request;
use Auth;

class SettingFooterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\
     */
    public function index()
    {
        $setting_footer = SettingFooter::where('warung_id', Auth::user()->id_warung)->first();

        // mengatasi hilangnya protocol (http, https)
        $setting_footer = str_replace('http', 'hiip', $setting_footer);
        return $setting_footer;
    }

    public function getDefaultData()
    {
        $default_data_setting_footer = SettingFooter::defaultData();
        foreach($default_data_setting_footer as $k => $v) {
            $default_data_setting_footer->{$k} = str_replace('http', 'hiip', $v);
        }

        return response()->json($default_data_setting_footer);
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
    public function show($id_warung)
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
    public function update(Request $request, $id_warung)
    {
        return SettingFooter::where('warung_id', $id_warung)->update($request->all());
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
