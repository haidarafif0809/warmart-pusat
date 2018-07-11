<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\KategoriBarang;

class KelompokProdukTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testTambahKelompokProduk()
    {
       $this->browse(function ($first, $second) {
        $first->loginAs(User::find(1))
        ->visit('dashboard#/kelompok-produk/') 
        ->assertSee('Kelompok Produk')
        ->clickLink('Tambah Kelompok Produk')
        ->pause(1000)
        ->type('nama_kelompok','Kuliner')
        ->type('icon_kelompok','repeat')
        ->press('#btnSimpanKelompokProduk')
        ->pause(1000)
        ->assertSee('Menambah Kelompok Produk Kuliner')
        ->press('OK');
    }); 
   }

   public function testValidateInputanKosong()
   {
       $this->browse(function ($first, $second) {
        $first->loginAs(User::find(1))
        ->visit('dashboard#/kelompok-produk/') 
        ->assertSee('Kelompok Produk')
        ->clickLink('Tambah Kelompok Produk')
        ->pause(1000)
        ->press('#btnSimpanKelompokProduk')
        ->pause(1000)
        ->assertSee('NAMA KELOMPOK HARUS DIISI.')
        ->pause(1000)
        ->assertSee('ICON KELOMPOK HARUS DIISI.');
    }); 
   }
   public function testValidateInputanUnique()
   {
       $this->browse(function ($first, $second) {
        $first->loginAs(User::find(1))
        ->visit('dashboard#/kelompok-produk/') 
        ->assertSee('Kelompok Produk')
        ->clickLink('Tambah Kelompok Produk')
        ->pause(1000)
        ->type('nama_kelompok','Kuliner')
        ->type('icon_kelompok','repeat')
        ->press('#btnSimpanKelompokProduk')
        ->pause(1000)
        ->assertSee('MAAF NAMA KELOMPOK SUDAH TERPAKAI.');
    }); 
   }

   public function testEditKelompokProduk()
   {
    $kelompok_produk = KategoriBarang::select('id')->where('nama_kategori_barang','Kuliner')->first();

    $this->browse(function ($first, $second)use($kelompok_produk) {
        $first->loginAs(User::find(1))
        ->visit('/dashboard#/kelompok-produk')
        ->assertSeeLink('Tambah Kelompok Produk')
        ->pause(1000)
        ->with('.table', function ($table) use($kelompok_produk){
          $table->assertSee('Kuliner')
          ->press('#edit-'.$kelompok_produk->id);
      })
        ->assertSee('Edit Kelompok Produk')
        ->clear('nama_kelompok')
        ->type('nama_kelompok','Kelompok Produk Test')
        ->type('icon_kelompok','done')
        ->press('#btnSimpanKelompokProduk')
        ->pause(1000)
        ->assertSee('Berhasil Mengubah Kelompok Produk Kelompok Produk Test')
        ->press('OK');
    });
}

public function testPencarianKelompokProduk() {

  $this->browse(function ($komunitasTest) {
    $komunitasTest->loginAs(User::find(1))
    ->visit('/dashboard#/kelompok-produk')
    ->assertSeeLink('Tambah Kelompok Produk')
    ->pause(1000)
    ->type('pencarian','Kelompok Produk Test')
    ->pause(2000)
    ->with('.table', function ($table){
      $table->assertSee('Kelompok Produk Test');
  });

});

}

public function testHapusKelompokProduk() {
   $kelompok_produk = KategoriBarang::select('id')->where('nama_kategori_barang','Kelompok Produk Test')->first();
   $this->browse(function ($first)use($kelompok_produk) {
    $first->loginAs(User::find(1))
    ->visit('/dashboard#/kelompok-produk')
    ->assertSeeLink('Tambah Kelompok Produk')
    ->pause(1000)
    ->with('.table', function ($table) use($kelompok_produk){
      $table->assertSee('Kelompok Produk Test')
      ->pause(2000)
      ->press('#delete-'.$kelompok_produk->id)
      ->pause(1000);
  })->driver->switchTo()->alert()->accept();
    $first->pause(1000)
    ->assertSee('Menghapus Kelompok Produk Kelompok Produk Test')
    ->press('OK');
});

}
}
