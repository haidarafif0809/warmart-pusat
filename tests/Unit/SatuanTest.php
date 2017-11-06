<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Satuan;
use App\User;
use URL;
class SatuanTest extends TestCase
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
    	
    	// TEST INSERT SATUAN
        $satuan_test = Satuan::create([
        'nama_satuan' => 'SATUAN COBA'
        ]);
        //CEK DATABASE TAMBAH
        $this->assertDatabaseHas('satuans',['nama_satuan' => 'SATUAN COBA']);
        // TEST UPDATE SATUAN
        Satuan::find($satuan_test->id)->update(['nama_satuan' => 'SATUAN COBA']);
        //CEK DATABASE UPDATE
        $this->assertDatabaseHas('satuans',['nama_satuan' => 'SATUAN COBA']);

        // TEST DELETE SATUAN
        Satuan::destroy($satuan_test->id);
        $suplier = Satuan::find($satuan_test->id);

        // cek DATA BASE
        $this->assertDatabaseMissing('satuans',[
        'nama_satuan' => 'SATUAN COBA'
        ]);
   

    }


//HTTP TESTING
    //TAMBAH SATUAN
    public function testHTTPTambahSatuan() {

        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('satuan.store'), ["nama_satuan" => "BELIMBING"]);

        $response->assertStatus(200);

        $this->assertDatabaseHas("satuans",["nama_satuan" => "BELIMBING"]);
    }

        //HAPUS SATUAN
    public function testHTTPHapusSatuan(){

        $satuan = Satuan::create(["nama_satuan" => "BELIMBING"]);
        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('satuan.destroy',$satuan->id), ['_method' => 'DELETE']);

        $response->assertStatus(200);   

    }

        //HALAMAN MENU EDIT SATUAN
    public function testHTTPUpdateSatuan(){

        $satuan = Satuan::create(["nama_satuan" => "BELIMBING"]);
        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->get(route('satuan.edit',$satuan->id));

        $response->assertStatus(200);
     
    }

    //PROSES EDIT SATUAN
    public function testHTTPEditSatuan(){
        
        $satuan = Satuan::create(["nama_satuan" => "BELIMBING"]);
        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('satuan.update',$satuan->id), ['_method' => 'PUT','nama_satuan' => 'BELIMBING']);

        $response->assertStatus(200);

    }

}
