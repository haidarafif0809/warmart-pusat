<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Suplier;
use App\User;
use URL;
class SuplierTest extends TestCase
{
	use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */


    protected function setUp()
    {
        parent::setUp();
        /*kode untuk menset base url nya jadi localhost
          karena kalau gak localhost jadi tidak bisa jalan testing http nya
         selalu responnya 404 */
        URL::forceRootUrl('https://localhost');
    
    }

    public function testCrudSuplier()
    {
    	// TEST INSERT SUPLIER
    	$suplier_test = Suplier::create([
  		'nama_suplier' => 'PT SELAMAT DATANG','alamat'=>'jln ds','no_telp'=>'082394234233','warung_id'=>'1'
    	]);
    	//CEK DATABASE TAMBAH
    	$this->assertDatabaseHas('supliers',['nama_suplier' => 'PT SELAMAT DATANG','alamat'=>'jln ds','no_telp'=>'082394234233','warung_id'=>'1']);
    	// TEST UPDATE Suplier
    	Suplier::find($suplier_test->id)->update(['nama_suplier' => 'PT SELAMAT DATANG DI','alamat'=>'jln desa pemandangan','no_telp'=>'082394234233']);
    	//CEK DATABASE UPDATE
    	$this->assertDatabaseHas('supliers',['nama_suplier' => 'PT SELAMAT DATANG DI','alamat'=>'jln desa pemandangan','no_telp'=>'082394234233']);

    	// TEST DELETE USER
    	Suplier::destroy($suplier_test->id);
    	$suplier = Suplier::find($suplier_test->id);
    	// cek DATA BASE
    	$this->assertDatabaseMissing('supliers',[
    	'nama_suplier' => 'PT SELAMAT DATANG DI','alamat'=>'jln desa pemandangan','no_telp'=>'082394234233'
    	]);
    
    }

// HTTP TESTING 

    //TAMBAH SUPLIER
    public function testHTTPTambahSuplier() {

        $user = User::find(5);

        $response = $this->actingAs($user)->json('POST', route('suplier.store'), ['nama_suplier' => 'PT SELAMAT DATANG d','alamat'=>'jln ds','no_telp'=>'082394234233']);

        $response->assertStatus(302)
                 ->assertRedirect(route('suplier.index'));
        

        $response2 = $this->get($response->headers->get('location'))->assertSee('Sukses : Berhasil Menambah Suplier "PT SELAMAT DATANG d"');

        $this->assertDatabaseHas("supliers",['nama_suplier' => 'PT SELAMAT DATANG d','alamat'=>'jln ds','no_telp'=>'082394234233']);
    }



   //HAPUS SUPLIER
    public function testHTTPHapusSuplier(){

    	$suplier = Suplier::create(['nama_suplier' => 'PT SELAMAT DATANG di','alamat'=>'jln ds','no_telp'=>'082394234233']);

        $user = User::find(5);

        $response = $this->actingAs($user)->json('POST', route('suplier.destroy',1), ['_method' => 'DELETE']);

        $response->assertStatus(302)
                 ->assertRedirect(route('suplier.index'));
        
        $response2 = $this->get($response->headers->get('location'))->assertSee('Sukses : Berhasil Menghapus Suplier');       

    }


	//EDIT SUPLIER
    public function testHTTPeditBarang(){
        
    	$suplier = Suplier::create(['nama_suplier' => 'PT SELAMAT DATANG di','alamat'=>'jln ds','no_telp'=>'082394234233']);

        //login user -> admin
        $user = User::find(5);

        $response = $this->actingAs($user)->json('POST', route('suplier.update',1), ['_method' => 'PUT','nama_suplier' => 'PT SELAMAT DATANG dong','alamat'=>'jln ds','no_telp'=>'082394234233']);

        $response->assertStatus(302)
                 ->assertRedirect(route('suplier.index'));

        $response2 = $this->get($response->headers->get('location'))->assertSee('Sukses : Berhasil Mengubah Suplier "PT SELAMAT DATANG dong"');
     
    }


}
