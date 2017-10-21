<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Error;
use Session;
use Auth;
use App\TrackerLog;

class ErrorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

        public function __construct()
    {
        $this->middleware('user-must-admin');
    }


  //PROSES MENAMPILKAN ERROR LOG
     public function index(Request $request, Builder $htmlBuilder)
    { 
         if ($request->ajax()) {

            $error_log = Error::all()->where('message','<>',NULL);
            return Datatables::of($error_log)->
            addColumn('route',function($error){

                $log = TrackerLog::where('error_id',$error->id)->where('route_path_id','<>',NULL);

                if ($log->count() > 0) {
                    # code...
                    return $log->first()->route_path->path;
                }
                else {
                    return "-";
                }

            })->
            addColumn('method',function($error){

                $log = TrackerLog::where('error_id',$error->id)->where('method','<>',NULL);

                if ($log->count() > 0) {
                    # code...
                    return $log->first()->method;
                }
                else {
                    return "-";
                }

            })->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
        ->addColumn(['data' => 'code', 'name' => 'code', 'title' => 'KODE'])  
        ->addColumn(['data' => 'method', 'name' => 'method', 'title' => 'Method', 'orderable' => false, 'searchable'=>false])  
        ->addColumn(['data' => 'route', 'name' => 'route', 'title' => 'Route', 'orderable' => false, 'searchable'=>false])  
        ->addColumn(['data' => 'message', 'name' => 'message', 'title' => 'Pesan Error'])
        ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Waktu']);

        return view('error_log.index')->with(compact('html')); 
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
