<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\User;
use App\ItemKeluar;
use App\TbsItemKeluar;

class ItemKeluarTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */

//FORM ITEM KELUAR
    public function testTambahTbsItemKeluarJumlahProdukKosong() {
        $this->browse(function ($first, $second){

            $first->loginAs(User::find(5))
                    ->visit('/item-keluar')
                    ->clickLink('Tambah Item Keluar');
            
            $first->script("document.getElementById('pilih_produk').selectize.setValue('1');");
            $first->assertSee('B001 - KECAP ASIN ABC')
                    ->pause(1000);

            $first->press('Submit')
                    ->assertSee('Jumlah Harus Di isi!');
        });
    }

    public function testTambahTbsItemKeluar() {
        $this->browse(function ($first, $second){

            $first->loginAs(User::find(5))
                    ->visit('/item-keluar')
                    ->clickLink('Tambah Item Keluar');
            
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
                        //cari tombol hapus tbs item keluar dan scroll ke sana
                        ->element('.btn-hapus-tbs')->getLocationOnScreenOnceScrolledIntoView();
                        //form hapus tbs item keluarnya di submit
                        $table->element('.form-hapus-tbs')->submit();                      
                    })
                //untuk menclick tombol oke di alert dialog javascript untuk menghapus tbs item keluar
                ->driver->switchTo()->alert()->accept();
        });
    }

    public function testTambahTbsItemKeluarSudahAda() {
        $this->browse(function ($first, $second){

            $first->loginAs(User::find(5))
                    ->visit('/item-keluar')
                    ->clickLink('Tambah Item Keluar');
            
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
                        //cari tombol hapus tbs item keluar dan scroll ke sana
                        ->element('.btn-hapus-tbs')->getLocationOnScreenOnceScrolledIntoView();
                        //form hapus tbs item keluarnya di submit
                        $table->element('.form-hapus-tbs')->submit();                      
                    })
                //untuk menclick tombol oke di alert dialog javascript untuk menghapus tbs item keluar
                ->driver->switchTo()->alert()->accept();
        });
    }

    public function testUpdateTbsItemKeluar() {
        $this->browse(function ($first, $second){

            $first->loginAs(User::find(5))
                    ->visit('/item-keluar')
                    ->clickLink('Tambah Item Keluar');
            
            $first->script("document.getElementById('pilih_produk').selectize.setValue('1');");
            $first->assertSee('B001 - KECAP ASIN ABC')
                    ->type('qty_produk', '1')
                    ->pause(1000);

            $first->press('Submit')
                    ->assertSee('SUKSES : BERHASIL MENAMBAH PRODUK "KECAP ASIN ABC"')
                    ->pause(1000)
                    ->press('#edit_jumlah_produk')
                    ->assertSee('Jumlah Item Keluar')
                    ->type('edit_qty_produk', '2');

            $first->press('Submit')
                    ->assertSee('SUKSES : BERHASIL MENGUBAH JUMLAH ITEM KELUAR "KECAP ASIN ABC"');

            //PRODUK LANGSUNG DIHAPUS
            $first->waitFor('.js-confirm')
                    ->with('.table', function ($table) {
                        $table->assertSee('KECAP ASIN ABC')
                        //cari tombol hapus tbs item keluar dan scroll ke sana
                        ->element('.btn-hapus-tbs')->getLocationOnScreenOnceScrolledIntoView();
                        //form hapus tbs item keluarnya di submit
                        $table->element('.form-hapus-tbs')->submit();                      
                    })
                //untuk menclick tombol oke di alert dialog javascript untuk menghapus tbs item keluar
                ->driver->switchTo()->alert()->accept();

        });
    }

    public function testHapusTbsItemKeluar() {
        $this->browse(function ($first, $second){

            $first->loginAs(User::find(5))
                    ->visit('/item-keluar')
                    ->clickLink('Tambah Item Keluar');
            
            $first->script("document.getElementById('pilih_produk').selectize.setValue('1');");
            $first->assertSee('B001 - KECAP ASIN ABC')
                    ->type('qty_produk', '1')
                    ->pause(1000);

            $first->press('Submit')
                    ->assertSee('SUKSES : BERHASIL MENAMBAH PRODUK "KECAP ASIN ABC"')
                    ->waitFor('.js-confirm')
                    ->with('.table', function ($table) {
                        $table->assertSee('KECAP ASIN ABC')
                        //cari tombol hapus tbs item keluar dan scroll ke sana
                        ->element('.btn-hapus-tbs')->getLocationOnScreenOnceScrolledIntoView();
                        //form hapus tbs item keluarnya di submit
                        $table->element('.form-hapus-tbs')->submit();                      
                    })
                //untuk menclick tombol oke di alert dialog javascript untuk menghapus tbs item keluar
                ->driver->switchTo()->alert()->accept();

            $first->assertSee('SUKSES : BERHASIL MENGHAPUS PRODUK');
        });
    }

    public function testBatalTbsItemKeluar() {
        $this->browse(function ($first, $second){

            $first->loginAs(User::find(5))
                    ->visit('/item-keluar')
                    ->clickLink('Tambah Item Keluar');
            
            $first->script("document.getElementById('pilih_produk').selectize.setValue('1');");
            $first->assertSee('B001 - KECAP ASIN ABC')
                    ->type('qty_produk', '1')
                    ->pause(1000);

            $first->press('Submit')
                    ->assertSee('SUKSES : BERHASIL MENAMBAH PRODUK "KECAP ASIN ABC"')
                    ->press('#btnBatal')
                    ->driver->switchTo()->alert()->accept();

            $first->assertSee('SUKSES : BERHASIL MEMBATALKAN ITEM KELUAR');
        });
    }

    public function testSelesaiItemKeluar() {
        $this->browse(function ($first, $second){

        //MEMBUAT NO FAKTUR
            $warung_id = 1;
            $no_faktur = ItemKeluar::no_faktur($warung_id);

            $first->loginAs(User::find(5))
                    ->visit('/item-keluar')
                    ->clickLink('Tambah Item Keluar');
            
            $first->script("document.getElementById('pilih_produk').selectize.setValue('1');");
            $first->assertSee('B001 - KECAP ASIN ABC')
                    ->type('qty_produk', '1')
                    ->pause(1000);

            $first->press('Submit')
                    ->assertSee('SUKSES : BERHASIL MENAMBAH PRODUK "KECAP ASIN ABC"')
                    ->press('#btnSelesai')
                    ->assertSee('Anda Yakin Ingin Menyelesaikan Transaksi Ini')
                    ->type('#keterangan','Keterangan BrowserTest')
                    ->press('#btn-simpan-item-keluar')
                    ->assertSee('SUKSES : BERHASIL MELAKUKAN TRANSAKSI ITEM KELUAR FAKTUR "'.$no_faktur.'"');
        });
    }

    public function testSelesaiBelumAdaProduk(){

        $this->browse(function ($first, $second){

            $first->loginAs(User::find(5))
                    ->visit('/item-keluar')
                    ->clickLink('Tambah Item Keluar');
            
            $first->press('#btnSelesai')
                    ->assertSee('Anda Yakin Ingin Menyelesaikan Transaksi Ini')
                    ->type('#keterangan','Keterangan BrowserTest')
                    ->press('#btn-simpan-item-keluar')
                    ->assertSee('GAGAL : BELUM ADA PRODUK YANG DIINPUTKAN');
        }); 
    }

//ITEM KELUAR
    public function testDetailItemKeluar(){

        $item_keluar = ItemKeluar::select('id')->orderBy('id', 'DESC')->first();
        $this->browse(function ($first, $second)use($item_keluar){

            $first->loginAs(User::find(5))
                    ->visit('/item-keluar')
                    ->assertSeeLink('Tambah Item Keluar')
                    ->whenAvailable('.js-confirm', function ($table) { 
                        ;
                    })
                    ->with('.table', function ($table)use($item_keluar){
                        $table->press('#btnDetail-'.$item_keluar->id);
                    });
            $first->assertSee('Detail Item Keluar');
        });
    }

    public function testEditItemKeluar(){

        $item_keluar = ItemKeluar::select(['id', 'no_faktur'])->orderBy('id', 'DESC')->first();
        $this->browse(function ($first, $second)use($item_keluar){

            $first->loginAs(User::find(5))
                    ->visit('/item-keluar')
                    ->assertSeeLink('Tambah Item Keluar')
                    ->whenAvailable('.js-confirm', function ($table) { 
                        ;
                    })
                    ->with('.table', function ($table) use($item_keluar){
                        $table->assertSee(''.$item_keluar->no_faktur.'')
                              ->press('#btnEdit-'.$item_keluar->id);
                    })
                    ->assertSee('Edit Item Keluar : '.$item_keluar->no_faktur.'')
                    ->press('#edit_jumlah_produk')
                    ->assertSee('Jumlah Item Keluar')
                    ->type('edit_qty_produk', '2')
                    ->press('Submit')
                    ->assertSee('SUKSES : BERHASIL MENGUBAH JUMLAH ITEM KELUAR "KECAP ASIN ABC"');
                    
                $first->press('#btnSelesai')
                    ->assertSee('Anda Yakin Ingin Menyelesaikan Transaksi Ini')
                    ->type('#keterangan','Keterangan Update BrowserTest')
                    ->press('#btn-simpan-item-keluar')
                    ->assertSee('SUKSES : BERHASIL MELAKUKAN EDIT TRANSAKSI ITEM KELUAR FAKTUR "'.$item_keluar->no_faktur.'"');
        });
    }

    public function testHapusItemKeluar(){

        $item_keluar = ItemKeluar::select(['id', 'no_faktur'])->orderBy('id', 'DESC')->first();
        $this->browse(function ($first, $second)use($item_keluar){

            $first->loginAs(User::find(5))
                    ->visit('/item-keluar')
                    ->assertSeeLink('Tambah Item Keluar')
                    ->whenAvailable('.js-confirm', function ($table) { 
                        ;
                    })
                    ->with('.table', function ($table) use($item_keluar){
                        $table->assertSee(''.$item_keluar->no_faktur.'')
                              ->press('#btnHapus-'.$item_keluar->id)
                              ->assertDialogOpened('Anda Yakin Ingin Menghapus Item Keluar Faktur "'.$item_keluar->no_faktur.'" ?');
                    })->driver->switchTo()->alert()->accept();

            $first->assertSee('SUKSES : ITEM KELUAR BERHASIL DIHAPUS');
        });
    }

}