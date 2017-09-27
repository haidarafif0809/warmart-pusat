<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;

class KategoriHargaTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testTambahKategoriHarga()
    {
        $this->browse(function ($kategori_harga, $second) {
           $kategori_harga->loginAs(User::find(1))
                      ->visit('/home')
                      ->clickLink('Kategori Harga')
                      ->clickLink('Tambah Kategori Harga')
                      ->type('nama_kategori_harga','Perkotaan') 
                      ->press('#tombol_simpan')
                      ->assertSee('SUKSES : BERHASIL MENAMBAH BANK "Perkotaan"');
        });
    }
}
