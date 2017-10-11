<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Kas;
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

    // TEST CRUD KAS
    public function testCrudKas()
    {	
    	// TEST INSERT KAS
    	$kas_test = Kas::create(['kode_kas' => '1.3', 'nama_kas' => 'Kas Insert', 'status_kas' => '1', 'default_kas' => '1']);
    	//CEK DATABASE
    	$this->assertDatabaseHas('kas',[
    	'kode_kas' => '1.3', 'nama_kas' => 'Kas Insert', 'status_kas' => '1', 'default_kas' => '1']);

    	// TEST UPDATE KAS
    	Kas::find($kas_test->id)->update([
    		'kode_kas' => '1.4', 'nama_kas' => 'Kas Update', 'status_kas' => '2', 'default_kas' => '2']);
    	//CEK DATABASE
    	$this->assertDatabaseHas('kas',[
    	'kode_kas' => '1.4', 'nama_kas' => 'Kas Update', 'status_kas' => '2', 'default_kas' => '2']);

    	// TEST DELETE USER
    	Kas::destroy($kas_test->id);
    	$kas = Kas::find($kas_test->id);
    	// cek DATA BASE
    	$this->assertDatabaseMissing('kas',[
    		'kode_kas' => '1.4', 'nama_kas' => 'Kas Update', 'status_kas' => '2', 'default_kas' => '2']);
    }
}
