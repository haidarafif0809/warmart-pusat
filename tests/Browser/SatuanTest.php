<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Satuan;

class SatuanTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testTambahSatuan() {
      $this->browse(function ($masterSatuan, $second) {
        $masterSatuan->loginAs(User::find(1))
        ->visit('/satuan')
        ->clickLink('Tambah Satuan')
        ->type('nama_satuan','BANDENG');
        $masterSatuan->press('#btnSimpanSatuan')
        ->whenAvailable('.swal-modal', function ($modal) {
          $modal->assertSee('Sukses : Berhasil Menambah Satuan BANDENG');
          
        });
      });
    }

    public function testUbahSatuan() {

      $satuan = Satuan::select('id')->where('nama_satuan','BANDENG')->first();

      $this->browse(function ($mastersatuan, $second)use($satuan) {
        $mastersatuan->loginAs(User::find(1))
        ->visit('/satuan')
        ->assertSeeLink('Tambah Satuan')
        ->whenAvailable('.data-ada', function ($modal) use ($satuan) {
          $modal->press('#edit-'.$satuan->id);
          
        })
        ->assertSee('Edit Satuan')
        ->type('nama_satuan','BANDENG EDIT'); 
        $mastersatuan->press('#btnSimpanSatuan')
        ->whenAvailable('.swal-modal', function ($modal) {
          $modal->assertSee('Berhasil Mengubah Satuan!');
          
        });
      });

    }

    public function testHapusSatuan() {

      $satuan = Satuan::select('id')->where('nama_satuan','BANDENG EDIT')->first();

      $this->browse(function ($masterSatuan, $second)use($satuan) {
        $masterSatuan->loginAs(User::find(1))
        ->visit('/satuan')
        ->assertSeeLink('Tambah Satuan')
        ->whenAvailable('.data-ada', function ($modal) use ($satuan) {
          $modal->click('#delete-'.$satuan->id) 
          ->assertDialogOpened('Yakin Ingin Menghapus satuan BANDENG EDIT ?');
          
        })
        ->driver->switchTo()->alert()->accept();
        $masterSatuan->whenAvailable('.swal-modal', function ($modal) {
          $modal->assertSee('Berhasil Menghapus satuan BANDENG EDIT');
          
        });
      });

    }



  }
