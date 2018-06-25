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
     $permission_master_data = Permission::where('grup','master_data')->get();
     $permission_item_masuk = Permission::where('grup','item_masuk')->get();
     $permission_item_keluar = Permission::where('grup','item_keluar')->get();
     $permission_kas = Permission::where('grup','kas')->get();
     $permission_kas_masuk = Permission::where('grup','kas_masuk')->get();
     $permission_kas_keluar = Permission::where('grup','kas_keluar')->get();
     $permission_kas_mutasi = Permission::where('grup','kas_mutasi')->get();
     $permission_kategori_transaksi = Permission::where('grup','kategori_transaksi')->get();
     $permission_kelompok_produk = Permission::where('grup','kelompok_produk')->get();
     $permission_laporan = Permission::where('grup','laporan')->get();
     $permission_laporan_persediaan = Permission::where('grup','laporan_persediaan')->get();
     $permission_pembayaran_piutang = Permission::where('grup','pembayaran_piutang')->get();
     $permission_pembayaran_hutang = Permission::where('grup','pembayaran_hutang')->get();
     $permission_pembelian = Permission::where('grup','pembelian')->get();
     $permission_penjualan = Permission::where('grup','penjualan')->get();
     $permission_pesanan = Permission::where('grup','pesanan')->get();
     $permission_produk = Permission::where('grup','produk')->get();
     $permission_satuan = Permission::where('grup','satuan')->get();
     $permission_setting = Permission::where('grup','setting')->get();
     $permission_stok_opname = Permission::where('grup','stok_opname')->get();
     $permission_supplier = Permission::where('grup','supplier')->get();
     $permission_setting_promo = Permission::where('grup','setting_promo')->get();
     $permission_retur_pembelian = Permission::where('grup','retur_pembelian')->get();
     $permission_order_pembelian = Permission::where('grup','order_pembelian')->get();
     $permission_penerimaan_produk = Permission::where('grup','penerimaan_produk')->get();
     $otoritas = Role::where('id',$id)->first();

     $arrayPermissionUser = array();
     $arrayPermissionOtoritas = array();
     $arrayPermissionBank = array();
     $arrayPermissionCustomer = array();
     $arrayPermissionMasterData = array();
     $arrayPermissionItemMasuk = array();
     $arrayPermissionItemKeluar = array();
     $arrayPermissionKas = array();
     $arrayPermissionKasMasuk = array();
     $arrayPermissionKasKeluar = array();
     $arrayPermissionKasMutasi = array();
     $arrayPermissionKategoriTransaksi = array();
     $arrayPermissionKelompokProduk = array();
     $arrayPermissionLaporan = array();
     $arrayPermissionLaporanPersediaan = array();
     $arrayPermissionPembayaranPiutang = array();
     $arrayPermissionPembelian = array();
     $arrayPermissionPembayaranHutang = array();
     $arrayPermissionPenjualan = array();
     $arrayPermissionPesanan = array();
     $arrayPermissionProduk = array();
     $arrayPermissionSatuan = array();
     $arrayPermissionSetting = array();
     $arrayPermissionStokOpname = array();
     $arrayPermissionSupplier = array();
     $arrayPermissionSettingPromo = array();
     $arrayPermissionReturPembelian = [];
     $arrayPermissionOrderPembelian = [];
     $arrayPermissionPenerimaanProduk = [];

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
     if ($permission_roles->permissions->grup == "master_data") {
       array_push($arrayPermissionMasterData, $permission_roles->permission_id);
     }
     if ($permission_roles->permissions->grup == "item_masuk"){
       array_push($arrayPermissionItemMasuk, $permission_roles->permission_id);
     }
     if ($permission_roles->permissions->grup == "item_keluar"){
       array_push($arrayPermissionItemKeluar, $permission_roles->permission_id);
     }
     if ($permission_roles->permissions->grup == "kas"){
       array_push($arrayPermissionKas, $permission_roles->permission_id);
     }
     if ($permission_roles->permissions->grup == "kas_masuk"){
       array_push($arrayPermissionKasMasuk, $permission_roles->permission_id);
     }
     if ($permission_roles->permissions->grup == "kas_keluar"){
       array_push($arrayPermissionKasKeluar, $permission_roles->permission_id);
     }
     if ($permission_roles->permissions->grup == "kas_mutasi"){
       array_push($arrayPermissionKasMutasi, $permission_roles->permission_id);
     }
     if ($permission_roles->permissions->grup == "kategori_transaksi"){
      array_push($arrayPermissionKategoriTransaksi, $permission_roles->permission_id);
    }
    if ($permission_roles->permissions->grup == "kelompok_produk"){
     array_push($arrayPermissionKelompokProduk, $permission_roles->permission_id);
   }
   if ($permission_roles->permissions->grup == "laporan"){
     array_push($arrayPermissionLaporan, $permission_roles->permission_id);
   }
   if ($permission_roles->permissions->grup == "laporan_persediaan"){
     array_push($arrayPermissionLaporanPersediaan, $permission_roles->permission_id);
   }
   if ($permission_roles->permissions->grup == "pembayaran_piutang"){
     array_push($arrayPermissionPembayaranPiutang, $permission_roles->permission_id);
   }
   if ($permission_roles->permissions->grup == "pembelian"){
     array_push($arrayPermissionPembelian, $permission_roles->permission_id);
   }
   if ($permission_roles->permissions->grup == "pembayaran_hutang"){
     array_push($arrayPermissionPembayaranHutang, $permission_roles->permission_id);
   }
   if ($permission_roles->permissions->grup == "penjualan"){
     array_push($arrayPermissionPenjualan, $permission_roles->permission_id);
   }
   if ($permission_roles->permissions->grup == "pesanan"){
     array_push($arrayPermissionPesanan, $permission_roles->permission_id);
   }
   if ($permission_roles->permissions->grup == "produk"){
     array_push($arrayPermissionProduk, $permission_roles->permission_id);
   }
   if ($permission_roles->permissions->grup == "satuan"){
     array_push($arrayPermissionSatuan, $permission_roles->permission_id);
   }
   if ($permission_roles->permissions->grup == "setting"){
     array_push($arrayPermissionSetting, $permission_roles->permission_id);
   }
   if ($permission_roles->permissions->grup == "stok_opname"){
     array_push($arrayPermissionStokOpname, $permission_roles->permission_id);
   }
   if ($permission_roles->permissions->grup == "supplier"){
     array_push($arrayPermissionSupplier, $permission_roles->permission_id);
   }
   if ($permission_roles->permissions->grup == "setting_promo"){
     array_push($arrayPermissionSettingPromo, $permission_roles->permission_id);
   }
   if ($permission_roles->permissions->grup == "retur_pembelian"){
     array_push($arrayPermissionReturPembelian, $permission_roles->permission_id);
   }
   if ($permission_roles->permissions->grup == "order_pembelian"){
     array_push($arrayPermissionOrderPembelian, $permission_roles->permission_id);
   }
   if ($permission_roles->permissions->grup == "penerimaan_produk"){
     array_push($arrayPermissionPenerimaanProduk, $permission_roles->permission_id);
   }
 }

 return response()->json([
  "permission_user" => $permission_user,
  "permission_otoritas"     => $permission_otoritas,
  "permission_master_data" => $permission_master_data,
  "permission_bank"     => $permission_bank,
  "permission_customer" => $permission_customer,
  "permission_item_masuk" => $permission_item_masuk,
  "permission_item_keluar" => $permission_item_keluar,
  "permission_kas" => $permission_kas,
  "permission_kas_masuk" => $permission_kas_masuk,
  "permission_kas_keluar" => $permission_kas_keluar,
  "permission_kas_mutasi" => $permission_kas_mutasi,
  "permission_kategori_transaksi" => $permission_kategori_transaksi,
  "permission_kelompok_produk" => $permission_kelompok_produk,
  "permission_laporan" => $permission_laporan,
  "permission_laporan_persediaan" => $permission_laporan_persediaan,
  "permission_pembayaran_piutang" => $permission_pembayaran_piutang,
  "permission_pembayaran_hutang" => $permission_pembayaran_hutang,
  "permission_pembelian" => $permission_pembelian,
  "permission_penjualan" => $permission_penjualan,
  "permission_pesanan" => $permission_pesanan,
  "permission_produk" => $permission_produk,
  "permission_satuan" => $permission_satuan,
  "permission_setting" => $permission_setting,
  "permission_stok_opname" => $permission_stok_opname,
  "permission_supplier" => $permission_supplier,
  "permission_setting_promo" => $permission_setting_promo,
  "permission_retur_pembelian" => $permission_retur_pembelian,
  "permission_order_pembelian" => $permission_order_pembelian,
  "permission_penerimaan_produk" => $permission_penerimaan_produk,

  "otoritas"     => $otoritas,

  "data_permission_user"     => $arrayPermissionUser,
  "data_permission_otoritas"     => $arrayPermissionOtoritas,
  "data_permission_bank"     => $arrayPermissionBank,
  "data_permission_customer"     => $arrayPermissionCustomer,
  "data_permission_master_data"     => $arrayPermissionMasterData,
  "data_permission_item_masuk" => $arrayPermissionItemMasuk,
  "data_permission_item_keluar" => $arrayPermissionItemKeluar,
  "data_permission_kas" => $arrayPermissionKas,
  "data_permission_kas_masuk" => $arrayPermissionKasMasuk,
  "data_permission_kas_keluar" => $arrayPermissionKasKeluar,
  "data_permission_kas_mutasi" => $arrayPermissionKasMutasi,
  "data_permission_kategori_transaksi" => $arrayPermissionKategoriTransaksi,
  "data_permission_kelompok_produk" => $arrayPermissionKelompokProduk,
  "data_permission_laporan" => $arrayPermissionLaporan,
  "data_permission_laporan_persediaan" => $arrayPermissionLaporanPersediaan,
  "data_permission_pembayaran_piutang" => $arrayPermissionPembayaranPiutang,
  "data_permission_pembelian" => $arrayPermissionPembelian,
  "data_permission_pembayaran_hutang" => $arrayPermissionPembayaranHutang,
  "data_permission_penjualan" => $arrayPermissionPenjualan,
  "data_permission_pesanan" => $arrayPermissionPesanan,
  "data_permission_produk" => $arrayPermissionProduk,
  "data_permission_satuan" => $arrayPermissionSatuan,
  "data_permission_setting" => $arrayPermissionSetting,
  "data_permission_stok_opname" => $arrayPermissionStokOpname,
  "data_permission_supplier" => $arrayPermissionSupplier,
  "data_permission_setting_promo" => $arrayPermissionSettingPromo,
  "data_permission_retur_pembelian" => $arrayPermissionReturPembelian,
  "data_permission_order_pembelian" => $arrayPermissionOrderPembelian,
  "data_permission_penerimaan_produk" => $arrayPermissionPenerimaanProduk,
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
    foreach ($request->master_data as $setting_master_data) {
      $permission_master_data = Permission::whereId($setting_master_data)->first();
      $role->attachPermission($permission_master_data);
    }
    foreach ($request->item_masuk as $setting_item_masuk) {
      $permission_item_masuk = Permission::whereId($setting_item_masuk)->first();
      $role->attachPermission($permission_item_masuk);
    }
    foreach ($request->item_keluar as $setting_item_keluar) {
      $permission_item_keluar = Permission::whereId($setting_item_keluar)->first();
      $role->attachPermission($permission_item_keluar);
    }
    foreach ($request->kas as $setting_kas) {
      $permission_kas = Permission::whereId($setting_kas)->first();
      $role->attachPermission($permission_kas);
    }
    foreach ($request->kas_masuk as $setting_kas_masuk) {
      $permission_kas_masuk = Permission::whereId($setting_kas_masuk)->first();
      $role->attachPermission($permission_kas_masuk);
    }
    foreach ($request->kas_keluar as $setting_kas_keluar) {
      $permission_kas_keluar = Permission::whereId($setting_kas_keluar)->first();
      $role->attachPermission($permission_kas_keluar);
    }
    foreach ($request->kas_mutasi as $setting_kas_mutasi) {
      $permission_kas_mutasi = Permission::whereId($setting_kas_mutasi)->first();
      $role->attachPermission($permission_kas_mutasi);
    }
    foreach ($request->kategori_transaksi as $setting_kategori_transaksi) {
      $permission_kategori_transaksi = Permission::whereId($setting_kategori_transaksi)->first();
      $role->attachPermission($permission_kategori_transaksi);
    }
    foreach ($request->kelompok_produk as $setting_kelompok_produk) {
      $permission_kelompok_produk = Permission::whereId($setting_kelompok_produk)->first();
      $role->attachPermission($permission_kelompok_produk);
    }
    foreach ($request->produk as $setting_produk) {
      $permission_produk = Permission::whereId($setting_produk)->first();
      $role->attachPermission($permission_produk);
    }

    foreach ($request->laporan as $setting_laporan) {
      $permission_laporan = Permission::whereId($setting_laporan)->first();
      $role->attachPermission($permission_laporan);
    }
    foreach ($request->laporan_persediaan as $setting_laporan_persediaan) {
      $permission_laporan_persediaan = Permission::whereId($setting_laporan_persediaan)->first();
      $role->attachPermission($permission_laporan_persediaan);
    }
    foreach ($request->pembayaran_piutang as $setting_pembayaran_piutang) {
      $permission_pembayaran_piutang = Permission::whereId($setting_pembayaran_piutang)->first();
      $role->attachPermission($permission_pembayaran_piutang);
    }
    foreach ($request->pembayaran_hutang as $setting_pembayaran_hutang) {
      $permission_pembayaran_hutang = Permission::whereId($setting_pembayaran_hutang)->first();
      $role->attachPermission($permission_pembayaran_hutang);
    }
    foreach ($request->pembelian as $setting_pembelian) {
      $permission_pembelian = Permission::whereId($setting_pembelian)->first();
      $role->attachPermission($permission_pembelian);
    }
    foreach ($request->penjualan as $setting_penjualan) {
      $permission_penjualan = Permission::whereId($setting_penjualan)->first();
      $role->attachPermission($permission_penjualan);
    }
    foreach ($request->pesanan as $setting_pesanan) {
      $permission_pesanan = Permission::whereId($setting_pesanan)->first();
      $role->attachPermission($permission_pesanan);
    }
    foreach ($request->satuan as $setting_satuan) {
      $permission_satuan = Permission::whereId($setting_satuan)->first();
      $role->attachPermission($permission_satuan);
    }
    foreach ($request->setting as $setting_setting) {
      $permission_setting = Permission::whereId($setting_setting)->first();
      $role->attachPermission($permission_setting);
    }
    foreach ($request->stok_opname as $setting_stok_opname) {
      $permission_stok_opname = Permission::whereId($setting_stok_opname)->first();
      $role->attachPermission($permission_stok_opname);
    }
    foreach ($request->supplier as $setting_supplier) {
      $permission_supplier = Permission::whereId($setting_supplier)->first();
      $role->attachPermission($permission_supplier);
    }
    foreach ($request->setting_promo as $setting_setting_promo) {
      $permission_setting_promo = Permission::whereId($setting_setting_promo)->first();
      $role->attachPermission($permission_setting_promo);
    }
    foreach ($request->retur_pembelian as $setting_retur_pembelian) {
      $permission_retur_pembelian = Permission::whereId($setting_retur_pembelian)->first();
      $role->attachPermission($permission_retur_pembelian);
    }
    foreach ($request->order_pembelian as $setting_order_pembelian) {
      $permission_order_pembelian = Permission::whereId($setting_order_pembelian)->first();
      $role->attachPermission($permission_order_pembelian);
    }
    foreach ($request->penerimaan_produk as $setting_penerimaan_produk) {
      $permission_penerimaan_produk = Permission::whereId($setting_penerimaan_produk)->first();
      $role->attachPermission($permission_penerimaan_produk);
    }
  }



  public function paginationData($user, $user_array,  $url)
  {
        //DATA PAGINATION
    $respons['current_page']   = $user->currentPage();
    $respons['data']           = $user_array;
    $respons['otoritas']      = $this->otoritas();
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
  public function paginationPencarianData($user,$array, $url, $search)
  {
        //DATA PAGINATION
    $respons['current_page']   = $user->currentPage();
    $respons['data']           = $array;
    $respons['otoritas']      = $this->otoritas();
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
    $role = Role::whereNotIn('id',[3,4,5])->paginate(10);
    $array       = array();

    foreach ($role as $roles) {
      array_push($array, [
        'id'               => $roles->id,
        'name'            => $roles->name,
        'display_name'        => $roles->display_name,
        'description' => $roles->description
      ]);
    }

    $url     = '/otoritas/view';
    $respons = $this->paginationData($role, $array, $url);

    return response()->json($respons);
  }

  public function pencarian(Request $request){
    $role = Role::whereNotIn('id',[3,4,5])->where(function ($query) use ($request) {
      $query->orWhere('name', 'LIKE', $request->search . '%')
      ->orWhere('display_name', 'LIKE', $request->search . '%')
      ->orWhere('description', 'LIKE', $request->search . '%');
    })->paginate(10);
    $array       = array();

    foreach ($role as $roles) {
      array_push($array, [
        'id'               => $roles->id,
        'name'            => $roles->name,
        'display_name'        => $roles->display_name,
        'description' => $roles->description
      ]);
    }

    $url     = '/otoritas/pencarian';
    $respons = $this->paginationPencarianData($role, $array, $url,$request->search);

    return response()->json($respons);
  }

  public function otoritas(){

    if (Laratrust::can('tambah_otoritas')) {
      $tambah_otoritas = 1;
    }else{
      $tambah_otoritas = 0;
    }
    if (Laratrust::can('edit_otoritas')) {
      $edit_otoritas = 1;
    }else{
      $edit_otoritas = 0;
    }
    if (Laratrust::can('hapus_otoritas')) {
      $hapus_otoritas = 1;
    }else{
      $hapus_otoritas = 0;
    }
    if (Laratrust::can('permission_otoritas')) {
      $permission_otoritas = 1;
    }else{
      $permission_otoritas = 0;
    }
    $respons['tambah_otoritas'] = $tambah_otoritas;
    $respons['edit_otoritas'] = $edit_otoritas;
    $respons['hapus_otoritas'] = $hapus_otoritas;
    $respons['permission_otoritas'] = $permission_otoritas;

    return response()->json($respons);
  }
}
