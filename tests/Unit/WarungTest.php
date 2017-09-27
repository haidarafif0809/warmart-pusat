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
        URL::forceRootUrl('http://localhost');
    }

    //fungsi untuk testing crud proses 
    public function testCrudWarung()
    {
    
    	$password = bcrypt('rahasia');

        $warung = Warung::create(['email' => 'fahrizal95rhm@gmail.com','password'=>$password,'name'=>'Fahrizal','alamat'=>'Jalan Way Seputih Pahoman','wilayah'=>'103','no_telp'=>'0832476234734','nama_bank'=>'Bank BNI','no_rekening'=>'0404045423','an_rekening'=>'Fahrizal Rahman','tipe_user'=>'2','status_konfirmasi'=>'0']);

        $this->assertDatabaseHas('users',['email' => 'fahrizal95rhm@gmail.com','password'=>$password,'name'=>'Fahrizal','alamat'=>'Jalan Way Seputih Pahoman','wilayah'=>'103','no_telp'=>'0832476234734','nama_bank'=>'Bank BNI','no_rekening'=>'0404045423','an_rekening'=>'Fahrizal Rahman','tipe_user'=>'2','status_konfirmasi'=>'0']);

        Warung::find($warung->id)->update(['email' => 'fahrizal95rhm@gmail.com','name'=>'Fahrizal Edit','alamat'=>'Jalan Way Seputih Pahoman Bandar Lampung','wilayah'=>'104','no_telp'=>'083247623466','nama_bank'=>'BCA','no_rekening'=>'64565694562','an_rekening'=>'Fahrizal Rahman Programmer']);
 		
 		$this->assertDatabaseHas('users',['email' => 'fahrizal95rhm@gmail.com','name'=>'Fahrizal Edit','alamat'=>'Jalan Way Seputih Pahoman Bandar Lampung','wilayah'=>'104','no_telp'=>'083247623466','nama_bank'=>'BCA','no_rekening'=>'64565694562','an_rekening'=>'Fahrizal Rahman Programmer']);

 		Warung::destroy($warung->id);
 		$warung = Warung::find($warung->id);
 		$this->assertEquals(null,$warung);


    }

    //fungsi untuk testing http proses ketika tambah masuk ke data base
    public function testHTTPTambahWarung (){

        $user = User::find(1);
        
        $response = $this->actingAs($user)->json('POST', route('warung.store'), ['email' => 'fahrizal95rhm@gmail.com','name'=>'Fahrizaladsas','alamat'=>'Jalan Way Seputih Pahoman','kelurahan'=>'103','no_telp'=>'08329343243','nama_bank'=>'BNI','no_rekening'=>'0404045423','an_rekening'=>'FahrizalXAS']);

        $response->assertStatus(302)
                 ->assertRedirect(route('warung.index'));
        

        $response2 = $this->get($response->headers->get('location'))->assertSee('Success : Berhasil Menambah Warung Fahrizal');



        $this->assertDatabaseHas('users',['email' => 'fahrizal95rhm@gmail.com','name'=>'Fahrizaladsas','alamat'=>'Jalan Way Seputih Pahoman','wilayah'=>'103','no_telp'=>'08329343243','nama_bank'=>'BNI','no_rekening'=>'0404045423','an_rekening'=>'FahrizalXAS','tipe_user'=>'2','status_konfirmasi'=>'0']);

    }

      //fungsi untuk testing http proses ketika pindah halaman edit
    public function testHTTPEditWarung (){

    	$password = bcrypt('rahasia');

        $warung = Warung::create(['email' => 'fahrizal954rhm@gmail.com','password'=>$password,'name'=>'Fahrizal45645','alamat'=>'Jalan Way Seputih Pahoman','wilayah'=>'104','no_telp'=>'0832476234734','nama_bank'=>'Bank BNI','no_rekening'=>'0404045423','an_rekening'=>'Fahrizal Rahman','tipe_user'=>'2','status_konfirmasi'=>'4']);

        $user = User::find(1);

        $response = $this->actingAs($user)->get(route('warung.edit',$warung->id));

        $response->assertStatus(200)
                 ->assertSee('Edit Warung');

     
    }

     //test http update Warung
    public function testHTTPUpdateWarung (){

        $password = bcrypt('rahasia');

        $warung = Warung::create(['email' => 'fahrizal954rhm@gmail.com','password'=>$password,'name'=>'Fahrizal45645','alamat'=>'Jalan Way Seputih Pahoman','wilayah'=>'104','no_telp'=>'0832476234734','nama_bank'=>'Bank BNI','no_rekening'=>'0404045423','an_rekening'=>'Fahrizal Rahman','tipe_user'=>'2','status_konfirmasi'=>'0']);

        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('warung.update',$warung->id), ['_method' => 'PUT','email' => 'fahrizal954rhm@gmail.com','name'=>'Fahrizal45645','alamat'=>'Jalan Way Seputih Pahoman','kelurahan'=>'104','no_telp'=>'0832476234734','nama_bank'=>'Bank BNI','no_rekening'=>'0404045423','an_rekening'=>'Fahrizal Rahman']);

        $response->assertStatus(302)
                 ->assertRedirect(route('warung.index'));
        
         $response2 = $this->get($response->headers->get('location'))->assertSee('Success : Berhasil Mengubah Warung Fahrizal45645');

     
    }


     //test http hapus Warung
    public function testHTTPHapusWarung(){

    	$password = bcrypt('rahasia');

        $warung = Warung::create(['email' => 'fahrizal95rhm@gmail.com','password'=>$password,'name'=>'Fahrizal','alamat'=>'Jalan Way Seputih Pahoman','wilayah'=>'103','link_afiliasi'=>'andaglos.com','no_telp'=>'0832476234734','nama_bank'=>'Bank BNI','no_rekening'=>'0404045423','an_rekening'=>'Fahrizal Rahman','tipe_user'=>'2','status_konfirmasi'=>'0']);
        
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('warung.destroy',$warung->id), ['_method' => 'DELETE']);

        $response->assertStatus(302)
                 ->assertRedirect(route('warung.index'));
        
        $response2 = $this->get($response->headers->get('location'))->assertSee('Success : Berhasil Menghapus Warung'); 

    }


}
