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
            $first->loginAs(User::find(1))
                  ->visit('/barang') 
                  ->clickLink('Tambah Produk')
                  ->type('kode_barcode','123789')
                  ->type('kode_barang','BB1')
                  ->type('nama_barang','Mie')
                  ->type('harga_beli','2000')
                  ->type('harga_jual','3000');
                    $first->script("document.getElementById('hitung_stok').selectize.setValue('1');");  
                    $first->assertSee('Ya');
                    $first->script("document.getElementById('kategori_barang').selectize.setValue('1');");  
                    $first->assertSee('SEMBAKOK');
                    $first->script("document.getElementById('satuan').selectize.setValue('8');");  
                    $first->assertSee('BUNGKUS');
                    $first->script("document.getElementById('status_aktif').selectize.setValue('1');");  
                    $first->assertSee('Aktif');
                  $first->attach('foto', __DIR__.'/screenshots/testFoto.png');
                    $first->element('#btnSimpan')->submit();
                  $first->assertSee('<b>BERHASIL:</b> Menambahkan Produk <b>Mie</b>');
        }); 
    } 

   
}
