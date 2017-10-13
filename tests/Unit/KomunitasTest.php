<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Komunitas;
use App\BankKomunitas;
use App\KomunitasPenggiat;
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
      $password = bcrypt('123456');

        $tambah_komunitas = Komunitas::create(['email' => 'userkomunitas@gmail.com', 'password' => $password, 'name' => 'User Komunitas', 'alamat' => 'Jl. Kedaton, Bandar Lampung', 'wilayah' => '1',  'no_telp' => '085383550858','id_warung'=>'1', 'tipe_user' => '2', 'status_konfirmasi' => 0]);
      $this->assertDatabaseHas('users', ['email' => 'userkomunitas@gmail.com', 'password' => $password, 'name' => 'User Komunitas', 'alamat' => 'Jl. Kedaton, Bandar Lampung', 'wilayah' => '1',  'no_telp' => '085383550858','id_warung'=>'1', 'tipe_user' => '2', 'status_konfirmasi' => 0]);

      //TAMBAH BANK komunitas
        $tambah_bank_komunitas = BankKomunitas::create(['nama_bank'=>'BNI','atas_nama'=>'Rindang Ramadhan','no_rek'=>'0433156248','komunitas_id'=>'1']);
        $this->assertDatabaseHas('bank_komunitas', ['nama_bank'=>'BNI','atas_nama'=>'Rindang Ramadhan','no_rek'=>'0433156248','komunitas_id'=>'1']);


      //TAMBAH penggiat komunitas
        $tambah_komunitas_penggiat = KomunitasPenggiat::create(['nama_penggiat'=>'saa','alamat_penggiat'=>'Fahrizal Ramadhan','komunitas_id'=>'1']);
        $this->assertDatabaseHas('komunitas_penggiats', ['nama_penggiat'=>'saa','alamat_penggiat'=>'Fahrizal Ramadhan','komunitas_id'=>'1']);



    //UPDATE komunitas
    Komunitas::find($tambah_komunitas->id)->update(['email' => 'userkomunitas_update@gmail.com', 'password' => $password, 'name' => 'User Komunitas Update', 'alamat' => 'Jl. Kedaton, Bandar Lampung Update', 'wilayah' => '1',  'no_telp' => '085383550858','id_warung'=>'1','tipe_user' => '2', 'status_konfirmasi' => 1]);
    $this->assertDatabaseHas('users', ['email' => 'userkomunitas_update@gmail.com', 'password' => $password, 'name' => 'User Komunitas Update', 'alamat' => 'Jl. Kedaton, Bandar Lampung Update', 'wilayah' => '1', 'no_telp' => '085383550858','id_warung'=>'1', 'tipe_user' => '2', 'status_konfirmasi' => 1]);


    //UPDATE komunitas
    BankKomunitas::find($tambah_bank_komunitas->id)->update(['nama_bank'=>'BNI Syariah','no_rek'=>'04331562484','atas_nama'=>'Rindang Ramadhan01']);
    $this->assertDatabaseHas('bank_komunitas', ['nama_bank'=>'BNI Syariah','no_rek'=>'04331562484','atas_nama'=>'Rindang Ramadhan01']);


      //UPDATE penggiat komunitas
     KomunitasPenggiat::find($tambah_komunitas_penggiat->id)->update(['nama_penggiat'=>'saaaa','alamat_penggiat'=>'Rindang Ramadhan ss']);
      $this->assertDatabaseHas('komunitas_penggiats', ['nama_penggiat'=>'saaaa','alamat_penggiat'=>'Rindang Ramadhan ss']);


		//DELETE komunitas
		$hapus_komunitas = Komunitas::destroy($tambah_komunitas->id);

    $hapus_bank_komunitas = BankKomunitas::where('komunitas_id',$tambah_komunitas->id);

    $hapus_komunitas_penggiat = KomunitasPenggiat::where('komunitas_id',$tambah_komunitas->id);


    //CEK KOMUNITAS
        $cek_komunitas = Komunitas::find($tambah_komunitas->id);
      $this->assertDatabaseMissing('users', ['email' => 'userkomunitas_update@gmail.com', 'password' => $password, 'name' => 'User Komunitas Update', 'alamat' => 'Jl. Kedaton, Bandar Lampung Update', 'wilayah' => '1',  'no_telp' => '085383550858','id_warung'=>'1', 'tipe_user' => '2', 'status_konfirmasi' => 1]);


    }

    //HTTP TESTING
    //TAMBAH WARUNG
    public function testHTTPTambahKomunitas() {

        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('komunitas.store'), [
         'email'     => 'rindangramadhan10@gmail.com',
         'name'      => 'Rindang CLOTH',
         'name_penggiat'=>'Roman Camelo',
         'alamat'    => 'Jl. Kemiling Raya',
         'alamat_penggiat'=>'Jalan Sriwijaya',
         'kelurahan' => '10',
         'no_telp'   => '08953362211964',
         'nama_bank' => 'BNI SYARIAH',
         'no_rekening'    => '0433857710',
         'an_rekening' => 'RINDANG RAMADHAN',
         'id_warung' => '1',
         ]);

        $response->assertStatus(302)
                 ->assertRedirect(route('komunitas.index'));
        
        $password = bcrypt('123456');

        //TAMBAH KOMUNITAS
        $komunitas = Komunitas::create(['email' => '-','password'=>$password,'name' => 'Rindang CLOTH', 'alamat' => 'Jl. Kemiling Raya', 'wilayah' => '10',  'no_telp' => '5645645','id_warung'=>'1', 'tipe_user' => '2', 'status_konfirmasi' => 0]);

        $bank_komunitas = BankKomunitas::create(['nama_bank'=>'BNI SYARIAH','atas_nama'=>'Rindang CLOTH','no_rek'=>'0433857710','komunitas_id'=>$komunitas->id]);

        $tambah_komunitas_penggiat = KomunitasPenggiat::create(['nama_penggiat'=>'Roman Camelo','alamat_penggiat'=>'Jalan Sriwijaya','komunitas_id'=>$komunitas->id]);

        $response2 = $this->get($response->headers->get('location'))->assertSee('Success : Berhasil Menambah Komunitas Rindang CLOTH');

        //CEK DB TABLE komunitas
        $response_komunitas = $this->assertDatabaseHas("users",['email' => '-','password'=>$password,'name' => 'Rindang CLOTH', 'alamat' => 'Jl. Kemiling Raya', 'wilayah' => '10','no_telp' => '5645645','id_warung'=>'1', 'tipe_user' => '2', 'status_konfirmasi' => 0]);

        //CEK DB TABLE bank_komunitas
        $response_bank_warung = $this->assertDatabaseHas("bank_komunitas",['nama_bank' => 'BNI SYARIAH','atas_nama' => 'Rindang CLOTH','no_rek'=>'0433857710', 'komunitas_id' => $komunitas->id]);

        $response_komunitas_penggiat = $this->assertDatabaseHas('komunitas_penggiats', ['nama_penggiat'=>'Roman Camelo','alamat_penggiat'=>'Jalan Sriwijaya','komunitas_id'=>$komunitas->id]);

    }


        //HAPUS WARUNG
    public function testHTTPHapusKomunitas(){


        $password = bcrypt('123456');

        //TAMBAH KOMUNITAS
        $komunitas = Komunitas::create(['email' => '-','password'=>$password,'name' => 'Rindang CLOTH', 'alamat' => 'Jl. Kemiling Raya', 'wilayah' => '10',  'no_telp' => '5645645','id_warung'=>'1', 'tipe_user' => '2', 'status_konfirmasi' => 0]);

        $bank_komunitas = BankKomunitas::create(['nama_bank'=>'BNI SYARIAH','atas_nama'=>'Rindang CLOTH','no_rek'=>'0433857710','komunitas_id'=>$komunitas->id]);

        $tambah_komunitas_penggiat = KomunitasPenggiat::create(['nama_penggiat'=>'Roman Camelo','alamat_penggiat'=>'Jalan Sriwijaya','komunitas_id'=>$komunitas->id]);


        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('komunitas.destroy',$komunitas->id), ['_method' => 'DELETE']);

        //DELETE BANK komunitas
        $hapus_bank_komunitas = BankKomunitas::where('komunitas_id', $komunitas->id)->delete();
        
        //DELETE BANK komunitas
        $hapus_user_komunitas = KomunitasPenggiat::where('komunitas_id', $komunitas->id)->delete();

        $response->assertStatus(302)
                 ->assertRedirect(route('komunitas.index'));
        
        $response2 = $this->get($response->headers->get('location'))->assertSee('Success : Berhasil Menghapus Komunitas');       

    }


        //HALAMAN MENU EDIT KOMUNITAS
    public function testHTTPUpdateKomunitas(){

        $password = bcrypt('123456');


        //TAMBAH KOMUNITAS
        $komunitas = Komunitas::create(['email' => '-','password'=>$password,'name' => 'Rindang CLOTH', 'alamat' => 'Jl. Kemiling Raya', 'wilayah' => '10',  'no_telp' => '5645645','id_warung'=>'1', 'tipe_user' => '2', 'status_konfirmasi' => 0]);

        //TAMBAH BANK Komunitas
        $bank_komunitas = BankKomunitas::create(['nama_bank'=>'BNI SYARIAH','atas_nama'=>'Rindang CLOTH','no_rek'=>'0433857710','komunitas_id'=>$komunitas->id]);

        $tambah_komunitas_penggiat = KomunitasPenggiat::create(['nama_penggiat'=>'Roman Camelo','alamat_penggiat'=>'Jalan Sriwijaya','komunitas_id'=>$komunitas->id]);

        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->get(route('komunitas.edit',$komunitas->id));

        $response->assertStatus(200)
                 ->assertSee('Edit Komunitas');

     
    }


        //PROSES EDIT WARUNG
    public function testHTTPEditKomunitas(){
        
        $password = bcrypt('123456');


        //TAMBAH KOMUNITAS
        $komunitas = Komunitas::create(['email' => '-','password'=>$password,'name' => 'Rindang CLOTH', 'alamat' => 'Jl. Kemiling Raya', 'wilayah' => '10',  'no_telp' => '5645645','id_warung'=>'1']);

        //TAMBAH BANK Komunitas
        $bank_komunitas = BankKomunitas::create(['nama_bank'=>'BNI SYARIAH','atas_nama'=>'Rindang CLOTH','no_rek'=>'0433857710','komunitas_id'=>$komunitas->id]);

        $tambah_komunitas_penggiat = KomunitasPenggiat::create(['nama_penggiat'=>'Roman Camelo','alamat_penggiat'=>'Jalan Sriwijaya','komunitas_id'=>$komunitas->id]);


        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('komunitas.update',$komunitas->id), ['_method' => 'PUT','email' => 'jhbj@gmail.com','name' => 'Rindang CLOTH Update','name_penggiat'=>'Roman Camelos', 'alamat' => 'Jl. Kemiling Raya Update', 'alamat_penggiat'=>'Jalan Sriwijayas','kelurahan' => '10', 'no_telp' => '45344', 'nama_bank'=>'BNI', 'an_rekening'=>'Rindang Ramadhan', 'no_rekening'=>'5432','id_warung'=>'1']);

        $response->assertStatus(302)
                 ->assertRedirect(route('komunitas.index'));

        $response2 = $this->get($response->headers->get('location'))->assertSee('Success : Berhasil Mengubah Komunitas Rindang CLOTH Update ');
     
    }


}
