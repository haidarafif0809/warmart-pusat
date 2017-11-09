<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use App\Pembelian;
use App\DetailPembelian;
use App\TbsPembelian;
use App\EditTbsPembelian;
use App\Hpp;
use App\User;
use App\Barang;
use App\TransaksiKas;
use App\TransaksiHutang;
use URL;
use Session;
use Auth;

class PembelianTest extends TestCase
{
	use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */

    protected function setUp() {
    	parent::setUp();
        // kode untuk menset base url nya jadi localhost
        //   karena kalau gak localhost jadi tidak bisa jalan testing http nya
        //  selalu responnya 404 
    	URL::forceRootUrl('http://localhost');    
    }

//CRUDTEST
    public function testCrudPembelian()
    {

   	//MEMBUAT NO FAKTUR
    	$warung_id = 1;
    	$no_faktur = Pembelian::no_faktur($warung_id);

    	/*TAMBAH*/
	// TEST INSERT TBS PEMBELIAN
    	$create_tbs_pembelian = TbsPembelian::create([        
    		'id_produk'     => 1,               
    		'session_id'    => session()->getId(), 
    		'jumlah_produk' => 1, 
    		'harga_produk'  => 1000, 
    		'subtotal'      => 1000, 
    		'satuan_id'     => 1, 
    		'warung_id'     => $warung_id]);

	//CEK DATABASE TBS PEMBELIAN
    	$see_tbs_pembelian = $this->assertDatabaseHas('tbs_pembelians',[    		
    		'id_produk'     => 1,               
    		'session_id'    => session()->getId(), 
    		'jumlah_produk' => 1, 
    		'harga_produk'  => 1000, 
    		'subtotal'      => 1000, 
    		'satuan_id'     => 1, 
    		'warung_id'     => $warung_id]);

    // TEST INSERT DETAIL PEMBELIAN
    	$create_detail_pembelian = DetailPembelian::create([          
    		'no_faktur'         => $no_faktur, 
    		'satuan_id'         => 1, 
    		'id_produk'         => 1, 
    		'jumlah_produk'     => 1, 
    		'harga_produk'      => 1000, 
    		'subtotal'          => 1000, 
    		'tax'               => 0, 
    		'potongan'          => 0, 
    		'warung_id'         => $warung_id ]);

    //CEK DATABASE DETAIL PEMBELIAN
    	$see_detail_pembelian = $this->assertDatabaseHas('detail_pembelians',[
    		'no_faktur'         => $no_faktur, 
    		'satuan_id'         => 1, 
    		'id_produk'         => 1, 
    		'jumlah_produk'     => 1, 
    		'harga_produk'      => 1000, 
    		'subtotal'          => 1000, 
    		'tax'               => 0, 
    		'potongan'          => 0, 
    		'warung_id'         => $warung_id ]);

    //CEK DATABASE HPP PEMBELIAN
    	$total_nilai = $create_detail_pembelian->jumlah_produk *  $create_detail_pembelian->harga_produk;
    	$see_hpp = $this->assertDatabaseHas('hpps',[
    		'no_faktur' 		=> $no_faktur, 
    		'id_produk' 		=> $create_detail_pembelian->id_produk, 
    		'jenis_transaksi' 	=> 'pembelian', 
    		'jumlah_masuk' 		=> $create_detail_pembelian->jumlah_produk, 
    		'harga_unit' 		=> $create_detail_pembelian->harga_produk, 
    		'total_nilai' 		=> $total_nilai, 
    		'jenis_hpp' 		=> '1',
    		'warung_id'			=> $warung_id
    	]);

	// TEST INSERT PEMBELIAN
    	$create_pembelian = Pembelian::create([
    		'no_faktur'         => $no_faktur, 
    		'total'             => 1000, 
    		'suplier_id'        => 1, 
    		'status_pembelian'  => 'Tunai', 
    		'potongan'          => 0, 
    		'tunai'             => 1000, 
    		'kembalian'         => 0, 
    		'kredit'            => 0, 
    		'nilai_kredit'      => 0, 
    		'cara_bayar'        => 5, 
    		'status_beli_awal'  => 'Tunai', 
    		'tanggal_jt_tempo'  => '', 
    		'keterangan'        => '-', 
    		'ppn'               => '', 
    		'warung_id'         => $warung_id ]);

	//CEK DATABASE PEMBELIAN
    	$see_pembelian = $this->assertDatabaseHas('pembelians',[ 
    		'no_faktur'         => $no_faktur, 
    		'total'             => 1000, 
    		'suplier_id'        => 1, 
    		'status_pembelian'  => 'Tunai', 
    		'potongan'          => 0, 
    		'tunai'             => 1000, 
    		'kembalian'         => 0, 
    		'kredit'            => 0, 
    		'nilai_kredit'      => 0, 
    		'cara_bayar'        => 5, 
    		'status_beli_awal'  => 'Tunai', 
    		'tanggal_jt_tempo'  => '', 
    		'keterangan'        => '-', 
    		'ppn'               => '', 
    		'warung_id'         => $warung_id]);

    		//CEK DATABASE TRANSAKSI KAS
    	$see_kas_pembelian = $this->assertDatabaseHas('transaksi_kas',[ 
    		'no_faktur'         => $no_faktur, 
    		'jenis_transaksi'   =>'pembelian' , 
    		'jumlah_keluar'     => 1000, 
    		'kas'               => 5, 
    		'warung_id'         => $warung_id]);

    	//CEK DATABASE TRANSAKSI HUTANG
    	$see_hutang_pembelian = $this->assertDatabaseMissing('transaksi_hutangs',[ 
    		'no_faktur'         => $no_faktur]);

    	$hapus_pembelian = Pembelian::destroy($create_pembelian->id);

    	$hapus_detail_pembelian = DetailPembelian::where('no_faktur', $create_pembelian->no_faktur)->where('warung_id', $create_pembelian->warung_id)->delete();
    	$hapus_hpp_keluar = Hpp::where('no_faktur', $create_pembelian->no_faktur)->where('warung_id', $create_pembelian->warung_id)->delete();

    	$this->assertDatabaseMissing('pembelians', [
    		'no_faktur'         => $no_faktur, 
    		'total'             => 1000, 
    		'suplier_id'        => 1, 
    		'status_pembelian'  => 'Tunai', 
    		'potongan'          => 0, 
    		'tunai'             => 1000, 
    		'kembalian'         => 0, 
    		'kredit'            => 0, 
    		'nilai_kredit'      => 0, 
    		'cara_bayar'        => 5, 
    		'status_beli_awal'  => 'Tunai', 
    		'tanggal_jt_tempo'  => '', 
    		'keterangan'        => '-', 
    		'ppn'               => '', 
    		'warung_id'         => $warung_id]);


    }
}
