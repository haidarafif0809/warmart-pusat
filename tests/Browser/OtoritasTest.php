<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Role;
class OtoritasTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
   public function testTambahOtoritas() {
        $this->browse(function ($otoritasTes, $second) {
           $otoritasTes->loginAs(User::find(1))
                      ->visit('/otoritas')
                      ->clickLink('Tambah Otoritas')
                      ->type('name','Pimpinan')
                      ->type('display_name','Pimpinan Tertinggi')
                      ->type('description','bla-bla')
                      ->press('#btnSimpanOtoritas')
                      ->assertSee('Berhasil Menambah Otoritas Pimpinan Tertinggi');
        });
    }

    public function testUbahOtoritas() {
      $otoritas = Role::select('id')->where('name','Pimpinan')->first();
      $this->browse(function ($otoritasTes)use($otoritas) {
        $otoritasTes->loginAs(User::find(1))
                  ->visit('/otoritas')
                  ->assertSeeLink('Tambah Otoritas')
                  ->whenAvailable('.js-confirm', function ($table) { 
                              ;
                    })
                  ->with('.table', function ($table) use($otoritas){
                        $table->assertSee('Pimpinan')
                              ->press('#edit-'.$otoritas->id);
                    })
                  ->assertSee('Edit Otoritas')
                      ->type('name','Kasir')
                      ->type('display_name','Kasir Pusat')
                      ->type('description','bla-bla-bla')
                      ->press('#btnSimpanOtoritas')
                      ->assertSee('Berhasil Mengubah Otoritas Kasir Pusat');
        });

    } 

   public function testSettingPermissionOtoritas() {
      $otoritas = Role::select('id')->where('name','Kasir')->first();
      $this->browse(function ($otoritasTes)use($otoritas) {
        $otoritasTes->loginAs(User::find(1))
                  ->visit('/otoritas')
                  ->assertSeeLink('Tambah Otoritas')
                  ->whenAvailable('.js-confirm', function ($table) { 
                              ;
                    })
                  ->with('.table', function ($table) use($otoritas){
                        $table->assertSee('Kasir')
                              ->press('#permission-'.$otoritas->id);
                    })
                  ->assertSee('Setting Permisson');
                  $otoritasTes->script("document.getElementById('lihat_master_data').checked = true;"); 
                  $otoritasTes->script("document.getElementById('tambah_bank').checked = true;"); 
                  $otoritasTes->script("document.getElementById('edit_bank').checked = true;"); 
                  $otoritasTes->script("document.getElementById('hapus_bank').checked = true;"); 
                  $otoritasTes->script("document.getElementById('lihat_bank').checked = true;"); 
                  $otoritasTes->script("document.getElementById('tambah_customer').checked = true;"); 
                  $otoritasTes->script("document.getElementById('edit_customer').checked = true;"); 
                  $otoritasTes->script("document.getElementById('hapus_customer').checked = true;"); 
                  $otoritasTes->script("document.getElementById('lihat_customer').checked = true;"); 
                  $otoritasTes->script("document.getElementById('tambah_komunitas').checked = true;"); 
                  $otoritasTes->script("document.getElementById('edit_komunitas').checked = true;"); 
                  $otoritasTes->script("document.getElementById('hapus_komunitas').checked = true;"); 
                  $otoritasTes->script("document.getElementById('lihat_komunitas').checked = true;"); 
                  $otoritasTes->script("document.getElementById('tambah_otoritas').checked = true;"); 
                  $otoritasTes->script("document.getElementById('edit_otoritas').checked = true;"); 
                  $otoritasTes->script("document.getElementById('hapus_otoritas').checked = true;"); 
                  $otoritasTes->script("document.getElementById('lihat_otoritas').checked = true;"); 
                  $otoritasTes->script("document.getElementById('permission_otoritas').checked = true;");   
                  $otoritasTes->script("document.getElementById('tambah_user').checked = true;"); 
                  $otoritasTes->script("document.getElementById('edit_user').checked = true;"); 
                  $otoritasTes->script("document.getElementById('hapus_user').checked = true;"); 
                  $otoritasTes->script("document.getElementById('lihat_user').checked = true;");                
                  $otoritasTes->script("document.getElementById('konfirmasi_user').checked = true;"); 
                  $otoritasTes->script("document.getElementById('reset_password_user').checked = true;");
                  $otoritasTes->script("document.getElementById('tambah_warung').checked = true;"); 
                  $otoritasTes->script("document.getElementById('edit_warung').checked = true;"); 
                  $otoritasTes->script("document.getElementById('hapus_warung').checked = true;"); 
                  $otoritasTes->script("document.getElementById('lihat_warung').checked = true;"); 
                  $otoritasTes->press('#btnSubmitPermession')
                  ->assertSee('Setting Permission Kasir Pusat Berhasil Dirubah');
        });

    } 

    public function testHapusOtoritas() {
      $otoritas = Role::select('id')->where('name','Kasir')->first();
      $this->browse(function ($otoritasTes)use($otoritas) {
        $otoritasTes->loginAs(User::find(1))
                  ->visit('/otoritas')
                  ->assertSeeLink('Tambah Otoritas')
                  ->whenAvailable('.js-confirm', function ($table) { 
                              ;
                    })
                  ->with('.table', function ($table) use($otoritas){
                            $table->assertSee('Kasir')
                              ->press('#delete-'.$otoritas->id)
                              ->assertDialogOpened('Apakah Anda Yakin Ingin Menghapus Otoritas Kasir?');
                    })->driver->switchTo()->alert()->accept();
                  $otoritasTes->assertSee('Otoritas Berhasil Di Hapus');
        });

    } 

      public function testHapusOtoritasYgSudahTerpakai() {
      $otoritas = Role::select('id')->where('name','admin')->first();
      $this->browse(function ($otoritasTes)use($otoritas) {
        $otoritasTes->loginAs(User::find(1))
                  ->visit('/otoritas')
                  ->assertSeeLink('Tambah Otoritas')
                  ->whenAvailable('.js-confirm', function ($table) { 
                              ;
                    })
                  ->with('.table', function ($table) use($otoritas){
                            $table->assertSee('admin')
                              ->press('#delete-'.$otoritas->id)
                              ->assertDialogOpened('Apakah Anda Yakin Ingin Menghapus Otoritas admin?');
                    })->driver->switchTo()->alert()->accept();
                  $otoritasTes->assertSee('Otoritas tidak bisa dihapus karena masih memiliki User');
        });

    } 

       public function testUniqueNameOtoritas() {
        $this->browse(function ($otoritasTes, $second) {
           $otoritasTes->loginAs(User::find(1))
                      ->visit('/otoritas')
                      ->clickLink('Tambah Otoritas')
                      ->type('name','admin')
                      ->type('display_name','Admin Tertinggi')
                      ->type('description','bla-bla')
                      ->press('#btnSimpanOtoritas');
                      $otoritasTes->script("document.getElementById('name').focus();");
                      $otoritasTes->assertSeeIn('#name_error','Maaf name Sudah Terpakai');
        });
    }


       public function testUniqueDisplayNameOtoritas() {
        $this->browse(function ($otoritasTes, $second) {
           $otoritasTes->loginAs(User::find(1))
                      ->visit('/otoritas')
                      ->clickLink('Tambah Otoritas')
                      ->type('name','admin atas')
                      ->type('display_name','Admin')
                      ->type('description','bla-bla')
                      ->press('#btnSimpanOtoritas');
                      $otoritasTes->script("document.getElementById('display_name').focus();");
                      $otoritasTes->assertSeeIn('#display_name_error','Maaf display name Sudah Terpakai');
        });
    }
}
