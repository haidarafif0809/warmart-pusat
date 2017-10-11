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
}
