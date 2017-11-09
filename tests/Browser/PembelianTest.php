<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Auth;
use App\User;

class PembelianTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testTambahTbsPembelianJumlahProdukKosong()
    {
        $this->browse(function ($first, $second)  {
            $first->loginAs(User::find(5))
            ->visit('/pembelian')                  
            ->clickLink('Tambah Pembelian')
            ->assertSee('Pembelian');
            $first->script("document.getElementById('pilih_produk').selectize.setValue('1-KECAP ASIN ABC-55000');");
            $first->assertSee('B001 - KECAP ASIN ABC')
            ->pause(1000);
            $first->press('Submit')
            ->assertSee('Jumlah Produk Tidak Boleh 0 atau Kosong !')
            ->press('OK');
        }); 
    }

    public function testTambahTbsPembelian()
    {
        $this->browse(function ($first, $second)  {
            $first->loginAs(User::find(5))
            ->visit('/pembelian')                  
            ->clickLink('Tambah Pembelian')
            ->assertSee('Pembelian');
            $first->script("document.getElementById('pilih_produk').selectize.setValue('1-KECAP ASIN ABC-55000');");
            $first->assertSee('B001 - KECAP ASIN ABC')
            ->pause(1000);
            $first->type('#jadwal_produk_swal','1')
            ->press('Submit')
            ->assertSee('BERHASIL MENAMBAH PRODUK "KECAP ASIN ABC"');
        }); 
    }

    public function testTambahTbsPembelianHargaProdukDirubah()
    {
        $this->browse(function ($first, $second)  {
            $first->loginAs(User::find(5))
            ->visit('/pembelian')                  
            ->clickLink('Tambah Pembelian')
            ->assertSee('Pembelian');
            $first->script("document.getElementById('pilih_produk').selectize.setValue('1-KECAP ASIN ABC-55000');");
            $first->assertSee('B001 - KECAP ASIN ABC')
            ->pause(1000);
            $first->type('#jadwal_produk_swal','1')
            ->type('#harga_produk_swal','5000')
            ->press('Submit')
            ->assertSee('Anda Yakin Ingin Merubah Harga Beli Produk?')
            ->press('Ya')
            ->assertSee('BERHASIL MENAMBAH PRODUK "KECAP ASIN ABC"');
        }); 
    }

    public function testEditJumlahTbsPembelian(){
        $this->browse(function ($first, $second)  {
            $first->loginAs(User::find(5))
            ->visit('/pembelian')                  
            ->clickLink('Tambah Pembelian')
            ->assertSee('Pembelian');

            $first->script("document.getElementById('pilih_produk').selectize.setValue('1-KECAP ASIN ABC-55000');");
            $first->assertSee('B001 - KECAP ASIN ABC')
            ->pause(1000);

            $first->type('#jadwal_produk_swal','1')
            ->press('Submit')
            ->assertSee('BERHASIL MENAMBAH PRODUK "KECAP ASIN ABC"')

            ->press("#edit_jumlah_produk")
            ->assertSee('Berapa Jumlah Produk Yang Akan Dimasukkan ?')
            ->type('edit_qty_produk','2')
            ->press('Submit')
            ->assertSee('BERHASIL MENGUBAH JUMLAH PRODUK "KECAP ASIN ABC"')
            ->assertSeeIn('#edit_jumlah_produk','2');
        }); 
    }


    public function testEditHargaTbsPembelian(){
        $this->browse(function ($first, $second)  {
            $first->loginAs(User::find(5))
            ->visit('/pembelian')                  
            ->clickLink('Tambah Pembelian')
            ->assertSee('Pembelian');

            $first->script("document.getElementById('pilih_produk').selectize.setValue('1-KECAP ASIN ABC-55000');");
            $first->assertSee('B001 - KECAP ASIN ABC')
            ->pause(1000);

            $first->type('#jadwal_produk_swal','1')
            ->press('Submit')
            ->assertSee('BERHASIL MENAMBAH PRODUK "KECAP ASIN ABC"')

            ->press("#edit_harga_produk")
            ->assertSee('Berapa Harga Produk Yang Akan Dimasukkan ?')
            ->type('edit_harga_produk','7000')
            ->press('Submit')
            ->assertSee('Anda Yakin Ingin Merubah Harga Beli Produk?')
            ->press('Ya')
            ->assertSee('BERHASIL MENGUBAH HARGA PRODUK "KECAP ASIN ABC"')
            ->assertSeeIn('#edit_harga_produk','7000');
        }); 
    }



    public function testPotonganTbsPembelian(){
        $this->browse(function ($first, $second)  {
            $first->loginAs(User::find(5))
            ->visit('/pembelian')                  
            ->clickLink('Tambah Pembelian')
            ->assertSee('Pembelian');

            $first->script("document.getElementById('pilih_produk').selectize.setValue('1-KECAP ASIN ABC-55000');");
            $first->assertSee('B001 - KECAP ASIN ABC')
            ->pause(1000);

            $first->type('#jadwal_produk_swal','1')
            ->press('Submit')
            ->assertSee('BERHASIL MENAMBAH PRODUK "KECAP ASIN ABC"')

            ->press("#edit_potongan")
            ->assertSee('Sertakan % Jika Ingin Potongan Dalam Bentuk Persentase')
            ->type('edit_potongan_produk','1000')
            ->press('Submit')
            ->assertSee('BERHASIL MENGUBAH POTONGAN PRODUK "KECAP ASIN ABC"')
            ->assertSeeIn('#table-subtotal','54000');
        }); 
    }


    public function testPotonganPersenTbsPembelian(){
        $this->browse(function ($first, $second)  {
            $first->loginAs(User::find(5))
            ->visit('/pembelian')                  
            ->clickLink('Tambah Pembelian')
            ->assertSee('Pembelian');

            $first->script("document.getElementById('pilih_produk').selectize.setValue('1-KECAP ASIN ABC-55000');");
            $first->assertSee('B001 - KECAP ASIN ABC')
            ->pause(1000);

            $first->type('#jadwal_produk_swal','1')
            ->press('Submit')
            ->assertSee('BERHASIL MENAMBAH PRODUK "KECAP ASIN ABC"')

            ->press("#edit_potongan")
            ->assertSee('Sertakan % Jika Ingin Potongan Dalam Bentuk Persentase')
            ->type('edit_potongan_produk','1000%')
            ->press('Submit')
            ->assertSee('Potongan Tidak Boleh Lebih Dari 100%!')
            ->press('OK')
            ->pause(1000)

            ->press("#edit_potongan")
            ->assertSee('Sertakan % Jika Ingin Potongan Dalam Bentuk Persentase')
            ->type('edit_potongan_produk','10%')
            ->press('Submit')

            ->assertSee('BERHASIL MENGUBAH POTONGAN PRODUK "KECAP ASIN ABC"')
            ->assertSeeIn('#table-subtotal','49500');
        }); 
    }

    public function testPajakIncludeTbsPembelian(){
        $this->browse(function ($first, $second)  {
            $first->loginAs(User::find(5))
            ->visit('/pembelian')                  
            ->clickLink('Tambah Pembelian')
            ->assertSee('Pembelian');

            $first->script("document.getElementById('pilih_produk').selectize.setValue('1-KECAP ASIN ABC-55000');");
            $first->assertSee('B001 - KECAP ASIN ABC')
            ->pause(1000);

            $first->type('#jadwal_produk_swal','1')
            ->press('Submit')
            ->assertSee('BERHASIL MENAMBAH PRODUK "KECAP ASIN ABC"')

            ->press("#edit_tax_produk")
            ->assertSee('Sertakan % Jika Ingin Pajak Dalam Bentuk Persentase')
            ->select('#ppn_swal','Include')
            ->assertSeeIn('#ppn_swal','Include')
            ->type('#tax_swal','1000')
            ->press('Submit')
            ->assertSee('BERHASIL MENGUBAH PAJAK PRODUK "KECAP ASIN ABC"')
            ->assertSeeIn('#table-subtotal','55000');
        }); 
    }

    public function testPajakIExcludeTbsPembelian(){
        $this->browse(function ($first, $second)  {
            $first->loginAs(User::find(5))
            ->visit('/pembelian')                  
            ->clickLink('Tambah Pembelian')
            ->assertSee('Pembelian');

            $first->script("document.getElementById('pilih_produk').selectize.setValue('1-KECAP ASIN ABC-55000');");
            $first->assertSee('B001 - KECAP ASIN ABC')
            ->pause(1000);

            $first->type('#jadwal_produk_swal','1')
            ->press('Submit')
            ->assertSee('BERHASIL MENAMBAH PRODUK "KECAP ASIN ABC"')

            ->press("#edit_tax_produk")
            ->assertSee('Sertakan % Jika Ingin Pajak Dalam Bentuk Persentase')
            ->select('#ppn_swal','Exclude')
            ->assertSeeIn('#ppn_swal','Exclude')
            ->type('#tax_swal','1000')
            ->press('Submit')
            ->assertSee('BERHASIL MENGUBAH PAJAK PRODUK "KECAP ASIN ABC"')
            ->assertSeeIn('#table-subtotal','56000');
        }); 
    }

}
