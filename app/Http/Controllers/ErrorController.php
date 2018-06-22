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

        return view('error_log.index')->with(compact('html')); 
    }

    //VIEW ERROR LOG
    public function view(){
        $error = Error::with('log')->where('message','<>',NULL)->paginate(10);
        $error_array = array();

        foreach ($error as $errors) {

            $log_route = TrackerLog::where('error_id', $errors->id)->where('route_path_id','<>',NULL);
            $log_method = TrackerLog::where('error_id', $errors->id)->where('method','<>',NULL);

            if ($log_route->count() > 0) {
                $route_log = $log_route->first()->route_path->path;
            }
            else{
                $route_log = "-";
            }

            if ($log_method->count() > 0) {
                $metod_log = $log_method->first()->method;
            }
            else{
                $metod_log = "-";
            }

            array_push($error_array, ['error'=>$errors, 'method'=>$metod_log, 'route'=>$route_log]);

        }

        //DATA PAGINATION 
        $respons['current_page'] = $error->currentPage();
        $respons['data'] = $error_array;
        $respons['first_page_url'] = url('/error/view?page='.$error->firstItem());
        $respons['from'] = 1;
        $respons['last_page'] = $error->lastPage();
        $respons['last_page_url'] = url('/error/view?page='.$error->lastPage());
        $respons['next_page_url'] = $error->nextPageUrl();
        $respons['path'] = url('/error/view');
        $respons['per_page'] = $error->perPage();
        $respons['prev_page_url'] = $error->previousPageUrl();
        $respons['to'] = $error->perPage();
        $respons['total'] = $error->total();
        //DATA PAGINATION

        return response()->json($respons);  
    }

    //PEBCARIAN ERROR LOG
    public function pencarian(Request $request){
        $search = $request->search;// REQUEST SEARCH

        $error = Error::with('log')->where('message','<>',NULL)
        ->where(function($query) use ($search){// search
            $query->orwhere('id','LIKE','%'.$search.'%')
            ->orWhere('code','LIKE','%'.$search.'%')
            ->orWhere('message','LIKE','%'.$search.'%')
            ->orWhere('created_at','LIKE','%'.$search.'%');
        })->paginate(10);

        $error_array = array();

        foreach ($error as $errors) {

            $log_route = TrackerLog::where('error_id', $errors->id)->where('route_path_id','<>',NULL);
            $log_method = TrackerLog::where('error_id', $errors->id)->where('method','<>',NULL);

            if ($log_route->count() > 0) {
                $route_log = $log_route->first()->route_path->path;
            }
            else{
                $route_log = "-";
            }

            if ($log_method->count() > 0) {
                $metod_log = $log_method->first()->method;
            }
            else{
                $metod_log = "-";
            }

            array_push($error_array, ['error'=>$errors, 'method'=>$metod_log, 'route'=>$route_log]);

        }

        //DATA PAGINATION 
        $respons['current_page'] = $error->currentPage();
        $respons['data'] = $error_array;
        $respons['first_page_url'] = url('/error/view?page='.$error->firstItem());
        $respons['from'] = 1;
        $respons['last_page'] = $error->lastPage();
        $respons['last_page_url'] = url('/error/view?page='.$error->lastPage());
        $respons['next_page_url'] = $error->nextPageUrl();
        $respons['path'] = url('/error/view');
        $respons['per_page'] = $error->perPage();
        $respons['prev_page_url'] = $error->previousPageUrl();
        $respons['to'] = $error->perPage();
        $respons['total'] = $error->total();
        //DATA PAGINATION

        return response()->json($respons); 
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
