<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Support\Facades\Schema;
use Validator;
use App\Observers\WarungObserver;
use App\Observers\UserWarungObserver;
use App\Observers\KasObserver;
use App\Observers\KasKeluarObserver;
use App\Observers\KategoriTransaksiObserver;
use App\Observers\KasMutasiObserver;
use App\Observers\DetailItemKeluarObserver;
use App\Observers\ItemKeluarObserver;
use App\Observers\DetailItemMasukObserver;
use App\Observers\ItemMasukObserver;
use App\Warung;
use App\Kas;
use App\KasKeluar;
use App\UserWarung;
use App\KategoriTransaksi;
use App\KasMutasi;
use App\DetailItemKeluar;
use App\ItemKeluar;
use App\DetailItemMasuk;
use App\ItemMasuk;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //    
        Schema::defaultStringLength(191);
        Validator::extend('without_spaces', function($attr, $value){
            return preg_match('/^\S*$/u', $value);
        });

        Warung::observe(WarungObserver::class);
        UserWarung::observe(UserWarungObserver::class);
        Kas::observe(KasObserver::class);
        KasKeluar::observe(KasKeluarObserver::class);
        KategoriTransaksi::observe(KategoriTransaksiObserver::class);
        KasMutasi::observe(KasMutasiObserver::class);
        DetailItemKeluar::observe(DetailItemKeluarObserver::class);
        ItemKeluar::observe(ItemKeluarObserver::class);
        DetailItemMasuk::observe(DetailItemMasukObserver::class);
        ItemMasuk::observe(ItemMasukObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
    }
}
