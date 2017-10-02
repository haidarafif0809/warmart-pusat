<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
class UserTest extends DuskTestCase
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

    public function testTambahUser(){

        $this->browse(function ($first, $second){

            $first->loginAs(User::find(1))
                    ->visit('/home')
                    ->ClickLink('User')
                    ->ClickLink("Tambah User")
                    ->type('name','Browser Test')
                    ->type('email','browserTest@gmail.com')
                    ->type('alamat','Jl. browserTest');
                      $first->script("document.getElementById('otoritas').selectize.setValue('2');");
                      $first->assertSee("Member")
                    ->press('Simpan')
                    ->assertSee('Berhasil Menambah User Browser Test');

        });
    }

     public function testResetPassword(){

         $this->browse(function ($first, $second) {
            $first->loginAs(User::find(1))
                  ->visit('/home')
                  ->ClickLink('User')
                  ->whenAvailable('.js-confirm', function ($table) {  
                              ;
                    })
                  ->with('.table', function ($table) {
                        $table->assertSee('Browser Test')
                              ->press('Reset Password');
                    })->driver->switchTo()->alert()->accept();

            $first->assertSee('Password Browser Test Berhasil Di Reset');

                 
        });


    }


     public function testKonfirmasi(){

         $this->browse(function ($first, $second) {
            $first->loginAs(User::find(1))
                  ->visit('/home')
                  ->ClickLink('User')
                  ->whenAvailable('.js-confirm', function ($table) {  
                              ;
                    })
                  ->with('.table', function ($table) {
                        $table->assertSee('Browser Test')
                              ->press('Iya');
                    })->driver->switchTo()->alert()->accept();

            $first->assertSee('User Browser Test Berhasil Di Konfirmasi');

                 
        });


    }

    public function testTidakKonfirmasi(){

         $this->browse(function ($first, $second) {
            $first->loginAs(User::find(1))
                  ->visit('/home')
                  ->ClickLink('User')
                  ->whenAvailable('.js-confirm', function ($table) {  
                              ;
                    })
                  ->with('.table', function ($table) {
                        $table->assertSee('Browser Test')
                              ->press('Tidak');
                    })->driver->switchTo()->alert()->accept();

            $first->assertSee('User Browser Test Tidak Di Konfirmasi');

                 
        });


    }

    public function testEditUser(){

        $this->browse( function ($first, $second){

            $first->loginAs(User::find(1))
                  ->visit('/home')
                  ->ClickLink('User')
                  ->whenAvailable('.js-confirm', function ($table){
                    ;
                  })
                  ->with('.table', function ($table){
                    $table->assertSee("Browser Test")
                          ->ClickLink("Ubah");
                  })
                  ->assertSee('Edit User')
                  ->type('name','Browser Test Edit')
                  ->type('email','browserTestEdit@gmail.com')
                  ->type('alamat','Jl. browserTest Edit');
                  $first->script("document.getElementById('otoritas').selectize.setValue('2');");
                  $first->assertSee("Member")
                  ->press('Simpan')
                  ->assertSee('Berhasil Mengubah User Browser Test Edit');


        });

    } 

    public function testHapusUser(){

         $this->browse(function ($first, $second) {
            $first->loginAs(User::find(1))
                  ->visit('/home')
                  ->ClickLink('User')
                  ->whenAvailable('.js-confirm', function ($table) {  
                              ;
                    })
                  ->with('.table', function ($table) {
                        $table->assertSee('Sample Member')
                              ->press('Hapus');
                    })->driver->switchTo()->alert()->accept();

            $first->assertSee('User Browser Test Edit Berhasil Di Hapus');

                 
        });


    }





}
