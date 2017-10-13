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
            $first->loginAs(User::find(4))
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
                    $first->script("document.getElementById('HitungStokYa').checked = true;"); 
                    $first->script("document.getElementById('StatusYa').checked = true;"); 
                    $first->attach('foto', __DIR__.'/screenshots/testFoto.png');
                    $first->element('#btnSimpan')->submit();
        }); 
    } 

    public function testEditProduk(){

          $this->browse(function ($first, $second) {
            $first->loginAs(User::find(4))
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
                    $first->script("document.getElementById('HitungStokYa').checked = true;"); 
                    $first->script("document.getElementById('StatusYa').checked = true;"); 
                    $first->attach('foto', __DIR__.'/screenshots/testFoto.png');                    
                    $first->element('#btnSimpan')->submit();

        }); 
    }



}
