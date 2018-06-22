<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Auth;
use App\User;

class KasMutasiTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    
     public function testTambahKasMutasi(){

        //$kas = Kas::select('id','nama_kas')->where('warung_id',Auth::user()->id_warung)->limit(1)->first();

        $this->browse(function ($first, $second)  {
            $first->loginAs(User::find(5))
                  ->visit('/kas_mutasi')                  
                  ->clickLink('Tambah Kas Mutasi'); 
                    $first->script("document.getElementById('dari_kas').selectize.setValue('4');");  
                    $first->assertSee('Kas Warmart');
                    $first->script("document.getElementById('ke_kas').selectize.setValue('3');");  
                    $first->assertSee('Kas Warung');
                    $first->type('jumlah','1000')
                          ->type('keterangan','Test Browser');
                    $first->element('#submit_kas')->submit();
                    $first->assertSee(' <b>BERHASIL:</b> Memutasikan Kas Sejumlah <b>1000</b>'); 

        }); 
    } 
}
