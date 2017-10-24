<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;


class SuplierTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testTambahSuplier()
    {
        $this->browse(function ($first, $second) {
            $first->loginAs(User::find(5))
                  ->visit('/suplier') 
                  ->clickLink('Tambah Suplier')
                  ->type('nama_suplier','PT ANDAGLOS')
                  ->type('alamat','jln permadani')
                  ->type('no_telp','085524596647');
                    $first->element('#btnSubmit')->submit();
                    $first->assertSeeLink('Tambah Suplier'); 
        }); 
    }


     public function testEditProduk(){

          $this->browse(function ($first, $second) {
            $first->loginAs(User::find(5))
                  ->visit('/suplier')  
                  ->whenAvailable('.js-confirm', function ($table) { 
                              ;
                    })
                  ->with('.table', function ($table) {
                        $table->assertSee('PT ANDAGLOS')
                              ->clickLink('Ubah');
                    })
                  ->assertSee('Edit Suplier')
                  ->type('nama_suplier','PT ANDAGLOS GLOBAL TEKNOLOGI')
                  ->type('alamat','jln permadani 90DA')
                  ->type('no_telp','08552459664755');
                   
                    $first->element('#btnSubmit')->submit();                    
                    $first->assertSeeLink('Tambah Suplier'); 

        }); 
    }

       public function testBarcodeTidakBolehSama(){// BARCODE PER WARUNG TIDAK BOLEH SAMA 

        $this->browse(function ($first, $second) {
            $first->loginAs(User::find(5))
                  ->visit('/suplier') 
                  ->clickLink('Tambah Suplier')
                  ->type('nama_suplier','PT ANDAGLOS GLOBAL TEKNOLOGI')
                  ->type('alamat','jln permadani 90DA')
                  ->type('no_telp','08552459664755');
                  $first->element('#btnSubmit')->submit();
                    $first->script('document.getElementById("nama_suplier").focus();');
                    $first->assertSeeIn('#nama_suplier_error','Maaf nama suplier Sudah Terpakai.');
        }); 

    }

}
