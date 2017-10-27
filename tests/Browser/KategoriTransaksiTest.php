<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\KategoriTransaksi;
use App\TransaksiKas;
use App\KasMasuk;
use App\Kas;
class KategoriTransaksiTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testTambahKategoriTransaksi()
    {
         $this->browse(function ($first, $second) {
            $first->loginAs(User::find(5))
                  ->visit('/kategori_transaksi') 
                  ->clickLink('Tambah Kategori Transaksi')
                  ->type('nama_kategori_transaksi','MODAL SIMPANAN');
                    $first->element('#btnSubmit')->submit();
                    $first->assertSeeLink('Tambah Kategori Transaksi');
        }); 
    }

   public function testEditKategoriTransaksi(){

          $this->browse(function ($first, $second) {
            $first->loginAs(User::find(5))
                  ->visit('/kategori_transaksi')  
                  ->whenAvailable('.js-confirm', function ($table) { 
                              ;
                    })
                  ->with('.table', function ($table) {
                        $table->assertSee('MODAL SIMPANAN')
                              ->clickLink('Ubah');
                    })
                  ->assertSee('Edit Kategori Transaksi')
                  ->type('nama_kategori_transaksi','MODAL SIMPANAN BERBEDA');
                   
                    $first->element('#btnSubmit')->submit();                    
                    $first->assertSeeLink('Tambah Kategori Transaksi'); 

        }); 
    }


       public function testNamaKategoriTransaksiBolehSama(){// BARCODE PER WARUNG TIDAK BOLEH SAMA 

        $this->browse(function ($first, $second) {
            $first->loginAs(User::find(5))
                  ->visit('/kategori_transaksi') 
                  ->clickLink('Tambah Kategori Transaksi')
                   ->type('nama_kategori_transaksi','MODAL SIMPANAN BERBEDA');
                  $first->element('#btnSubmit')->submit();
                    $first->script('document.getElementById("nama_kategori_transaksi").focus();');
                    $first->assertSeeIn('#nama_kategori_transaksi_error','Maaf nama kategori transaksi Sudah Terpakai.');
        }); 

    }

       public function testHapusKategoriTransaksi(){
          $kategori = KategoriTransaksi::select('id')->where('nama_kategori_transaksi','MODAL SIMPANAN BERBEDA')->first();
          $this->browse(function ($first) use ($kategori) {
            $first->loginAs(User::find(5))
                  ->visit('/kategori_transaksi')
                  ->whenAvailable('.js-confirm', function ($table) { 
                              ;
                    })
                  ->with('.table', function ($table) use ($kategori) {
                        $table->press('#delete-'.$kategori->id)
                              ->assertDialogOpened('Yakin Mau Menghapus Kategori Transaksi MODAL SIMPANAN BERBEDA?');
                    })->driver->switchTo()->alert()->accept();
                    $first->assertSee('SUKSES : KATEGORI TRANSAKSI BERHASIL DIHAPUS'); 

        }); 
    }

       public function testNamaKategoriSudahTerpakaiTransaksi(){// BARCODE PER WARUNG TIDAK BOLEH SAMA 
                 $id_warung = 1;
                 $no_faktur = KasMasuk::no_faktur($id_warung);
                $tambah_kategori_transaksi = KategoriTransaksi::create([
                'nama_kategori_transaksi' =>'COBA TRANSAKSI',
                'id_warung' =>1]); 

                $kategori = KategoriTransaksi::select('id')->where('nama_kategori_transaksi',$tambah_kategori_transaksi->nama_kategori_transaksi)->first();
                $kas = Kas::select('id')->where('warung_id',1)->first();

                $kas_tambah = KasMasuk::create(['no_faktur' => $no_faktur,'kas' => $kas->id,'kategori' => $kategori->id,'jumlah' => 1000,'keterangan' => '-','id_warung'=> 1]);
             
             //PROSES MEMBUAT TRANSAKSI KAS
             TransaksiKas::create(['no_faktur' => $no_faktur,'jenis_transaksi'=>'kas_masuk' ,'jumlah_masuk' => 1000,'kas' => $kas->id,'warung_id'=>1]);

        $this->browse(function ($first) use ($kategori){
            $first->loginAs(User::find(5))
                  ->visit('/kategori_transaksi')
                  ->whenAvailable('.js-confirm', function ($table){ 
                              ;
                    })
                  ->with('.table', function ($table) use ($kategori) {
                        $table->press('#delete-'.$kategori->id)
                              ->assertDialogOpened('Yakin Mau Menghapus Kategori Transaksi COBA TRANSAKSI?');
                    })->driver->switchTo()->alert()->accept();
                    $first->assertSee('GAGAL : KATEGORI TRANSAKSI TIDAK BISA DIHAPUS. KARENA SUDAH TERPAKAI.'); 
        }); 

    }




}
