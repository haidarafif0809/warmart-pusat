<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\User;

class KasKeluarTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
//TAMBAH KAS KELUAR
    public function testTambahKasKeluar(){

        $this->browse(function ($first, $second){

            $first->loginAs(User::find(4))
                    ->visit('/home')
                    ->clickLink("Kas Keluar")->assertSee('No Faktur')
                    ->clickLink("Tambah Kas Keluar")->assertSee('Tambah Kas')
                    ->type('kode_kas', 'K-001')
                    ->type('nama_kas', 'Kas Browser');
            $first->script("document.getElementById('status_kas_radioOn').checked = true;");
            $first->script("document.getElementById('default_kas_radioOn').checked = true;");
            $first->element('#btnSimpanKas')->submit();
            $first->assertSee('Tambah Kas');

        });
    }
}
