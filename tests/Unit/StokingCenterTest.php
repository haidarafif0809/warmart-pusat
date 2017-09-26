<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\StokingCenter;
use App\User;
use URL;

class StokingCenterTest extends TestCase
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
        URL::forceRootUrl('http://localhost');
    }

  //fungsi untuk testing crud proses 
    public function testCrudStokingCenter()
    {
    
        $stokingcenter = StokingCenter::create(['name'=>'Fahrizal','alamat'=>'Jalan Way Seputih Pahoman','wilayah'=>'103','kategori_harga'=>'1','url_api'=>'war-mar.id/sc']);

        $this->assertDatabaseHas('stoking_centers',['name'=>'Fahrizal','alamat'=>'Jalan Way Seputih Pahoman','wilayah'=>'103','kategori_harga'=>'1','url_api'=>'war-mar.id/sc']);

        StokingCenter::find($stokingcenter->id)->update(['name'=>'FahrizalRahman','alamat'=>'Jalan Way Seputih Pahomann','wilayah'=>'104','kategori_harga'=>'2','url_api'=>'war-mar.id/sc']);
 		
 		$this->assertDatabaseHas('stoking_centers',['name'=>'FahrizalRahman','alamat'=>'Jalan Way Seputih Pahomann','wilayah'=>'104','kategori_harga'=>'2','url_api'=>'war-mar.id/sc']);

 		StokingCenter::destroy($stokingcenter->id);
 		$stokingcenter = StokingCenter::find($stokingcenter->id);
 		$this->assertEquals(null,$stokingcenter);


    }


    //fungsi untuk testing http proses ketika tambah masuk ke data base
    public function testHTTPTambahStokingCenter (){

        $user = User::find(1);
        
        $response = $this->actingAs($user)->json('POST', route('stoking-center.store'), ['name'=>'Fahrizal','alamat'=>'Jalan Way Seputih Pahoman','kelurahan'=>'103','kategoriharga'=>'1','url_api'=>'war-mar.id/sc']);

        $response->assertStatus(302)
                 ->assertRedirect(route('stoking-center.index'));
        

        $response2 = $this->get($response->headers->get('location'))->assertSee('Success : Berhasil Menambah Stoking Center Fahrizal');



        $this->assertDatabaseHas('stoking_centers',['name'=>'Fahrizal','alamat'=>'Jalan Way Seputih Pahoman','wilayah'=>'103','kategori_harga'=>'1','url_api'=>'war-mar.id/sc']);

    }

	//fungsi untuk testing http proses ketika pindah halaman edit
    public function testHTTPEditStokingCenter(){


        $stokingcenter = StokingCenter::create(['name'=>'Fahrizal Rahman','alamat'=>'Jalan Way Seputih Pahoman Bandar Lampung','wilayah'=>'103','kategori_harga'=>'2','url_api'=>'war-mar.id/sc/2']);

        $user = User::find(1);

        $response = $this->actingAs($user)->get(route('stoking-center.edit',$stokingcenter->id));

        $response->assertStatus(200)
                 ->assertSee('Edit Stoking Center');

     
    }


   	//test http update STOKING CENTER
    public function testHTTPUpdateWarung (){

        $stokingcenter = StokingCenter::create(['name'=>'Fahrizal Rahman','alamat'=>'Jalan Way Seputih Pahoman Bandar Lampung','wilayah'=>'103','kategori_harga'=>'2','url_api'=>'war-mar.id/sc/2']);

        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('stoking-center.update',$stokingcenter->id), ['_method' => 'PUT','name'=>'Fahrizal Rahman','alamat'=>'Jalan Way Seputih Pahoman Bandar Lampung','kelurahan'=>'103','kategoriharga'=>'2','url_api'=>'war-mar.id/sc/2']);

        $response->assertStatus(302)
                 ->assertRedirect(route('stoking-center.index'));
        
         $response2 = $this->get($response->headers->get('location'))->assertSee('Success : Berhasil Mengubah Stoking Center Fahrizal Rahman');

     
    }


    //test http hapus STOKING CENTER
    public function testHTTPHapusStokingCenter(){


        $stokingcenter = StokingCenter::create(['name'=>'Fahrizal Rahman','alamat'=>'Jalan Way Seputih Pahoman Bandar Lampung','wilayah'=>'103','kategori_harga'=>'2','url_api'=>'war-mar.id/sc/2']);
        
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('stoking-center.destroy',$stokingcenter->id), ['_method' => 'DELETE']);

        $response->assertStatus(302)
                 ->assertRedirect(route('stoking-center.index'));
        
        $response2 = $this->get($response->headers->get('location'))->assertSee('Success : Berhasil Menghapus Stoking Center'); 

    }




}
