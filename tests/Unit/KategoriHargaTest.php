<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\KategoriHarga;
use URL;

class KategoriHargaTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }


//CRUD TESTING
    public function testKategoriHargaCrud() {
    	
    	//TAMBAH KategoriHarga
        $kategoriharga = KategoriHarga::create(["nama_kategori_harga" => "Perkantotan"]);
		$this->assertDatabaseHas('kategori_hargas', ["nama_kategori_harga" => "Perkantotan"]);

		//UPDATE KategoriHarga
		KategoriHarga::find($kategoriharga->id)->update(["nama_kategori_harga" => "Perkampungan"]);
		$this->assertDatabaseHas('kategori_hargas', ["nama_kategori_harga" => "Perkampungan"]);

		//DELETE KategoriHarga
		$hapus_KategoriHarga = KategoriHarga::destroy($kategoriharga->id);

        $kategoriharga = KategoriHarga::find($kategoriharga->id);
        $this->assertDatabaseMissing('kategori_hargas', ["nama_kategori_harga" => "Perkampungan"]);

    }
}
