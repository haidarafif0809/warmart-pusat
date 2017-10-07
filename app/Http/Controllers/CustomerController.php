<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB; 
use Session;
use Laratrust;
use App\Customer;
use App\User;
use App\Role;
use App\KomunitasCustomer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $master_customer = Customer::with(['kelurahan'])->where('tipe_user',3)->get();
            return Datatables::of($master_customer)->addColumn('action', function($customer){
                    return view('datatable._action', [
                        'model'     => $customer,
                        'form_url'  => route('customer.destroy', $customer->id),
                        'edit_url'  => route('customer.edit', $customer->id),
                        'confirm_message'   => 'Anda Yakin Ingin Menghapus ' .$customer->name . ' ?',
                        'permission_ubah' => Laratrust::can('edit_customer'),
                        'permission_hapus' => Laratrust::can('hapus_customer'),

                        ]);
                })
                ->addColumn('komunitas', function($customer){
                  
                    
                    return $customer->komunitas;
                })
                ->addColumn('tgl_lahir', function($tgl_lahir){
                    if ($tgl_lahir->tgl_lahir == "") {
                        $tgl_lahir = "-";
                    }
                    else{
                        $tgl_lahir = $tgl_lahir->tgl_lahir;
                    }
                    
                    return $tgl_lahir;
                })
                ->addColumn('no_telp', function($no_telp){
                    if ($no_telp->no_telp == "") {
                        $no_telp = "-";
                    }
                    else{
                        $no_telp = $no_telp->no_telp;
                    }
                    
                    return $no_telp;
                })->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'email', 'name' => 'email', 'title' => 'No. Telpon']) 
        ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama']) 
        ->addColumn(['data' => 'no_telp', 'name' => 'no_telp', 'title' => 'Email']) 
        ->addColumn(['data' => 'tgl_lahir', 'name' => 'tgl_lahir', 'title' => 'Tanggal Lahir'])
        ->addColumn(['data' => 'alamat', 'name' => 'alamat', 'title' => 'Alamat'])  
        ->addColumn(['data' => 'komunitas', 'name' => 'komunitas', 'title' => 'Komunitas'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable'=>false]);

        return view('customer.index')->with(compact('html'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //UBAH FORMAT TANGGAL - TANGGAL DB


    public function store(Request $request)
    {

        //TANGGAL SQL
        function tanggal_mysql($tanggalDB){
            $date= date_create($tanggalDB);
            return $date_format =  date_format($date,"Y-m-d");    
         }

        $this->validate($request, [
            'name'      => 'required',
            'email'     => 'required|without_spaces|numeric',
            'alamat'    => 'required',
            'no_telp'   => 'without_spaces|unique:users,email',
            'tgl_lahir' => '', 
            'komunitas' => '', 
        ]);


    //INSERT CUSTOMER
        $customer_baru = Customer::create([ 
            'name'              => $request->name,
            'email'             => $request->email, 
            'alamat'            => $request->alamat,
            'no_telp'           => $request->no_telp,
            'tgl_lahir'         => tanggal_mysql($request->tgl_lahir),
            'wilayah'           => $request->kelurahan,
            'tipe_user'         => 3,
            'status_konfirmasi' => 0,
            'komunitas' => $request->komunitas,
            'password'  => bcrypt('123456')
        ]);

    //INSERT OTORITAS CUSTOMER
        $customer_baru->attachRole(3);
        
        $pesan_alert = 
             '<div class="container-fluid">
                  <div class="alert-icon">
                  <i class="material-icons">check</i>
                  </div>
                  <b>Sukses : Berhasil Menambah Customer "'.$request->name.'"</b>
              </div>';

            Session::flash("flash_notification", [
                "level"=>"success",
                "message"=> $pesan_alert
            ]);

            return redirect()->route('customer.index');
           
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

        function tanggal_terbalik($tanggal){
             $date= date_create($tanggal);
             $date_terbalik =  date_format($date,"d/m/Y");
             return $date_terbalik;
        }

        $customer = Customer::find($id);
        $tanggal = tanggal_terbalik($customer->tgl_lahir);
        $komunitas = KomunitasCustomer::where('user_id',$id)->first();


        return view('customer.edit')->with(compact('customer', 'tanggal','komunitas'));
        
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

        //TANGGAL SQL
        function tanggal_mysql($tanggalDB){
            $date= date_create($tanggalDB);
            return $date_format =  date_format($date,"Y-m-d");    
         }

        $this->validate($request, [            
            'name'      => 'required',
            'email'     => 'required|without_spaces|unique:users,email,' .$id,
            'alamat'    => 'required',
            'no_telp'   => 'required|without_spaces|numeric',
            'tgl_lahir' => 'required',
            
        ]);

        Customer::where('id', $id)->update([
            'name'              => $request->name,
            'email'             => $request->email, 
            'alamat'            => $request->alamat,
            'no_telp'           => $request->no_telp,
            'tgl_lahir'         => tanggal_mysql($request->tgl_lahir),
            'wilayah'           => $request->kelurahan,
        ]);

        //hapus komunitas sebelumnya, masukkan komunitas baru
        KomunitasCustomer::where('user_id',$id)->delete();
        KomunitasCustomer::create(['user_id' =>$id ,'komunitas_id' => $request->komunitas]);

        $pesan_alert = 
             '<div class="container-fluid">
                  <div class="alert-icon">
                  <i class="material-icons">check</i>
                  </div>
                  <b>Sukses : Berhasil Mengubah Customer "'.$request->name.'"</b>
              </div>';

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>$pesan_alert
            ]);

        return redirect()->route('customer.index');  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $pesan_alert = 
               '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">check</i>
                    </div>
                    <b>Sukses : Customer Berhasil Dihapus</b>
                </div>';

        Customer::destroy($id);
        KomunitasCustomer::where('user_id',$id)->delete();

        Session:: flash("flash_notification", [
            "level"=>"success",
            "message"=> $pesan_alert
            ]);
        return redirect()->route('customer.index');
    }
}
