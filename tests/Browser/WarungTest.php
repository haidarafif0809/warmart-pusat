<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;


class WarungTest extends DuskTestCase
{
    
    /**
     * A Dusk test example.
     *
     * @return void
     */
     public function testTambahWarung(){

        $this->browse(function ($first, $second) {
            $first->loginAs(User::find(1))
                  ->visit('/home')
                  ->clickLink('Warung')
                  ->clickLink('Tambah Warung')
                  ->type('email','fafa@gmail.com')
                  ->type('name','Fahri')
                  ->type('alamat','Jalan Way Halim')
                  ->script("document.getElementById('pilih_kelurahan').selectize.setValue('1');")
                  ->type('no_telp','085784343722')
                  ->type('nama_bank','BNI')
                  ->type('no_rekening','634735432')
                  ->type('an_rekening','Jajang')
                  ->press('Submit')
                  ->assertSee('Success : Berhasil Menambah Warung Fahri');
        });

    } 
}
