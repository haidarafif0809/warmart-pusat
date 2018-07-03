<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Bank;
use App\User;
use URL;

class BankTest extends TestCase
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
    public function testBankCrud() {
    	
    	//TAMBAH BANK
        $bank = Bank::create(["nama_bank" => "BRI","atas_nama" => "Rindang Ramadhan","no_rek"=>"1234567890"]);
		$this->assertDatabaseHas('banks', ["nama_bank" => "BRI","atas_nama" => "Rindang Ramadhan","no_rek"=>"1234567890"]);

		//UPDATE BANK
		Bank::find($bank->id)->update(["nama_bank" => "BCA", "atas_nama"=>"Afrizal Ansyah", "no_rek"=>"9876543210"]);
		$this->assertDatabaseHas('banks', ["nama_bank" => "BCA", "atas_nama" => "Afrizal Ansyah", "no_rek" => "9876543210"]);

		//DELETE BANK
		$hapus_bank = Bank::destroy($bank->id);

        $bank = Bank::find($bank->id);
        $this->assertDatabaseMissing('banks', ["nama_bank" => "BCA", "atas_nama" => "Afrizal Ansyah", "no_rek" => "9876543210"]);

    }


//HTTP TESTING
    //TAMBAH BANK
    public function testHTTPTambahBank() {

    	//login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('bank.store'), ["nama_bank" => "BRI TESTING","atas_nama" => "Rindang Testing","no_rek"=>"7357146", "tampil_customer"=>"1"]);

        $response->assertStatus(200);

        $this->assertDatabaseHas("banks",["nama_bank" => "BRI TESTING","atas_nama" => "Rindang Testing","no_rek"=>"7357146"]);
    }

    //HAPUS BANK
    public function testHTTPHapusBank(){

    	$bank = Bank::create(["nama_bank" => "BCA TESTING", "atas_nama" => "Ramadhan", "no_rek" => "1237855463622"]);
        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('bank.destroy',$bank->id), ['_method' => 'DELETE']);

        $response->assertStatus(200);   

    }

    //HALAMAN MENU EDIT BANK
    public function testHTTPUpdateBank(){

        $bank = Bank::create(["nama_bank" => "Bank Lampung", "atas_nama" => "Maulana Pasa", "no_rek" => "15475398433265"]);
        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->get(route('bank.edit',$bank->id));

        $response->assertStatus(200)
                 ->assertSee('Edit Bank');

     
    }

    //PROSES EDIT BANK
    public function testHTTPEditBank(){
        
        $bank = Bank::create(["nama_bank" => "Bank Lampung", "atas_nama" => "Maulana Pasa", "no_rek" => "15475398433265"]);
        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('bank.update',$bank->id), ['_method' => 'PUT','nama_bank' => 'Bank Lampung Testing Update', 'atas_nama' => 'Pasa Maulana', 'no_rek' => '15186366591366', 'tampil_customer' => '1']);

        $response->assertStatus(200);


     
    }
}
