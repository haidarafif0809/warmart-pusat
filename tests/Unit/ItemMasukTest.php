<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use App\ItemMasuk;
use App\DetailItemMasuk;
use App\TbsItemMasuk;
use App\EditTbsItemMasuk;
use App\Hpp;
use App\User;
use App\Barang;
use URL;
use Session;
use Auth;

class ItemMasukTest extends TestCase
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
    public function testCrudItemMasuk() {
    	
   	//MEMBUAT NO FAKTUR
    	$warung_id = 1;
    	$no_faktur = ItemMasuk::no_faktur($warung_id);

/*TAMBAH*/
	// TEST INSERT TBS ITEM MASUK
	    $create_tbs_item_masuk = TbsItemMasuk::create(['id_produk' => '1', 'session_id' => session()->getId(), 'jumlah_produk' => '1', 'warung_id' => '1']);

	//CEK DATABASE TBS ITEM MASUK
	    $see_tbs_item_masuk = $this->assertDatabaseHas('tbs_item_masuks',['id_produk' => '1', 'session_id' => session()->getId(), 'jumlah_produk' => '1', 'warung_id' => '1']);

    // TEST INSERT DETAIL ITEM MASUK
        $create_detail_item_masuk = DetailItemMasuk::create(['id_produk' => $create_tbs_item_masuk->id_produk, 'no_faktur' => $no_faktur, 'jumlah_produk' => $create_tbs_item_masuk->jumlah_produk, 'warung_id' => $create_tbs_item_masuk->warung_id]);
        
    //CEK DATABASE DETAIL ITEM MASUK
        $see_detail_item_masuk = $this->assertDatabaseHas('detail_item_masuks',['id_produk' => '1', 'no_faktur' => $no_faktur, 'jumlah_produk' => '1', 'warung_id' => '1']);
    //CEK DATABASE HPP ITEM MASUK

		$total_nilai = $create_detail_item_masuk->jumlah_produk *  $create_detail_item_masuk->produk->harga_beli;
        $see_detail_item_masuk = $this->assertDatabaseHas('hpps',[
									'no_faktur' => $create_detail_item_masuk->no_faktur, 
									'id_produk' => $create_detail_item_masuk->id_produk, 
									'jenis_transaksi' => 'item_masuk',
									'jumlah_masuk' => $create_detail_item_masuk->jumlah_produk, 
									'harga_unit' => $create_detail_item_masuk->produk->harga_beli,
									'total_nilai' => $total_nilai, 
									'jenis_hpp' => '1',
                                    'warung_id'           => $create_detail_item_masuk->warung_id, 
                                ]);

	// TEST INSERT ITEM MASUK
	    $create_item_masuk = ItemMasuk::create(['no_faktur' => $no_faktur, 'keterangan' => 'TestCase Item Masuk', 'warung_id' => $create_detail_item_masuk->warung_id]);

	//CEK DATABASE ITEM MASUK
	    $see_item_masuk = $this->assertDatabaseHas('item_masuks',['no_faktur' => $no_faktur, 'keterangan' => 'TestCase Item Masuk', 'warung_id' => $create_detail_item_masuk->warung_id]);

/*HAPUS*/
		$hapus_item_masuk = ItemMasuk::destroy($create_item_masuk->id);

		$hapus_detail_item_masuk = DetailItemMasuk::where('no_faktur', $create_item_masuk->no_faktur)->where('warung_id', $create_item_masuk->warung_id)->delete();
        $hapus_hpp_masuk = Hpp::where('no_faktur', $create_item_masuk->no_faktur)->where('warung_id', $create_item_masuk->warung_id)->delete();

        $hapus_item_masuk = ItemMasuk::find($create_item_masuk->id);
        $this->assertDatabaseMissing('item_masuks', ['no_faktur' => $no_faktur, 'keterangan' => 'TestCase Item Masuk', 'warung_id' => $create_detail_item_masuk->warung_id]);
 
    }
}
