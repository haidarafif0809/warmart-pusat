<?php

use Illuminate\Database\Seeder;
use App\Permission;
use App\Role;

class TambahPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // KAS
    	$lihat_kas = Permission::create(['name'=> 'lihat_kas','display_name' => 'Lihat Kas','grup'=>'kas']);
    	$tambah_kas = Permission::create(['name'=> 'tambah_kas','display_name' => 'Tambah Kas','grup'=>'kas']);
    	$edit_kas = Permission::create(['name'=> 'edit_kas','display_name' => 'Edit Kas','grup'=>'kas']);
    	$hapus_kas = Permission::create(['name'=> 'hapus_kas','display_name' => 'Hapus Kas','grup'=>'kas']);
        // KATEGORI KAS
    	$lihat_kategori_kas = Permission::create(['name'=> 'lihat_kategori_kas','display_name' => 'Lihat Kategori Kas','grup'=>'kategori_kas']);
    	$tambah_kategori_kas = Permission::create(['name'=> 'tambah_kategori_kas','display_name' => 'Tambah Kategori Kas','grup'=>'kategori_kas']);
    	$edit_kategori_kas = Permission::create(['name'=> 'edit_kategori_kas','display_name' => 'Edit Kategori Kas','grup'=>'kategori_kas']);
    	$hapus_kategori_kas = Permission::create(['name'=> 'hapus_kategori_kas','display_name' => 'Hapus Kategori Kas','grup'=>'kategori_kas']);
        // KAS MASUK
    	$lihat_kas_masuk = Permission::create(['name'=> 'lihat_kas_masuk','display_name' => 'Lihat Kas Masuk','grup'=>'kas_masuk']);
    	$tambah_kas_masuk = Permission::create(['name'=> 'tambah_kas_masuk','display_name' => 'Tambah Kas Masuk','grup'=>'kas_masuk']);
    	$edit_kas_masuk = Permission::create(['name'=> 'edit_kas_masuk','display_name' => 'Edit Kas Masuk','grup'=>'kas_masuk']);
    	$hapus_kas_masuk = Permission::create(['name'=> 'hapus_kas_masuk','display_name' => 'Hapus Kas Masuk','grup'=>'kas_masuk']);
        // KAS KELUAR
    	$lihat_kas_keluar = Permission::create(['name'=> 'lihat_kas_keluar','display_name' => 'Lihat Kas Keluar','grup'=>'kas_keluar']);
    	$tambah_kas_keluar = Permission::create(['name'=> 'tambah_kas_keluar','display_name' => 'Tambah Kas Keluar','grup'=>'kas_keluar']);
    	$edit_kas_keluar = Permission::create(['name'=> 'edit_kas_keluar','display_name' => 'Edit Kas Keluar','grup'=>'kas_keluar']);
    	$hapus_kas_keluar = Permission::create(['name'=> 'hapus_kas_keluar','display_name' => 'Hapus Kas Keluar','grup'=>'kas_keluar']);
        // KAS MUTASI
    	$lihat_kas_mutasi = Permission::create(['name'=> 'lihat_kas_mutasi','display_name' => 'Lihat Kas Mutasi','grup'=>'kas_mutasi']);
    	$tambah_kas_mutasi = Permission::create(['name'=> 'tambah_kas_mutasi','display_name' => 'Tambah Kas Mutasi','grup'=>'kas_mutasi']);
    	$edit_kas_mutasi = Permission::create(['name'=> 'edit_kas_mutasi','display_name' => 'Edit Kas Mutasi','grup'=>'kas_mutasi']);
    	$hapus_kas_mutasi = Permission::create(['name'=> 'hapus_kas_mutasi','display_name' => 'Hapus Kas Mutasi','grup'=>'kas_mutasi']);
        // PRODUK
    	$lihat_produk = Permission::create(['name'=> 'lihat_produk','display_name' => 'Lihat Produk','grup'=>'produk']);
    	$tambah_produk = Permission::create(['name'=> 'tambah_produk','display_name' => 'Tambah Produk','grup'=>'produk']);
    	$edit_produk = Permission::create(['name'=> 'edit_produk','display_name' => 'Edit Produk','grup'=>'produk']);
    	$hapus_produk = Permission::create(['name'=> 'hapus_produk','display_name' => 'Hapus Produk','grup'=>'produk']);
        // ITEM MASUK
    	$lihat_item_masuk = Permission::create(['name'=> 'lihat_item_masuk','display_name' => 'Lihat Item Masuk','grup'=>'item_masuk']);
    	$tambah_item_masuk = Permission::create(['name'=> 'tambah_item_masuk','display_name' => 'Tambah Item Masuk','grup'=>'item_masuk']);
    	$edit_item_masuk = Permission::create(['name'=> 'edit_item_masuk','display_name' => 'Edit Item Masuk','grup'=>'item_masuk']);
    	$hapus_item_masuk = Permission::create(['name'=> 'hapus_item_masuk','display_name' => 'Hapus Item Masuk','grup'=>'item_masuk']);
        // ITEM KELUAR
    	$lihat_item_keluar = Permission::create(['name'=> 'lihat_item_keluar','display_name' => 'Lihat Item Keluar','grup'=>'item_keluar']);
    	$tambah_item_keluar = Permission::create(['name'=> 'tambah_item_keluar','display_name' => 'Tambah Item Keluar','grup'=>'item_keluar']);
    	$edit_item_keluar = Permission::create(['name'=> 'edit_item_keluar','display_name' => 'Edit Item Keluar','grup'=>'item_keluar']);
    	$hapus_item_keluar = Permission::create(['name'=> 'hapus_item_keluar','display_name' => 'Hapus Item Keluar','grup'=>'item_keluar']);
        // STOK OPNAME
    	$lihat_stok_opname = Permission::create(['name'=> 'lihat_stok_opname','display_name' => 'Lihat Stok Opname','grup'=>'stok_opname']);
    	$tambah_stok_opname = Permission::create(['name'=> 'tambah_stok_opname','display_name' => 'Tambah Stok Opname','grup'=>'stok_opname']);
    	$edit_stok_opname = Permission::create(['name'=> 'edit_stok_opname','display_name' => 'Edit Stok Opname','grup'=>'stok_opname']);
    	$hapus_stok_opname = Permission::create(['name'=> 'hapus_stok_opname','display_name' => 'Hapus Stok Opname','grup'=>'stok_opname']);
        // LAPORAN PERSEDIAAN
    	$lihat_laporan_persediaan = Permission::create(['name'=> 'lihat_laporan_persediaan','display_name' => 'Lihat Laporan Persediaan','grup'=>'laporan_persediaan']);
        // STOK OPNAME
    	$lihat_pembelian = Permission::create(['name'=> 'lihat_pembelian','display_name' => 'Lihat Pembelian','grup'=>'pembelian']);
    	$tambah_pembelian = Permission::create(['name'=> 'tambah_pembelian','display_name' => 'Tambah Pembelian','grup'=>'pembelian']);
    	$edit_pembelian = Permission::create(['name'=> 'edit_pembelian','display_name' => 'Edit Pembelian','grup'=>'pembelian']);
    	$hapus_pembelian = Permission::create(['name'=> 'hapus_pembelian','display_name' => 'Hapus Pembelian','grup'=>'pembelian']);
        // PESANAN
    	$lihat_pesanan = Permission::create(['name'=> 'lihat_pesanan','display_name' => 'Lihat Pesanan','grup'=>'pesanan']);
    	$tambah_pesanan = Permission::create(['name'=> 'tambah_pesanan','display_name' => 'Tambah Pesanan','grup'=>'pesanan']);
    	$edit_pesanan = Permission::create(['name'=> 'edit_pesanan','display_name' => 'Edit Pesanan','grup'=>'pesanan']);
    	$hapus_pesanan = Permission::create(['name'=> 'hapus_pesanan','display_name' => 'Hapus Pesanan','grup'=>'pesanan']);
        // PENJUALAN
    	$lihat_penjualan = Permission::create(['name'=> 'lihat_penjualan','display_name' => 'Lihat Penjualan','grup'=>'penjualan']);
    	$tambah_penjualan = Permission::create(['name'=> 'tambah_penjualan','display_name' => 'Tambah Penjualan','grup'=>'penjualan']);
    	$edit_penjualan = Permission::create(['name'=> 'edit_penjualan','display_name' => 'Edit Penjualan','grup'=>'penjualan']);
    	$hapus_penjualan = Permission::create(['name'=> 'hapus_penjualan','display_name' => 'Hapus Penjualan','grup'=>'penjualan']);
        // PEMBAYARAN PIUTANG
    	$lihat_pembayaran_piutang = Permission::create(['name'=> 'lihat_pembayaran_piutang','display_name' => 'Lihat Pembayaran Piutang','grup'=>'pembayaran_piutang']);
    	$tambah_pembayaran_piutang = Permission::create(['name'=> 'tambah_pembayaran_piutang','display_name' => 'Tambah Pembayaran Piutang','grup'=>'pembayaran_piutang']);
    	$edit_pembayaran_piutang = Permission::create(['name'=> 'edit_pembayaran_piutang','display_name' => 'Edit Pembayaran Piutang','grup'=>'pembayaran_piutang']);
    	$hapus_pembayaran_piutang = Permission::create(['name'=> 'hapus_pembayaran_piutang','display_name' => 'Hapus Pembayaran Piutang','grup'=>'pembayaran_piutang']);
        // PEMBAYARAN HUTANG
    	$lihat_pembayaran_hutang = Permission::create(['name'=> 'lihat_pembayaran_hutang','display_name' => 'Lihat Pembayaran Hutang','grup'=>'pembayaran_hutang']);
    	$tambah_pembayaran_hutang = Permission::create(['name'=> 'tambah_pembayaran_hutang','display_name' => 'Tambah Pembayaran Hutang','grup'=>'pembayaran_hutang']);
    	$edit_pembayaran_hutang = Permission::create(['name'=> 'edit_pembayaran_hutang','display_name' => 'Edit Pembayaran Hutang','grup'=>'pembayaran_hutang']);
    	$hapus_pembayaran_hutang = Permission::create(['name'=> 'hapus_pembayaran_hutang','display_name' => 'Hapus Pembayaran Hutang','grup'=>'pembayaran_hutang']);
        // LAPORAN
    	$bucket_size = Permission::create(['name'=> 'lihat_bucket_size','display_name' => 'Bucket Size','grup'=>'laporan']);
    	$jam_transaksi_penjualan = Permission::create(['name'=> 'jam_transaksi_penjualan','display_name' => 'Jam Transaksi Penjualan','grup'=>'laporan']);
    	$laba_kotor_perpelanggan = Permission::create(['name'=> 'laba_kotor_perpelanggan','display_name' => 'Laba Kotor/Pelanggan','grup'=>'laporan']);
    	$laba_kotor_perproduk = Permission::create(['name'=> 'laba_kotor_perproduk','display_name' => 'Laba Kotor/Produk','grup'=>'laporan']);
    	$kartu_stok = Permission::create(['name'=> 'kartu_stok','display_name' => 'Kartu Stok','grup'=>'laporan']);
    	$kas = Permission::create(['name'=> 'kas','display_name' => 'Kas','grup'=>'laporan']);
    	$mutasi_stok = Permission::create(['name'=> 'mutasi_stok','display_name' => 'Mutasi Stok','grup'=>'laporan']);
    	$pembelian_perproduk = Permission::create(['name'=> 'pembelian_perproduk','display_name' => 'Pembelian/Produk','grup'=>'laporan']);
    	$hutang_beredar = Permission::create(['name'=> 'hutang_beredar','display_name' => 'Hutang Beredar','grup'=>'laporan']);
    	$penjualan = Permission::create(['name'=> 'penjualan','display_name' => 'Penjualan','grup'=>'laporan']);
    	$penjualan_harian = Permission::create(['name'=> 'penjualan_harian','display_name' => 'Penjualan Harian','grup'=>'laporan']);
    	$penjualan_perproduk = Permission::create(['name'=> 'penjualan_perproduk','display_name' => 'Penjualan /Produk','grup'=>'laporan']);
    	$penjualan_perpelanggan = Permission::create(['name'=> 'penjualan_perpelanggan','display_name' => 'Penjualan /Pelanggan','grup'=>'laporan']);
    	$penjualan_terbaik_perproduk = Permission::create(['name'=> 'penjualan_terbaik_perproduk','display_name' => 'Penjualan Terbaik /Produk','grup'=>'laporan']);
        //KELOMPOK PRODUK
    	$tambah_kelompok_produk = Permission::create(['name'=> 'tambah_kelompok_produk','display_name' => 'Tambah Kelompok Produk','grup'=>'kelompok_produk']);
    	$edit_kelompok_produk = Permission::create(['name'=> 'edit_kelompok_produk','display_name' => 'Edit Kelompok Produk','grup'=>'kelompok_produk']);
    	$hapus_kelompok_produk = Permission::create(['name'=> 'hapus_kelompok_produk','display_name' => 'Hapus Kelompok Produk','grup'=>'kelompok_produk']);
    	$lihat_kelompok_produk = Permission::create(['name'=> 'lihat_kelompok_produk','display_name' => 'Lihat Kelompok Produk','grup'=>'kelompok_produk']);
        //SATUAN
    	$tambah_satuan = Permission::create(['name'=> 'tambah_satuan','display_name' => 'Tambah Satuan','grup'=>'satuan']);
    	$edit_satuan = Permission::create(['name'=> 'edit_satuan','display_name' => 'Edit Satuan','grup'=>'satuan']);
    	$hapus_satuan = Permission::create(['name'=> 'hapus_satuan','display_name' => 'Hapus Satuan','grup'=>'satuan']);
    	$lihat_satuan = Permission::create(['name'=> 'lihat_satuan','display_name' => 'Lihat Satuan','grup'=>'satuan']);
        //SUPPLIER
    	$tambah_supplier = Permission::create(['name'=> 'tambah_supplier','display_name' => 'Tambah Supplier','grup'=>'supplier']);
    	$edit_supplier = Permission::create(['name'=> 'edit_supplier','display_name' => 'Edit Supplier','grup'=>'supplier']);
    	$hapus_supplier = Permission::create(['name'=> 'hapus_supplier','display_name' => 'Hapus Supplier','grup'=>'supplier']);
    	$lihat_supplier = Permission::create(['name'=> 'lihat_supplier','display_name' => 'Lihat Supplier','grup'=>'supplier']);
        //SETTING
    	$setting_footer = Permission::create(['name'=> 'setting_footer','display_name' => 'Setting Footer','grup'=>'setting']);
    	$setting_pengiriman = Permission::create(['name'=> 'setting_pengiriman','display_name' => 'Setting Pengiriman','grup'=>'setting']);
    	$seetting_verifikasi = Permission::create(['name'=> 'seetting_verifikasi','display_name' => 'Setting Verifikasi','grup'=>'setting']);

    	$role = Role::find(1);
    	$role->attachPermissions([
    		$lihat_kas,$tambah_kas,$edit_kas,$hapus_kas,$lihat_kategori_kas,$tambah_kategori_kas,$edit_kategori_kas,$hapus_kategori_kas,$lihat_kas_masuk,$tambah_kas_masuk,$edit_kas_masuk,$hapus_kas_masuk,$lihat_kas_keluar,$tambah_kas_keluar,$edit_kas_keluar,$hapus_kas_keluar,$lihat_kas_mutasi,$tambah_kas_mutasi,$edit_kas_mutasi,$hapus_kas_mutasi,$lihat_produk,$tambah_produk,$edit_produk,$hapus_produk,$lihat_item_masuk,$tambah_item_masuk,$edit_item_masuk,$hapus_item_masuk,$lihat_item_keluar,$tambah_item_keluar,$edit_item_keluar,$hapus_item_keluar,$lihat_stok_opname,$tambah_stok_opname,$edit_stok_opname,$hapus_stok_opname,$lihat_laporan_persediaan,$lihat_pembelian,$tambah_pembelian,$edit_pembelian,$hapus_pembelian,$lihat_pesanan,$tambah_pesanan,$edit_pesanan,$hapus_pesanan,$lihat_penjualan,$tambah_penjualan,$edit_penjualan,$hapus_penjualan,$lihat_pembayaran_piutang,$tambah_pembayaran_piutang,$edit_pembayaran_piutang,$hapus_pembayaran_piutang,$lihat_pembayaran_hutang,$tambah_pembayaran_hutang,$edit_pembayaran_hutang,$hapus_pembayaran_hutang,$bucket_size,$jam_transaksi_penjualan,$laba_kotor_perpelanggan,$laba_kotor_perproduk,$kartu_stok,$kas,$mutasi_stok,$pembelian_perproduk,$hutang_beredar,$penjualan,$penjualan_harian,$penjualan_perproduk,$penjualan_perpelanggan,$penjualan_terbaik_perproduk,$tambah_kelompok_produk,$edit_kelompok_produk,$hapus_kelompok_produk,$lihat_kelompok_produk,$tambah_satuan,$edit_satuan,$hapus_satuan,$lihat_satuan,$tambah_supplier,$edit_supplier,$hapus_supplier,$lihat_supplier,$setting_footer,$setting_pengiriman,$seetting_verifikasi
    	]);

    }
}
