<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Warung;


class WarungTest extends DuskTestCase
{
    
    /**
     * A Dusk test example.
     *
     * @return void
     */
     public function testTambahWarung(){

        $this->browse(function ($WarungTest) {
            $WarungTest->loginAs(User::find(1))
                  ->visit('/dashboard#/warung')
                  ->clickLink('Tambah Warung')
                  ->type('name','Fahri')
                  ->type('no_telpon','085675753645')
                  ->type('email','Testswarung@gmail.com')
                  ->type('nama_bank','BNI')
                  ->type('atas_nama','Jajang')
                  ->type('no_rek','634735432')
                  ->type('alamat','Jl. Testing');
                  $WarungTest->script("document.getElementById('pilih_kelurahan').selectize.setValue('1');");  
                  $WarungTest->assertSee('Kedaton');
                  $WarungTest->press('#btnSimpanWarung')
                   ->whenAvailable('.swal-modal', function ($modal) {
                    $modal->assertSee('Sukses : Berhasil Menambah warung Fahri');
                    });
              });

    }

      public function testUniqueWarung(){

        $this->browse(function ($WarungTest) {
            $WarungTest->loginAs(User::find(1))
                  ->visit('/dashboard#/warung')
                  ->clickLink('Tambah Warung')
                  ->type('name','Fahri')
                  ->type('no_telpon','085675753645')
                  ->type('email','Testswarung@gmail.com')
                  ->type('nama_bank','BNI')
                  ->type('atas_nama','Jajang')
                  ->type('no_rek','634735432')
                  ->type('alamat','Jl. Testing');
                  $WarungTest->script("document.getElementById('pilih_kelurahan').selectize.setValue('1');");  
                  $WarungTest->assertSee('Kedaton');
                  $WarungTest->press('#btnSimpanWarung');
                  $WarungTest->whenAvailable('#name_error', function ($label) {
                      $label->assertSee('MAAF NAME SUDAH TERPAKAI.');
                  });
                  $WarungTest->whenAvailable('#no_telpon_error', function ($label) {
                      $label->assertSee('MAAF NO TELPON SUDAH TERPAKAI.');
                  });
                  $WarungTest->whenAvailable('#no_rek_error', function ($label) {
                      $label->assertSee('MAAF NO REK SUDAH TERPAKAI.');
                  });
        });

    }

      public function testUbahWarung() {
                $warung = Warung::select('id')->where('no_telpon','085675753645')->first();
                $this->browse(function ($WarungTest)use($warung) {
                  $WarungTest->loginAs(User::find(1))
                  ->visit('/dashboard#/warung')
                  ->assertSeeLink('Tambah Warung')
                   ->whenAvailable('.data-ada', function ($modal) use ($warung) {
                      $modal->click('#edit-'.$warung->id);
                    })
                  ->assertSee('Edit Warung')
                  ->type('name','Fahrizal Ramadhan')
                  ->type('no_telpon','08567767975')
                  ->type('email','Testswarungedit@gmail.com')
                  ->type('nama_bank','BNI SYARIAH')
                  ->type('atas_nama','Jajang NUrjaman')
                  ->type('no_rek','634735436432')
                  ->type('alamat','Jl. Testing Browser');
                  $WarungTest->script("document.getElementById('pilih_kelurahan').selectize.setValue('2');");  
                  $WarungTest->assertSee('Surabaya');
                  $WarungTest->press('#btnSimpanWarung')
                    ->whenAvailable('.swal-modal', function ($modal) {
                    $modal->assertSee('Sukses : Berhasil Mengubah Warung');
                    });
                  
        });

    }

    public function testHapusWarung() {
      $warung = Warung::select('id')->where('no_telpon','08567767975')->first();
      $this->browse(function ($WarungTest)use($warung) {
        $WarungTest->loginAs(User::find(1))
                  ->visit('/dashboard#/warung')
                  ->assertSeeLink('Tambah Warung')
                  ->whenAvailable('.data-ada', function ($table) use($warung){
                        $table->assertSee('Fahrizal Ramadhan')
                              ->press('#delete-'.$warung->id)
                              ->assertDialogOpened('Anda Yakin Ingin Menghapus Warung Fahrizal Ramadhan ?');
                    })->driver->switchTo()->alert()->accept();
                  $WarungTest->assertSee('Warung Berhasil Dihapus!');
        });

   }   

}
