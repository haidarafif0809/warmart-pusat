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
use App\SettingAplikasi;
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
        $settings_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();
        if ($settings_aplikasi->tipe_aplikasi == 0) {            
            $this->middleware('user-must-admin');
        }
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
                    'confirm_message'   => 'Apakah Anda Yakin Ingin Menghapus Otoritas ' .$otoritas->name. '?',
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

        $cek_otoritas = Role::where('name',$request->otoritas)->orWhere('display_name',$request->otoritas)->count();
        if ($cek_otoritas > 0) {
           $respons['status'] = $cek_otoritas;
       }else{
        $otoritas = Role::create([
            'name' => $request->otoritas,
            'display_name' => $request->otoritas
        ]);
        $respons['status'] = $cek_otoritas;
        return response()->json($respons);
    }


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

        $permission_user = Permission::where('grup','user')->get();
        $permission_otoritas = Permission::where('grup','otoritas')->get(); 
        $permission_master_data = Permission::where('grup','master_data')->get(); 
        $permission_bank = Permission::where('grup','bank')->get(); 
        $permission_customer = Permission::where('grup','customer')->get(); 
        $otoritas = Role::where('id',$id)->first();

        $arrayPermissionUser = array();
        $arrayPermissionOtoritas = array();
        $arrayPermissionBank = array();
        $arrayPermissionCustomer = array();
        $permission_role = PermissionRole::with('permissions')->where('role_id',$id)->get();
        foreach ($permission_role as $permission_roles) {
            if ($permission_roles->permissions->grup == "user") {
               array_push($arrayPermissionUser, $permission_roles->permission_id);
           }
           if ($permission_roles->permissions->grup == "otoritas") {
               array_push($arrayPermissionOtoritas, $permission_roles->permission_id);
           }
           if ($permission_roles->permissions->grup == "bank") {
               array_push($arrayPermissionBank, $permission_roles->permission_id);
           }
           if ($permission_roles->permissions->grup == "customer") {
               array_push($arrayPermissionCustomer, $permission_roles->permission_id);
           }
       }

       return response()->json([
        "permission_user" => $permission_user,
        "permission_otoritas"     => $permission_otoritas,
        "permission_master_data" => $permission_master_data,
        "permission_bank"     => $permission_bank,
        "permission_customer" => $permission_customer,
        "otoritas"     => $otoritas,
        "data_permission_user"     => $arrayPermissionUser,
        "data_permission_otoritas"     => $arrayPermissionOtoritas,
        "data_permission_bank"     => $arrayPermissionBank,
        "data_permission_customer"     => $arrayPermissionCustomer,
    ]);
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
        $cek_otoritas = Role::where(function($query) use ($request){
            $query->where('name',$request->otoritas)->orWhere('display_name',$request->otoritas);
        })->where('id','!=',$id)->count();
        if ($cek_otoritas > 0) {

           $respons['status'] = $cek_otoritas;

       }else{

           Role::where('id', $id)->update([ 
            'name' =>$request->otoritas,
            'display_name'=>$request->otoritas]);

           $respons['status'] = $cek_otoritas;
           return response()->json($respons);
       }


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
            $respons['status'] = $user->count();
            return response()->json($respons);
        }
        else{
            Role::destroy($id);
            $respons['status'] = $user->count();
            return response()->json($respons);
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
         $role->detachPermission($permissions);
     }

     foreach ($request->user as $setting_user) {
      $permission_user = Permission::whereId($setting_user)->first();
      $role->attachPermission($permission_user);
  }
  foreach ($request->otoritas as $setting_otoritas) {
    $permission_otoritas = Permission::whereId($setting_otoritas)->first();
    $role->attachPermission($permission_otoritas);
}
foreach ($request->bank as $setting_bank) {
    $permission_bank = Permission::whereId($setting_bank)->first();
    $role->attachPermission($permission_bank);
}
foreach ($request->customer as $setting_customer) {
    $permission_customer = Permission::whereId($setting_customer)->first();
    $role->attachPermission($permission_customer);
}

}



public function paginationData($user, $user_array,  $url)
{
        //DATA PAGINATION
    $respons['current_page']   = $user->currentPage();
    $respons['data']           = $user_array;
    $respons['first_page_url'] = url($url . '?page=' . $user->firstItem());
    $respons['from']           = 1;
    $respons['last_page']      = $user->lastPage();
    $respons['last_page_url']  = url($url . '?page=' . $user->lastPage());
    $respons['next_page_url']  = $user->nextPageUrl();
    $respons['path']           = url($url);
    $respons['per_page']       = $user->perPage();
    $respons['prev_page_url']  = $user->previousPageUrl();
    $respons['to']             = $user->perPage();
    $respons['total']          = $user->total();
        //DATA PAGINATION

    return $respons;
}
public function paginationPencarianData($user, $url, $search)
{
        //DATA PAGINATION
    $respons['current_page']   = $user->currentPage();
    $respons['data']           = $user;
    $respons['first_page_url'] = url($url . '?page=' . $user->firstItem() . '&search=' . $search);
    $respons['from']           = 1;
    $respons['last_page']      = $user->lastPage();
    $respons['last_page_url']  = url($url . '?page=' . $user->lastPage() . '&search=' . $search);
    $respons['next_page_url']  = $user->nextPageUrl();
    $respons['path']           = url($url);
    $respons['per_page']       = $user->perPage();
    $respons['prev_page_url']  = $user->previousPageUrl();
    $respons['to']             = $user->perPage();
    $respons['total']          = $user->total();
        //DATA PAGINATION

    return $respons;
}
public function view(){
    return Role::whereNotIn('id',[3,4,5])->paginate(10);
}
}
