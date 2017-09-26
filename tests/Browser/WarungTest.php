<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;


class WarungTest extends DuskTestCase
{

    /**
     * A Dusk test example.
     *
     * @return void
     */
     public function testTambahWarung(){

        $this->browse(function ($first, $second) {
            $first->loginAs(User::find(1))
                  ->visit('/home')
                  ->clickLink('Warung')
                  ->clickLink('Tambah Warung')
                  ->type('email','fafa@gmail.com');
        });

    } 
}
