<?php

use Illuminate\Database\Seeder;
use App\Permission;
use App\Role;

class TambahSeederPermissionKategoriTransaksi extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // KATEGORI TRANSAKSI
    	$lihat_kategori_transaksi = Permission::create(['name'=> 'lihat_kategori_transaksi','display_name' => 'Lihat Kategori Transaksi','grup'=>'kategori_transaksi']);
    	$tambah_kategori_transaksi = Permission::create(['name'=> 'tambah_kategori_transaksi','display_name' => 'Tambah Kategori Transaksi','grup'=>'kategori_transaksi']);
    	$edit_kategori_transaksi = Permission::create(['name'=> 'edit_kategori_transaksi','display_name' => 'Edit Kategori Transaksi','grup'=>'kategori_transaksi']);
    	$hapus_kategori_transaksi = Permission::create(['name'=> 'hapus_kategori_transaksi','display_name' => 'Hapus Kategori Transaksi','grup'=>'kategori_transaksi']);
    }
}
