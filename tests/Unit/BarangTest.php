<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Barang;
use App\User;
use URL;

class BarangTest extends TestCase
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


    public function testCrudProduk()
    {
    	// TEST INSERT BARANG
    	$produk_test = Barang::create([
    		'kode_barang'=>'B0000', 'kode_barcode'=>'123456789', 'nama_barang'=>'Test Produk', 'harga_beli'=>'1000', 'harga_jual'=>'2000',
    		'satuan_id'=>'1', 'kategori_barang_id'=>'1', 'status_aktif'=>'1', 'hitung_stok'=>'1', 'id_warung'=>'4'
    	]);
    	//CEK DATABASE
    	$this->assertDatabaseHas('barangs',[
    		'kode_barang'=>'B0000', 'kode_barcode'=>'123456789', 'nama_barang'=>'Test Produk', 'harga_beli'=>'1000', 'harga_jual'=>'2000',
    		'satuan_id'=>'1', 'kategori_barang_id'=>'1', 'status_aktif'=>'1', 'hitung_stok'=>'1', 'id_warung'=>'4'
    	]);

    	// TEST UPDATE BARANG
    	Barang::find($produk_test->id)->update([
    		'kode_barang'=>'BX000', 'kode_barcode'=>'0000175', 'nama_barang'=>'Test Edit Produk', 'harga_beli'=>'10000', 'harga_jual'=>'20000',
    		'satuan_id'=>'1', 'kategori_barang_id'=>'1', 'status_aktif'=>'1', 'hitung_stok'=>'0', 'id_warung'=>'4'
    	]);
    	//CEK DATABASE
    	$this->assertDatabaseHas('barangs',[
    		'kode_barang'=>'BX000', 'kode_barcode'=>'0000175', 'nama_barang'=>'Test Edit Produk', 'harga_beli'=>'10000', 'harga_jual'=>'20000',
    		'satuan_id'=>'1', 'kategori_barang_id'=>'1', 'status_aktif'=>'1', 'hitung_stok'=>'0', 'id_warung'=>'4'
    	]);

    	// TEST DELETE USER
    	Barang::destroy($produk_test->id);
    	$barang = Barang::find($produk_test->id);
    	// cek DATA BASE
    	$this->assertDatabaseMissing('barangs',[
    		'kode_barang'=>'BX000', 'kode_barcode'=>'0000175', 'nama_barang'=>'Test Edit Produk', 'harga_beli'=>'10000', 'harga_jual'=>'20000',
    		'satuan_id'=>'1', 'kategori_barang_id'=>'1', 'status_aktif'=>'1', 'hitung_stok'=>'0', 'id_warung'=>'4'
    	]);
    
    }


    public function testHTTPtambahBarang()
    {

        $user = User::find(5);

        $response = $this->actingAs($user)->json('POST', route('barang.store'),[
            'kode_barcode'          => '124958781489',
            'kode_barang'           => 'BB27',
            'nama_barang'           => 'Test Http',
            'harga_beli'            => '1000',
            'harga_jual'            => '2000',
            'hitung_stok'           => '1',
            'kategori_barang_id'    => '1',
            'satuan_id'             => '1',
            'status_aktif'          => '1',
            'id_warung'             => '1'
        ]);
        

        $response->assertStatus(302)
                 ->assertRedirect(route('barang.index'));


        $response2 = $this->get($response->headers->get('location'))->assertSee('<b>BERHASIL:</b> Menambahkan Produk <b>Test Http</b>');

           $this->assertDatabaseHas('barangs',[
            'kode_barcode'          => '124958781489',
            'kode_barang'           => 'BB27',
            'nama_barang'           => 'Test Http',
            'harga_beli'            => '1000',
            'harga_jual'            => '2000',
            'hitung_stok'           => '1',
            'kategori_barang_id'    => '1',
            'satuan_id'             => '1',
            'status_aktif'          => '1',
            'id_warung'             => '1'
            ]);
    }

     public function testHTTPeditBarang()
     {

        $user = User::find(5);

        $response = $this->actingAs($user)->json('POST', route('barang.update',1),[
            'kode_barcode'          => '23',
            'kode_barang'           => 'BB2723',
            'nama_barang'           => 'Test Http Edit',
            'harga_beli'            => '10000',
            'harga_jual'            => '20000',
            'hitung_stok'           => '0',
            'kategori_barang_id'    => '2',
            'satuan_id'             => '2',
            'status_aktif'          => '0',
            'id_warung'             => '1',
            '_method' => 'PUT'
        ]);

        $response->assertStatus(302)
                ->assertRedirect(route('barang.index'));

        $response2 = $this->get($response->headers->get('location'))->assertSee('<b>BERHASIL:</b> Mengubah Produk <b>Test Http Edit</b>');

           $this->assertDatabaseHas('barangs',[
            'kode_barcode'          => '23',
            'kode_barang'           => 'BB2723',
            'nama_barang'           => 'Test Http Edit',
            'harga_beli'            => '10000',
            'harga_jual'            => '20000',
            'hitung_stok'           => '0',
            'kategori_barang_id'    => '2',
            'satuan_id'             => '2',
            'status_aktif'          => '0',
            'id_warung'             => '1'
            ]);
     }

      public function testHTTPhapusBarang(){

        $user = User::find(5);
        $barang_hapus = Barang::find(1);// user yang dihapus

        $response = $this->actingAs($user)->json('POST', route('barang.destroy',1) , ['_method' => 'DELETE']);

        $response->assertStatus(302)
                 ->assertRedirect(route('barang.index'));

        $response = $this->get($response->headers->get('location'))->assertSee('<b>BERHASIL:</b> Menghapus Produk <b>'.$barang_hapus->nama_barang.'</b>');

        $this->assertDatabaseMissing('barangs',['id' => 1]);


    }
}
