<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionReturPembelianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $lihat_retur_pembelian  = Permission::create(['name'=> 'lihat_retur_pembelian','display_name' => 'Lihat Retur Pembelian','grup'=>'retur_pembelian']);
      $tambah_retur_pembelian = Permission::create(['name'=> 'tambah_retur_pembelian','display_name' => 'Tambah Retur Pembelian','grup'=>'retur_pembelian']);
      $edit_retur_pembelian   = Permission::create(['name'=> 'edit_retur_pembelian','display_name' => 'Edit Retur Pembelian','grup'=>'retur_pembelian']);
      $hapus_retur_pembelian  = Permission::create(['name'=> 'hapus_retur_pembelian','display_name' => 'Hapus Retur Pembelian','grup'=>'retur_pembelian']);
    }
}
