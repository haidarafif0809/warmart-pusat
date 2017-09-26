<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\KategoriHarga;
use App\User;
use URL;

class KategoriHargaTest extends TestCase
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


//CRUD TESTING
    public function testKategoriHargaCrud() {
    	
    	//TAMBAH KategoriHarga
        $kategoriharga = KategoriHarga::create(["nama_kategori_harga" => "Perkantotan"]);
		$this->assertDatabaseHas('kategori_hargas', ["nama_kategori_harga" => "Perkantotan"]);

		//UPDATE KategoriHarga
		KategoriHarga::find($kategoriharga->id)->update(["nama_kategori_harga" => "Perkampungan"]);
		$this->assertDatabaseHas('kategori_hargas', ["nama_kategori_harga" => "Perkampungan"]);

		//DELETE KategoriHarga
		$hapus_KategoriHarga = KategoriHarga::destroy($kategoriharga->id);

        $kategoriharga = KategoriHarga::find($kategoriharga->id);
        $this->assertDatabaseMissing('kategori_hargas', ["nama_kategori_harga" => "Perkampungan"]);

    }


    //TAMBAH KategoriHarga 
    public function testHTTPTambahKategoriHarga() {

        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('kategori-harga.store'), ["nama_kategori_harga" => "Level 1"]);

        $response->assertStatus(302)
                 ->assertRedirect(route('kategori-harga.index'));
        

        $response2 = $this->get($response->headers->get('location'))->assertSee('Sukses : Berhasil Menambah Kategori Harga "Level 1"');

        $this->assertDatabaseHas("kategori_hargas",["nama_kategori_harga" => "Level 1"]);
    }

    //HAPUS KategoriHarga
    public function testHTTPHapusKategoriHarga(){

        $kategori_harga = KategoriHarga::create(["nama_kategori_harga" => "Level 1"]);
        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('kategori-harga.destroy',$kategori_harga->id), ['_method' => 'DELETE']);

        $response->assertStatus(302)
                 ->assertRedirect(route('kategori-harga.index'));
        
        $response2 = $this->get($response->headers->get('location'))->assertSee('Sukses : Kategori Harga Berhasil Dihapus');       

    }


    //HALAMAN MENU EDIT BANK
    public function testHTTPUpdateKategoriHarga (){

        $kategori_harga = KategoriHarga::create(["nama_kategori_harga" => "Level 2"]);
        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->get(route('kategori-harga.edit',$kategori_harga->id));

        $response->assertStatus(200)
                 ->assertSee('Edit Kategori Harga');

     
    }

    //PROSES EDIT BANK
    public function testHTTPEditKategoriHarga (){
        
        $kategori_harga = KategoriHarga::create(["nama_kategori_harga" => "Level 2"]);
        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('kategori-harga.update',$kategori_harga->id), ['_method' => 'PUT','nama_kategori_harga' => 'Level 3']);

        $response->assertStatus(302)
                 ->assertRedirect(route('kategori-harga.index'));

        $response2 = $this->get($response->headers->get('location'))->assertSee('Sukses : Berhasil Mengubah Kategori Harga "Level 3"');
     
    }
}
