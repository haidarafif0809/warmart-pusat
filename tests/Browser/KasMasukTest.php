<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Kas;
use App\KategoriTransaksi;
use App\KasMasuk;
use App\User;

class KasMasukTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
  public function testTambahKasMasuk(){

        $this->browse(function ($first){
            $first->loginAs(User::find(4))
                  ->visit('/kas_masuk') 
                  ->assertSeeLink('Tambah Kas Masuk')
                  ->clickLink('Tambah Kas Masuk');
                  $first->script("document.getElementById('kas').selectize.setValue('1');");  
                  $first->assertSee('KAS BESAR');
                  $first->script("document.getElementById('kategori_transaksi').selectize.setValue('1');");  
                  $first->assertSee('BIAYA OPERASIONAL');
                  $first->type('jumlah','2000')
                  ->type('keterangan','-');
                    $first->element('#btnSimpan')->submit();
                    $first->assertSee('SUKSES: Berhasil Menambah Transaksi Kas Masuk Sebesar "2000"'); 

        }); 
    }
    public function testEditKasMasuk(){
          $user = User::select('id_warung')->where('id',4)->first();
          $kas_masuk = KasMasuk::select('id','no_faktur')->where('id_warung',$user->id_warung)->limit(1)->first();
          $this->browse(function ($first) use($kas_masuk) {
            $first->loginAs(User::find(4))
                  ->visit('/kas_masuk')  
                  ->whenAvailable('.js-confirm', function ($table) { 
                              ;
                    })
                  ->with('.table', function ($table) use($kas_masuk) {
                        $table->press('#edit-'.$kas_masuk->id);
                    })
                  ->assertSee('Edit Kas Masuk');
                  $first->script("document.getElementById('kas').selectize.setValue('2');");  
                  $first->assertSee('KAS WARUNG');
                  $first->script("document.getElementById('kategori_transaksi').selectize.setValue('2');");  
                  $first->assertSee('GAJI KARYAWAN');
                  $first->type('jumlah','4000')
                  ->type('keterangan','-');                   
                    $first->element('#btnSimpan')->submit();
                    $first->assertSee('SUKSES :Berhasil Mengubah Transaksi Kas Masuk "'.$kas_masuk->no_faktur.'"'); 

        }); 
    }

    public function testHapusKasMasuk(){
          $user = User::select('id_warung')->where('id',4)->first();
          $kas_masuk = KasMasuk::select('id','no_faktur')->where('id_warung',$user->id_warung)->limit(1)->first();
          $this->browse(function ($first) use($kas_masuk) {
            $first->loginAs(User::find(4))
                  ->visit('/kas_masuk')  
                  ->whenAvailable('.js-confirm', function ($table) { 
                              ;
                    })
                  ->with('.table', function ($table) use($kas_masuk) {
                        $table->press('#delete-'.$kas_masuk->id)
                              ->assertDialogOpened('Yakin Mau Menghapus Kas Masuk '.$kas_masuk->no_faktur.'?');
                    })->driver->switchTo()->alert()->accept();
                    $first->assertSee('SUCCES : Kas Masuk '.$kas_masuk->no_faktur.' Berhasil Di Hapus'); 

        }); 
    }
     public function testTambahKasMasukLagi(){

        $this->browse(function ($first){
            $first->loginAs(User::find(4))
                  ->visit('/kas_masuk') 
                  ->assertSeeLink('Tambah Kas Masuk')
                  ->clickLink('Tambah Kas Masuk');
                  $first->script("document.getElementById('kas').selectize.setValue('1');");  
                  $first->assertSee('KAS BESAR');
                  $first->script("document.getElementById('kategori_transaksi').selectize.setValue('1');");  
                  $first->assertSee('BIAYA OPERASIONAL');
                  $first->type('jumlah','20000')
                  ->type('keterangan','-');
                    $first->element('#btnSimpan')->submit();
                    $first->assertSee('SUKSES: Berhasil Menambah Transaksi Kas Masuk Sebesar "20000"'); 

        }); 
    }

    // test untuk mengeluarkan kas , 
   public function testTambahKasKeluar(){

        $this->browse(function ($first){
            $first->loginAs(User::find(4))
                  ->visit('/kas_keluar') 
                  ->assertSeeLink('Tambah Kas Keluar')
                  ->clickLink('Tambah Kas Keluar');
                  $first->script("document.getElementById('nama_kas').selectize.setValue('1');");  
                  $first->assertSee('KAS BESAR');
                  $first->script("document.getElementById('kategori_transaksi').selectize.setValue('1');");  
                  $first->assertSee('BIAYA OPERASIONAL');
                  $first->type('jumlah','20000')
                  ->type('keterangan','-');
                    $first->element('#btnKasKeluar')->submit();
                    $first->assertSee('SUKSES : BERHASIL MENAMBAH TRANSAKSI KAS KELUAR SEBESAR "20000"'); 

        }); 
    }
    public function testHapusKasMasukYgKasNyaMinusApabilaDihapus(){
          $user = User::select('id_warung')->where('id',4)->first();
          $kas_masuk = KasMasuk::select('id','no_faktur')->where('id_warung',$user->id_warung)->limit(1)->first();
          $this->browse(function ($first) use($kas_masuk) {
            $first->loginAs(User::find(4))
                  ->visit('/kas_masuk')  
                  ->whenAvailable('.js-confirm', function ($table) { 
                              ;
                    })
                  ->with('.table', function ($table) use($kas_masuk) {
                        $table->press('#delete-'.$kas_masuk->id)
                              ->assertDialogOpened('Yakin Mau Menghapus Kas Masuk '.$kas_masuk->no_faktur.'?');
                    })->driver->switchTo()->alert()->accept();
                    $first->assertSee('INFO : Kas Masuk '.$kas_masuk->no_faktur.' Tidak Di Hapus, Jika Dihapus Kas akan Minus'); 

        }); 
    }

}


