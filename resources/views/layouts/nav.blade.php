@if(Auth::user()->kasir_id == 0)

<li>
    <a class="disabled-menu" data-toggle="collapse" href="#transaksiKas">
        <i class="material-icons">
            autorenew
        </i>
        <p>
            Kas
            <b class="caret">
            </b>
        </p>
    </a>
    <div class="collapse" id="transaksiKas">
        <ul class="nav">
            <li>
                <router-link :to="{name: 'indexKas'}" class="menu-nav">
                    <span class="sidebar-mini">
                        K
                    </span>
                    <span class="sidebar-normal">
                        Kas
                    </span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'indexKategoriTransaksi'}" class="menu-nav">
                    <span class="sidebar-mini">
                        KT
                    </span>
                    <span class="sidebar-normal">
                        Kategori Transaksi
                    </span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'indexKasMasuk'}" class="menu-nav">
                    <span class="sidebar-mini">
                        KM
                    </span>
                    <span class="sidebar-normal">
                        Kas Masuk
                    </span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'indexKasKeluar'}" class="menu-nav">
                    <span class="sidebar-mini">
                        KK
                    </span>
                    <span class="sidebar-normal">
                        Kas Keluar
                    </span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'indexKasMutasi'}" class="menu-nav">
                    <span class="sidebar-mini">
                        KMT
                    </span>
                    <span class="sidebar-normal">
                        Kas Mutasi
                    </span>
                </router-link>
            </li>
        </ul>
    </div>
</li>
<!--PRODUK -->
<li>
    <router-link :to="{name: 'indexProduk'}" class="menu-nav disabled-menu">
        <i class="material-icons">
            store
        </i>
        <p>
            Produk
        </p>
    </router-link>
</li>
<li>
    <a class="disabled-menu" data-toggle="collapse" href="#persediaan">
        <i class="material-icons">
            assessment
        </i>
        <p>
            Persediaan
            <b class="caret">
            </b>
        </p>
    </a>
    <div class="collapse" id="persediaan">
        <ul class="nav">
            <li>
                <router-link :to="{name: 'indexItemMasuk'}" class="menu-nav">
                    <span class="sidebar-mini">
                        IM
                    </span>
                    <span class="sidebar-normal">
                        Item Masuk
                    </span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'indexItemKeluar'}" class="menu-nav">
                    <span class="sidebar-mini">
                        IK
                    </span>
                    <span class="sidebar-normal">
                        Item Keluar
                    </span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'indexLaporanPersediaan'}" class="menu-nav">
                    <span class="sidebar-mini">
                        LP
                    </span>
                    <span class="sidebar-normal">
                        Laporan Persediaan
                    </span>
                </router-link>
            </li>
        </ul>
    </div>
</li>
<!--PEMBELIAN-->
<li>
    <router-link :to="{name: 'indexPembelian'}" class="menu-nav disabled-menu">
        <i class="material-icons">
            add_shopping_cart
        </i>
        <p>
            Pembelian
        </p>
    </router-link>
</li>

@endif


<!--PESANAN -->
<li>
    <router-link :to="{name: 'indexPesananWarung'}" class="menu-nav disabled-menu">
        <i class="material-icons">
            archive
        </i>
        <p>
            Pesanan
        </p>
    </router-link>
</li>
{{-- PENJUALAN --}}
<li>
    <router-link :to="{name: 'createPenjualan'}" class="menu-nav disabled-menu">
        <i class="material-icons">
            shopping_cart
        </i>
        <p>
            Penjualan
        </p>
    </router-link>
</li>

@if(Auth::user()->kasir_id == 0)

<li>
    <a class="disabled-menu" data-toggle="collapse" href="#pembayaran">
        <i class="material-icons">
            local_atm
        </i>
        <p>
            Pembayaran
            <b class="caret">
            </b>
        </p>
    </a>
    <div class="collapse" id="pembayaran">
        <ul class="nav">
            <li>
                <router-link :to="{name: 'indexPembayaranPiutang'}" class="menu-nav">
                    <span class="sidebar-mini">
                        PP
                    </span>
                    <span class="sidebar-normal">
                        Pembayaran Piutang
                    </span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'indexPembayaranHutang'}" class="menu-nav">
                    <span class="sidebar-mini">
                        PH
                    </span>
                    <span class="sidebar-normal">
                        Pembayaran Hutang
                    </span>
                </router-link>
            </li>
        </ul>
    </div>
</li>
<li>
    <a class="disabled-menu" data-toggle="collapse" href="#laporan">
        <i class="material-icons">
            assignment
        </i>
        <p>
            Laporan
            <b class="caret">
            </b>
        </p>
    </a>
    <div class="collapse" id="laporan">
        <ul class="nav">
            <li>
                <router-link :to="{name: 'indexLaporanLabaKotor'}" class="menu-nav">
                    <span class="sidebar-mini">
                        LK
                    </span>
                    <span class="sidebar-normal">
                        Laba Kotor /Pelanggan
                    </span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'indexLaporanLabaKotorProduk'}" class="menu-nav">
                    <span class="sidebar-mini">
                        LK
                    </span>
                    <span class="sidebar-normal">
                        Laba Kotor /Produk
                    </span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'indexLaporanKartuStok'}" class="menu-nav">
                    <span class="sidebar-mini">
                        LM
                    </span>
                    <span class="sidebar-normal">
                        Laporan Kartu Stok
                    </span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'indexLaporanKas'}" class="menu-nav">
                    <span class="sidebar-mini">
                        LK
                    </span>
                    <span class="sidebar-normal">
                        Laporan Kas
                    </span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'indexLaporanMutasiStok'}" class="menu-nav">
                    <span class="sidebar-mini">
                        LM
                    </span>
                    <span class="sidebar-normal">
                        Laporan Mutasi Stok
                    </span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'indexLaporanPembelianProduk'}" class="menu-nav">
                    <span class="sidebar-mini">
                        LP
                    </span>
                    <span class="sidebar-normal">
                        Laporan Pembelian /Produk
                    </span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'indexPenjualan'}" class="menu-nav">
                    <span class="sidebar-mini">
                        LP
                    </span>
                    <span class="sidebar-normal">
                        Laporan Penjualan
                    </span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'indexLaporanPenjualanProduk'}" class="menu-nav">
                    <span class="sidebar-mini">
                        LP
                    </span>
                    <span class="sidebar-normal">
                        Laporan Penjualan /Produks
                    </span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'indexLaporanPenjualanPelanggan'}" class="menu-nav">
                    <span class="sidebar-mini">
                        LP
                    </span>
                    <span class="sidebar-normal">
                        Laporan Penjualan /Pelanggan
                    </span>
                </router-link>
            </li>
        </ul>
    </div>
</li>

@endif

<li>
    <a class="disabled-menu" data-toggle="collapse" href="#pagesExamples">
        <i class="material-icons">
            image
        </i>
        <p>
            Master Data
            <b class="caret">
            </b>
        </p>
    </a>
    <div class="collapse" id="pagesExamples">
        <ul class="nav">
            @if(\App\SettingAplikasi::select('tipe_aplikasi')->first()->tipe_aplikasi == 1)
            <li>
                <router-link :to="{name: 'indexCustomer'}" class="menu-nav">
                    <span class="sidebar-mini">
                        CU
                    </span>
                    <span class="sidebar-normal">
                        Customer
                    </span>
                </router-link>
            </li>

            @if(Auth::user()->kasir_id == 0)
            <li>
                <router-link :to="{name: 'indexKelompokProduk'}" class="menu-nav">
                    <span class="sidebar-mini">
                        KP
                    </span>
                    <span class="sidebar-normal">
                        Kelompok Produk
                    </span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'indexSatuan'}" class="menu-nav">
                    <span class="sidebar-mini">
                        SA
                    </span>
                    <span class="sidebar-normal">
                        Satuan
                    </span>
                </router-link>
            </li>
            @endif
            @endif

            @if(Auth::user()->kasir_id == 0)
            <li>
                <router-link :to="{name: 'indexSuplier'}" class="menu-nav">
                    <span class="sidebar-mini">
                        SU
                    </span>
                    <span class="sidebar-normal">
                        Supplier
                    </span>
                </router-link>
            </li>
            @endif
        </ul>
    </div>
</li>


@if(Auth::user()->kasir_id == 0)

<li>
    <a class="disabled-menu" data-toggle="collapse" href="#settingWarung">
        <i class="material-icons">
            settings
        </i>
        <p>
            Setting
            <b class="caret">
            </b>
        </p>
    </a>
    <div class="collapse" id="settingWarung">
        <ul class="nav">
            <li>
                <router-link :to="{name: 'indexSettingFooter'}" class="menu-nav">
                    <span class="sidebar-mini">
                        SF
                    </span>
                    <span class="sidebar-normal">
                        Setting Footer
                    </span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'indexSettingVerifikasi', params: {id_warung: <?=\Auth::user()->id_warung;?>}}" class="menu-nav">
                    <span class="sidebar-mini">
                        SV
                    </span>
                    <span class="sidebar-normal">
                        Setting Verifikasi
                    </span>
                </router-link>
            </li>
        </ul>
    </div>
</li>

@endif