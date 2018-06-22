<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Customer;
use App\User;
use URL;

class CustomerTest extends TestCase
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
    public function testCustomerCrud() {
    	
    	//TAMBAH CUSTOMER
    	$password = bcrypt('admintamvan');
        $customer = Customer::create(['email' => 'rindang@gmail.com', 'password' => $password, 'name' => 'RindangRamadhan', 'alamat' => 'Jl. Kedaton, Bandar Lampung', 'wilayah' => '1',  'no_telp' => '085383550858', 'tgl_lahir' => '1997-01-10', 'tipe_user' => '3', 'status_konfirmasi' => 0]);

        $this->assertDatabaseHas('users', ['email' => 'rindang@gmail.com', 'password' => $password, 'name' => 'RindangRamadhan', 'alamat' => 'Jl. Kedaton, Bandar Lampung', 'wilayah' => '1',  'no_telp' => '085383550858', 'tgl_lahir' => '1997-01-10', 'tipe_user' => '3', 'status_konfirmasi' => 0]);

		//UPDATE CUSTOMER
        Customer::find($customer->id)->update(['email' => 'rindang_update@gmail.com', 'password' => $password, 'name' => 'RindangRamadhan Update', 'alamat' => 'Jl. Kedaton, Bandar Lampung Update', 'wilayah' => '1',  'no_telp' => '085383550858', 'tgl_lahir' => '1997-01-10', 'tipe_user' => '3', 'status_konfirmasi' => 1]);
        $this->assertDatabaseHas('users', ['email' => 'rindang_update@gmail.com', 'password' => $password, 'name' => 'RindangRamadhan Update', 'alamat' => 'Jl. Kedaton, Bandar Lampung Update', 'wilayah' => '1', 'no_telp' => '085383550858', 'tgl_lahir' => '1997-01-10', 'tipe_user' => '3', 'status_konfirmasi' => 1]);

		//DELETE CUSTOMER
        $hapus_bank = Customer::destroy($customer->id);

        $customer = Customer::find($customer->id);
        $this->assertDatabaseMissing('users', ['email' => 'rindang_update@gmail.com', 'password' => $password, 'name' => 'RindangRamadhan Update', 'alamat' => 'Jl. Kedaton, Bandar Lampung Update', 'wilayah' => '1',  'no_telp' => '085383550858', 'tgl_lahir' => '1997-01-10', 'tipe_user' => '3', 'status_konfirmasi' => 1]);

    }


//HTTP TESTING
    //TAMBAH CUSTOMER
    public function testHTTPTambahCustomer() {

        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('customer.store'),
         ['email' => 'rindang@gmail.com',
         'name' => 'RindangRamadhan', 
         'alamat' => 'Jl. Kedaton, Bandar Lampung', 
         'kelurahan' => '1', 
         'no_telp' => '085383550858',
         'tgl_lahir' => '1997-01-10',
         'komunitas' => 2]);

        $response->assertStatus(200);

        $customer = Customer::where("email",'rindang@gmail.com')->first();

        //cek apakah data customer nya masuk
        $this->assertDatabaseHas("users",['name' => 'RindangRamadhan', 'email' => 'rindang@gmail.com', 'alamat' => 'Jl. Kedaton, Bandar Lampung', 'status_konfirmasi' => '0', 'no_telp' => '085383550858', 'tipe_user' => '3', 'tgl_lahir' => '1997-01-10']);

        //cek apakah data komunitas nya masuk
        $this->assertDatabaseHas("komunitas_customers",['komunitas_id' => 2, 'user_id' => $customer->id]);
    }

    //HAPUS CUSTOMER
    public function testHTTPHapusCustomer(){

        $password = bcrypt('admintamvan');
        $customer = Customer::create(['email' => 'rindang_hapus@gmail.com', 'password' => $password, 'name' => 'RindangRamadhan Hapus', 'alamat' => 'Jl. Kedaton, Bandar Lampung Hapus', 'wilayah' => '1', 'no_telp' => '085383550858', 'tgl_lahir' => '1997-01-10', 'tipe_user' => '3', 'status_konfirmasi' => 0]);
        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('customer.destroy',$customer->id), ['_method' => 'DELETE']);

        $response->assertStatus(200); 

        $this->assertDatabaseMissing('users', ['email' => 'rindang_hapus@gmail.com', 'password' => $password, 'name' => 'RindangRamadhan Hapus', 'alamat' => 'Jl. Kedaton, Bandar Lampung Hapus', 'wilayah' => '1', 'no_telp' => '085383550858', 'tgl_lahir' => '1997-01-10', 'tipe_user' => '3', 'status_konfirmasi' => 0]);

    }

    //HALAMAN MENU EDIT CUSTOMER
    public function testHTTPEditCustomer(){

        $password = bcrypt('admintamvan');
        $customer = Customer::create(['email' => 'rindang_update@gmail.com', 'password' => $password, 'name' => 'RindangRamadhan Update', 'alamat' => 'Jl. Kedaton, Bandar Lampung Update', 'wilayah' => '1', 'warung' => '0', 'no_telp' => '085383550858', 'tgl_lahir' => '1997-01-10', 'tipe_user' => '3', 'status_konfirmasi' => 0]);
        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->get(route('customer.edit',$customer->id));

        $response->assertStatus(200);

    }

    //PROSES EDIT CUSTOMER
    public function testHTTPUpdateCustomer(){

        function tanggal_mysql($tanggal){
           $date= date_create($tanggal);
           $date_terbalik =  date_format($date,"Y-m-d");
           return $date_terbalik;
       }

       $password = bcrypt('admintamvan');
       $tanggal = tanggal_mysql("10/01/1997");

       $customer = Customer::create(['email' => 'rindang_edit@gmail.com', 'password' => $password, 'name' => 'RindangRamadhan Edit', 'alamat' => 'Jl. Kedaton, Bandar Lampung Edit', 'wilayah' => '1','no_telp' => '085383550858', 'tgl_lahir' => '1997-01-10', 'tipe_user' => '3', 'status_konfirmasi' => 0]);

       $this->assertDatabaseHas("users",['email' => 'rindang_edit@gmail.com', 'password' => $password, 'name' => 'RindangRamadhan Edit', 'alamat' => 'Jl. Kedaton, Bandar Lampung Edit', 'wilayah' => '1','no_telp' => '085383550858', 'tgl_lahir' => '1997-01-10', 'tipe_user' => '3', 'status_konfirmasi' => 0]);


        //login user -> admin
       $user = User::find(1);

       $response = $this->actingAs($user)->json('POST', route('customer.update',$customer->id), 
        ['email' => 'rindang_edit@gmail.com',
        'name' => 'RindangRamadhan Edit', 
        'alamat' => 'Jl. Kedaton, Bandar Lampung Edit',
        'kelurahan' => '1', 
        'no_telp' => '085383550858', 
        'tgl_lahir' => $tanggal,
        '_method' => 'PUT',
        'komunitas' => 2]);

       $response->assertStatus(200);


       $this->assertDatabaseHas("users",['name' => 'RindangRamadhan Edit', 'email' => 'rindang_edit@gmail.com', 'alamat' => 'Jl. Kedaton, Bandar Lampung Edit', 'status_konfirmasi' => '0', 'wilayah' => '1', 'no_telp' => '085383550858', 'tipe_user' => '3', 'tgl_lahir' => $tanggal]);

   }
}