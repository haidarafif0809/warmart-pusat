<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Suplier;

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


     public function testEditSuplier(){

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

       public function testNamaSuplierBolehSama(){// BARCODE PER WARUNG TIDAK BOLEH SAMA 

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

        public function testHapusSuplier(){
          $suplier = Suplier::select('id')->where('nama_suplier','PT ANDAGLOS GLOBAL TEKNOLOGI')->first();
          $this->browse(function ($first) use ($suplier) {
            $first->loginAs(User::find(5))
                  ->visit('/suplier')
                  ->whenAvailable('.js-confirm', function ($table) { 
                              ;
                    })
                  ->with('.table', function ($table) use($suplier) {
                        $table->press('#delete-'.$suplier->id)
                              ->assertDialogOpened('Anda Yakin Ingin Menghapus PT ANDAGLOS GLOBAL TEKNOLOGI ?');
                    })->driver->switchTo()->alert()->accept();
                    $first->assertSee('SUKSES : BERHASIL MENGHAPUS SUPLIER'); 

        }   ); 
    }

}
