<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Warung;
use App\UserWarung;
use App\BankWarung;
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

    public function testCrudWarung()
    {
    
        $password = bcrypt('123456');

//MASTER WARUNG
        //TAMBAH WARUNG
        $warung = Warung::create(['email' => '-','name'=>'Rindang Ramadhan','alamat'=>'Jalan Way Seputih Pahoman','wilayah'=>'103','no_telpon'=>'085383550858']);
        $this->assertDatabaseHas('warungs', ['email' => '-','name'=>'Rindang Ramadhan','alamat'=>'Jalan Way Seputih Pahoman','wilayah'=>'103','no_telpon'=>'085383550858']);

        //TAMBAH BANK WARUNG
        $bank_warung = BankWarung::create(['nama_bank'=>'BNI','atas_nama'=>'Rindang Ramadhan','no_rek'=>'0433156248','warung_id'=>'1']);
        $this->assertDatabaseHas('bank_warungs', ['nama_bank'=>'BNI','atas_nama'=>'Rindang Ramadhan','no_rek'=>'0433156248','warung_id'=>'1']);

        //TAMBAH BANK WARUNG
        $password = bcrypt('123456');

        $user_warung = UserWarung::create(['email' => 'rindang@gmail.com','password' => $password,'name' => 'Rindang Ramadhan', 'alamat' => 'Jalan Way Seputih Pahoman', 'wilayah' => '103', 'tipe_user' => '4', 'id_warung' => '1', 'status_konfirmasi' => '1', 'no_telp' => '085383550858', 'kode_verifikasi' => '1001']);
        $this->assertDatabaseHas('users', ['email' => 'rindang@gmail.com','password' => $password,'name' => 'Rindang Ramadhan', 'alamat' => 'Jalan Way Seputih Pahoman', 'wilayah' => '103', 'tipe_user' => '4', 'id_warung' => '1', 'status_konfirmasi' => '1', 'no_telp' => '085383550858', 'kode_verifikasi' => '1001']);

        //UPDATE WARUNG
        Warung::find($warung->id)->update(['email' => '-','name'=>'RindangRamadhan','alamat'=>'Jalan Way Seputih Pahoman 3','wilayah'=>'10','no_telpon'=>'085383550858']);
        $this->assertDatabaseHas('warungs', ['email' => '-','name'=>'RindangRamadhan','alamat'=>'Jalan Way Seputih Pahoman 3','wilayah'=>'10','no_telpon'=>'085383550858']);

        //DELETE WARUNG
        $hapus_bank = Warung::where('id', $warung->id)->delete();

        //DELETE BANK WARUNG
        $hapus_bank_warung = BankWarung::where('warung_id', $warung->id)->delete();
        
        //DELETE BANK WARUNG
        $hapus_user_warung = UserWarung::where('id_warung', $warung->id)->delete();


        //CEK WARUNG
        $warung = Warung::find($warung->id);
        $this->assertDatabaseMissing('warungs', ['email' => '-','name'=>'RindangRamadhan','alamat'=>'Jalan Way Seputih Pahoman 3','wilayah'=>'10','no_telpon'=>'085383550858']);
        
        //CEK BANK WARUNG
        $bank_warung = BankWarung::find($bank_warung->id);
        $this->assertDatabaseMissing('bank_warungs', ['nama_bank'=>'BNI SYARIAH','atas_nama'=>'RindangRamadhan','no_rek'=>'05486751238','warung_id'=>'1']);

        //CEK BANK WARUNG
        $user_warung = UserWarung::find($user_warung->id);
        $this->assertDatabaseMissing('users', ['email' => 'rindangramadhan@gmail.com','password' => $password,'name' => 'RindangRamadhan', 'alamat' => 'Jalan Way Seputih Pahoman', 'wilayah' => '103', 'tipe_user' => '4', 'id_warung' => '1', 'status_konfirmasi' => '1', 'no_telp' => '085383550858', 'kode_verifikasi' => '1001']);

    }

  //TAMBAH WARUNG
    public function testHTTPTambahWarung() {

        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('warung.store'), [
         'name'      => 'Rindang CLOTHH',
         'alamat'    => 'Jl. Kemiling Raya',
         'kelurahan' => '10',
         'nama_bank' => 'BNI SYARIAH',
         'atas_nama' => 'RINDANG RAMADHAN',
         'no_rek'    => '0433857710',
         'no_telpon' => '085383550858',
         'email'     => 'rindangramadhan10@gmail.com'
         ]);

        $response->assertStatus(302)
                 ->assertRedirect(route('warung.index'));
        
        //TAMBAH WARUNG
        $warung = Warung::create(['email' => '-','name'=>'Rindang CLOTHH','alamat'=>'Jl. Kemiling Raya','wilayah'=>'10','no_telpon'=>'085383550858']);       
        $data_id = Warung::select('id')->where('name', $warung->name)->first();
        
        $response2 = $this->get($response->headers->get('location'))->assertSee('Berhasil : Menambah Warung Rindang CLOTHH');
        //CEK DB TABLE warungs
        $response_warung = $this->assertDatabaseHas("warungs",['name' => 'Rindang CLOTHH','alamat' => 'Jl. Kemiling Raya','wilayah' => '10','no_telpon' => '085383550858','email' => '-']);
        //CEK DB TABLE bank_warungs
        $response_bank_warung = $this->assertDatabaseHas("bank_warungs",['nama_bank' => 'BNI SYARIAH','atas_nama' => 'RINDANG RAMADHAN','no_rek'=>'0433857710', 'warung_id' => $data_id->id]);
        //CEK DB TABLE users
        $response_user_warung = $this->assertDatabaseHas("users",['name' => 'Rindang CLOTHH', 'email' => 'rindangramadhan10@gmail.com', 'no_telp' => '085383550858', 'alamat' => 'Jl. Kemiling Raya', 'wilayah' => '10', 'id_warung' => $data_id->id, 'tipe_user' => '4', 'status_konfirmasi' => '1' ]);
    }

    //HAPUS WARUNG
    public function testHTTPHapusWarung(){

        //TAMBAH WARUNG
        $warung = Warung::create(['email' => '-','name'=>'Rindang CLOTH','alamat'=>'Jl. Kemiling Raya','wilayah'=>'10','no_telpon'=>'085383550858']);

        //TAMBAH BANK WARUNG
        $bank_warung = BankWarung::create(['nama_bank'=>'BNI','atas_nama'=>'Rindang Ramadhan','no_rek'=>'0433156248','warung_id'=>'1']);
        
        //TAMBAH BANK WARUNG
        $password = bcrypt('123456');
        $user_warung = UserWarung::create(['email' => 'rindang@gmail.com','password' => $password,'name' => 'Rindang Ramadhan', 'alamat' => 'Jalan Way Seputih Pahoman', 'wilayah' => '103', 'tipe_user' => '4', 'id_warung' => '1', 'status_konfirmasi' => '1', 'no_telp' => '085383550858', 'kode_verifikasi' => '1001']);

        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('warung.destroy',$warung->id), ['_method' => 'DELETE']);

        //DELETE BANK WARUNG
        $hapus_bank_warung = BankWarung::where('warung_id', $warung->id)->delete();
        
        //DELETE BANK WARUNG
        $hapus_user_warung = UserWarung::where('id_warung', $warung->id)->delete();

        $response->assertStatus(302)
                 ->assertRedirect(route('warung.index'));
        
        $response2 = $this->get($response->headers->get('location'))->assertSee('Berhasil : Menghapus Warung ');       

    }

  //PROSES EDIT WARUNG
    public function testHTTPEditWarung(){
        
        //TAMBAH WARUNG
        $warung = Warung::create(['email' => '-','name'=>'Rindang CLOTH','alamat'=>'Jl. Kemiling Raya','wilayah'=>'10','no_telpon'=>'085383550858']);       
        $data_id = Warung::select('id')->where('id', $warung->id)->first();

        //TAMBAH BANK WARUNG
        $bank_warung = BankWarung::create(['nama_bank'=>'BNI','atas_nama'=>'Rindang Ramadhan','no_rek'=>'0433156248','warung_id'=>$data_id->id]);

        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('warung.update',$warung->id), ['_method' => 'PUT','name' => 'Rindang CLOTH Update', 'alamat' => 'Jl. Kemiling Raya Update', 'kelurahan' => '10', 'no_telpon' => '085383550858', 'nama_bank'=>'BNI', 'atas_nama'=>'Rindang Ramadhan', 'no_rek'=>'0433156248']);

        $response->assertStatus(302)
                 ->assertRedirect(route('warung.index'));

        $response2 = $this->get($response->headers->get('location'))->assertSee('Berhasil : Mengubah Warung Rindang CLOTH Update');
     
    }
    
        //HALAMAN MENU EDIT WARUNG
    public function testHTTPUpdateWarung(){

        //TAMBAH WARUNG
        $warung = Warung::create(['email' => '-','name'=>'Rindang CLOTH','alamat'=>'Jl. Kemiling Raya','wilayah'=>'10','no_telpon'=>'085383550858']);       
        $data_id = Warung::select('id')->where('id', $warung->id)->first();
        //TAMBAH BANK WARUNG
        $bank_warung = BankWarung::create(['nama_bank'=>'BNI','atas_nama'=>'Rindang Ramadhan','no_rek'=>'0433156248','warung_id'=>$data_id->id]);
        
        //TAMBAH BANK WARUNG
        $password = bcrypt('123456');

        $user_warung = UserWarung::create(['email' => 'rindang@gmail.com','password' => $password,'name' => 'Rindang Ramadhan', 'alamat' => 'Jalan Way Seputih Pahoman', 'wilayah' => '103', 'tipe_user' => '4', 'id_warung' => '1', 'status_konfirmasi' => '1', 'no_telp' => '085383550858', 'kode_verifikasi' => '1001']);
        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->get(route('warung.edit',$warung->id));

        $response->assertStatus(200)
                 ->assertSee('Edit Warung');

     
    }


}