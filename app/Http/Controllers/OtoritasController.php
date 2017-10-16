<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Role; //Modal
use App\Otoritas; //Modal 
use Auth;
use App\Permission;
use Session;
use App\PermissionRole;
use Laratrust;

class OtoritasController extends Controller
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
        if ($request->ajax()) {
            $otoritas = Role::select(['id','name','display_name','description'])->whereNotIn('id',[3,4,5]);
            return Datatables::of($otoritas)->addColumn('action', function($otoritas){
                return view('otoritas._action', [
                    'model'             => $otoritas,
                    'form_url'          => route('otoritas.destroy', $otoritas->id),
                    'edit_url'          => route('otoritas.edit', $otoritas->id),
                    'permission_url'          => route('otoritas.permission', $otoritas->id),
                    'confirm_message'   => 'Apakah Anda Yakin Ingin Menghapus Block' .$otoritas->name. '?',
                    'permission_ubah' => Laratrust::can('edit_otoritas'),
                    'permission_hapus' => Laratrust::can('hapus_otoritas'),
                    'permission_otoritas' => Laratrust::can('permission_otoritas'),
                ]);
            })->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama'])
        ->addColumn(['data' => 'display_name', 'name' => 'display_name', 'title' => 'Display Nama'])
        ->addColumn(['data' => 'description', 'name' => 'description', 'title' => 'Deskripsi'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false]);

        return view('otoritas.index')->with(compact('html'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('otoritas.create');
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
        $this->validate($request, [
            'name' => 'required|unique:roles,name,',
            'display_name' => 'required|unique:roles,display_name',
            'description' => ' '
        ]);
    
        $otoritas = Role::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description
        ]);

        Session::flash("flash_notification", [
            "level"     => "success",
            "message"   => "Berhasil Menambah Otoritas $otoritas->display_name"
        ]);

        return redirect()->route('otoritas.index');
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
        $otoritas = Role::find($id);
        return view('otoritas.edit')->with(compact('otoritas'));
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
         $this->validate($request, [
            'name'   => 'required|unique:roles,name,' .$id,
            'display_name'     => 'required|unique:roles,display_name,' .$id,
            'description'    => ' ',
            ]);

        Role::where('id', $id) ->update([ 
            'name' =>$request->name,
            'display_name'=>$request->display_name,
            'description'=>$request->description]);

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Berhasil Mengubah Otoritas $request->display_name"
            ]);

        return redirect()->route('otoritas.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        //menghapus data dengan pengecekan alert /peringatan
        $user = Otoritas::where('role_id',$id); 
 
    if ($user->count() > 0) {
        // menyiapkan pesan error
        $html = 'Otoritas tidak bisa dihapus karena masih memiliki User'; 
        
        Session::flash("flash_notification", [
          "level"=>"danger",
          "message"=>$html
        ]); 

        return redirect()->route('otoritas.index');      
        }
    else{

        Role::destroy($id);

        Session:: flash("flash_notification", [
            "level"=>"success",
            "message"=>"Otoritas Berhasil Di Hapus"
            ]);
        return redirect()->route('otoritas.index');
        }
    }


    public function setting_permission($id){

        $otoritas = Role::find($id); 
        $permission_user = Permission::where('grup','user')->get();
        $permission_otoritas = Permission::where('grup','otoritas')->get(); 
        $permission_master_data = Permission::where('grup','master_data')->get(); 
        $permission_bank = Permission::where('grup','bank')->get(); 
        $permission_customer = Permission::where('grup','customer')->get(); 
        $permission_komunitas = Permission::where('grup','komunitas')->get(); 
        $permission_warung = Permission::where('grup','warung')->get(); 

        return view('otoritas.permission',
            ['otoritas' => $otoritas, 
            'permission_user' => $permission_user,
            'permission_otoritas' => $permission_otoritas, 
            'permission_master_data' => $permission_master_data, 
            'permission_bank' => $permission_bank,
            'permission_customer' => $permission_customer,
            'permission_komunitas' => $permission_komunitas,
            'permission_warung' => $permission_warung,]);
    }


    public function proses_setting_permission(Request $request,$id){
         $permission = Permission::all();
         $role = Role::find($id);

         foreach ($permission as $permissions ) {

            $permission_name = $permissions->name;
            //jika checkbox nya di centang
             if (isset($request->$permission_name)) {
                //jika permission role nya belum ada maka di kaitkan role dengen pemissionya
                if(PermissionRole::where('role_id',$id)->where('permission_id',$permissions->id)->count() == 0) {
                     $role->attachPermission($permissions);
                } 
              
             }
             //jika checkbox nya tidak di centang
             else {
                //jika permission role nya ada maka di hilangkan permissionnya 
                if(PermissionRole::where('role_id',$id)->where('permission_id',$permissions->id)->count() == 1) {
                     $role->detachPermission($permissions);
                } 
             }
         }

          Session:: flash("flash_notification", [
            "level"=>"success",
            "message"=>"Setting Permission <b> $role->display_name </b> Berhasil Dirubah"
            ]);
        return redirect()->route('otoritas.index');

    }
}
