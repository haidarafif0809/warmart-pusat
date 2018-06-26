<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\KategoriTransaksi;
use App\User;
use URL;

class KategoriTransaksiTest extends TestCase
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
    public function testKategoriTransaksiCrud() {
    	
    	// TEST INSERT KATEGORI TRANSAKSI
        $kategori_transaksi_test = KategoriTransaksi::create([
        'nama_kategori_transaksi' => 'MODAL SIM','id_warung'=>'1'
        ]);
        //CEK DATABASE TAMBAH
        $this->assertDatabaseHas('kategori_transaksis',['nama_kategori_transaksi' => 'MODAL SIM','id_warung'=>'1']);
        // TEST UPDATE KATEGORI TRANSAKSI
        KategoriTransaksi::find($kategori_transaksi_test->id)->update(['nama_kategori_transaksi' => 'MODAL SIM DI']);
        //CEK DATABASE UPDATE
        $this->assertDatabaseHas('kategori_transaksis',['nama_kategori_transaksi' => 'MODAL SIM DI']);

        // TEST DELETE KATEGORI TRANSAKSI
        KategoriTransaksi::destroy($kategori_transaksi_test->id);
        $suplier = KategoriTransaksi::find($kategori_transaksi_test->id);

        // cek DATA BASE
        $this->assertDatabaseMissing('kategori_transaksis',[
        'nama_kategori_transaksi' => 'MODAL SIM DI'
        ]);
   

    }

    //TAMBAH KATEGORI TRANSAKSI
    public function testHTTPTambahKategoriTransaksi() {

        $user = User::find(5);

        $response = $this->actingAs($user)->json('POST', route('kategori_transaksi.store'), ["nama_kategori_transaksi" => "GAJI MANAGEMEN","id_warung"=>"1"]);

        $response->assertStatus(302)
                 ->assertRedirect(route('kategori_transaksi.index'));
        

        $response2 = $this->get($response->headers->get('location'))->assertSee('Sukses : Berhasil Menambah Kategori Transaksi "GAJI MANAGEMEN"');

        $this->assertDatabaseHas("kategori_transaksis",["nama_kategori_transaksi" => "GAJI MANAGEMEN","id_warung"=>"1"]);
    }

    //HAPUS KATEGORI TRANSAKSI
    public function testHTTPHapusKategoriTransaksi(){

        $kategori_transaksi = KategoriTransaksi::create(["nama_kategori_transaksi" => "GAJI MANAGEMEN","id_warung"=>"1"]);

        $user = User::find(5);

        $response = $this->actingAs($user)->json('POST', route('kategori_transaksi.destroy',$kategori_transaksi->id), ['_method' => 'DELETE']);

        $response->assertStatus(302)
                 ->assertRedirect(route('kategori_transaksi.index'));
        
        $response2 = $this->get($response->headers->get('location'))->assertSee('Sukses : Kategori Transaksi Berhasil Dihapus');       

    }

        //HALAMAN MENU EDIT KATEGORI TRANSAKSI
    public function testHTTPUpdatekategori_transaksi(){

        $kategori_transaksi = KategoriTransaksi::create(["nama_kategori_transaksi" => "GAJI MANAGEMEN","id_warung"=>"1"]);

        $user = User::find(5);

        $response = $this->actingAs($user)->get(route('kategori_transaksi.edit',$kategori_transaksi->id));
        $response->assertStatus(200)
                 ->assertSee('Edit Kategori Transaksi');

     
    }
     //PROSES EDIT KATEGORI TRANSAKSI
    public function testHTTPEditKategoriTransaksi(){
        
        $kategori_transaksi = KategoriTransaksi::create(["nama_kategori_transaksi" => "GAJI MANAGEMEN","id_warung"=>"1"]);
        //login user -> admin
        $user = User::find(5);

        $response = $this->actingAs($user)->json('POST', route('kategori_transaksi.update',$kategori_transaksi->id), ['_method' => 'PUT','nama_kategori_transaksi' => 'GAJI MANAGEMEN UPDATE','id_warung'=>'1']);

        $response->assertStatus(302)
                 ->assertRedirect(route('kategori_transaksi.index'));

        $response2 = $this->get($response->headers->get('location'))->assertSee('Sukses : Berhasil Mengubah Kategori Transaksi "GAJI MANAGEMEN UPDATE"');
     
    }


}
