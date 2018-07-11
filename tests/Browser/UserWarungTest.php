<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\UserWarung;

class UserWarungTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testKonfirmasiYa() {

        $userWarung = UserWarung::select('id')->where('tipe_user', 4)->where('konfirmasi_admin', 0)->orderBy('id', 'DESC')->first();

        $this->browse(function ($userWarungTest)use($userWarung) {
            $userWarungTest->loginAs(User::find(1))
            ->visit('/dashboard#/user-warung')
            ->assertSee('User Warung')
            ->pause(1000)
            ->with('.table', function ($table) use($userWarung){
            $table->assertSee('003') //sesuaikan dengan data masing masing
            ->pause(2000)
            ->press('#confirm-ya-'.$userWarung->id)
            ->pause(1000);
        })
            ->waitFor('.swal-modal', 10)
            ->whenAvailable('.swal-modal', function ($modal) {
                $modal->assertSee('Konfirmasi User')
                ->press('OK');
            });        
            $userWarungTest->pause(1000)
            ->whenAvailable('.swal-modal', function ($modal) {
                $modal->assertSee('Berhasil Mengkonfirmasi Warung!')
                ->press('OK');
            });
        });

    } 

    public function testKonfirmasiNo() {

        $userWarung = UserWarung::select('id')->where('tipe_user', 4)->where('konfirmasi_admin', 1)->orderBy('id', 'DESC')->first();

        $this->browse(function ($userWarungTest)use($userWarung) {
            $userWarungTest->loginAs(User::find(1))
            ->visit('/dashboard#/user-warung')
            ->assertSee('User Warung')
            ->pause(1000)
            ->with('.table', function ($table) use($userWarung){
            $table->assertSee('003') //sesuaikan dengan data masing masing
            ->pause(2000)
            ->press('#confirm-no-'.$userWarung->id)
            ->pause(1000);
        })
            ->waitFor('.swal-modal', 10)
            ->whenAvailable('.swal-modal', function ($modal) {
                $modal->assertSee('Konfirmasi User')
                ->press('OK');
            });        
            $userWarungTest->pause(1000)
            ->whenAvailable('.swal-modal', function ($modal) {
                $modal->assertSee('Berhasil Membatalkan Konfirmasi Warung!')
                ->press('OK');
            });
        });

    } 

    public function testUbahUserWarung() {
        $userWarung = UserWarung::select('id')->where('tipe_user', 4)->orderBy('id', 'DESC')->first();

        $this->browse(function ($userWarungTest, $second)use($userWarung) {
            $userWarungTest->loginAs(User::find(1))
            ->visit('/dashboard#/user-warung')
            ->assertSee('User Warung')
            ->pause(1000)
            ->with('.table', function ($table) use($userWarung){
                $table->assertSee('003')//sesuaikan dengan data masing masing
                ->press('#edit-'.$userWarung->id);
            })
            ->assertSee('Edit User Warung')
            ->type('name','Hobnob Apparel')
            ->type('no_telp','081210011997')
            ->type('email','hobnobapp@gmail.com')
            ->type('alamat','Podomomoro, Kab. Pringsewu');
            $userWarungTest->script("document.getElementById('kelurahan').selectize.setValue('97');");  
            $userWarungTest->assertSee("Jagabaya III");// sesuaikan dengan data masing masing
            $userWarungTest->script("document.getElementById('warung').selectize.setValue('2');");  
            $userWarungTest->assertSee("Difes Store");// sesuaikan dengan data masing masing
            $userWarungTest->press('#btnSimpanuserWarung');
            $userWarungTest->pause(1000)
            ->whenAvailable('.swal-modal', function ($modal) {
                $modal->assertSee('Berhasil Mengubah User Warung Hobnob Apparel')
                ->press('OK');
            });
        });
    }

    public function testUniqueNoTelpUserWarung() {
        $userWarung = UserWarung::select('id')->where('tipe_user', 4)->orderBy('id', 'DESC')->first();

        $this->browse(function ($userWarungTest, $second)use($userWarung) {
            $userWarungTest->loginAs(User::find(1))
            ->visit('/dashboard#/user-warung')
            ->assertSee('User Warung')
            ->pause(1000)
            ->with('.table', function ($table) use($userWarung){
                $table->assertSee('081210011997')//sesuaikan dengan data masing masing
                ->press('#edit-'.$userWarung->id);
            })
            ->assertSee('Edit User Warung')
            ->type('name','Hobnob Apparel')
            ->type('no_telp','001')
            ->type('email','hobnobapp@gmail.com')
            ->type('alamat','Podomomoro, Kab. Pringsewu');
            $userWarungTest->script("document.getElementById('kelurahan').selectize.setValue('97');");  
            $userWarungTest->assertSee("Jagabaya III");// sesuaikan dengan data masing masing
            $userWarungTest->script("document.getElementById('warung').selectize.setValue('2');");  
            $userWarungTest->assertSee("Difes Store");// sesuaikan dengan data masing masing
            $userWarungTest->press('#btnSimpanuserWarung');
            $userWarungTest->pause(2000)
            ->assertDialogOpened('Periksa Kembali Data Yang Anda Masukan')
            ->acceptDialog();
            $userWarungTest->waitForText('MAAF NO TELP SUDAH TERPAKAI.');
        });
    }

    public function testUniqueEmailUserWarung() {
        $userWarung = UserWarung::select('id')->where('tipe_user', 4)->orderBy('id', 'DESC')->first();

        $this->browse(function ($userWarungTest, $second)use($userWarung) {
            $userWarungTest->loginAs(User::find(1))
            ->visit('/dashboard#/user-warung')
            ->assertSee('User Warung')
            ->pause(1000)
            ->with('.table', function ($table) use($userWarung){
                $table->assertSee('081210011997')//sesuaikan dengan data masing masing
                ->press('#edit-'.$userWarung->id);
            })
            ->assertSee('Edit User Warung')
            ->type('name','Hobnob Apparel')
            ->type('no_telp','085383558355')
            ->type('email','admin@gmail.com')
            ->type('alamat','Podomomoro, Kab. Pringsewu');
            $userWarungTest->script("document.getElementById('kelurahan').selectize.setValue('97');");  
            $userWarungTest->assertSee("Jagabaya III");// sesuaikan dengan data masing masing
            $userWarungTest->script("document.getElementById('warung').selectize.setValue('2');");  
            $userWarungTest->assertSee("Difes Store");// sesuaikan dengan data masing masing
            $userWarungTest->press('#btnSimpanuserWarung');
            $userWarungTest->pause(2000)
            ->assertDialogOpened('Periksa Kembali Data Yang Anda Masukan')
            ->acceptDialog();
            $userWarungTest->waitForText('MAAF EMAIL SUDAH TERPAKAI.');
        });
    }

    public function testPencarianUserWarung() {
        $this->browse(function ($komunitasTest) {

            $komunitasTest->loginAs(User::find(1))
            ->visit('/dashboard#/user-warung')
            ->assertSee('User Warung')
            ->pause(1000)
            ->type('pencarian',"Market's Monkey")
            ->pause(2000)
            ->with('.table', function ($table){
                $table->assertSee("Market's Monkey");
            });
            
        });

    }
}
