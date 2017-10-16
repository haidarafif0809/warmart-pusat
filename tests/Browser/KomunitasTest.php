<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class KomunitasTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Laravel');
        });
    }


        public function testTambahKomunitas(){

        $this->browse(function ($first, $second){

            $first->loginAs(User::find(1))
                    ->visit('/home')
                    ->ClickLink('Komunitas')
                    ->ClickLink("Tambah Komunitas")
                    ->type('no_telp','8574383454')
                    ->type('name','asasa')
                    ->type('name_penggiat','asasa pengigiat')
                    ->type('email','fahrizal@gmail.com');
                      $first->script("document.getElementById('pilih_id_warung').selectize.setValue('1');");
                      $first->assertSee("Member")
                    ->press('Simpan')
                    ->assertSee('Berhasil Menambah User Browser Test');

        });
    }

}
