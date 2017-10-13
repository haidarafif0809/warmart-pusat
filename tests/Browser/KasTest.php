<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\User;

class KasTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */

//TAMBAH KAS
    public function testTambahKas(){

        $this->browse(function ($first, $second){

            $first->loginAs(User::find(4))
                    ->visit('/home')
                    ->clickLink("Kas")->assertSee('Kode Kas')
                    ->clickLink("Tambah Kas")->assertSee('Tambah Kas')
                    ->type('kode_kas', 'K-001')
                    ->type('nama_kas', 'Kas Browser');
            $first->script("document.getElementById('status_kas_radioOn').checked = true;");
            $first->script("document.getElementById('default_kas_radioOn').checked = true;");
            $first->element('#btnSimpanKas')->submit();
            $first->assertSee('Tambah Kas');

        });
    }

//EDIT KAS
    public function testEditKas(){

        $this->browse( function ($first, $second){

            $first->loginAs(User::find(4))
                    ->visit('/home')
                    ->clickLink("Kas")->assertSee('Kode Kas')
                    ->whenAvailable('.js-confirm', function ($table){
                        ;
                    })
                    ->with('.table', function ($table){
                        $table->assertSee("Kas Browser")->clickLink("Ubah");
                    })
                    ->assertSee('Edit Kas')
                    ->type('kode_kas', 'K-001')
                    ->type('nama_kas', 'Kas Browser Edit');
            $first->script("document.getElementById('status_kas_radioOn').checked = true;");
            $first->script("document.getElementById('default_kas_radioOn').checked = true;");
            $first->element('#btnSimpanKas')->submit();


        });

    }

//JIKA KODE KAS SUDAH TERPAKAI
    public function testKodeKasTidakBolehSama(){

          $this->browse(function ($first, $second) {
            $first->loginAs(User::find(4))
                    ->visit('/home')
                    ->clickLink("Kas")->assertSee('Kode Kas')
                    ->clickLink("Tambah Kas")->assertSee('Tambah Kas')
                    ->type('kode_kas', 'K001')
                    ->type('nama_kas', 'Kas Browser');
            $first->script("document.getElementById('status_kas_radioOn').checked = true;");
            $first->script("document.getElementById('default_kas_radioOn').checked = true;");
            $first->element('#btnSimpanKas')->submit();
            $first->type('kode_kas','fokus')->assertSee('Maaf kode kas Sudah Terpakai.');
        });

    }

}
