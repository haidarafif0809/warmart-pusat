<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Role;
use App\Otoritas;
use Auth;
use Session;
use Laratrust;

class UserController extends Controller
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

    
    public function index(Request $request, Builder $htmlBuilder)
    {
        //index user
        return view('user.index')->with(compact('html'));
    }

     public function view(){

        $user = User::with('role')->where('tipe_user',1)->paginate(5);
        $user_array = array();
        foreach ($user as $users) {
            # code...
            $paginate = $users->paginate(5);
            $role_users = Role::where('id',$users->role->role_id)->first();
            array_push($user_array, ['role_user'=>$role_users->display_name,'user'=>$users,'paginate'=>$paginate]);

          }


        return response()->json($user_array);

      
    }

     public function selectize(){
        $otoritas = Role::where('id','!=',3)->where('id','!=',4)->where('id','!=',5)->get();
        return response()->json($otoritas);
    }
    public function pencarian(Request $request){

        $user = User::with('role')->where('tipe_user',1)
        ->orwhere('name','LIKE',"%$request->search%")
        ->orWhere('no_telp','LIKE',"%$request->search%")
        ->orWhere('email','LIKE',"%$request->search%")
        ->orWhere('alamat','LIKE',"%$request->search%")->paginate(5);

         $user_array = array();

        foreach ($user as $users) {
            # code...
            $paginate = $users->paginate(5);
            $role_users = Role::where('id',$users->role->role_id)->first();
            array_push($user_array, ['role_user'=>$role_users->display_name,'user'=>$users,'paginate'=>$paginate]);
          }

        return response()->json($user_array);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
            
            // ambil otoritas yang bukan warung, customer, dan komunitas
        $otoritas = Role::where('id','!=',3)->where('id','!=',4)->where('id','!=',5)->pluck('display_name','id');

        return view('user.create',['otoritas' => $otoritas]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // // proses tambah user
         $this->validate($request, [
            'name'   => 'required',
            'email'     => 'nullable|unique:users,email', 
            'alamat'    => 'required',
            'no_telp'    => 'required|without_spaces|unique:users,no_telp,',
            'role_id'    => 'required', 
            ]);

         $user_baru = User::create([ 
            'name'      =>$request->name,
            'email'     =>$request->email, 
            'alamat'    =>$request->alamat,  
            'no_telp'   =>$request->no_telp, 
            'tipe_user' => '1',
            'password'  => bcrypt('123456')]);

        $role_baru = Role::where('id',$request->role_id)->first();
        $user_baru->attachRole($role_baru->id);


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
        // edoit user
        //
        $user = User::with('role')->find($id);
            // ambil otoritas yang bukan warung, customer, dan komunitas
        return url('user#/edit_user/'.$id);
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
                //  proses update user

         $this->validate($request, [
            'name'      => 'required',
            'email'     => 'nullable|unique:users,email,'.$id, 
            'alamat'    => 'required',
            'role_id'   => 'required', 
            'role_lama' => 'required',
            'no_telp'   => 'required|without_spaces|unique:users,no_telp,'.$id
            ]);

         // update user
         $user = User::where('id',$id)->update([
                'name'  => $request->name,
                'email' => $request->email,
                'alamat'=> $request->alamat,
                'no_telp'  => $request->no_telp   
            ]);

         $role_lama = Role::where('id', $request->role_lama)->first();
         $role_baru = Role::where('id', $request->role_id)->first();
         $user_baru = User::find($id);

         // buang role lama
         $user_baru->detachRole($role_lama->id);
         // masukan role baru
         $user_baru->attachRole($role_baru->id);

         Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"<b>BERHASIL:</b> Mengubah User <b>$request->name</b>"
            ]);

        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // hapus 
        $user = User::find($id);
        $user->detachRole($user->role->role_id);
        $hapus_user = User::destroy($id);
        return response(200);
    }

 

    public function konfirmasi(Request $request){
        // konfirmasi user
        $username = User::select('name')->where('id',$request->confirm)->first();
        $user = User::where('id',$request->confirm)->update(['status_konfirmasi' => '1']);
    }

    public function no_konfirmasi(Request $request){
        // no_konfirmasi user
        $username = User::select('name')->where('id',$request->confirm)->first();
        $user = User::where('id',$request->confirm)->update(['status_konfirmasi' => '0']);
    }

    public function reset_password(Request $request){
        // reset password
        $username = User::select('name')->where('id',$request->idreset)->first();

        $user = User::where('id',$request->idreset)->update(['password' => bcrypt('123456')]);
    }

}
