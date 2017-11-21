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

class UserWarungTest extends TestCase
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

      public function testUpdateHapusUserWarung()
      {
        //TAMBAH USER WARUNG
        $password = bcrypt('123456');

        //TAMBAH WARUNG
        $warung = Warung::create(['email' => '-','name'=>'Rindang CLOTH','alamat'=>'Jl. Kemiling Raya','wilayah'=>'10','no_telpon'=>'085383550858']);       
        $data_id = Warung::select('id')->where('name', $warung->name)->first();

        $user_warung = UserWarung::create(['email' => 'rindang@gmail.com','password' => $password,'name' => 'Rindang CLOTH', 'alamat' => 'JJl. Kemiling Raya', 'wilayah' => '103', 'tipe_user' => '4', 'id_warung' => $data_id->id, 'status_konfirmasi' => '1', 'no_telp' => '085383550858', 'kode_verifikasi' => '1001']);

        //UPDATE USER WARUNG
        UserWarung::find($user_warung->id)->update(['name' => 'RindangCLOTH', 'email' => 'rindang@gmail.com', 'no_telp' => '085383550858', 'alamat' => 'Jl. Kemiling Raya Update', 'wilayah' => '103', 'id_warung' => $data_id->id]);
        $this->assertDatabaseHas('users', ['name' => 'RindangCLOTH', 'email' => 'rindang@gmail.com', 'no_telp' => '085383550858', 'alamat' => 'Jl. Kemiling Raya Update', 'wilayah' => '103', 'id_warung' => $data_id->id]);

        //DELETE USER WARUNG
        $hapus_user_warung = UserWarung::destroy($user_warung->id);

        //CEK USER WARUNG

    }

    //HAPUS WARUNG JIKA DATA USER WARUNG TINGGAL 1
    public function testHTTPHapusUserWarung(){

    	//TAMBAH USER WARUNG
        $password = bcrypt('123456');

        //TAMBAH WARUNG
        $warung = Warung::create(['email' => '-','name'=>'Rindang CLOTH','alamat'=>'Jl. Kemiling Raya','wilayah'=>'10','no_telpon'=>'085383550858']);       
        $data_id = Warung::select('id')->where('name', $warung->name)->first();

        $user_warung = UserWarung::create(['email' => 'rindang@gmail.com','password' => $password,'name' => 'Rindang CLOTH', 'alamat' => 'JJl. Kemiling Raya', 'wilayah' => '103', 'tipe_user' => '4', 'id_warung' => $data_id->id, 'status_konfirmasi' => '1', 'no_telp' => '085383550858', 'kode_verifikasi' => '1001']);

        //login user -> admin
        $user = User::find(1);

        $jumalh_user_warung = UserWarung::where('id_warung', $data_id->id)->count();

        $response = $this->actingAs($user)->json('POST', route('user-warung.destroy',$user_warung->id), ['_method' => 'DELETE']);

        if ($jumalh_user_warung > 1) {

        	$response->assertStatus(200);
            $this->assertDatabaseMissing('users', ['email' => 'rindang@gmail.com','password' => $password,'name' => 'Rindang CLOTH', 'alamat' => 'JJl. Kemiling Raya', 'wilayah' => '103', 'tipe_user' => '4', 'id_warung' => $data_id->id, 'status_konfirmasi' => '1', 'no_telp' => '085383550858', 'kode_verifikasi' => '1001']);
        }
        else{        	
            $this->assertDatabaseHas("users",['email' => 'rindang@gmail.com','password' => $password,'name' => 'Rindang CLOTH', 'alamat' => 'JJl. Kemiling Raya', 'wilayah' => '103', 'tipe_user' => '4', 'id_warung' => $data_id->id, 'status_konfirmasi' => '1', 'no_telp' => '085383550858', 'kode_verifikasi' => '1001']);
        }
    }

    //HAPUS WARUNG JIKA DATA USER WARUNG LEBIH DARI 1
    public function testHTTPHapusUserWarungg(){

        //TAMBAH USER WARUNG
        $password = bcrypt('123456');

        //TAMBAH WARUNG
        $warung = Warung::create(['email' => '-','name'=>'Rindang CLOTH','alamat'=>'Jl. Kemiling Raya','wilayah'=>'10','no_telpon'=>'085383550858']);       
        $data_id = Warung::select('id')->where('name', $warung->name)->first();

        $user_warung = UserWarung::create(['email' => 'rindang@gmail.com','password' => $password,'name' => 'Rindang CLOTH', 'alamat' => 'JJl. Kemiling Raya', 'wilayah' => '103', 'tipe_user' => '4', 'id_warung' => $data_id->id, 'status_konfirmasi' => '1', 'no_telp' => '085383550858', 'kode_verifikasi' => '1001']);

        $user_warung2 = UserWarung::create(['email' => 'rindang2@gmail.com','password' => $password,'name' => 'Rindang2 CLOTH', 'alamat' => 'Jl. Kemiling Raya', 'wilayah' => '103', 'tipe_user' => '4', 'id_warung' => $data_id->id, 'status_konfirmasi' => '1', 'no_telp' => '0853835508580', 'kode_verifikasi' => '1011']);
        //login user -> admin
        $user = User::find(1);

        $jumalh_user_warung = UserWarung::where('id_warung', $data_id->id)->count();

        $response = $this->actingAs($user)->json('POST', route('user-warung.destroy',$user_warung->id), ['_method' => 'DELETE']);

        if ($jumalh_user_warung > 1) {
            $response->assertStatus(200);
            $this->assertDatabaseMissing('users', ['email' => 'rindang@gmail.com','password' => $password,'name' => 'Rindang CLOTH', 'alamat' => 'JJl. Kemiling Raya', 'wilayah' => '103', 'tipe_user' => '4', 'id_warung' => $data_id->id, 'status_konfirmasi' => '1', 'no_telp' => '085383550858', 'kode_verifikasi' => '1001']);
        }
        else{           
            $this->assertDatabaseHas("users",['email' => 'rindang@gmail.com','password' => $password,'name' => 'Rindang CLOTH', 'alamat' => 'JJl. Kemiling Raya', 'wilayah' => '103', 'tipe_user' => '4', 'id_warung' => $data_id->id, 'status_konfirmasi' => '1', 'no_telp' => '085383550858', 'kode_verifikasi' => '1001']);
        } 
    }

    //HALAMAN MENU EDIT USER WARUNG
    public function testHTTPUpdateUserWarung(){
        //TAMBAH WARUNG
        $warung = Warung::create(['email' => '-','name'=>'Rindang CLOTH','alamat'=>'Jl. Kemiling Raya','wilayah'=>'10','no_telpon'=>'085383550858']);       
        $data_id = Warung::select('id')->where('id', $warung->id)->first();
        
        //TAMBAH BANK WARUNG
        $password = bcrypt('123456');
        $user_warung = UserWarung::create(['email' => 'rindang@gmail.com','password' => $password,'name' => 'Rindang Ramadhan', 'alamat' => 'Jalan Way Seputih Pahoman', 'wilayah' => '103', 'tipe_user' => '4', 'id_warung' => $data_id->id, 'status_konfirmasi' => '1', 'no_telp' => '085383550858', 'kode_verifikasi' => '1001']);

        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->get(route('user-warung.edit',$user_warung->id));

        $response->assertStatus(200);
    }

    //PROSES EDIT WARUNG
    public function testHTTPEditUserWarung(){

        //TAMBAH WARUNG
        $warung = Warung::create(['email' => '-','name'=>'Rindang CLOTH','alamat'=>'Jl. Kemiling Raya','wilayah'=>'10','no_telpon'=>'0853835508580']);       
        $data_id = Warung::select('id')->where('id', $warung->id)->first();

        //TAMBAH BANK WARUNG
        $password = bcrypt('123456');
        $user_warung = UserWarung::create(['email' => 'rindang10@gmail.com','password' => $password,'name' => 'Rindang CLOTH', 'alamat' => 'Jl. Kemiling Raya', 'wilayah' => '103', 'tipe_user' => '4', 'id_warung' => $data_id->id, 'status_konfirmasi' => '1', 'no_telp' => '0853835508580', 'kode_verifikasi' => '1001']);

        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('user-warung.update',$user_warung->id), 
            ['_method' => 'PUT',
            'name' => 'Rindang CLOTH Update',
            'alamat' => 'Jl. Kemiling Raya Update',
            'kelurahan' => '10',
            'email' => 'rindangramadhan10@gmail.com',
            'no_telp'=>'0853835508580',
            'id_warung'=>$user_warung->id_warung]);

        $response->assertStatus(200);

        $this->assertDatabaseHas("users",['name' => 'Rindang CLOTH Update', 'alamat' => 'Jl. Kemiling Raya Update', 'kelurahan' => '10', 'email' => 'rindangramadhan10@gmail.com', 'no_telp'=>'0853835508580']);

    }
}
