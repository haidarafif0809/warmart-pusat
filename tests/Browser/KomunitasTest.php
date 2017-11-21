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
        ->visit('/dashboard#/komunitas')
        ->assertSee('Komunitas')
        ->ClickLink("Tambah Komunitas")
        ->type('no_telp','08571212477')
        ->type('name','PPI LAMBAR')
        ->type('name_penggiat','RIKO PERNANDO')
        ->type('email','riko@gmail.com');
        $first->script("document.getElementById('pilih_id_warung').selectize.setValue('1');");
        $first->assertSee("085658780793")// sesuaikan dengan warung masing2
        ->type('alamat','LIWA')
        ->type('alamat_penggiat','LAMBAR');
        $first->script("document.getElementById('pilih_kelurahan').selectize.setValue('1');");
        $first->assertSee("Kedaton") 
        ->type('nama_bank','PPI PUSAT')
        ->type('no_rekening','2013')
        ->type('an_rekening','RIKO PERNANDO')
        ->press('#btnSimpanKomunitas')
        ->pause(1000)
        ->assertSee('Berhasil Menambah Komunitas PPI LAMBAR')
        ->press('OK');

      });
    }    

    public function testConfirmYa() {

      $komunitas = Komunitas::select('id')->where('no_telp','08571212477')->first();

      $this->browse(function ($komunitasTest)use($komunitas) {
        $komunitasTest->loginAs(User::find(1))
        ->visit('/dashboard#/komunitas')
        ->assertSeeLink('Tambah Komunitas')
        ->pause(1000)
        ->with('.table', function ($table) use($komunitas){
          $table->assertSee('08571212477')
          ->pause(2000)
          ->press('#konfirmasi-ya-'.$komunitas->id)
          ->pause(1000);
        })->driver->switchTo()->alert()->accept();
        $komunitasTest->pause(1000)
        ->assertSee('Mengkonfirmasi Komunitas PPI LAMBAR')
        ->press('OK');
      });

    } 

    public function testConfirmNo() {

      $komunitas = Komunitas::select('id')->where('no_telp','08571212477')->first();

      $this->browse(function ($komunitasTest)use($komunitas) {
        $komunitasTest->loginAs(User::find(1))
        ->visit('/dashboard#/komunitas')
        ->assertSeeLink('Tambah Komunitas')
        ->pause(1000)
        ->with('.table', function ($table) use($komunitas){
          $table->assertSee('08571212477')
          ->pause(2000)
          ->press('#konfirmasi-no-'.$komunitas->id)
          ->pause(1000);
        })->driver->switchTo()->alert()->accept();
        $komunitasTest->pause(1000)
        ->assertSee('Komunitas PPI LAMBAR Tidak Dikonfirmasi')
        ->press('OK');
      });


    }   


    public function testDetailKomunitas() {

      $komunitas = Komunitas::with(['komunitas_penggiat'])->where('no_telp','08571212477')->first();
      $nama_penggiat = $komunitas->komunitas_penggiat->nama_penggiat;

      $this->browse(function ($komunitasTes, $second)use($komunitas) {
        $komunitasTes->loginAs(User::find(1))
        ->visit('/dashboard#/komunitas')
        ->assertSeeLink('Tambah Komunitas')
        ->pause(1000)
        ->with('.table', function ($table) use($komunitas){
          $table->assertSee('08571212477')
          ->press('#detail-'.$komunitas->id);
        })
        ->pause(1000)
        ->assertSee('Detail Komunitas PPI LAMBAR')
        ->with('.table', function ($table) use($komunitas){
          $table->assertSee(''.$komunitas->komunitas_penggiat->nama_penggiat);
        });
      });


    }   


    public function testUniqueKomunitas(){
      $this->browse(function ($first, $second){
        $first->loginAs(User::find(1))
        ->visit('/dashboard#/komunitas')
        ->assertSee('Komunitas')
        ->ClickLink("Tambah Komunitas")
        ->type('no_telp','087345365743')
        ->type('name','PPI LAMBAR')
        ->type('name_penggiat','RIKO PERNANDO')
        ->type('email','member@gmail.com');
        $first->script("document.getElementById('pilih_id_warung').selectize.setValue('1');");
        $first->assertSee("085658780793")// sesuaikan dengan warung masing2
        ->type('alamat','LIWA')
        ->type('alamat_penggiat','LAMBAR');
        $first->script("document.getElementById('pilih_kelurahan').selectize.setValue('1');");
        $first->assertSee("Kedaton") 
        ->type('nama_bank','PPI PUSAT')
        ->type('no_rekening','2013')
        ->type('an_rekening','RIKO PERNANDO')
        ->press('#btnSimpanKomunitas');
        $first->script('document.getElementById("no_telp").focus();');
        $first->pause(1000);
        $first->assertSee('MAAF NO TELP SUDAH TERPAKAI');
        $first->script('document.getElementById("email").focus();');
        $first->pause(1000);
        $first->assertSee('MAAF EMAIL SUDAH TERPAKAI');
        $first->script('document.getElementById("no_rekening").focus();');
        $first->pause(1000);
        $first->assertSee('MAAF NO REKENING SUDAH TERPAKAI');

      });
    }



    public function testValidateInputKosongKomunitas(){
      $this->browse(function ($first, $second){
        $first->loginAs(User::find(1))
        ->visit('/dashboard#/komunitas')
        ->assertSee('Komunitas')
        ->ClickLink("Tambah Komunitas")
        ->press('#btnSimpanKomunitas');
        $first->script('document.getElementById("no_telp").focus();');
        $first->pause(1000);
        $first->assertSee('NO TELP HARUS DIISI.');
        $first->script('document.getElementById("name").focus();');
        $first->pause(1000);
        $first->assertSee('NAME HARUS DIISI.');
        $first->script('document.getElementById("email").focus();');
        $first->pause(1000);
        $first->assertSee('EMAIL HARUS DIISI.');
        $first->pause(1000);
        $first->assertSee('ID WARUNG HARUS DIISI.');
        $first->script('document.getElementById("alamat").focus();');
        $first->pause(1000);
        $first->assertSee('ALAMAT HARUS DIISI.');
        $first->pause(1000);
        $first->assertSee('KELURAHAN HARUS DIISI.');
        $first->script('document.getElementById("nama_bank").focus();');
        $first->pause(1000);
        $first->assertSee('NAMA BANK HARUS DIISI.');
        $first->script('document.getElementById("no_rekening").focus();');
        $first->pause(1000);
        $first->assertSee('NO REKENING HARUS DIISI.');
        $first->script('document.getElementById("an_rekening").focus();');
        $first->pause(1000);
        $first->assertSee('AN REKENING HARUS DIISI.');

      });
    }
    
    public function testUbahKomunitas() {

      $komunitas = Komunitas::select('id')->where('no_telp','08571212477')->first();

      $this->browse(function ($komunitasTes, $second)use($komunitas) {
        $komunitasTes->loginAs(User::find(1))
        ->visit('/dashboard#/komunitas')
        ->assertSeeLink('Tambah Komunitas')
        ->pause(1000)
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
        $komunitasTes->assertSee("085658780793")// sesuaikan dengan warung masing2
        ->type('alamat','BANDAR LAMPUNG')
        ->type('alamat_penggiat','BANDAR LAMPUNG');
        $komunitasTes->script("document.getElementById('pilih_kelurahan').selectize.setValue('2');");
        $komunitasTes->assertSee("Surabaya") 
        ->type('nama_bank','PPI PUSAT JAKARTA')
        ->type('no_rekening','2017')
        ->type('an_rekening','RIKO')
        ->press('#btnSimpanKomunitas')
        ->pause(1000)
        ->assertSee('Berhasil Mengubah Komunitas PPI LAMPUNG')
        ->press('OK');
      });

    }   


    public function testHapusKomunitas() {
      $komunitas = Komunitas::select('id')->where('no_telp','072187979776')->first();
      $this->browse(function ($komunitasTest)use($komunitas) {
        $komunitasTest->loginAs(User::find(1))
        ->visit('/dashboard#/komunitas')
        ->assertSeeLink('Tambah Komunitas')
        ->pause(1000)
        ->with('.table', function ($table) use($komunitas){
          $table->assertSee('072187979776')
          ->pause(2000)
          ->press('#delete-'.$komunitas->id)
          ->pause(1000);
        })->driver->switchTo()->alert()->accept();
        $komunitasTest->pause(1000)
        ->assertSee('Menghapus Komunitas PPI LAMPUNG')
        ->press('OK');
      });

    }


    public function testPencarianKomunitas() {

      $this->browse(function ($komunitasTest) {
        $komunitasTest->loginAs(User::find(1))
        ->visit('/dashboard#/komunitas')
        ->assertSeeLink('Tambah Komunitas')
        ->pause(1000)
        ->type('pencarian','Sample Member')
        ->pause(2000)
        ->with('.table', function ($table){
          $table->assertSee('Sample Member');
        });
        
      });

    }

  }
