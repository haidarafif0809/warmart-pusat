<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionPenerimaanProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $lihat_penerimaan_produk  = Permission::create(['name'=> 'lihat_penerimaan_produk','display_name' => 'Lihat Penerimaan','grup'=>'penerimaan_produk']);
      $tambah_penerimaan_produk = Permission::create(['name'=> 'tambah_penerimaan_produk','display_name' => 'Tambah Penerimaan','grup'=>'penerimaan_produk']);
      $edit_penerimaan_produk   = Permission::create(['name'=> 'edit_penerimaan_produk','display_name' => 'Edit Penerimaan','grup'=>'penerimaan_produk']);
      $hapus_penerimaan_produk  = Permission::create(['name'=> 'hapus_penerimaan_produk','display_name' => 'Hapus Penerimaan','grup'=>'penerimaan_produk']);
    }
}
