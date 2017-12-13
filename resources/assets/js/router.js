import BankCreate from './components/bank/BankCreate.vue';
import BankIndex from './components/bank/BankIndex.vue';
import BankEdit from './components/bank/BankEdit.vue';
import LazyIndex from './components/lazy_load/LazyIndex.vue';
import DashboardAdminIndex from './components/dashboard/DashboardAdminIndex.vue';
/**Satuan*/
import SatuanIndex from './components/satuan/SatuanIndex.vue';
import SatuanCreate from './components/satuan/SatuanCreate.vue';
import SatuanEdit from './components/satuan/SatuanEdit.vue';
/**User*/
import UserIndex from './components/user/UserIndex.vue';
import UserCreate from './components/user/UserCreate.vue';
import UserEdit from './components/user/UserEdit.vue';
/**Customer*/
import CustomerIndex from './components/customer/CustomerIndex.vue';
import CustomerCreate from './components/customer/CustomerCreate.vue';
import CustomerEdit from './components/customer/CustomerEdit.vue';
import CustomerDetail from './components/customer/CustomerDetail.vue';
/**Error Log*/
import ErrorIndex from './components/error/ErrorIndex.vue';
/**Warung*/
import WarungIndex from './components/warung/WarungIndex.vue';
import WarungCreate from './components/warung/WarungCreate.vue';
import WarungEdit from './components/warung/WarungEdit.vue';
/**KOMUNITAS*/
import KomunitasIndex from './components/komunitas/KomunitasIndex.vue';
import KomunitasCreate from './components/komunitas/KomunitasCreate.vue';
import KomunitasEdit from './components/komunitas/KomunitasEdit.vue';
import KomunitasDetail from './components/komunitas/KomunitasDetail.vue';
/**USER WARUNG*/
import UserWarungIndex from './components/user_warung/UserWarungIndex.vue';
import UserWarungEdit from './components/user_warung/UserWarungEdit.vue';
/**kelompok produk*/
import KelompokProdukIndex from './components/kelompok_produk/KelompokProdukIndex.vue';
import KelompokProdukCreate from './components/kelompok_produk/KelompokProdukCreate.vue';
import KelompokProdukEdit from './components/kelompok_produk/KelompokProdukEdit.vue';
// UBAH PROFIL ADMIN
import UbahProfilAdmin from './components/ubah_profil/UbahProfilAdmin.vue';
// UBAH PASSSWORD
import UbahPasswordAdmin from './components/ubah_password/UbahPasswordAdmin.vue';
// UBAH DATA PROFIL WARUNG
import ProfilWarungIndex from './components/warung_profil/ProfilWarungIndex.vue';
import ProfilWarungEdit from './components/warung_profil/ProfilWarungEdit.vue';
// KATEGORI TRANSAKSI
import KategoriTransaksiIndex from './components/kategori_transaksi/KategoriTransaksiIndex.vue';
import KategoriTransaksiCreate from './components/kategori_transaksi/KategoriTransaksiCreate.vue';
import KategoriTransaksiEdit from './components/kategori_transaksi/KategoriTransaksiEdit.vue';
// KAS WARUNG
import KasIndex from './components/kas/KasIndex.vue';
import KasCreate from './components/kas/KasCreate.vue';
import KasEdit from './components/kas/KasEdit.vue';
// SUPLIER
import SuplierIndex from './components/suplier/SuplierIndex.vue';
import SuplierCreate from './components/suplier/SuplierCreate.vue';
import SuplierEdit from './components/suplier/SuplierEdit.vue';
// PRODUK
import ProdukIndex from './components/produk/ProdukIndex.vue';
import ProdukCreate from './components/produk/ProdukCreate.vue';
import ProdukEdit from './components/produk/ProdukEdit.vue';
import ProdukDetail from './components/produk/ProdukDetail.vue';
// UBAH PASSSWORD USER WARUNG
import UbahPasswordUserWarung from './components/ubah_password/UbahPasswordUserWarung.vue';
// UBAH PROFIL USER WARUNG
import UbahProfilUserWarung from './components/ubah_profil/UbahProfilUserWarung.vue';
// ITEM MASUK 
import ItemMasukIndex from './components/item_masuk/ItemMasukIndex.vue';
import ItemMasukCreate from './components/item_masuk/ItemMasukCreate.vue';
import ItemMasukEdit from './components/item_masuk/ItemMasukEdit.vue';
import ItemMasukProsesEdit from './components/item_masuk/ItemMasukProsesEdit.vue';
import ItemMasukDetail from './components/item_masuk/ItemMasukDetail.vue';
// KAS KELUAR
import KasKeluarIndex from './components/kas_keluar/KasKeluarIndex.vue';
import KasKeluarCreate from './components/kas_keluar/KasKeluarCreate.vue';
import KasKeluarEdit from './components/kas_keluar/KasKeluarEdit.vue';
//KAS MASUK 
import KasMasukIndex from './components/kas_masuk/KasMasukIndex.vue';
import KasMasukCreate from './components/kas_masuk/KasMasukCreate.vue';
import KasMasukEdit from './components/kas_masuk/KasMasukEdit.vue';
// PESANAN WARUNG
import PesananWarungIndex from './components/pesanan_warung/PesananWarungIndex.vue';
import PesananWarungDetail from './components/pesanan_warung/PesananWarungDetail.vue';
//PEMBELIAN
import PembelianIndex from './components/pembelian/PembelianIndex.vue';
import PembelianCreate from './components/pembelian/PembelianCreate.vue';
import PembelianEdit from './components/pembelian/PembelianEdit.vue';
// ITEM KELUAR 
import ItemKeluarIndex from './components/item_keluar/ItemKeluarIndex.vue';
import ItemKeluarCreate from './components/item_keluar/ItemKeluarCreate.vue';
import ItemKeluarEdit from './components/item_keluar/ItemKeluarEdit.vue';
import ItemKeluarDetail from './components/item_keluar/ItemKeluarDetail.vue';
import ItemKeluarProsesEdit from './components/item_keluar/ItemKeluarProsesEdit.vue';
const routes = [{
        path: '/',
        components: {
            dashboardIndex: DashboardAdminIndex
        },
        name: 'indexDashboard'
    }, {
        path: '/create-bank',
        component: BankCreate,
        name: 'createBank'
    }, {
        path: '/satuan',
        component: SatuanIndex,
        name: 'indexSatuan'
    }, {
        path: '/bank',
        component: BankIndex,
        name: 'indexBank'
    }, {
        path: '/user',
        component: UserIndex,
        name: 'indexUser'
    },
    /*CUSTOMER*/
    {
        path: '/customer',
        component: CustomerIndex,
        name: 'indexCustomer'
    },
    /*ERROR LOG*/
    {
        path: '/error',
        component: ErrorIndex,
        name: 'indexError'
    }, {
        path: '/edit-bank/:id',
        component: BankEdit,
        name: 'editBank'
    }, {
        path: '/lazy_load',
        component: LazyIndex,
        name: 'indexLazy'
    }, {
        path: '/create-satuan',
        component: SatuanCreate,
        name: 'createSatuan'
    }, {
        path: '/edit-satuan/:id',
        component: SatuanEdit,
        name: 'editSatuan'
    },
    /*CUSTOMER*/
    {
        path: '/create-customer',
        component: CustomerCreate,
        name: 'createCustomer'
    }, {
        path: '/edit-customer/:id',
        component: CustomerEdit,
        name: 'editCustomer'
    }, {
        path: '/detail-customer/:id',
        component: CustomerDetail,
        name: 'detailCustomer'
    }, {
        path: '/create-user',
        component: UserCreate,
        name: 'createUser'
    }, {
        path: '/edit-user/:id',
        component: UserEdit,
        name: 'editUser'
    },
    /**Warung*/
    {
        path: '/warung',
        component: WarungIndex,
        name: 'indexWarung'
    }, {
        path: '/create-warung',
        component: WarungCreate,
        name: 'createWarung'
    }, {
        path: '/edit-warung/:id',
        component: WarungEdit,
        name: 'editWarung'
    },
    /**USER WARUNG*/
    {
        path: '/user-warung',
        component: UserWarungIndex,
        name: 'indexUserWarung'
    }, {
        path: '/edit-user-warung/:id',
        component: UserWarungEdit,
        name: 'editUserWarung'
    },
    // Komunitas
    {
        path: '/komunitas',
        component: KomunitasIndex,
        name: 'indexKomunitas'
    }, {
        path: '/create-komunitas',
        component: KomunitasCreate,
        name: 'createKomunitas'
    }, {
        path: '/edit-komunitas/:id',
        component: KomunitasEdit,
        name: 'editKomunitas'
    }, {
        path: '/detail-komunitas/:id',
        component: KomunitasDetail,
        name: 'detailKomunitas'
    },
    // kelompok produk
    {
        path: '/kelompok-produk',
        component: KelompokProdukIndex,
        name: 'indexKelompokProduk'
    }, {
        path: '/create-kelompok-produk',
        component: KelompokProdukCreate,
        name: 'createKelompokProduk'
    }, {
        path: '/edit-kelompok-produk/:id',
        component: KelompokProdukEdit,
        name: 'editKelompokProduk'
    },
    // ubah profil admin
    {
        path: '/ubah-profil-admin',
        component: UbahProfilAdmin,
        name: 'ubahProfilAdmin'
    },
    // ubah password admin
    {
        path: '/ubah-password-admin',
        component: UbahPasswordAdmin,
        name: 'ubahPasswordAdmin'
    },
    // Ubah Data Profil Warung
    {
        path: '/profil-warung',
        component: ProfilWarungIndex,
        name: 'indexProfilWarung'
    }, {
        path: '/edit-profil-warung/:id',
        component: ProfilWarungEdit,
        name: 'editProfilWarung'
    },
    // Kategori Transaksi
    {
        path: '/kategori-transaksi',
        component: KategoriTransaksiIndex,
        name: 'indexKategoriTransaksi'
    }, {
        path: '/create-kategori-transaksi',
        component: KategoriTransaksiCreate,
        name: 'createKategoriTransaksi'
    }, {
        path: '/edit-kategori-transaksi/:id',
        component: KategoriTransaksiEdit,
        name: 'editKategoriTransaksi'
    },
    // Kas
    {
        path: '/kas',
        component: KasIndex,
        name: 'indexKas'
    }, {
        path: '/create-kas',
        component: KasCreate,
        name: 'createKas'
    }, {
        path: '/edit-kas/:id',
        component: KasEdit,
        name: 'editKas'
    },
    // Suplier
    {
        path: '/suplier',
        component: SuplierIndex,
        name: 'indexSuplier'
    }, {
        path: '/create-suplier',
        component: SuplierCreate,
        name: 'createSuplier'
    }, {
        path: '/edit-suplier/:id',
        component: SuplierEdit,
        name: 'editSuplier'
    },
    // Produk
    {
        path: '/produk',
        component: ProdukIndex,
        name: 'indexProduk'
    }, {
        path: '/create-produk',
        component: ProdukCreate,
        name: 'createProduk'
    }, {
        path: '/edit-produk/:id',
        component: ProdukEdit,
        name: 'editProduk'
    }, {
        path: '/detail-produk/:id',
        component: ProdukDetail,
        name: 'detailProduk'
    },
    // ubah password user warung 
    {
        path: '/ubah-password-user-warung',
        component: UbahPasswordUserWarung,
        name: 'ubahPasswordUserWarung'
    },
    // ubah profil user warung
    {
        path: '/ubah-profil-user-warung',
        component: UbahProfilUserWarung,
        name: 'ubahProfilUserWarung'
    },
    // Kas Keluar
    {
        path: '/kas-keluar',
        component: KasKeluarIndex,
        name: 'indexKasKeluar'
    }, {
        path: '/create-kas-keluar',
        component: KasKeluarCreate,
        name: 'createKasKeluar'
    }, {
        path: '/edit-kas-keluar/:id',
        component: KasKeluarEdit,
        name: 'editKasKeluar'
    },
    // ITEM MASUK 
    {
        path: '/item-masuk',
        component: ItemMasukIndex,
        name: 'indexItemMasuk'
    }, {
        path: '/create-item-masuk',
        component: ItemMasukCreate,
        name: 'createItemMasuk'
    }, {
        path: '/edit-item-masuk/:id',
        component: ItemMasukEdit,
        name: 'editItemMasuk'
    }, {
        path: '/item-masuk-edit/:id',
        component: ItemMasukProsesEdit,
        name: 'editItemMasukProses'
    }, {
        path: '/detail-item-masuk/:id',
        component: ItemMasukDetail,
        name: 'detailItemMasuk'
    },
    // kas masuk
    {
        path: '/kas-masuk',
        component: KasMasukIndex,
        name: 'indexKasMasuk'
    }, {
        path: '/create-kas-masuk',
        component: KasMasukCreate,
        name: 'createKasMasuk'
    }, {
        path: '/edit-kas-masuk/:id',
        component: KasMasukEdit,
        name: 'editKasMasuk'
    },
    // Pesanan Warung
    {
        path: '/pesanan-warung',
        component: PesananWarungIndex,
        name: 'indexPesananWarung'
    }, {
        path: '/detail-pesanan-warung/:id',
        component: PesananWarungDetail,
        name: 'detailPesananWarung'
    },
    // pembelian
    {
        path: '/pembelian',
        component: PembelianIndex,
        name: 'indexPembelian'
    }, {
        path: '/create-pembelian',
        component: PembelianCreate,
        name: 'createPembelian'
    }, {
        path: '/edit-pembelian/:id',
        component: PembelianEdit,
        name: 'editPembelian'
    }, // ITEM KELUAR 
    {
        path: '/item-keluar',
        component: ItemKeluarIndex,
        name: 'indexItemKeluar'
    }, {
        path: '/create-item-keluar',
        component: ItemKeluarCreate,
        name: 'createItemKeluar'
    }, {
        path: '/edit-item-keluar/:id',
        component: ItemKeluarEdit,
        name: 'editItemKeluar'
    }, {
        path: '/detail-item-keluar/:id',
        component: ItemKeluarDetail,
        name: 'detailItemKeluar'
    }, {
        path: '/item-keluar-edit/:id',
        component: ItemKeluarProsesEdit,
        name: 'editItemKeluarProses'
    }
]
export default routes;