<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\User;
use App\ItemMasuk;
use App\TbsItemMasuk;

class ItemMasukTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */

    //TAMBAH TBS ITEM MASUK TETAPI JUMLAH PRODUK KOSONG
    public function testTambahTbsItemMasukJumlahProdukKosong() {
        $this->browse(function ($first, $second){

            $first->loginAs(User::find(4))
                    ->visit('/item-masuk')
                    ->clickLink('Tambah Item Masuk');
            
            $first->script("document.getElementById('pilih_produk').selectize.setValue('1');");
            $first->assertSee('B001 - KECAP ASIN ABC')
                    ->pause(1000);

            $first->press('Submit')
                    ->assertSee('Jumlah Harus Di isi!');
        });
    }

    //TAMBAH TBS ITEM MASUK
    public function testTambahTbsItemMasuk() {
        $this->browse(function ($first, $second){

            $first->loginAs(User::find(4))
                    ->visit('/item-masuk')
                    ->clickLink('Tambah Item Masuk');
            
            $first->script("document.getElementById('pilih_produk').selectize.setValue('1');");
            $first->assertSee('B001 - KECAP ASIN ABC')
                    ->type('qty_produk', '1')
                    ->pause(1000);

            $first->press('Submit')
                    ->assertSee('SUKSES : BERHASIL MENAMBAH PRODUK "KECAP ASIN ABC"');

            //PRODUK LANGSUNG DIHAPUS
            $first->waitFor('.js-confirm')
                    ->with('.table', function ($table) {
                        $table->assertSee('KECAP ASIN ABC')
                        //cari tombol hapus tbs item masuk dan scroll ke sana
                        ->element('.btn-hapus-tbs')->getLocationOnScreenOnceScrolledIntoView();
                        //form hapus tbs item masuknya di submit
                        $table->element('.form-hapus-tbs')->submit();                      
                    })
                //untuk menclick tombol oke di alert dialog javascript untuk menghapus tbs item masuk
                ->driver->switchTo()->alert()->accept();
        });
    }

    //TAMBAH TBS ITEM MASUK  TETAPI PRODUK SUDUH ADA
    public function testTambahTbsItemMasukSudahAda() {
        $this->browse(function ($first, $second){

            $first->loginAs(User::find(4))
                    ->visit('/item-masuk')
                    ->clickLink('Tambah Item Masuk');
            
            $first->script("document.getElementById('pilih_produk').selectize.setValue('1');");
            $first->assertSee('B001 - KECAP ASIN ABC')
                    ->type('qty_produk', '1')
                    ->pause(1000);

            $first->press('Submit')
                    ->assertSee('SUKSES : BERHASIL MENAMBAH PRODUK "KECAP ASIN ABC"')
                    ->pause(1000);

            $first->script("document.getElementById('pilih_produk').selectize.setValue('1');");
            $first->assertSee('B001 - KECAP ASIN ABC')
                    ->type('qty_produk', '1')
                    ->pause(1000);

            $first->press('Submit')
                    ->assertSee('WARNING : PRODUK "KECAP ASIN ABC" SUDAH ADA, SILAKAN PILIH PRODUK LAIN !');

            //PRODUK LANGSUNG DIHAPUS
            $first->waitFor('.js-confirm')
                    ->with('.table', function ($table) {
                        $table->assertSee('KECAP ASIN ABC')
                        //cari tombol hapus tbs item masuk dan scroll ke sana
                        ->element('.btn-hapus-tbs')->getLocationOnScreenOnceScrolledIntoView();
                        //form hapus tbs item masuknya di submit
                        $table->element('.form-hapus-tbs')->submit();                      
                    })
                //untuk menclick tombol oke di alert dialog javascript untuk menghapus tbs item masuk
                ->driver->switchTo()->alert()->accept();
        });
    }

    //EDIT JUMLAH PRODUK KETIKA DI TBS ITEM MASUK
    public function testUpdateTbsItemMasuk() {
        $this->browse(function ($first, $second){

            $first->loginAs(User::find(4))
                    ->visit('/item-masuk')
                    ->clickLink('Tambah Item Masuk');
            
            $first->script("document.getElementById('pilih_produk').selectize.setValue('1');");
            $first->assertSee('B001 - KECAP ASIN ABC')
                    ->type('qty_produk', '1')
                    ->pause(1000);

            $first->press('Submit')
                    ->assertSee('SUKSES : BERHASIL MENAMBAH PRODUK "KECAP ASIN ABC"')
                    ->pause(1000)
                    ->press('#edit_jumlah_produk')
                    ->assertSee('Jumlah Item Masuk')
                    ->type('edit_qty_produk', '2');

            $first->press('Submit')
                    ->assertSee('SUKSES : BERHASIL MENGUBAH JUMLAH ITEM MASUK "KECAP ASIN ABC"');

            //PRODUK LANGSUNG DIHAPUS
            $first->waitFor('.js-confirm')
                    ->with('.table', function ($table) {
                        $table->assertSee('KECAP ASIN ABC')
                        //cari tombol hapus tbs item masuk dan scroll ke sana
                        ->element('.btn-hapus-tbs')->getLocationOnScreenOnceScrolledIntoView();
                        //form hapus tbs item masuknya di submit
                        $table->element('.form-hapus-tbs')->submit();                      
                    })
                //untuk menclick tombol oke di alert dialog javascript untuk menghapus tbs item masuk
                ->driver->switchTo()->alert()->accept();

        });
    }

    //HAPUS TBS ITEM MASUK
    public function testHapusTbsItemMasuk() {
        $this->browse(function ($first, $second){

            $first->loginAs(User::find(4))
                    ->visit('/item-masuk')
                    ->clickLink('Tambah Item Masuk');
            
            $first->script("document.getElementById('pilih_produk').selectize.setValue('1');");
            $first->assertSee('B001 - KECAP ASIN ABC')
                    ->type('qty_produk', '1')
                    ->pause(1000);

            $first->press('Submit')
                    ->assertSee('SUKSES : BERHASIL MENAMBAH PRODUK "KECAP ASIN ABC"')
                    ->waitFor('.js-confirm')
                    ->with('.table', function ($table) {
                        $table->assertSee('KECAP ASIN ABC')
                        //cari tombol hapus tbs item masuk dan scroll ke sana
                        ->element('.btn-hapus-tbs')->getLocationOnScreenOnceScrolledIntoView();
                        //form hapus tbs item masuknya di submit
                        $table->element('.form-hapus-tbs')->submit();                      
                    })
                //untuk menclick tombol oke di alert dialog javascript untuk menghapus tbs item masuk
                ->driver->switchTo()->alert()->accept();

            $first->assertSee('SUKSES : BERHASIL MENGHAPUS PRODUK');
        });
    }

    //BATALKAN TAMBAH ITEM MASUK
    public function testBatalTbsItemMasuk() {
        $this->browse(function ($first, $second){

            $first->loginAs(User::find(4))
                    ->visit('/item-masuk')
                    ->clickLink('Tambah Item Masuk');
            
            $first->script("document.getElementById('pilih_produk').selectize.setValue('1');");
            $first->assertSee('B001 - KECAP ASIN ABC')
                    ->type('qty_produk', '1')
                    ->pause(1000);

            $first->press('Submit')
                    ->assertSee('SUKSES : BERHASIL MENAMBAH PRODUK "KECAP ASIN ABC"')
                    ->press('#btnBatal')
                    ->driver->switchTo()->alert()->accept();

            $first->assertSee('SUKSES : BERHASIL MEMBATALKAN ITEM MASUK');
        });
    }

    //TAMBAH ITEM MASUK
    public function testSelesaiItemMasuk() {
        $this->browse(function ($first, $second){

        //MEMBUAT NO FAKTUR
            $warung_id = 1;
            $no_faktur = ItemMasuk::no_faktur($warung_id);

            $first->loginAs(User::find(4))
                    ->visit('/item-masuk')
                    ->clickLink('Tambah Item Masuk');
            
            $first->script("document.getElementById('pilih_produk').selectize.setValue('1');");
            $first->assertSee('B001 - KECAP ASIN ABC')
                    ->type('qty_produk', '1')
                    ->pause(1000);

            $first->press('Submit')
                    ->assertSee('SUKSES : BERHASIL MENAMBAH PRODUK "KECAP ASIN ABC"')
                    ->press('#btnSelesai')
                    ->assertSee('Anda Yakin Ingin Menyelesaikan Transaksi Ini')
                    ->type('#keterangan','Keterangan BrowserTest')
                    ->press('#btn-simpan-item-masuk')
                    ->assertSee('SUKSES : BERHASIL MELAKUKAN TRANSAKSI ITEM MASUK FAKTUR "'.$no_faktur.'"');
        });
    }

    //TAMBAH ITEM MASUK TETAPI BELUM ADA PRODUK
    public function testSelesaiItemMasukBelumAdaProduk(){

        $this->browse(function ($first, $second){

            $first->loginAs(User::find(4))
                    ->visit('/item-masuk')
                    ->clickLink('Tambah Item Masuk');
            
            $first->press('#btnSelesai')
                    ->assertSee('Anda Yakin Ingin Menyelesaikan Transaksi Ini')
                    ->type('#keterangan','Keterangan BrowserTest')
                    ->press('#btn-simpan-item-masuk')
                    ->assertSee('GAGAL : BELUM ADA PRODUK YANG DIINPUTKAN');
        }); 
    }

    //CEK DETAIL PRODUK ITEM MASUK
    public function testDetailItemMasuk(){

        $item_masuk = ItemMasuk::select('id')->orderBy('id', 'DESC')->first();
        $this->browse(function ($first, $second)use($item_masuk){

            $first->loginAs(User::find(4))
                    ->visit('/item-masuk')
                    ->assertSeeLink('Tambah Item Masuk')
                    ->whenAvailable('.js-confirm', function ($table) { 
                        ;
                    })
                    ->with('.table', function ($table)use($item_masuk){
                        $table->press('#btnDetail-'.$item_masuk->id);
                    });
            $first->assertSee('Detail Item Masuk');
        });
    }

    public function testEditItemMasuk(){

        $item_masuk = ItemMasuk::select(['id', 'no_faktur'])->orderBy('id', 'DESC')->first();
        $this->browse(function ($first, $second)use($item_masuk){

            $first->loginAs(User::find(5))
                    ->visit('/item-masuk')
                    ->assertSeeLink('Tambah Item Masuk')
                    ->whenAvailable('.js-confirm', function ($table) { 
                        ;
                    })
                    ->with('.table', function ($table) use($item_masuk){
                        $table->assertSee(''.$item_masuk->no_faktur.'')
                              ->press('#btnEdit-'.$item_masuk->id);
                    })
                    ->assertSee('Edit Item Masuk : '.$item_masuk->no_faktur.'')
                    ->press('#edit_jumlah_produk')
                    ->assertSee('Jumlah Item Masuk')
                    ->type('edit_qty_produk', '2')
                    ->press('Submit')
                    ->assertSee('SUKSES : BERHASIL MENGUBAH JUMLAH ITEM MASUK "KECAP ASIN ABC"');
                    
                $first->press('#btnSelesai')
                    ->assertSee('Anda Yakin Ingin Menyelesaikan Transaksi Ini')
                    ->type('#keterangan','Keterangan Update BrowserTest')
                    ->press('#btn-simpan-item-masuk')
                    ->assertSee('SUKSES : BERHASIL MELAKUKAN EDIT TRANSAKSI ITEM MASUK FAKTUR "'.$item_masuk->no_faktur.'"');
        });
    }
}
