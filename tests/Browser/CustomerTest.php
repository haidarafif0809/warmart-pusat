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
       ->visit('/dashboard#/customer')
       ->assertSee('Customer')
       ->clickLink('Tambah Customer')
       ->type('name','Ridho Ficardo')
       ->type('no_telp','4')
       ->type('email','ridho@gmail.com')
       ->type('alamat','Jl. Kemiling');
       $CustomerTest->script(["document.getElementById('tanggal_lahir').value = '10 Jan 1997' ",]);
       $CustomerTest->script("document.getElementById('komunitas').selectize.setValue('2');");
       $CustomerTest->assertSee('Sample Member');
       $CustomerTest->press("#btnSimpanCustomer")
       ->whenAvailable('.swal-modal', function ($modal) {
        $modal->assertSee('Berhasil Mengubah Menambah Ridho Ficardo')
        ->press('OK');
      });
     });
    }


    public function testUbahCustomer(){

      $customer = Customer::select('id')->where('no_telp','4')->first();
      $this->browse(function ($customerTest) use($customer){
        $customerTest->loginAs(User::find(1))
        ->visit('/dashboard#/customer')
        ->assertSee('Customer')
        ->with('.data-ada', function ($table) use($customer){
          $table->assertSee('Ridho Ficardo')
          ->press('#edit-'.$customer->id);
        })
        ->assertSee('Edit Customer')
        ->type('name','Ficardo Ridho')
        ->type('no_telp','3')
        ->type('email','ficardo@gmail.com')
        ->type('alamat','Jl. Kedaton');
        $customerTest->script(["document.getElementById('tanggal_lahir').value = '10 Mar 1996' ",]);
        $customerTest->script("document.getElementById('komunitas').selectize.setValue('2');");  
        $customerTest->assertSee('Sample Member');
        $customerTest->press("#btnSimpanCustomer")
        ->whenAvailable('.swal-modal', function ($modal) {
          $modal->assertSee('Berhasil Mengubah customer Ficardo Ridho')
          ->press('OK');
        });        
      });
    }

    public function testHapusCustomer() {

      $customer = Customer::select('id')->where('no_telp','4')->first();

      $this->browse(function ($customerTest)use($customer) {
        $customerTest->loginAs(User::find(1))
        ->visit('/dashboard#/customer')
        ->assertSee('Customer')
        ->with('.data-ada', function ($table) use($customer){
          $table->assertSee('Ficardo Ridho')
          ->press('#delete-'.$customer->id)
          ->whenAvailable('.swal-modal', function ($modal) {
            $modal->assertSee('Anda Yakin Ingin Menghapus Customer Ficardo Ridho ?')
            ->press('OK')
            ->whenAvailable('.swal-modal', function ($modal_hapus) {
              $modal_hapus->assertSee('Customer Berhasil Dihapus!')
              ->press('OK');
            });
          });        
        });
      });

    }

    public function testUniqueEmailCustomer() {
      $this->browse(function ($CustomerTest, $second) {
       $CustomerTest->loginAs(User::find(1))
       ->visit('/dashboard#/customer')
       ->assertSee('Customer')
       ->clickLink('Tambah Customer')
       ->type('name','CUSTOMER PRIBADI')
       ->type('no_telp','4')
       ->type('email','customer@gmail.com')
       ->type('alamat','Jl.Customer, no.48');
       $CustomerTest->script(["document.getElementById('tanggal_lahir').value = '10 Mar 1996' ",]);
       $CustomerTest->script("document.getElementById('komunitas').selectize.setValue('2');");  
       $CustomerTest->assertSee('Sample Member');
       $CustomerTest->press("#btnSimpanCustomer");
       $CustomerTest->assertSeeIn('#no_telp_error','MAAF NO TELP SUDAH TERPAKAI.');
     });
    }

    public function testDetailCustomer(){

      $customer = Customer::select('id')->where('no_telp','4')->first();
      $this->browse(function ($customerTest) use($customer){
        $customerTest->loginAs(User::find(1))
        ->visit('/dashboard#/customer')
        ->assertSee('Customer')
        ->with('.data-ada', function ($table) use($customer){
          $table->assertSee('Ficardo Ridho')
          ->press('#detail-'.$customer->id);
        })
        ->assertSee('Detail Customer');     
      });
    }
    

  }