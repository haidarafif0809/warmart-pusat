<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;

class StokingCenterTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
        public function testTambahStokingCenter(){
        $this->browse(function ($first, $second) {
            $first->loginAs(User::find(1))
                  ->visit('/home')
                  ->clickLink('Stoking Center')
                  ->clickLink('Tambah Stoking Center')
                  ->type('name','Fahri')
                  ->type('alamat','Jalan Way Halim')
                  ->script("document.getElementById('pilih_kelurahan').selectize.setValue('1');")
                  ->script("document.getElementById('pilih_kategoriharga').selectize.setValue('1');")
                  ->type('url_api','war-mart.id/sc')
                  ->press('Submit')
                  ->assertSee('Success : Berhasil Menambah Stoking Center Fahri');
        });

    } 
}
