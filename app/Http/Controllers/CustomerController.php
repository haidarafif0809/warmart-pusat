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
use App\Komunitas;
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
      return view('customer.index')->with(compact('html'));
    }

    public function view(){
      $customer = Customer::where('tipe_user', 3)->paginate(10);
      return response()->json($customer);
    }

    public function pencarian(Request $request){
      $customer = Customer::where('tipe_user', 3)->where(function($query) use ($request){
        $query->orwhere('name','LIKE',"%$request->search%")
        ->orWhere('alamat','LIKE',"%$request->search%")
        ->orWhere('wilayah','LIKE',"%$request->search%")
        ->orWhere('no_telp','LIKE',"%$request->search%")
        ->orWhere('tgl_lahir','LIKE',"%$request->search%");
      })
      ->paginate(10);
      return response()->json($customer);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      // PILIH KOMUNITAS (FORM INPUT)
      $komunitas = Komunitas::where('tipe_user', 2)->where('konfirmasi_admin', 1)->pluck('name','id');
      return view('customer.create',['komunitas' => $komunitas]);
    }

    //PROSES PILIH KOMUNITAS
    public function pilih_komunitas(){
      $komunitas = Komunitas::where('tipe_user', 2)->get();
      return response()->json($komunitas);
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

      $this->validate($request, [
        'name'      => 'required',
        'email'     => 'nullable|unique:users,email', 
        'alamat'    => 'required',
        'no_telp'   => 'without_spaces|unique:users,no_telp|numeric',
        'tgl_lahir' => 'date', 
        'komunitas' => '', 
        ]);

      //INSERT CUSTOMER
      $customer_baru = Customer::create([ 
        'name'              => $request->name,
        'email'             => $request->email, 
        'alamat'            => $request->alamat,
        'no_telp'           => $request->no_telp,
        'tgl_lahir'         => $request->tgl_lahir,
        'tipe_user'         => 3,
        'status_konfirmasi' => 0,
        'password'  => bcrypt('123456')
        ]);

      //INSERT OTORITAS CUSTOMER
      $customer_baru->attachRole(3);
      
      if (isset($request->komunitas)) {
        KomunitasCustomer::create(['user_id' =>$customer_baru->id ,'komunitas_id' => $request->komunitas]);
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
      //EDIT CUSTOMER
      $customer = Customer::find($id);
      $cek_komunitas = KomunitasCustomer::where('user_id',$id)->count();
      if ($cek_komunitas > 0) {
        $komunitas = KomunitasCustomer::where('user_id',$id)->first();
        $customer['komunitas_id'] = $komunitas->komunitas_id;
      }
      else{
        $customer['komunitas_id'] = '';
      }

      return $customer;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $customer = Customer::find($id);
      $tanggal = $customer->tgl_lahir;
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
      $this->validate($request, [            
        'name'      => 'required',
        'email'     => 'nullable|without_spaces|unique:users,email,' .$id,
        'alamat'    => 'required',
        'no_telp'   => 'required|without_spaces|numeric|unique:users,no_telp,' .$id,
        'tgl_lahir' => 'required|date',

        ]);

      Customer::find($id)->update([
        'name'              => $request->name,
        'email'             => $request->email, 
        'alamat'            => $request->alamat,
        'no_telp'           => $request->no_telp,
        'tgl_lahir'         => $request->tgl_lahir,
        'wilayah'           => $request->kelurahan,
        ]);

        //hapus komunitas sebelumnya, masukkan komunitas baru
      KomunitasCustomer::where('user_id',$id)->delete();
      if (isset($request->komunitas)) {
        KomunitasCustomer::create(['user_id' =>$id ,'komunitas_id' => $request->komunitas]);
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
    // HAPUS CUSTOMER 
      $customer = Customer::find($id);
      KomunitasCustomer::where('user_id',$id)->delete();

      $hapus_customer = Customer::destroy($id);
      return response(200);
    }

    //DETAIL CUSTOMER
    public function view_detail($id){
      $data_customer = Customer::with('komunitas_customer')->where('tipe_user', 3)->where('id', $id)->first();
      if (KomunitasCustomer::where('user_id', $id)->count() > 0) {
        $data_komunitas = Komunitas::select('name')->where('id', $data_customer->komunitas_customer->komunitas_id)->first();
        $komunitas = $data_komunitas->name;
      }
      else{
        $komunitas = "Tidak Ada Komunitas";
      }

      $customer['name'] = $data_customer->name;
      $customer['email'] = $data_customer->email;
      $customer['alamat'] = $data_customer->alamat;
      $customer['no_telp'] = $data_customer->no_telp;
      $customer['tgl_lahir'] = $data_customer->tgl_lahir;
      $customer['komunitas'] = $komunitas;      
      
      return response()->json($customer);
    }
  }
