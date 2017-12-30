<?php

namespace App\Providers;

use App\DetailItemKeluar;
use App\DetailItemMasuk;
use App\DetailPembelian;
use App\DetailPenjualan;
use App\DetailPenjualanPos;
use App\PenjualanPos;
use App\ItemKeluar;
use App\ItemMasuk;
use App\Kas;
use App\KasKeluar;
use App\KasMutasi;
use App\KategoriTransaksi;
use App\Observers\DetailItemKeluarObserver;
use App\Observers\DetailItemMasukObserver;
use App\Observers\DetailPembelianObserver;
use App\Observers\DetailPenjualanObserver;
use App\Observers\DetailPenjualanPosObserver;
use App\Observers\ItemKeluarObserver;
use App\Observers\ItemMasukObserver;
use App\Observers\KasKeluarObserver;
use App\Observers\KasMutasiObserver;
use App\Observers\KasObserver;
use App\Observers\KategoriTransaksiObserver;
use App\Observers\PembelianObserver;
use App\Observers\PenjualanPosObserver;
use App\Observers\UserWarungObserver;
use App\Observers\WarungObserver;
use App\Pembelian;
use App\UserWarung;
use App\Warung;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;
use Validator;

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
        Validator::extend('without_spaces', function ($attr, $value) {
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
        DetailPembelian::observe(DetailPembelianObserver::class);
        Pembelian::observe(PembelianObserver::class);
        DetailPenjualan::observe(DetailPenjualanObserver::class);
        DetailPenjualanPos::observe(DetailPenjualanPosObserver::class);
        PenjualanPos::observe(PenjualanPosObserver::class);
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
