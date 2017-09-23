<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Warung;
use App\User;
use URL;

class WarungTest extends TestCase
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

    //fungsi untuk testing crud proses 
    public function testCrudWarung()
    {
        $warung = Warung::create(['email' => 'fahrizal95rhm@gmail.com','password'=>'123456','name'=>'Fahrizal','alamat'=>'Jalan Way Seputih Pahoman','wilayah'=>'103','link_afiliasi'=>'andaglos.com','no_telp'=>'0832476234734','nama_bank'=>'Bank BNI','no_rekening'=>'0404045423','an_rekening'=>'Fahrizal Rahman']);

        $this->assertDatabaseHas('warungs',['email' => 'fahrizal95rhm@gmail.com','password'=>'123456','name'=>'Fahrizal','alamat'=>'Jalan Way Seputih Pahoman','wilayah'=>'103','link_afiliasi'=>'andaglos.com','no_telp'=>'0832476234734','nama_bank'=>'Bank BNI','no_rekening'=>'0404045423','an_rekening'=>'Fahrizal Rahman']);

        Warung::find($warung->id)->update(['email' => 'fahrizal95rhm@gmail.com','password'=>'12345676','name'=>'Fahrizal Edit','alamat'=>'Jalan Way Seputih Pahoman Bandar Lampung','wilayah'=>'104','link_afiliasi'=>'andaglos.com/aff/','no_telp'=>'083247623466','nama_bank'=>'BCA','no_rekening'=>'64565694562','an_rekening'=>'Fahrizal Rahman Programmer']);
 		
 		$this->assertDatabaseHas('warungs',['email' => 'fahrizal95rhm@gmail.com','password'=>'12345676','name'=>'Fahrizal Edit','alamat'=>'Jalan Way Seputih Pahoman Bandar Lampung','wilayah'=>'104','link_afiliasi'=>'andaglos.com/aff/','no_telp'=>'083247623466','nama_bank'=>'BCA','no_rekening'=>'64565694562','an_rekening'=>'Fahrizal Rahman Programmer']);

 		Warung::destroy($warung->id);
 		$warung = Warung::find($warung->id);
 		$this->assertEquals(null,$warung);


    }

    //fungsi untuk testing http proses ketika tambah masuk ke data base
    public function testHTTPTambahWarung (){

        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('warung.store'), ['email' => 'fahrizal95rhm@gmail.com','password'=>'123456','name'=>'Fahrizal','alamat'=>'Jalan Way Seputih Pahoman','wilayah'=>'103','link_afiliasi'=>'andaglos.com','no_telp'=>'0832476234734','nama_bank'=>'Bank BNI','no_rekening'=>'0404045423','an_rekening'=>'Fahrizal Rahman']);

        $response->assertStatus(302)
                 ->assertRedirect(route('warung.index'));
        

        $response2 = $this->get($response->headers->get('location'))->assertSee('Success : Berhasil Menambah Warung Fahrizal');

        $this->assertDatabaseHas('warungs',['email' => 'fahrizal95rhm@gmail.com','password'=>'123456','name'=>'Fahrizal','alamat'=>'Jalan Way Seputih Pahoman','wilayah'=>'103','link_afiliasi'=>'andaglos.com','no_telp'=>'0832476234734','nama_bank'=>'Bank BNI','no_rekening'=>'0404045423','an_rekening'=>'Fahrizal Rahman']);
    }




}
