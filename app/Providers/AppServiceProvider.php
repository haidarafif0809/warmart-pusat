<?php

namespace App\Providers;

use App\BankWarung;
use App\Barang;
use App\DetailItemKeluar;
use App\DetailItemMasuk;
use App\DetailPembelian;
use App\PembelianOrder;
use App\PenerimaanProduk;
use App\DetailPenjualan;
use App\DetailPenjualanPos;
use App\DetailReturPembelian;
use App\ItemKeluar;
use App\ItemMasuk;
use App\Kas;
use App\KasKeluar;
use App\KasMutasi;
use App\KategoriTransaksi;
use App\Observers\BankWarungObserver;
use App\Observers\DetailItemKeluarObserver;
use App\Observers\DetailItemMasukObserver;
use App\Observers\DetailPembelianObserver;
use App\Observers\PembelianOrderObserver;
use App\Observers\PenerimaanProdukObserver;
use App\Observers\DetailPenjualanObserver;
use App\Observers\DetailPenjualanPosObserver;
use App\Observers\DetailReturPembelianObserver;
use App\Observers\ItemKeluarObserver;
use App\Observers\ItemMasukObserver;
use App\Observers\KasKeluarObserver;
use App\Observers\KasMutasiObserver;
use App\Observers\KasObserver;
use App\Observers\KategoriTransaksiObserver;
use App\Observers\PembayaranHutangObserver;
use App\Observers\PembayaranPiutangObserver;
use App\Observers\PembelianObserver;
use App\Observers\PenjualanPosObserver;
use App\Observers\ReturPembelianObserver;
use App\Observers\ProdukObserver;
use App\Observers\StokOpnameObserver;
use App\Observers\UserWarungObserver;
use App\Observers\WarungObserver;
use App\Observers\ReturPenjualanObserver;
use App\Observers\DetailReturPenjualanObserver;
use App\PembayaranHutang;
use App\PembayaranPiutang;
use App\Pembelian;
use App\PenjualanPos;
use App\ReturPembelian;
use App\StokOpname;
use App\UserWarung;
use App\Warung;
use App\ReturPenjualan;
use App\DetailReturPenjualan;
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
        PembayaranPiutang::observe(PembayaranPiutangObserver::class);
        PembayaranHutang::observe(PembayaranHutangObserver::class);
        StokOpname::observe(StokOpnameObserver::class);
        Barang::observe(ProdukObserver::class);
        BankWarung::observe(BankWarungObserver::class);
        PembelianOrder::observe(PembelianOrderObserver::class);
        PenerimaanProduk::observe(PenerimaanProdukObserver::class);
        DetailReturPenjualan::observe(DetailReturPenjualanObserver::class);
        ReturPenjualan::observe(ReturPenjualanObserver::class);
        ReturPembelian::observe(ReturPembelianObserver::class);
        DetailReturPembelian::observe(DetailReturPembelianObserver::class);
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
