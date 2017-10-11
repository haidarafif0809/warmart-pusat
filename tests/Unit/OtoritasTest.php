<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Role;
use App\User;
use URL;

class OtoritasTest extends TestCase
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
    public function testOtoritasCrud() {
    	
    	//TAMBAH Role
        $role = Role::create(["name" => "contoh","display_name"=>"Contoh"]);
		$this->assertDatabaseHas('roles', ["name" => "contoh","display_name"=>"Contoh"]);

		//UPDATE Role
		Role::find($role->id)->update(["name" => "contoh_edit","display_name"=>"Contoh Edit"]);
		$this->assertDatabaseHas('roles', ["name" => "contoh_edit","display_name"=>"Contoh Edit"]);

		//DELETE Role
		$hapus_role = Role::destroy($role->id);

        $role = Role::find($role->id);
        $this->assertDatabaseMissing('roles', ["name" => "contoh_edit","display_name"=>"Contoh Edit"]);

    }

    //TAMBAH Role 
    public function testHTTPTambahRole() {

        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('otoritas.store'), ["name" => "member1","display_name"=>"Member 1"]);

        $response->assertStatus(302)
                 ->assertRedirect(route('otoritas.index'));
        

        $response2 = $this->get($response->headers->get('location'))->assertSee('Berhasil Menambah Otoritas');

        $this->assertDatabaseHas("roles",["name" => "member1","display_name"=>"Member 1"]);
    }

    //HAPUS Role
    public function testHTTPHapusRole(){

        $role = Role::create(["name" => "member2","display_name"=>"Member 2"]);
        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('otoritas.destroy',$role->id), ['_method' => 'DELETE']);

        $response->assertStatus(302)
                 ->assertRedirect(route('otoritas.index'));
        
        $response2 = $this->get($response->headers->get('location'))->assertSee('Otoritas Berhasil Di Hapus');       

    }


    //HALAMAN MENU EDIT BANK
    public function testHTTPUpdateRole (){

        $role = Role::create(["name" => "member3","display_name"=>"Member 3"]);
        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->get(route('otoritas.edit',$role->id));

        $response->assertStatus(200)
                 ->assertSee('Edit Otoritas');
 
    }

    //PROSES EDIT BANK
    public function testHTTPEditRole (){
        
        $role = Role::create(["name" => "member4","display_name"=>"Member 4"]);
        //login user -> admin
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('otoritas.update',$role->id), ['_method' => 'PUT','name' => 'member4','display_name'=>'Member 4']);

        $response->assertStatus(302)
                 ->assertRedirect(route('otoritas.index'));

        $response2 = $this->get($response->headers->get('location'))->assertSee('Berhasil Mengubah Otoritas ');
     
    }
}
