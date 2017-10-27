<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Bank;

class BankTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testTambahBank() {
        $this->browse(function ($masterBank, $second) {
           $masterBank->loginAs(User::find(1))
                      ->visit('/bank')
                      ->clickLink('Tambah Bank')
                      ->type('nama_bank','MANDIRI')
                      ->type('atas_nama','Afif Maulana')
                      ->type('no_rek','01204656596');
            $masterBank->script("document.getElementById('tampil_customer').checked = true;"); 
          $masterBank->press('#btnSimpanBank')
                     ->whenAvailable('.swal-modal', function ($modal) {
                            $modal->assertSee('Sukses : Berhasil Menambah Bank MANDIRI');
               
                        });
        });
    }

    public function testUbahBank() {

      $bank = Bank::select('id')->where('no_rek','01204656596')->first();

      $this->browse(function ($masterBank, $second)use($bank) {
        $masterBank->loginAs(User::find(1))
                  ->visit('/bank')
                  ->assertSeeLink('Tambah Bank')
                  ->whenAvailable('.data-ada', function ($modal) use ($bank) {
                            $modal->click('#edit-'.$bank->id);
               
                  })
                  ->assertSee('Edit Bank')
                  ->type('nama_bank','MANDIRI SYARIAH')
                  ->type('atas_nama','Rindang Ramadhan')
                  ->type('no_rek','01245286425');
              $masterBank->script("document.getElementById('tampil_customer').checked = true;"); 
                  $masterBank->press('#btnSimpanBank')
                  ->whenAvailable('.swal-modal', function ($modal) {
                            $modal->assertSee('Berhasil Mengubah Bank!');
               
                        });
        });

    } 

    public function testHapusBank() {

      $bank = Bank::select('id')->where('no_rek','01245286425')->first();

      $this->browse(function ($masterBank, $second)use($bank) {
        $masterBank->loginAs(User::find(1))
                  ->visit('/bank')
                  ->assertSeeLink('Tambah Bank')
                  ->whenAvailable('.data-ada', function ($modal) use ($bank) {
                            $modal->click('#delete-'.$bank->id) 
                            ->assertDialogOpened('Anda Yakin Ingin Menghapus Bank MANDIRI SYARIAH ?');
               
                  })
                  ->driver->switchTo()->alert()->accept();
                  $masterBank->whenAvailable('.swal-modal', function ($modal) {
                            $modal->assertSee('Berhasil Menghapus Bank!');
               
                        });
        });

    } 

        public function testUniqueBank() {
        $this->browse(function ($masterBank, $second) {
           $masterBank->loginAs(User::find(1))
                      ->visit('/bank')
                      ->clickLink('Tambah Bank')
                      ->type('nama_bank','BNI SYARIAH')
                      ->type('atas_nama','Afif Maulana')
                      ->type('no_rek','784596123');
          $masterBank->script("document.getElementById('tampil_customer').checked = true;"); 
        $masterBank->press('#btnSimpanBank');                      
        $masterBank->script('document.getElementById("no_rek").focus();');
        $masterBank->assertSeeIn('#no_rek_error','Maaf no rek Sudah Terpakai');
        });
    }

  
}
