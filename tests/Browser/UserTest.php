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


    public function testTambahUser(){

        $this->browse(function ($first, $second){

            $first->loginAs(User::find(1))
                    ->visit('/user')
                    ->clickLink("Tambah User")
                    ->pause(1000)
                    ->type('name','Browser Test')
                    ->type('no_telp','08594794637')
                    ->type('email','Test@gmail.com')
                    ->type('alamat','Jl. browserTest');
                      $first->script("document.getElementById('otoritas').selectize.setValue('2');");
                      $first->assertSee("Member");
                      $first->press('.btn-primary')
                    ->pause(1000)->assertSee("Menambah User");
        });
    }

       public function testResetPassword(){

        $user = User::select('id')->where('no_telp','08594794637')->first();

         $this->browse(function ($first, $second) use($user) {
            $first->loginAs(User::find(1))
                  ->visit('/user')
                  ->assertSeeLink('Tambah User')
                  ->whenAvailable('.js-confirm', function ($table) {  
                              ;
                    })
                  ->with('.table', function ($table) use($user) {
                        $table->assertSee('Browser Test')
                              ->press('#reset-'.$user->id)
                              ->assertDialogOpened("Apakah Anda Yakin Ingin Me Reset Password User Browser Test?");
                    })->driver->switchTo()->alert()->accept();

            $first->assertSee('Berhasil Di Reset');

                 
        });


    }

    public function testKonfirmasiIya(){

        $user = User::select('id')->where('no_telp','08594794637')->first();

         $this->browse(function ($first, $second)use($user) {
            $first->loginAs(User::find(1))
                  ->visit('/user')
                  ->assertSeeLink('Tambah User')
                  ->whenAvailable('.js-confirm', function ($table) {  
                              ;
                    })
                  ->with('.table', function ($table) use($user){
                        $table->assertSee('Browser Test')
                              ->press('#confirm-ya-'.$user->id)
                              ->assertDialogOpened('Apakah Anda Yakin Ingin Meng Konfirmasi User Browser Test?');
                              
                    })->driver->switchTo()->alert()->accept();

            $first->assertSee('User Browser Test Berhasil Di Konfirmasi');

                 
        });


    }

    public function testKonfirmasiNo(){

        $user = User::select('id')->where('no_telp','08594794637')->first();

         $this->browse(function ($first, $second)use($user) {
            $first->loginAs(User::find(1))
                  ->visit('/user')
                  ->assertSeeLink('Tambah User')
                  ->whenAvailable('.js-confirm', function ($table) {  
                              ;
                    })
                  ->with('.table', function ($table) use($user){
                        $table->assertSee('Browser Test')
                              ->press('#confirm-no-'.$user->id)
                              ->assertDialogOpened('Apakah Anda Yakin Tidak Meng Konfirmasi User Browser Test?');
                              
                    })->driver->switchTo()->alert()->accept();

            $first->assertSee('User Browser Test Tidak Di Konfirmasi');

                 
        });


    }

    public function testEditUser(){

        $user = User::select('id')->where('no_telp','08594794637')->first();

        $this->browse( function ($first, $second)use($user){

            $first->loginAs(User::find(1))
                  ->visit('/user')
                  ->assertSeeLink('Tambah User')
                  ->whenAvailable('.js-confirm', function ($table){
                    ;
                  })
                  ->with('.table', function ($table)use($user){
                    $table->assertSee("Browser Test")
                          ->press('#edit-'.$user->id);
                  })
                  ->assertSee('Edit User')
                  ->type('name','Browser Test Edit')
                  ->type('no_telp','0856457897947')
                  ->type('email','browserTestEdit@gmail.com')
                  ->type('alamat','Jl. browserTest Edit');
                  $first->script("document.getElementById('otoritas').selectize.setValue('2');");
                  $first->assertSee("Member");
                  $first->press('.btn-primary')
                  ->pause(1000)->assertSee('Mengubah User');


        });

    } 

     public function testHapusUser(){

        $user = User::select('id')->where('no_telp','0856457897947')->first();

         $this->browse(function ($first, $second) use ($user) {
            $first->loginAs(User::find(1))
                  ->visit('/user')
                  ->assertSeeLink('Tambah User')
                  ->whenAvailable('.js-confirm', function ($table) {  
                              ;
                    })
                  ->with('.table', function ($table) use ($user) {
                        $table->assertSee('Browser Test Edit')
                              ->press('#delete-'.$user->id)
                              ->assertDialogOpened('Yakin Mau Menghapus User Browser Test Edit?');
                    })->driver->switchTo()->alert()->accept();

            $first->assertSee('User Browser Test Edit Berhasil Di Hapus');

                 
        });


    }

    public function testUniqueNoTelp(){

            $this->browse(function ($first, $second){

            $first->loginAs(User::find(1))
                    ->visit('/user')
                    ->clickLink("Tambah User")
                    ->pause(1000)
                    ->type('name','Browser Test')
                    ->type('no_telp','081222498686')// no telp sudah ada
                    ->type('email','Test@gmail.com')
                    ->type('alamat','Jl. browserTest');
                      $first->script("document.getElementById('otoritas').selectize.setValue('2');");
                      $first->assertSee("Member");
                      $first->press('.btn-primary');
                      $first->script('document.getElementById("no_telp").focus();');
                      $first->assertSeeIn('#no_telp_error','Maaf no telp Sudah Terpakai.');

        });
    }

   public function testUniqueEmail(){

            $this->browse(function ($first, $second){

            $first->loginAs(User::find(1))
                    ->visit('/user')
                    ->clickLink("Tambah User")
                    ->pause(1000)
                    ->type('name','Browser Test')
                    ->type('no_telp','081222498686')
                    ->type('email','admin@gmail.com ')// email sudah ada
                    ->type('alamat','Jl. browserTest');
                      $first->script("document.getElementById('otoritas').selectize.setValue('2');");
                      $first->assertSee("Member");
                      $first->press('.btn-primary');
                      $first->script('document.getElementById("email").focus();');
                      $first->assertSeeIn('#email_error','Maaf email Sudah Terpakai.');

        });
    }


}
