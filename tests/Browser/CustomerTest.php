<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\DatePicker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Customer;

class CustomerTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */

   public function testTambahCustomer() {
        $this->browse(function ($CustomerTest, $second) {
           $CustomerTest->loginAs(User::find(1))
                      ->visit('/customer')
                      ->assertSee('Customer')
                      ->clickLink('Tambah Customer')
                      ->type('name','CUSTOMER PRIBADI')
                      ->type('no_telp','085678761466')
                      ->type('email','customerTest@gmail.com')
                      ->type('alamat','Jl.Customer, no.48');
                      $CustomerTest->script(["document.querySelector('#datepicker1').value = '2000-10-10' ",]);
                      $CustomerTest->script("document.getElementById('pilih_komunitas').selectize.setValue('2');");  
                      $CustomerTest->assertSee('Sample Member');
                      $CustomerTest->press("#btnSimpanCustomer")
                      ->assertSee('Sukses : Berhasil Menambah Customer CUSTOMER PRIBADI');
        });
    }

    public function testEditCustomer(){

        $customer = Customer::select('id')->where('no_telp','085678761466')->first();
        $this->browse(function ($customerTest) use($customer){
                $customerTest->loginAs(User::find(1))
                         ->visit('/customer')
                         ->assertSee('Customer')
                        ->whenAvailable('.js-confirm', function ($table) { 
                              ;
                         })
                         ->with('.table', function ($table) use($customer){
                                $table->assertSee('CUSTOMER PRIBADI')
                                      ->press('#edit-'.$customer->id);
                            })
                        ->assertSee('Edit Customer')
                        ->type('name','TAAT PRIBADI')
                        ->type('no_telp','085678761456')
                        ->type('email','customerTestEdit@gmail.com')
                        ->type('alamat','Jl.Customer Tert Edit, no.48');
                          $customerTest->script(["document.querySelector('#datepicker1').value = '1998-10-10' ",]);
                          $customerTest->script("document.getElementById('pilih_komunitas').selectize.setValue('2');");  
                          $customerTest->assertSee('Sample Member');
                          $customerTest->press("#btnSimpanCustomer")
                        ->assertSee('Sukses : Berhasil Mengubah Customer TAAT PRIBADI');         
        });
    }


    public function testHapusCustomer() {

    $customer = Customer::select('id')->where('no_telp','085678761456')->first();

      $this->browse(function ($customerTest)use($customer) {
        $customerTest->loginAs(User::find(1))
                  ->visit('/customer')
                  ->assertSeeLink('Tambah Customer')
                  ->whenAvailable('.js-confirm', function ($table) { 
                              ;
                    })
                  ->with('.table', function ($table) use($customer){
                        $table->assertSee('TAAT PRIBADI')
                              ->press('#delete-'.$customer->id)
                              ->assertDialogOpened('Anda Yakin Ingin Menghapus TAAT PRIBADI ?');
                    })->driver->switchTo()->alert()->accept();
                  $customerTest->assertSee('SUKSES : CUSTOMER BERHASIL DIHAPUS');
        });

    }

    public function testUniqueNoTelpCustomer() {
        $this->browse(function ($CustomerTest, $second) {
           $CustomerTest->loginAs(User::find(1))
                      ->visit('/customer')
                      ->assertSee('Customer')
                      ->clickLink('Tambah Customer')
                      ->type('name','CUSTOMER PRIBADI')
                      ->type('no_telp','085345330858')
                      ->type('email','customerTest@gmail.com')
                      ->type('alamat','Jl.Customer, no.48');
                      $CustomerTest->script(["document.querySelector('#datepicker1').value = '2000-10-10' ",]);
                      $CustomerTest->script("document.getElementById('pilih_komunitas').selectize.setValue('2');");  
                      $CustomerTest->assertSee('Sample Member');
                      $CustomerTest->press("#btnSimpanCustomer");
                      $CustomerTest->script('document.getElementById("telpon_customer").focus();');
                      $CustomerTest->assertSeeIn('#no_telp_error','Maaf no telp Sudah Terpakai');
        });
    }
    public function testUniqueEmailCustomer() {
        $this->browse(function ($CustomerTest, $second) {
           $CustomerTest->loginAs(User::find(1))
                      ->visit('/customer')
                      ->assertSee('Customer')
                      ->clickLink('Tambah Customer')
                      ->type('name','CUSTOMER PRIBADI')
                      ->type('no_telp','235357474')
                      ->type('email','customer@gmail.com')
                      ->type('alamat','Jl.Customer, no.48');
                      $CustomerTest->script(["document.querySelector('#datepicker1').value = '2000-10-10' ",]);
                      $CustomerTest->script("document.getElementById('pilih_komunitas').selectize.setValue('2');");  
                      $CustomerTest->assertSee('Sample Member');
                      $CustomerTest->press("#btnSimpanCustomer");
                      $CustomerTest->script('document.getElementById("email_customer").focus();');
                      $CustomerTest->assertSeeIn('#email_error','Maaf email Sudah Terpakai');
        });
    }
}
