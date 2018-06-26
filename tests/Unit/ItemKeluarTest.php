<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use App\ItemKeluar;
use App\DetailItemKeluar;
use App\TbsItemKeluar;
use App\EditTbsItemKeluar;
use App\Hpp;
use App\User;
use App\Barang;
use URL;
use Session;
use Auth;

class ItemKeluarTest extends TestCase
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
    public function testCrudItemKeluar() {
    	
   	//MEMBUAT NO FAKTUR
    	$warung_id = 1;
    	$no_faktur = ItemKeluar::no_faktur($warung_id);

/*TAMBAH*/
	// TEST INSERT TBS ITEM KELUAR
	    $create_tbs_item_keluar = TbsItemKeluar::create(['id_produk' => '1', 'session_id' => session()->getId(), 'jumlah_produk' => '1', 'warung_id' => '1']);

	//CEK DATABASE TBS ITEM KELUAR
	    $see_tbs_item_keluar = $this->assertDatabaseHas('tbs_item_keluars',['id_produk' => '1', 'session_id' => session()->getId(), 'jumlah_produk' => '1', 'warung_id' => '1']);

    // TEST INSERT DETAIL ITEM KELUAR
        $create_detail_item_keluar = DetailItemKeluar::create(['id_produk' => $create_tbs_item_keluar->id_produk, 'no_faktur' => $no_faktur, 'jumlah_produk' => $create_tbs_item_keluar->jumlah_produk, 'warung_id' => $create_tbs_item_keluar->warung_id]);
        
    //CEK DATABASE DETAIL ITEM KELUAR
        $see_detail_item_keluar = $this->assertDatabaseHas('detail_item_keluars',['id_produk' => '1', 'no_faktur' => $no_faktur, 'jumlah_produk' => '1', 'warung_id' => '1']);
    //CEK DATABASE HPP ITEM KELUAR
        $hpp_produk = $create_detail_item_keluar->produk->hpp;
        $total_hpp = $hpp_produk * $create_detail_item_keluar->jumlah_produk;
        $see_detail_item_keluar = $this->assertDatabaseHas('hpps',[
                                    'no_faktur'           => $no_faktur,
                                    'id_produk'           => $create_detail_item_keluar->id_produk, 
                                    'jenis_transaksi'     => 'item_keluar', 
                                    'jumlah_keluar'       => $create_detail_item_keluar->jumlah_produk, 
                                    'harga_unit'          => $hpp_produk, 
                                    'total_nilai'         => $total_hpp,
                                    'warung_id'           => $create_detail_item_keluar->warung_id, 
                                    'jenis_hpp'           => 2
                                ]);

	// TEST INSERT ITEM KELUAR
	    $create_item_keluar = ItemKeluar::create(['no_faktur' => $no_faktur, 'keterangan' => 'TestCase Item Keluar', 'warung_id' => '1']);

	//CEK DATABASE ITEM KELUAR
	    $see_item_keluar = $this->assertDatabaseHas('item_keluars',['no_faktur' => $no_faktur, 'keterangan' => 'TestCase Item Keluar', 'warung_id' => '1']);

/*HAPUS*/
		$hapus_item_keluar = ItemKeluar::destroy($create_item_keluar->id);

		$hapus_detail_item_keluar = DetailItemKeluar::where('no_faktur', $create_item_keluar->no_faktur)->where('warung_id', $create_item_keluar->warung_id)->delete();
        $hapus_hpp_keluar = Hpp::where('no_faktur', $create_item_keluar->no_faktur)->where('warung_id', $create_item_keluar->warung_id)->delete();

        $hapus_item_keluar = TbsItemKeluar::find($create_item_keluar->id);
        $this->assertDatabaseMissing('item_keluars', ['no_faktur' => $no_faktur, 'keterangan' => 'TestCase Item Keluar', 'warung_id' => '1']);


    }


//HTTPTEST

    // HTTPTEST TAMBAH ITEM KELUAR
    public function testHTTPTambahItemKeluar() {
    	
   	//MEMBUAT NO FAKTUR
    	$warung_id = 1;
    	$no_faktur = ItemKeluar::no_faktur($warung_id);

	// TEST INSERT TBS ITEM KELUAR
	    $create_tbs_item_keluar = TbsItemKeluar::create(['id_produk' => '1', 'session_id' => session()->getId(), 'jumlah_produk' => '1', 'warung_id' => '1']);

	// TEST INSERT DETAIL ITEM KELUAR
	    $create_detail_item_keluar = DetailItemKeluar::create(['id_produk' => $create_tbs_item_keluar->id_produk, 'no_faktur' => $no_faktur, 'jumlah_produk' => $create_tbs_item_keluar->jumlah_produk, 'warung_id' => $create_tbs_item_keluar->warung_id]);

	//CEK DATABASE TBS ITEM KELUAR
	    $see_tbs_item_keluar = $this->assertDatabaseHas('tbs_item_keluars',['id_produk' => '1', 'session_id' => session()->getId(), 'jumlah_produk' => '1', 'warung_id' => '1']);

	//CEK DATABASE DETAIL ITEM KELUAR
	    $see_detail_item_keluar = $this->assertDatabaseHas('tbs_item_keluars',['id_produk' => '1', 'session_id' => session()->getId(), 'jumlah_produk' => '1', 'warung_id' => '1']);
	//DATA TBS ITEM KELUAR
		$tbs_item_keluar = TbsItemKeluar::where('session_id', session()->getId())->where('warung_id', 1);


	//LOGIN USER WARUNG
	    $user = User::find(5);

        $response = $this->actingAs($user)->json('POST', route('item-keluar.store'), ['keterangan' => 'HttpTest Tambah Item Keluar']);

        $response->assertStatus(302)
	                 ->assertRedirect(route('item-keluar.index'));

	//HAPUS TBS ITEM KELUAR
        $tbs_item_keluar->delete();

	// TEST INSERT ITEM KELUAR
	    $create_item_keluar = ItemKeluar::create(['no_faktur' => $no_faktur, 'keterangan' => 'TestCase Item Keluar', 'warung_id' => '1']);

	//HASIL
		$hasil = $this->get($response->headers->get('location'))->assertSee('Sukses : Berhasil Melakukan Transaksi Item Keluar Faktur "'.$no_faktur.'"');		  

	//CEK DATABASE ITEM KELUAR
	    $see_item_keluar = $this->assertDatabaseHas('item_keluars',['no_faktur' => $no_faktur, 'warung_id' => '1', 'keterangan' => 'HttpTest Tambah Item Keluar']);
    }

    // HTTPTEST HAPUS ITEM KELUAR
    public function testHTTPHapusItemKeluar() {
    	
   	//MEMBUAT NO FAKTUR
    	$warung_id = 1;
    	$no_faktur = ItemKeluar::no_faktur($warung_id);

	    $create_item_keluar = ItemKeluar::create(['no_faktur' => $no_faktur, 'keterangan' => 'TestCase Item Keluar', 'warung_id' => '1']);

	    $user = User::find(5);

	    $response = $this->actingAs($user)->json('POST', route('item-keluar.destroy',$create_item_keluar->id), ['_method' => 'DELETE']);

	    $this->get($response->headers->get('location'))->assertSee('Sukses : Item Keluar Berhasil Dihapus');  
    }

    //HALAMAN MENU EDIT ITEM KELUAR
    public function testHTTPUpdateItemKeluar(){
    
    //MEMBUAT NO FAKTUR
    	$warung_id = 1;
        $no_faktur = ItemKeluar::no_faktur($warung_id);

        $user = User::find(5);
   	//BUAT ITEM KELUAR
    	$create_detail_item_keluar = DetailItemKeluar::create(['id_produk' => 1, 'no_faktur' => $no_faktur, 'jumlah_produk' => 1, 'warung_id' => $warung_id]);
	    $create_item_keluar = ItemKeluar::create(['no_faktur' => $no_faktur, 'keterangan' => 'TestCase Item Keluar', 'warung_id' => '1']);

	//FIND ITEM KELUAR dan DETAIL ITEM KELUAR -> INSERT KE EDIT TBS ITEM KELUAR
	    $data_item_keluar = ItemKeluar::find($create_item_keluar->id);  
        $detail_item_keluar = DetailItemKeluar::where('no_faktur', $data_item_keluar->no_faktur)->where('warung_id', $warung_id);

        $hapus_semua_edit_tbs_item_keluar = EditTbsItemKeluar::where('no_faktur', $data_item_keluar->no_faktur)->where('warung_id', $warung_id)
        ->delete();

        foreach ($detail_item_keluar->get() as $data_tbs) {
            $detail_item_keluar = EditTbsItemKeluar::create([
                'id_produk'     => $data_tbs->id_produk,              
                'no_faktur'     => $data_tbs->no_faktur,
                'jumlah_produk' => $data_tbs->jumlah_produk,          
                'warung_id'     => $data_tbs->warung_id,
                'session_id'    => session()->getId(),
            ]);
        }

	    $response = $this->actingAs($user)->get(route('item-keluar.edit',$create_item_keluar->id));
	    $response->assertStatus(200)
                 	->assertSee('Edit Item Keluar : <b>'.$no_faktur.'</b>');

        //SELESAI EDIT ITEM KELUAR
        $response = $this->actingAs($user)->json('POST', route('item-keluar.proses_edit_item_keluar',$create_item_keluar->id), ['keterangan' => 'HttpTest Tambah Item Keluar #2'])->assertStatus(302);
        $response->assertRedirect(route('item-keluar.index'));
        $response2 = $this->get($response->headers->get('location'))
                            ->assertSee('Sukses : Berhasil Melakukan Edit Transaksi Item Keluar Faktur "'.$create_item_keluar->no_faktur.'"');
        
    }

}