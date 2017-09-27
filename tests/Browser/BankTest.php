<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;

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
                      ->visit('/home')
                      ->clickLink('Bank')
                      ->clickLink('Tambah Bank')
                      ->type('nama_bank','MANDIRI')
                      ->type('atas_nama','Afif Maulana')
                      ->type('no_rek','01204656596')
                      ->press('#btnSimpanBank')
                      ->assertSee('SUKSES : BERHASIL MENAMBAH BANK "MANDIRI"');
        });
    }

    public function testUbahBank() {
      $this->browse(function ($masterBank, $second) {
        $masterBank->loginAs(User::find(1))
                  ->visit('/home')
                  ->clickLink('Bank')
                  ->whenAvailable('.js-confirm', function ($table) { 
                              ;
                    })
                  ->with('.table', function ($table) {
                        $table->assertSee('MANDIRI')
                              ->clickLink('#btnSimpanBank');
                    })
                  ->assertSee('Edit Bank')
                  ->type('nama_bank','MANDIRI SYARIAH')
                  ->type('atas_nama','Rindang Ramadhan')
                  ->type('no_rek','01245286425')
                  ->press('#btnSimpanBank')
                  ->assertSee('SUKSES : BERHASIL MENGUBAH BANK "MANDIRI SYARIAH"');
        });

    } 
}
