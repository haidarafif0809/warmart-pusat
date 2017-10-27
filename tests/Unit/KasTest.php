<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Kas;
use App\User;
use URL;

class KasTest extends TestCase
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

    public function testExample()
    {
        $this->assertTrue(true);
    }

//CrudTest
    // TEST CRUD KAS (JIKA DEFAULT KAS DIHAPUS)
    public function testCrudKas()
    {   
        // TEST INSERT KAS
        $kas_test = Kas::create(['kode_kas' => 'Kas001', 'nama_kas' => 'Kas Testing', 'status_kas' => '1', 'default_kas' => '1']);
        //CEK DATABASE
        $this->assertDatabaseHas('kas',[
        'kode_kas' => 'Kas001', 'nama_kas' => 'Kas Testing', 'status_kas' => '1', 'default_kas' => '1']);

        // TEST UPDATE KAS
        Kas::find($kas_test->id)->update([
            'kode_kas' => 'Kas002', 'nama_kas' => 'Kas Update', 'status_kas' => '0', 'default_kas' => '1']);
        //CEK DATABASE
        $this->assertDatabaseHas('kas',[
        'kode_kas' => 'Kas002', 'nama_kas' => 'Kas Update', 'status_kas' => '0', 'default_kas' => '1']);

        $data_kas = Kas::select('default_kas')->where('id', $kas_test->id)->first();
        $user = User::find(1);
        $response = $this->actingAs($user)->json('POST', route('kas.destroy',$kas_test->id), ['_method' => 'DELETE']);

        if ($data_kas->default_kas == 1) {
            $this->get($response->headers->get('location'))->assertSee('Gagal : Kas Yang Menjadi Defaul Kas Tidak Bisa Dihapus');
        }
        else{
            // TEST DELETE USER
            Kas::destroy($kas_test->id);
            $kas = Kas::find($kas_test->id);

            $this->assertDatabaseMissing('kas',['kode_kas' => 'Kas002', 'nama_kas' => 'Kas Update', 'status_kas' => '0', 'default_kas' => '1']);
        }        
        
    }

    // TEST CRUD HAPUS KAS (JIKA BUKAN DEFAULT KAS DIHAPUS)
    public function testCrudHapusKas()
    {   
        // TEST INSERT KAS
        $kas_test = Kas::create(['kode_kas' => 'Kas001', 'nama_kas' => 'Kas Testing', 'status_kas' => '1', 'default_kas' => '1']);
        $kas_test2 = Kas::create(['kode_kas' => 'Kas002', 'nama_kas' => 'Kas Testing 2', 'status_kas' => '1', 'default_kas' => '0']);
        
        // CEK DATA BASE
        $data_kas = Kas::select('default_kas')->where('id', $kas_test2->id)->first();
        $user = User::find(4);
        $response = $this->actingAs($user)->json('POST', route('kas.destroy',$kas_test2->id), ['_method' => 'DELETE']);

        if ($data_kas->default_kas == 1) {
            $this->get($response->headers->get('location'))->assertSee('Gagal : Kas Yang Menjadi Defaul Kas Tidak Bisa Dihapus');
        }
        else{
            // TEST DELETE USER
            Kas::destroy($kas_test2->id);
            $kas = Kas::find($kas_test2->id);

            $this->assertDatabaseMissing('kas',['kode_kas' => 'Kas002', 'nama_kas' => 'Kas Testing 2', 'status_kas' => '1', 'default_kas' => '0']);
        }        
    }

//HttpTest
    //TAMBAH KAS
    public function testHTTPTambahKas() {

        //login user -> admin
        $user = User::find(4);

        $response = $this->actingAs($user)->json('POST', route('kas.store'), [
         'kode_kas'   => 'K-001',
         'nama_kas'   => 'KAS KECIL',
         'status_kas' => '1',
         'default_kas'=> '1'
        ]);

        $response->assertStatus(302)
                 ->assertRedirect(route('kas.index'));
        
        //TAMBAH KAS
        $kas = Kas::create(['kode_kas' => 'K-001', 'nama_kas' => 'KAS KECIL', 'status_kas' => '1', 'default_kas' => '1' ]);
        $response2 = $this->get($response->headers->get('location'))->assertSee('Sukses : Berhasil Menambah Kas KAS KECIL');

        //CEK DB TABLE KAS
        $response_kas = $this->assertDatabaseHas("kas",['kode_kas' => 'K-001', 'nama_kas' => 'KAS KECIL', 'status_kas' => '1', 'default_kas' => '1' ]);
    }

    //HAPUS KAS (JIKA KAS DEFAULT DIHAPUS)
    public function testHTTPHapusKasDefault() {        
        
        //TAMBAH KAS
        $kas = Kas::create(['kode_kas' => 'K-001', 'nama_kas' => 'KAS KECIL', 'status_kas' => '1', 'default_kas' => '1' ]);
        //login user -> admin
        $user = User::find(4);

        $response = $this->actingAs($user)->json('POST', route('kas.destroy',$kas->id), ['_method' => 'DELETE']);

        $this->get($response->headers->get('location'))->assertSee('Gagal : Kas Yang Menjadi Defaul Kas Tidak Bisa Dihapus');              
        
    }

    //HAPUS KAS (JIKA BUKAN KAS DEFAULT DIHAPUS)
    public function testHTTPHapusKas() {
        
        //TAMBAH KAS
        $kas = Kas::create(['kode_kas' => 'K-001', 'nama_kas' => 'KAS KECIL', 'status_kas' => '1', 'default_kas' => '0' ]);

        //login user -> admin
        $user = User::find(4);

        $response = $this->actingAs($user)->json('POST', route('kas.destroy',$kas->id), ['_method' => 'DELETE']);

        $response->assertStatus(302)
                 ->assertRedirect(route('kas.index'));

        $response2 = $this->get($response->headers->get('location'))->assertSee('Sukses : Kas Berhasil Dihapus');    
        
    }

    //HALAMAN MENU EDIT KAS
    public function testHTTPUpdateKas(){
        //TAMBAH KAS
        $kas = Kas::create(['kode_kas' => 'K-001', 'nama_kas' => 'KAS KECIL', 'status_kas' => '1', 'default_kas' => '0' ]);

        //login user -> admin
        $user = User::find(4);

        $response = $this->actingAs($user)->get(route('kas.edit',$kas->id));

        $response->assertStatus(200)
                 ->assertSee('Edit Kas');
     
    }

    //PROSES EDIT KAS
    public function testHTTPEditKas(){
        //TAMBAH KAS
        $kas = Kas::create(['kode_kas' => 'K-001', 'nama_kas' => 'KAS KECIL', 'status_kas' => '1', 'default_kas' => '0' ]);

        //login user -> admin
        $user = User::find(4);

        $response = $this->actingAs($user)->json('POST', route('kas.update',$kas->id), ['_method' => 'PUT', 'kode_kas' => 'K-001', 'nama_kas' => 'KAS KECIL UPDATE', 'status_kas' => '1', 'default_kas' => '0']);

        $response->assertStatus(302)
                 ->assertRedirect(route('kas.index'));

        $response2 = $this->get($response->headers->get('location'))->assertSee('Sukses : Berhasil Mengubah Kas "KAS KECIL UPDATE"');
     
    }
}