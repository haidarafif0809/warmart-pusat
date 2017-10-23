<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Komunitas;

class KomunitasTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
  
        public function testTambahKomunitas(){
        $this->browse(function ($first, $second){
            $first->loginAs(User::find(1))
                    ->visit('/komunitas')
                    ->assertSee('Komunitas')
                    ->ClickLink("Tambah Komunitas")
                    ->type('no_telp','08571212477')
                    ->type('name','PPI LAMBAR')
                    ->type('name_penggiat','RIKO PERNANDO')
                    ->type('email','riko@gmail.com');
                      $first->script("document.getElementById('pilih_id_warung').selectize.setValue('1');");
                      $first->assertSee("212 Mart")
                    ->type('alamat','LIWA')
                    ->type('alamat_penggiat','LAMBAR');
                      $first->script("document.getElementById('pilih_kelurahan').selectize.setValue('1');");
                      $first->assertSee("Kedaton") 
                    ->type('nama_bank','PPI PUSAT')
                    ->type('no_rekening','2013')
                    ->type('an_rekening','RIKO PERNANDO')
                    ->press('#btnSimpanWarung')
                    ->assertSee('BERHASIL : MENAMBAH KOMUNITAS PPI LAMBAR');

        });
    }

    public function testUbahBank() {

      $komunitas = Komunitas::select('id')->where('no_telp','08571212477')->first();

      $this->browse(function ($komunitasTes, $second)use($komunitas) {
        $komunitasTes->loginAs(User::find(1))
                  ->visit('/komunitas')
                  ->assertSeeLink('Tambah Komunitas')
                  ->whenAvailable('.js-confirm', function ($table) { 
                              ;
                    })
                  ->with('.table', function ($table) use($komunitas){
                        $table->assertSee('08571212477')
                              ->press('#edit-'.$komunitas->id);
                    })
                  ->assertSee('Edit Komunitas')
                    ->type('no_telp','072187979776')
                    ->type('name','PPI LAMPUNG')
                    ->type('name_penggiat','ERLANGGA')
                    ->type('email','rikopernando96@gmail.com');
                      $komunitasTes->script("document.getElementById('pilih_id_warung').selectize.setValue('1');");
                      $komunitasTes->assertSee("212 Mart")
                    ->type('alamat','BANDAR LAMPUNG')
                    ->type('alamat_penggiat','BANDAR LAMPUNG');
                      $komunitasTes->script("document.getElementById('pilih_kelurahan').selectize.setValue('2');");
                      $komunitasTes->assertSee("Surabaya") 
                    ->type('nama_bank','PPI PUSAT JAKARTA')
                    ->type('no_rekening','2017')
                    ->type('an_rekening','RIKO')
                    ->press('#btnSimpanWarung')
                    ->assertSee('BERHASIL : MENGUBAH KOMUNITAS PPI LAMPUNG');
        });

    }     

    public function testHapusKomunitas() {
      $komunitas = Komunitas::select('id')->where('no_telp','072187979776')->first();
      $this->browse(function ($komunitasTest)use($komunitas) {
        $komunitasTest->loginAs(User::find(1))
                  ->visit('/komunitas')
                  ->assertSeeLink('Tambah Komunitas')
                  ->whenAvailable('.js-confirm', function ($table) { 
                              ;
                    })
                  ->with('.table', function ($table) use($komunitas){
                        $table->assertSee('072187979776')
                              ->press('#delete-'.$komunitas->id)
                              ->assertDialogOpened('Yakin Mau Menghapus komunitas PPI LAMPUNG?');
                    })->driver->switchTo()->alert()->accept();
                  $komunitasTest->assertSee('BERHASIL : MENGHAPUS KOMUNITAS');
        });

    }


        public function testUniqueNotelpKomunitas(){
        $this->browse(function ($first, $second){
            $first->loginAs(User::find(1))
                    ->visit('/komunitas')
                    ->assertSee('Komunitas')
                    ->ClickLink("Tambah Komunitas")
                    ->type('no_telp','087345365743')
                    ->type('name','PPI LAMBAR')
                    ->type('name_penggiat','RIKO PERNANDO')
                    ->type('email','riko@gmail.com');
                      $first->script("document.getElementById('pilih_id_warung').selectize.setValue('1');");
                      $first->assertSee("212 Mart")
                    ->type('alamat','LIWA')
                    ->type('alamat_penggiat','LAMBAR');
                      $first->script("document.getElementById('pilih_kelurahan').selectize.setValue('1');");
                      $first->assertSee("Kedaton") 
                    ->type('nama_bank','PPI PUSAT')
                    ->type('no_rekening','2013')
                    ->type('an_rekening','RIKO PERNANDO')
                    ->press('#btnSimpanWarung');
                      $first->script('document.getElementById("no_telp").focus();');
                      $first->assertSeeIn('#no_telp_error','Maaf no telp Sudah Terpakai');

        });
    }


    public function testUniqueEmailKomunitas(){
        $this->browse(function ($first, $second){
            $first->loginAs(User::find(1))
                    ->visit('/komunitas')
                    ->assertSee('Komunitas')
                    ->ClickLink("Tambah Komunitas")
                    ->type('no_telp','0873145365743')
                    ->type('name','PPI LAMBAR')
                    ->type('name_penggiat','RIKO PERNANDO')
                    ->type('email','member@gmail.com');
                      $first->script("document.getElementById('pilih_id_warung').selectize.setValue('1');");
                      $first->assertSee("212 Mart")
                    ->type('alamat','LIWA')
                    ->type('alamat_penggiat','LAMBAR');
                      $first->script("document.getElementById('pilih_kelurahan').selectize.setValue('1');");
                      $first->assertSee("Kedaton") 
                    ->type('nama_bank','PPI PUSAT')
                    ->type('no_rekening','2013')
                    ->type('an_rekening','RIKO PERNANDO')
                    ->press('#btnSimpanWarung');
                      $first->script('document.getElementById("email").focus();');
                      $first->assertSeeIn('#email_error','Maaf email Sudah Terpakai');

        });
    }

}
