<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\User;

class KasTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Login');
        });
    }

    public function testTambahKas(){

        $this->browse(function ($first, $second){

              $first->loginAs(User::find(4))
                    ->visit('/home')
                    ->clickLink("Kas")
                    ->clickLink("Kas")
                    ->type('kode_kas', 'K-001')
                    ->type('nama_kas', 'Kas Browser')
                    ->type('status_kas', '1')
                    ->type('default_kas', '0')
                    ->press('#btnSimpanBank')
                    ->assertSee('Sukses');

        });
    }
}
