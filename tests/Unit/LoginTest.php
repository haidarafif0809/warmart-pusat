<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions; 
use URL;
use App\User;

class LoginTest extends TestCase
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

    public function testHTTPLogin(){
              
        $response = $this->json('POST', url('/login'),[ 
            'no_telp' => '087345365743', 
            'password' => 'rahasia',  

        ]);

        $response->assertStatus(302)
                 ->assertRedirect(url('home'));

 
    }

    public function testHTTPLoginSalahNoTelp(){
              
        $response = $this->json('POST', url('/login'),[ 
            'no_telp' => '087345365743', 
            'password' => 'rahasiasa',  

        ]);

        $response->assertStatus(302)
                 ->assertRedirect(url('/login'));

        $response2 = $this->get($response->headers->get('location'))->assertSee('These credentials do not match our records.');
 
    }
}
