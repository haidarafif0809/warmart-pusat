<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Komunitas;
use App\User;
use URL;

class KomunitasTest extends TestCase
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
    public function testKomunitasCrud() {
    	
    	//TAMBAH komunitas
    	$password = bcrypt('admintamvan');
        $komunitas = Komunitas::create(['email' => 'userkomunitas@gmail.com', 'password' => $password, 'name' => 'User Komunitas', 'alamat' => 'Jl. Kedaton, Bandar Lampung', 'wilayah' => '1',  'no_telp' => '085383550858', 'tipe_user' => '2', 'status_konfirmasi' => 0]);

		$this->assertDatabaseHas('users', ['email' => 'userkomunitas@gmail.com', 'password' => $password, 'name' => 'User Komunitas', 'alamat' => 'Jl. Kedaton, Bandar Lampung', 'wilayah' => '1',  'no_telp' => '085383550858', 'tipe_user' => '2', 'status_konfirmasi' => 0]);

		//UPDATE komunitas
		Komunitas::find($komunitas->id)->update(['email' => 'userkomunitas_update@gmail.com', 'password' => $password, 'name' => 'User Komunitas Update', 'alamat' => 'Jl. Kedaton, Bandar Lampung Update', 'wilayah' => '1',  'no_telp' => '085383550858', 'tipe_user' => '2', 'status_konfirmasi' => 1]);
		$this->assertDatabaseHas('users', ['email' => 'userkomunitas_update@gmail.com', 'password' => $password, 'name' => 'User Komunitas Update', 'alamat' => 'Jl. Kedaton, Bandar Lampung Update', 'wilayah' => '1', 'no_telp' => '085383550858', 'tipe_user' => '2', 'status_konfirmasi' => 1]);

		//DELETE komunitas
		$hapus_bank = Komunitas::destroy($komunitas->id);

        $komunitas = Komunitas::find($komunitas->id);
        $this->assertDatabaseMissing('users', ['email' => 'userkomunitas_update@gmail.com', 'password' => $password, 'name' => 'User Komunitas Update', 'alamat' => 'Jl. Kedaton, Bandar Lampung Update', 'wilayah' => '1',  'no_telp' => '085383550858', 'tipe_user' => '2', 'status_konfirmasi' => 1]);

    }

//HTTP TESTING
    //TAMBAH komunitas
    public function testHTTPTambahkomunitas() {

    	//login user -> admin
        $user = User::find(1);
        $password = bcrypt('admintamvan');

        $response = $this->actingAs($user)->json('POST', route('komunitas.store'),
         ['email' => 'rindang@gmail.com',
          'name' => 'RindangRamadhan', 
          'alamat' => 'Jl. Kedaton, Bandar Lampung', 
          'kelurahan' => '1', 
          'no_telp' => '085383550858', 
          'komunitas' => 3]);

        $response->assertStatus(302)
                 ->assertRedirect(route('komunitas.index'));
        
        //cek apakah response alert nya sesuai 
        $response2 = $this->get($response->headers->get('location'))->assertSee('Sukses : Berhasil Menambah Komunitas "RindangRamadhan"');

        $komunitas = komunitas::where("email",'rindang@gmail.com')->first();

        //cek apakah data komunitas nya masuk
        $this->assertDatabaseHas("users",['name' => 'RindangRamadhan', 'email' => 'rindang@gmail.com', 'alamat' => 'Jl. Kedaton, Bandar Lampung', 'status_konfirmasi' => '0', 'wilayah' => '1', 'no_telp' => '085383550858', 'tipe_user' => '2', 'tgl_lahir' => '1997-01-10']);

        //cek apakah data komunitas nya masuk
        $this->assertDatabaseHas("komunitas_komunitass",['komunitas_id' => 2, 'user_id' => $komunitas->id]);
    }

}
