<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionOrderPembelianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $lihat_order_pembelian  = Permission::create(['name'=> 'lihat_order_pembelian','display_name' => 'Lihat Order Pembelian','grup'=>'order_pembelian']);
      $tambah_order_pembelian = Permission::create(['name'=> 'tambah_order_pembelian','display_name' => 'Tambah Order Pembelian','grup'=>'order_pembelian']);
      $edit_order_pembelian   = Permission::create(['name'=> 'edit_order_pembelian','display_name' => 'Edit Order Pembelian','grup'=>'order_pembelian']);
      $hapus_order_pembelian  = Permission::create(['name'=> 'hapus_order_pembelian','display_name' => 'Hapus Order Pembelian','grup'=>'order_pembelian']);
    }
}
