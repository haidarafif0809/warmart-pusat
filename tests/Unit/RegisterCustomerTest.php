<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions; 
use URL;

class RegisterCustomerTest extends TestCase
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


    public function testHTTPRegisterCustomer(){
             

        $response = $this->json('POST', url('/register'),[
            'name' => 'UserCustomerTestHttp',
            'email' => 'usercustomertesthttp@gmail.com',
            'no_telp' => '082282107750',
            'alamat' => 'Test Alamat Http', 
            'password' => 'rahasia', 
            'password_confirmation' => 'rahasia', 
            'id_register' => '1', 

        ]);

        $response->assertStatus(302)
                 ->assertRedirect(url('kirim-kode-verifikasi?nomor=082282107750&status=0'));


        $response2 = $this->get($response->headers->get('location'))->assertSee('Silakan input nomor verifikasi yang terkirim melalui SMS ke no 082282107750');

           $this->assertDatabaseHas('users',[
	            'name' => 'UserCustomerTestHttp',
	            'email' => 'usercustomertesthttp@gmail.com',
	            'no_telp' => '082282107750',
	            'alamat' => 'Test Alamat Http',   
                'tipe_user' => '3'
            ]); 

    }
 
}
