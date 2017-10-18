<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;

class BarangTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
 
    public function testTambahProduk(){

        $this->browse(function ($first, $second) {
            $first->loginAs(User::find(5))
                  ->visit('/barang') 
                  ->clickLink('Tambah Produk')
                  ->type('kode_barcode','123789')
                  ->type('kode_barang','BB1')
                  ->type('nama_barang','Mie')
                  ->type('harga_beli','2000')
                  ->type('harga_jual','3000');
                    $first->script("document.getElementById('kategori_barang').selectize.setValue('1');");  
                    $first->assertSee('SEMBAKOK');
                    $first->script("document.getElementById('satuan').selectize.setValue('8');");  
                    $first->assertSee('BUNGKUS');
                    $first->script("document.getElementById('hitung_stok').checked = true;"); 
                    $first->script("document.getElementById('status_aktif').checked = true;"); 
                    $first->attach('foto', __DIR__.'/screenshots/testFoto.png');
                    $first->element('#btnSimpan')->submit();
                    $first->assertSeeLink('Tambah Produk'); 

        }); 
    } 

    public function testEditProduk(){

          $this->browse(function ($first, $second) {
            $first->loginAs(User::find(5))
                  ->visit('/barang')  
                  ->whenAvailable('.js-confirm', function ($table) { 
                              ;
                    })
                  ->with('.table', function ($table) {
                        $table->assertSee('Mie')
                              ->clickLink('Ubah');
                    })
                  ->assertSee('Edit Produk')
                  ->type('kode_barcode','57000')
                  ->type('kode_barang','B00121')
                  ->type('nama_barang','Mie Ayam')
                  ->type('harga_beli','20000')
                  ->type('harga_jual','30000');
                    $first->script("document.getElementById('kategori_barang').selectize.setValue('4');");  
                    $first->assertSee('UMUM');
                    $first->script("document.getElementById('satuan').selectize.setValue('1');");  
                    $first->assertSee('PCS');
                    $first->script("document.getElementById('hitung_stok').checked = true;"); 
                    $first->script("document.getElementById('status_aktif').checked = true;"); 
                    $first->attach('foto', __DIR__.'/screenshots/testFoto.png');                    
                    $first->element('#btnSimpan')->submit();                    
                    $first->assertSeeLink('Tambah Produk'); 

        }); 
    }



   public function testBarcodeTidakBolehSama(){// BARCODE PER WARUNG TIDAK BOLEH SAMA 

        $this->browse(function ($first, $second) {
            $first->loginAs(User::find(5))
                  ->visit('/barang') 
                  ->clickLink('Tambah Produk')
                  ->type('kode_barcode','5700011001')// kode barcode ini sudah ada di SEEDER
                  ->type('kode_barang','MTK1000')
                  ->type('nama_barang','Mie test')
                  ->type('harga_beli','2000')
                  ->type('harga_jual','3000');
                    $first->script("document.getElementById('kategori_barang').selectize.setValue('1');");  
                    $first->assertSee('SEMBAKOK');
                    $first->script("document.getElementById('satuan').selectize.setValue('8');");  
                    $first->assertSee('BUNGKUS');
                    $first->script("document.getElementById('hitung_stok').checked = true;"); 
                    $first->script("document.getElementById('status_aktif').checked = true;"); 
                    $first->attach('foto', __DIR__.'/screenshots/testFoto.png');
                    $first->element('#btnSimpan')->submit();
                    $first->script('document.getElementById("kode_barcode").focus();');
                    $first->assertSeeIn('#kode_barcode_error','Maaf kode barcode Sudah Terpakai.');
        }); 

    }

   public function testKodeBarangTidakBolehSama(){// KODE BARANG PER WARUNG TIDAK BOLEH SAMA 

        $this->browse(function ($first, $second) {
            $first->loginAs(User::find(5))
                  ->visit('/barang') 
                  ->clickLink('Tambah Produk')
                  ->type('kode_barcode','5700141117')
                  ->type('kode_barang','B001')// kode barang ini sudah ada di SEEDER
                  ->type('nama_barang','Mie test')
                  ->type('harga_beli','2000')
                  ->type('harga_jual','3000');
                    $first->script("document.getElementById('kategori_barang').selectize.setValue('1');");  
                    $first->assertSee('SEMBAKOK');
                    $first->script("document.getElementById('satuan').selectize.setValue('8');");  
                    $first->assertSee('BUNGKUS');
                    $first->script("document.getElementById('hitung_stok').checked = true;"); 
                    $first->script("document.getElementById('status_aktif').checked = true;"); 
                    $first->attach('foto', __DIR__.'/screenshots/testFoto.png');
                    $first->element('#btnSimpan')->submit();
                    $first->script('document.getElementById("kode_barang").focus();');
                    $first->assertSeeIn('#kode_produk_error','Maaf kode barang Sudah Terpakai.');
        }); 

    }




}
