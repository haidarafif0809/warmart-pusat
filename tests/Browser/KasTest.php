<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\User;
use App\Kas;

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
            $first->loginAs(User::find(5))
                    ->visit('/kas')
                    ->clickLink("Tambah Kas")
                    ->type('kode_kas', 'K-001')
                    ->type('nama_kas', 'Kas Browser');
            $first->script("document.getElementById('status_kas').checked = true;");
            $first->script("document.getElementById('default_kas').checked = false;");
            $first->element('#btnSimpanKas')->submit();
            $first->assertSee('SUKSES : BERHASIL MENAMBAH KAS "KAS BROWSER"');

        });
    }

//EDIT KAS
    public function testEditKas(){

        $this->browse( function ($first, $second){

            $first->loginAs(User::find(5))
                    ->visit('/kas')
                    ->whenAvailable('.js-confirm', function ($table){
                        ;
                    })
                    ->with('.table', function ($table){
                        $table->assertSee("K-001")->clickLink("Ubah");
                    })
                    ->assertSee('Edit Kas')
                    ->type('nama_kas', 'Kas Browser Edit');
            $first->element('#btnSimpanKas')->submit();
            $first->assertSee('SUKSES : BERHASIL MENGUBAH KAS "KAS BROWSER EDIT"');

        });

    }

//JIKA KODE KAS SUDAH TERPAKAI
    public function testKodeKasTidakBolehSama(){

          $this->browse(function ($first, $second) {
            $first->loginAs(User::find(5))
                    ->visit('/kas')
                    ->clickLink("Tambah Kas")
                    ->type('kode_kas', 'K-001')
                    ->type('nama_kas', 'Kas Browser');
            $first->script("document.getElementById('status_kas').checked = true;");
            $first->script("document.getElementById('default_kas').checked = true;");
            $first->element('#btnSimpanKas')->submit();
            $first->script('document.getElementById("kode_kas").focus();');
            $first->assertSeeIn('#kode_kas_error','Maaf kode kas Sudah Terpakai.');
        });

    }
   
    public function testHapusKas(){
          $kas = Kas::select('id')->where('nama_kas','Kas Browser Edit')->first();
          $this->browse(function ($first) use ($kas) {
            $first->loginAs(User::find(5))
                  ->visit('/kas')
                  ->whenAvailable('.js-confirm', function ($table) { 
                              ;
                    })
                  ->with('.table', function ($table) use ($kas) {
                        $table->press('#delete-'.$kas->id)
                              ->assertDialogOpened('Yakin Mau Menghapus Kas Kas Browser Edit ?');
                    })->driver->switchTo()->alert()->accept();
                    $first->assertSee('SUKSES : KAS BERHASIL DIHAPUS'); 

        }); 
    }




}
