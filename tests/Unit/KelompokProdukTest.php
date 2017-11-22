<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\KategoriBarang;
use App\Barang;
use App\User;
use URL;

class KelompokProdukTest extends TestCase
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

    public function testCrudKelompokProduk()
    {
    	$kelompok_produk = KategoriBarang::create([
    		'nama_kategori_barang' => "KelompokProdukTest",
    		'kategori_icon' => "repeat"
    	]);

    	$this->assertDatabaseHas('kategori_barangs',['nama_kategori_barang' => 'KelompokProdukTest','kategori_icon'=>'repeat']);

    	// TEST UPDATE KELOMPOK PRODUK
    	KategoriBarang::find($kelompok_produk->id)->update(['nama_kategori_barang' => 'TEST EDIT','kategori_icon'=>'dns']);
        //CEK DATABASE UPDATE
    	$this->assertDatabaseHas('kategori_barangs',['nama_kategori_barang' => 'TEST EDIT','kategori_icon'=>'dns']);

        // TEST DELETE KELOMPOK PRODUK
    	KategoriBarang::destroy($kelompok_produk->id);
        // cek DATA BASE
    	$this->assertDatabaseMissing('kategori_barangs',[
    		'nama_kategori_barang' => 'TEST EDIT','kategori_icon'=>'dns'
    	]);

    }

    public function testViewKelompokProduk(){
    	$user = User::find(1);
    	$kelompok_produk = KategoriBarang::orderBy('id','desc')->paginate(10);
    	$array = array();
    	foreach ($kelompok_produk as $kelompok_produks) {

    		$barang = Barang::where('kategori_barang_id',$kelompok_produks->id)->count();
    		if ($barang > 0) {
    			$status_kelompok_produk = 1;
    		}else{
    			$status_kelompok_produk = 0;
    		}

    		array_push($array,[
    			'id' => $kelompok_produks->id,
    			'nama_kategori_barang' => $kelompok_produks->nama_kategori_barang,
    			'kategori_icon' => $kelompok_produks->kategori_icon,
    			'status_kelompok_produk' => $status_kelompok_produk]);
    	}
    	    	     //DATA PAGINATION 
    	$first_page_url = url('/kelompok-produk/view?page='.$kelompok_produk->firstItem());
    	$first_page_url = str_replace('http:','',$first_page_url);

    	$last_page_url = url('/kelompok-produk/view?page='.$kelompok_produk->lastPage());
    	$last_page_url = str_replace('http:','',$last_page_url);

    	if ($kelompok_produk->nextPageUrl() == null) {
    		$next_page_url = null;
    	}else{
    		$next_page_url = "//localhost/kelompok-produk/view?page=2";
    	}

    	$respons['current_page'] = $kelompok_produk->currentPage();
    	$respons['data'] = $array; 
    	$respons['first_page_url'] = $first_page_url;
    	$respons['from'] = 1;
    	$respons['last_page'] = $kelompok_produk->lastPage();
    	$respons['last_page_url'] = $last_page_url;
    	$respons['next_page_url'] = $next_page_url;
    	$respons['path'] = '//localhost/kelompok-produk/view';
    	$respons['per_page'] = $kelompok_produk->perPage();
    	$respons['prev_page_url'] = $kelompok_produk->previousPageUrl();
    	$respons['to'] = $kelompok_produk->perPage();
    	$respons['total'] = $kelompok_produk->total();
                  //DATA PAGINATION 

    	$response = $this->actingAs($user)->get(url('kelompok-produk/view?page=1'));
    	$response->assertStatus(200)
    	->assertJsonFragment(
    		$respons
    	);

    }

    public function testShowDataKelompokProduk(){
    	$user = User::find(1);
    	$kelompok_produk = KategoriBarang::create([
    		'nama_kategori_barang' => "KelompokProdukTest",
    		'kategori_icon' => "repeat"
    	]);

    	$array = array('id'=> $kelompok_produk->id,
    		'nama_kategori_barang'=> $kelompok_produk->nama_kategori_barang,
    		'created_by'=> $kelompok_produk->created_by,
    		'updated_by'=> $kelompok_produk->updated_by,
    		'kategori_icon'=> $kelompok_produk->kategori_icon,
    		'nama_kelompok' => $kelompok_produk->nama_kategori_barang,      
    		'icon_kelompok' => $kelompok_produk->kategori_icon);

    	$response = $this->actingAs($user)->get(url('kelompok-produk/'.$kelompok_produk->id));
    	$response->assertStatus(200)
    	->assertJson(
    		$array
    	);
    }

    public function testPencarianKelompokProduk(){
    	$user = User::find(1);   
    	$kelompok_produk = KategoriBarang::create([
    		'nama_kategori_barang' => "KelompokProdukTest",
    		'kategori_icon' => "repeat"
    	]);
    	$search = 'KelompokProdukTest';
    	$kelompok_produk = KategoriBarang::where('nama_kategori_barang','LIKE',$search.'%')->orderBy('id','desc')->paginate(10);

    	$array = array();
    	foreach ($kelompok_produk as $kelompok_produks) {

    		$barang = Barang::where('kategori_barang_id',$kelompok_produks->id)->count();
    		if ($barang > 0) {
    			$status_kelompok_produk = 1;
    		}else{
    			$status_kelompok_produk = 0;
    		}

    		array_push($array,[
    			'id' => $kelompok_produks->id,
    			'nama_kategori_barang' => $kelompok_produks->nama_kategori_barang,
    			'kategori_icon' => $kelompok_produks->kategori_icon,
    			'status_kelompok_produk' => $status_kelompok_produk]);
    	}
    	    	     //DATA PAGINATION 
    	$first_page_url = url('/kelompok-produk/view?page='.$kelompok_produk->firstItem());
    	$first_page_url = str_replace('http:','',$first_page_url);

    	$last_page_url = url('/kelompok-produk/view?page='.$kelompok_produk->lastPage());
    	$last_page_url = str_replace('http:','',$last_page_url);

    	if ($kelompok_produk->nextPageUrl() == null) {
    		$next_page_url = null;
    	}else{
    		$next_page_url = "//localhost/kelompok-produk/pencarian?page=2";
    	}

    	$respons['current_page'] = $kelompok_produk->currentPage();
    	$respons['data'] = $array; 
    	$respons['first_page_url'] = $first_page_url;
    	$respons['from'] = 1;
    	$respons['last_page'] = $kelompok_produk->lastPage();
    	$respons['last_page_url'] = $last_page_url;
    	$respons['next_page_url'] = $next_page_url;
    	$respons['path'] = '//localhost/kelompok-produk/view';
    	$respons['per_page'] = $kelompok_produk->perPage();
    	$respons['prev_page_url'] = $kelompok_produk->previousPageUrl();
    	$respons['to'] = $kelompok_produk->perPage();
    	$respons['total'] = $kelompok_produk->total();
                  //DATA PAGINATION 

    	$response = $this->actingAs($user)->get(url('kelompok-produk/pencarian?search=KelompokProdukTest&page=1'));
    	$response->assertStatus(200)
    	->assertJsonFragment(
    		$respons
    	);

    }

    public function testDeleteKelompokProduk(){
    	$user = User::find(1);   
    	$kelompok_produk = KategoriBarang::create([
    		'nama_kategori_barang' => "KelompokProdukTest",
    		'kategori_icon' => "repeat"
    	]);
    	$response = $this->actingAs($user)->json('POST', route('kelompok-produk.destroy',$kelompok_produk->id), ['_method' => 'DELETE']);
    	$response->assertStatus(200);
    	$this->assertDatabaseMissing('kategori_barangs',[
    		'nama_kategori_barang' => 'KelompokProdukTest','kategori_icon'=>'repeat'
    	]);

    	
    }

    public function testCreateKelompokProduk(){

    	$user = User::find(1);
    	$response = $this->actingAs($user)->json('POST', route('kelompok-produk.store'), ["nama_kelompok" => "KelompokProdukTest","icon_kelompok"=>"dns"]);
    	$response->assertStatus(200);
    	$this->assertDatabaseHas("kategori_barangs",["nama_kategori_barang" => "KelompokProdukTest","kategori_icon"=>"dns"]);

    }


    public function testUpdateKelompokProduk(){

    	$user = User::find(1);
    	$kelompok_produk = KategoriBarang::create([
    		'nama_kategori_barang' => "KelompokProdukTest",
    		'kategori_icon' => "repeat"
    	]);

    	$this->assertDatabaseHas("kategori_barangs",["nama_kategori_barang" => "KelompokProdukTest","kategori_icon"=>"repeat"]);

    	$response = $this->actingAs($user)->json('POST', route('kelompok-produk.update',$kelompok_produk->id), ['_method' => 'PUT','nama_kelompok' => 'Test Edit Kelompok Produk','icon_kelompok'=>'dns']);
    	$response->assertStatus(200);
    	
    	$this->assertDatabaseHas("kategori_barangs",["nama_kategori_barang" => "Test Edit Kelompok Produk","kategori_icon"=>"dns"]);

    }
}
