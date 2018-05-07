@if(Auth::user()->kasir_id == 0)

@if(Laratrust::can('lihat_kas') || Laratrust::can('lihat_kategori_transaksi') || Laratrust::can('lihat_kas_masuk') || Laratrust::can('lihat_kas_keluar') || Laratrust::can('lihat_kas_mutasi')) 
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
            @if(Laratrust::can('lihat_kas')) 
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
            @endif

            @if(Laratrust::can('lihat_kategori_transaksi')) 
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
            @endif

            @if(Laratrust::can('lihat_kas_masuk')) 
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
            @endif

            @if(Laratrust::can('lihat_kas_keluar')) 
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
            @endif

            @if(Laratrust::can('lihat_kas_mutasi')) 
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
            @endif
        </ul>
    </div>
</li>
@endif

@if(Laratrust::can('lihat_produk')) 
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
@endif

@if(Laratrust::can('lihat_item_masuk') || Laratrust::can('lihat_item_keluar') || Laratrust::can('lihat_laporan_persediaan') || Laratrust::can('lihat_stok_opname')) 
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

            @if(Laratrust::can('lihat_item_masuk')) 
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
            @endif

            @if(Laratrust::can('lihat_item_keluar')) 
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
            @endif

            @if(Laratrust::can('lihat_laporan_persediaan')) 
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
            @endif

            @if(Laratrust::can('lihat_stok_opname')) 
            <li>
                <router-link :to="{name: 'indexStokOpname'}" class="menu-nav">
                    <span class="sidebar-mini">
                        SO
                    </span>
                    <span class="sidebar-normal">
                        Stok Opname
                    </span>
                </router-link>
            </li>
            @endif
        </ul>
    </div>
</li>

@endif {{-- end if otoritas Persediaan --}}
@endif  {{-- end if kasir_id  --}}

@if(Laratrust::can('lihat_pembelian')) 
<!--PEMBELIAN ORDER-->
<li>
    <router-link :to="{name: 'indexPembelianOrder'}" class="menu-nav disabled-menu">
        <i class="material-icons">
            add_shopping_cart
        </i>
        <p>
            Order Pembelian
        </p>
    </router-link>
</li>

<!--PENERIMAAN PRODUK-->
<li>
    <router-link :to="{name: 'indexPenerimaanProduk'}" class="menu-nav disabled-menu">
        <i class="material-icons">
            add_shopping_cart
        </i>
        <p>
            Penerimaan Produk
        </p>
    </router-link>
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

@if(Laratrust::can('lihat_pesanan')) 
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
@endif 

@if(Laratrust::can('lihat_penjualan')) 
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
@endif 


@if(Auth::user()->kasir_id == 0)
@if(Laratrust::can('lihat_pembayaran_hutang') || Laratrust::can('lihat_pembayaran_hutang')) 
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

            @if(Laratrust::can('lihat_pembayaran_piutang')) 
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
            @endif 

            @if(Laratrust::can('lihat_pembayaran_hutang')) 
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
            @endif

        </ul>
    </div>
</li>
@endif

@if(Laratrust::can('lihat_bucket_size') || Laratrust::can('jam_transaksi_penjualan') || Laratrust::can('laba_kotor_perpelanggan') || Laratrust::can('laba_kotor_perproduk') || Laratrust::can('kartu_stok') || Laratrust::can('kas') || Laratrust::can('mutasi_stok') || Laratrust::can('pembelian_perproduk') || Laratrust::can('hutang_beredar') || Laratrust::can('penjualan') || Laratrust::can('penjualan_harian') || Laratrust::can('penjualan_perproduk') || Laratrust::can('penjualan_perpelanggan') || Laratrust::can('penjualan_terbaik_perproduk')) 
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

            @if(Laratrust::can('lihat_bucket_size')) 
            <li>
                <router-link :to="{name: 'indexLaporanBucketSize'}" class="menu-nav">
                    <span class="sidebar-mini">
                        LB
                    </span>
                    <span class="sidebar-normal">
                        Laporan Bucket Size
                    </span>
                </router-link>
            </li>
            @endif

            @if(Laratrust::can('jam_transaksi_penjualan')) 
            <li>
                <router-link :to="{name: 'indexGrafikJamTransaksiPenjualan'}" class="menu-nav">
                    <span class="sidebar-mini">
                        LJ
                    </span>
                    <span class="sidebar-normal">
                      Jam Transaksi Penjualan
                  </span>
              </router-link>
          </li>
          @endif

          @if(Laratrust::can('laba_kotor_perpelanggan')) 
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
        @endif


        @if(Laratrust::can('laba_kotor_perproduk')) 
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
        @endif
        
        @if(Laratrust::can('kartu_stok')) 
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
        @endif
        
        @if(Laratrust::can('kas')) 
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
        @endif
        
        @if(Laratrust::can('mutasi_stok')) 
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
        @endif
        
        @if(Laratrust::can('pembelian_perproduk')) 
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
        @endif
        
        @if(Laratrust::can('hutang_beredar')) 
        <li>
            <router-link :to="{name: 'indexLaporanHutangBeredar'}" class="menu-nav">
                <span class="sidebar-mini">
                    LH
                </span>
                <span class="sidebar-normal">
                    Laporan Hutang
                </span>
            </router-link>
        </li>
        @endif
        
        @if(Laratrust::can('penjualan')) 
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
        @endif
        
        @if(Laratrust::can('penjualan_harian')) 
        <li>
            <router-link :to="{name: 'indexLaporanPenjualanHarian'}" class="menu-nav">
                <span class="sidebar-mini">
                    LP
                </span>
                <span class="sidebar-normal">
                    Laporan Penjualan Harian
                </span>
            </router-link>
        </li>
        @endif
        
        @if(Laratrust::can('penjualan_perproduk')) 
        <li>
            <router-link :to="{name: 'indexLaporanPenjualanProduk'}" class="menu-nav">
                <span class="sidebar-mini">
                    LP
                </span>
                <span class="sidebar-normal">
                    Laporan Penjualan /Produk
                </span>
            </router-link>
        </li>
        @endif
        
        @if(Laratrust::can('penjualan_perpelanggan')) 
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
        @endif
        
        @if(Laratrust::can('penjualan_terbaik_perproduk')) 
        <li>
            <router-link :to="{name: 'indexLaporanPenjualanTerbaik'}" class="menu-nav">
                <span class="sidebar-mini">
                    LP
                </span>
                <span class="sidebar-normal">
                    Penjualan Terbaik /Produk
                </span>
            </router-link>
        </li>
        @endif
    </ul>
</div>
</li>
@endif {{-- @endif otoritas penjualan --}}

@endif{{-- @endif kasir id --}}

@if(Laratrust::can('lihat_master_data')) 
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

            @if(Laratrust::can('lihat_user')) 
            <li>
                <router-link :to="{name: 'indexDaftarUserWarung'}" class="menu-nav">
                    <span class="sidebar-mini">
                        U
                    </span>
                    <span class="sidebar-normal">
                        User
                    </span>
                </router-link>
            </li>
            @endif

            @if(Laratrust::can('lihat_otoritas')) 
            <li>
                <router-link :to="{name: 'indexOtoritas'}" class="menu-nav">
                    <span class="sidebar-mini">
                        O
                    </span>
                    <span class="sidebar-normal">
                        Otoritas
                    </span>
                </router-link>
            </li>
            @endif

            @if(Laratrust::can('lihat_customer')) 
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
            @endif

            @if(Auth::user()->kasir_id == 0)
            @if(Laratrust::can('lihat_bank'))
            {{-- <li>
                <router-link :to="{name: 'indexBankWarung'}" class="menu-nav">
                    <span class="sidebar-mini">
                        B
                    </span>
                    <span class="sidebar-normal">
                        Bank 
                    </span>
                </router-link>
            </li> --}}
            @endif

            {{-- user kasir 
            <li>
                <router-link :to="{name: 'indexUserKasir'}" class="menu-nav">
                    <span class="sidebar-mini">
                        KA
                    </span>
                    <span class="sidebar-normal">
                        Kasir
                    </span>
                </router-link>
            </li>--}}

            @if(Laratrust::can('lihat_kelompok_produk'))
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
            @endif


            @if(Laratrust::can('lihat_satuan'))
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

            @if(Laratrust::can('lihat_supplier'))
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

            @endif{{-- endif user kasir --}}
            @endif{{-- end if setting aplikasi --}}

        </ul>
    </div>
</li>
@endif


@if(Auth::user()->kasir_id == 0)
@if(Laratrust::can('setting_footer') || Laratrust::can('setting_pengiriman') || Laratrust::can('seetting_verifikasi')|| Laratrust::can('lihat_setting_verifikasi')) 
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
                <router-link :to="{name: 'optimasiSeoIndex'}" class="menu-nav">
                    <span class="sidebar-mini">
                        OS
                    </span>
                    <span class="sidebar-normal">
                        Optimasi SEO
                    </span>
                </router-link>
            </li>

            @if(Laratrust::can('setting_footer')) 
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
            @endif

            @if(Laratrust::can('setting_pengiriman')) 
            <li>
                <router-link :to="{name: 'indexSettingPengiriman'}" class="menu-nav">
                    <span class="sidebar-mini">
                        SP
                    </span>
                    <span class="sidebar-normal">
                        Setting Pengiriman
                    </span>
                </router-link>
            </li>
            @endif

            @if(Laratrust::can('seetting_verifikasi')) 
            <li>
                <router-link :to="{name: 'indexSettingVerifikasi'}" class="menu-nav">
                    <span class="sidebar-mini">
                        SV
                    </span>
                    <span class="sidebar-normal">
                        Setting Verifikasi
                    </span>
                </router-link>
            </li>
            @endif

            @if(Laratrust::can('lihat_setting_promo')) 
            <li>
                <router-link :to="{name: 'indexSettingPromo'}" class="menu-nav">
                    <span class="sidebar-mini">
                        SP
                    </span>
                    <span class="sidebar-normal">
                        Setting Promo
                    </span>
                </router-link>
            </li>
            @endif
            <li>
                <router-link :to="{name: 'settingFixelIndex'}" class="menu-nav">
                    <span class="sidebar-mini">
                        SP
                    </span>
                    <span class="sidebar-normal">
                        Setting Pixel & Analytics
                    </span>
                </router-link>
            </li>

        </ul>
    </div>
</li>

@endif{{-- end if otoritas setting --}}

@endif