<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use URL;

class UserTest extends TestCase
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

    public function testCrudUser(){

    	// password
    	$password = bcrypt("rahasia");
    	// Test Insert User
    	$test_user = User::create(['name' => 'UserTest', 'password' => $password, 'email' => 'usertest@gmail.com', 'no_telp' => '123456789' , 'alamat' => 'Test Alamat', 'status_konfirmasi' => 0, 'tipe_user' => '1']);

    	// cek insert user
    	$this->assertDatabaseHas('users',[
    			'name' => 'UserTest', 'password' => $password, 'email' => 'usertest@gmail.com',  'no_telp' => '123456789' ,'alamat' => 'Test Alamat', 'status_konfirmasi' => 0, 'tipe_user' => '1'
    		]);

    	// test update user
    	$password = bcrypt("123456");
    	User::find($test_user->id)->update([
    			'name' => 'TestEditUser', 'password' => $password, 'email' => 'edituser@gmail.com', 'no_telp' => '0123458912645' , 'alamat' => 'Test Alamat Edit', 'status_konfirmasi' => 1, 'tipe_user' => '1'
    		]);

    	// cek update user
    	$this->assertDatabaseHas('users',[
    			'name' => 'TestEditUser', 'password' => $password, 'email' => 'edituser@gmail.com',  'no_telp' => '0123458912645' ,'alamat' => 'Test Alamat Edit', 'status_konfirmasi' => 1, 'tipe_user' => '1'
    		]);

    	// test delete user
    	User::destroy($test_user->id);
    	$user = User::find($test_user->id);
    	// cek delete user
    	$this->assertDatabaseMissing('users',[
    			'name' => 'TestEditUser', 'password' => $password, 'email' => 'edituser@gmail.com', 'no_telp' => '0123458912645' ,'alamat' => 'Test Alamat Edit', 'status_konfirmasi' => 1, 'tipe_user' => '1'	
    		]);


    }


    public function testHTTPtambahUser(){
            
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('user.store'),[
            'name' => 'UserTestHttp',
            'email' => 'usertesthttp@gmail.com',
            'no_telp' => '085769105615',
            'alamat' => 'Test Alamat Http',
            'tipe_user' => '1',
            'role_id' => '1'
        ]);

        $response->assertStatus(302)
                 ->assertRedirect(route('user.index'));


        $response2 = $this->get($response->headers->get('location'))->assertSee('<b>BERHASIL:</b> Menambah User <b>UserTestHttp</b>');

           $this->assertDatabaseHas('users',[
                'name' => 'UserTestHttp',
                'email' => 'usertesthttp@gmail.com',
                'no_telp' => '085769105615',
                'alamat' => 'Test Alamat Http',
                'tipe_user' => '1'
            ]);

    }


    public function testHTTPUpdateUser(){

        $user = User::find(1); 

        $response = $this->actingAs($user)->get(route('user.edit',1)); 
 
        $response->assertStatus(200)->assertSee('Edit User'); 

     }

    public function testHTTPeditUser(){

        $user = User::find(1);

        // Test Insert User
        $test_user = User::create(['name' => 'UserTest', 'password' =>  bcrypt("rahasia"), 'email' => 'usertest@gmail.com', 'no_telp' => '123456789' , 'alamat' => 'Test Alamat', 'status_konfirmasi' => 0, 'tipe_user' => '1']);

        $response = $this->actingAs($user)->json('POST', route('user.update',$test_user->id),[
            'name'       => 'UserTestHttp',
            'email'      => 'usertesthttp@gmail.com',
            'no_telp'    => '085769105615',
            'alamat'     => 'Test Alamat Http',
            'tipe_user'  => '1',
            'role_id'    => '1',
            'role_lama'  => '2',
            '_method' => 'PUT'
        ]);

        $response->assertStatus(302)
                ->assertRedirect(route('user.index'));

        $response2 = $this->get($response->headers->get('location'))->assertSee('<b>BERHASIL:</b> Mengubah User <b>UserTestHttp</b>');

           $this->assertDatabaseHas('users',[
                'name' => 'UserTestHttp',
                'email' => 'usertesthttp@gmail.com',
                'no_telp' => '085769105615',
                'alamat' => 'Test Alamat Http',
                'tipe_user'  => '1'
            ]);
    }

    public function testHTTPhapusUser(){

        $user = User::find(1);
        $user_hapus = User::find(2);// user yang dihapus

        $response = $this->actingAs($user)->json('POST', route('user.destroy',2) , ['_method' => 'DELETE']);

        $response->assertStatus(302)
                 ->assertRedirect(route('user.index'));

        $response = $this->get($response->headers->get('location'))->assertSee('User '. $user_hapus->name .' Berhasil Di Hapus');

        $this->assertDatabaseMissing('users',['id' => 2]);


    }

    public function testHTTPkonfirmasiUser(){

        $user = User::find(1);
        $user_konfirmasi = User::find(2);// user yang dikonfirmassi

        $response = $this->actingAs($user)->get( route('user.konfirmasi',2) );

        $response->assertStatus(302)
                 ->assertRedirect(route('user.index'));

        $response = $this->get($response->headers->get('location'))->assertSee('User '. $user_konfirmasi->name .' Berhasil Di Konfirmasi');

        $this->assertDatabaseHas('users',['id' => 2, 'status_konfirmasi' => '1']);

    }

    public function testHTTPtidakJadiKonfirmasiUser(){

        $user = User::find(1);
        $user_konfirmasi = User::find(2);// user yang dikonfirmassi

        $response = $this->actingAs($user)->get( route('user.no_konfirmasi',2) );

        $response->assertStatus(302)
                 ->assertRedirect(route('user.index'));

        $response = $this->get($response->headers->get('location'))->assertSee('User '. $user_konfirmasi->name .' Tidak Di Konfirmasi');

        $this->assertDatabaseHas('users',['id' => 2, 'status_konfirmasi' => '0']);

    }

    public function testHTTPresetPassword(){

        $user = User::find(1);
        $user_reset = User::find(2);// user yang tidak dikonfirmassi

        $response = $this->actingAs($user)->get( route('user.reset',2) );

        $response->assertStatus(302)
                 ->assertRedirect(route('user.index'));

        $response = $this->get($response->headers->get('location'))->assertSee('Password '. $user_reset->name .' Berhasil Di Reset');

        $this->assertDatabaseMissing('users',['id' => 2, 'password' => $user_reset->password ]);

    }

    

}
