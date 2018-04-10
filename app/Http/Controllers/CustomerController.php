<?php

namespace App\Http\Controllers;

use App\Penjualan;
use App\PenjualanPos;
use App\PesananPelanggan;
use App\Customer;
use App\Komunitas;
use App\KomunitasCustomer;
use App\SettingAplikasi;
use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Laratrust;
use Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();
        if ($setting_aplikasi->tipe_aplikasi == 0) {
            $this->middleware('user-must-admin');
        } else {
            $this->middleware('user-must-topos');
        }
    }

    public function paginationData($customer, $array, $url)
    {
    //DATA PAGINATION
        $respons['current_page']   = $customer->currentPage();
        $respons['data']           = $array;
        $respons['otoritas']      = $this->otoritasCustomer();
        $respons['first_page_url'] = url($url . '?page=' . $customer->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $customer->lastPage();
        $respons['last_page_url']  = url($url . '?page=' . $customer->lastPage());
        $respons['next_page_url']  = $customer->nextPageUrl();
        $respons['path']           = url($url);
        $respons['per_page']       = $customer->perPage();
        $respons['prev_page_url']  = $customer->previousPageUrl();
        $respons['to']             = $customer->perPage();
        $respons['total']          = $customer->total();
    //DATA PAGINATION

        return $respons;
    }
    public function paginationPencarianData($customer, $array, $url, $search)
    {
    //DATA PAGINATION
        $respons['current_page']   = $customer->currentPage();
        $respons['data']           = $array;
        $respons['otoritas']      = $this->otoritasCustomer();
        $respons['first_page_url'] = url($url . '?page=' . $customer->firstItem() . '&search=' . $search);
        $respons['from']           = 1;
        $respons['last_page']      = $customer->lastPage();
        $respons['last_page_url']  = url($url . '?page=' . $customer->lastPage() . '&search=' . $search);
        $respons['next_page_url']  = $customer->nextPageUrl();
        $respons['path']           = url($url);
        $respons['per_page']       = $customer->perPage();
        $respons['prev_page_url']  = $customer->previousPageUrl();
        $respons['to']             = $customer->perPage();
        $respons['total']          = $customer->total();
    //DATA PAGINATION

        return $respons;
    }

    public function index(Request $request, Builder $htmlBuilder)
    {
        return view('customer.index')->with(compact('html'));
    }

    public function view()
    {

        $customer = Customer::where('tipe_user', 3)->orderBy('created_at', 'DESC')->paginate(10);
        $array_customer = array();

        foreach ($customer as $customers) {
           array_push($array_customer, [            
            'customer'   => $customers,
            'status_pelanggan' => $this->cekStatusPelanggan($customers->id)
        ]);
       }


       $url     = '/customer/view';
       $respons = $this->paginationData($customer, $array_customer, $url);

       return response()->json($respons);

   }

   public function cekStatusPelanggan($pelanggan){  

    if (PenjualanPos::where('pelanggan_id',$pelanggan)->count() > 0) {
        return 1;
    }elseif (PesananPelanggan::where('id_pelanggan',$pelanggan)->count() > 0) {
        return 1;
    }elseif (Penjualan::where('id_pelanggan',$pelanggan)->count() > 0) {
        return 1;
    }else{
        return 0;
    }
}

public function pencarian(Request $request)
{
    $customer = Customer::where('tipe_user', 3)->where(function ($query) use ($request) {
        $query->orwhere('name', 'LIKE', "%$request->search%")
        ->orWhere('alamat', 'LIKE', "%$request->search%")
        ->orWhere('kode_pelanggan', 'LIKE', "%$request->search%")
        ->orWhere('wilayah', 'LIKE', "%$request->search%")
        ->orWhere('no_telp', 'LIKE', "%$request->search%")
        ->orWhere('tgl_lahir', 'LIKE', "%$request->search%");
    })
    ->paginate(10);

    $array_customer = array();

    foreach ($customer as $customers) {
       array_push($array_customer, [            
        'customer'   => $customers,
        'status_pelanggan' => $this->cekStatusPelanggan($customers->id)
    ]);
   }


   $url     = '/customer/pencarian';
   $respons = $this->paginationData($customer, $array_customer, $url);

   return response()->json($respons);
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // PILIH KOMUNITAS (FORM INPUT)
        $komunitas = Komunitas::where('tipe_user', 2)->where('konfirmasi_admin', 1)->pluck('name', 'id');
        return view('customer.create', ['komunitas' => $komunitas]);
    }

    //PROSES PILIH KOMUNITAS
    public function pilih_komunitas()
    {
        $komunitas = Komunitas::where('tipe_user', 2)->get();
        return response()->json($komunitas);
    }

    //SETTING APLIKASI
    public function settingAplikasi()
    {
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();
        return $setting_aplikasi;
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
            'name'              => 'required',
            'email'             => 'nullable|unique:users,email',
            'kode_customer'    => 'nullable|unique:users,kode_pelanggan|max:50',
            'alamat'            => 'required',
            'no_telp'           => 'without_spaces|unique:users,no_telp|numeric',
            'tgl_lahir'         => 'required|date',
            'komunitas'         => 'nullable'
        ]);

        if ($setting_aplikasi = $this->settingAplikasi()->tipe_aplikasi == 0) {
            $status_konfirmasi = 0;
        } else {
            $status_konfirmasi = 1;
        }
        //INSERT CUSTOMER
        $customer_baru = Customer::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'kode_pelanggan'    => $request->kode_customer,
            'alamat'            => $request->alamat,
            'no_telp'           => $request->no_telp,
            'tgl_lahir'         => $request->tgl_lahir,
            'tipe_user'         => 3,
            'status_konfirmasi' => $status_konfirmasi,
            'password'          => bcrypt('123456'),
        ]);

        //INSERT OTORITAS CUSTOMER
        $customer_baru->attachRole(3);

        if (isset($request->komunitas)) {
            KomunitasCustomer::create(['user_id' => $customer_baru->id, 'komunitas_id' => $request->komunitas]);
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
      $customer = Customer::select('name','no_telp','email','alamat','tgl_lahir','kode_pelanggan as kode_customer')->where('id',$id)->first();
      $cek_komunitas = KomunitasCustomer::where('user_id', $id)->count();
      if ($cek_komunitas > 0) {
        $komunitas                = KomunitasCustomer::where('user_id', $id)->first();
        $customer['komunitas_id'] = $komunitas->komunitas_id;
    } else {
        $customer['komunitas_id'] = '';
    }
    $customer['setting_aplikasi'] = $this->settingAplikasi();
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

        return url('customer#/edit_customer/' . $id);
        // $tanggal = $customer->tgl_lahir;
        // $komunitas = KomunitasCustomer::where('user_id',$id)->first();

        // return view('customer.edit')->with(compact('customer', 'tanggal','komunitas'));

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
            'email'     => 'nullable|without_spaces|unique:users,email,' . $id,
            'kode_customer'    => 'nullable|unique:users,kode_pelanggan,' . $id.'|max:50',
            'alamat'    => 'required',
            'no_telp'   => 'required|without_spaces|numeric|unique:users,no_telp,' . $id,
            'tgl_lahir' => 'required|date',

        ]);

        Customer::find($id)->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'kode_pelanggan'    => $request->kode_customer,
            'alamat'    => $request->alamat,
            'no_telp'   => $request->no_telp,
            'tgl_lahir' => $request->tgl_lahir,
            'wilayah'   => $request->kelurahan,
        ]);

        //hapus komunitas sebelumnya, masukkan komunitas baru
        KomunitasCustomer::where('user_id', $id)->delete();
        if (isset($request->komunitas)) {
            KomunitasCustomer::create(['user_id' => $id, 'komunitas_id' => $request->komunitas]);
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
        KomunitasCustomer::where('user_id', $id)->delete();

        $hapus_customer = Customer::destroy($id);
        return response(200);
    }

    //DETAIL CUSTOMER
    public function view_detail($id)
    {
        $data_customer = Customer::with('komunitas_customer')->where('tipe_user', 3)->where('id', $id)->first();
        if (KomunitasCustomer::where('user_id', $id)->count() > 0) {
            $data_komunitas = Komunitas::select('name')->where('id', $data_customer->komunitas_customer->komunitas_id)->first();
            $komunitas      = $data_komunitas->name;
        } else {
            $komunitas = "Tidak Ada Komunitas";
        }

        $customer['name']      = $data_customer->name;
        $customer['email']     = $data_customer->email;
        $customer['alamat']    = $data_customer->alamat;
        $customer['no_telp']   = $data_customer->no_telp;
        $customer['tgl_lahir'] = $data_customer->tgl_lahir;
        $customer['komunitas'] = $komunitas;

        return response()->json([ 
            "customer" => $customer, 
            "setting_aplikasi"     => $this->settingAplikasi()->tipe_aplikasi
        ]);
    }

    public function otoritasCustomer(){

        if (Laratrust::can('tambah_customer')) {
            $tambah_customer = 1;
        }else{
            $tambah_customer = 0;            
        }
        if (Laratrust::can('edit_customer')) {
            $edit_customer = 1;
        }else{
            $edit_customer = 0;            
        }
        if (Laratrust::can('hapus_customer')) {
            $hapus_customer = 1;
        }else{
            $hapus_customer = 0;            
        }
        $respons['tambah_customer'] = $tambah_customer;
        $respons['edit_customer'] = $edit_customer;
        $respons['hapus_customer'] = $hapus_customer;

        return response()->json($respons);
    }

    public function cetakCustomer($no_telp){
        return view('customer.cetak',['no_telp'=>$no_telp]);
    }
}
