<?php

use Illuminate\Database\Seeder;

class PermissionPenerimaanProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $lihat_penerimaan_produk  = Permission::create(['name'=> 'lihat_penerimaan_produk','display_name' => 'Lihat Penerimaan Produk','grup'=>'retur_pembelian']);
      $tambah_penerimaan_produk = Permission::create(['name'=> 'tambah_penerimaan_produk','display_name' => 'Tambah Penerimaan Produk','grup'=>'retur_pembelian']);
      $edit_penerimaan_produk   = Permission::create(['name'=> 'edit_penerimaan_produk','display_name' => 'Edit Penerimaan Produk','grup'=>'retur_pembelian']);
      $hapus_penerimaan_produk  = Permission::create(['name'=> 'hapus_penerimaan_produk','display_name' => 'Hapus Penerimaan Produk','grup'=>'retur_pembelian']);
    }
}
