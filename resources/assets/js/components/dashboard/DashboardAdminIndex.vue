<template>

    <div class="row" v-if="tipeUser.content == 1">  
        <center v-if="dashboardData.setting_aplikasi == 0">
            <img v-bind:src="url+ 'image/warmart_logo.png'"class="img-responsive" width="500" height="160"> 
        </center>
         <center v-else>
             <img v-if="dashboardData.logo_toko.foto_ktp != null" v-bind:src="url_picture+'/'+dashboardData.logo_toko.foto_ktp"  class="img-responsive" width="500" height="160" /> 
             <img v-bind:src="url+'img/icon_topos.png?v=1'" class="img-responsive" width="500" height="160" v-else>
        </center>
        <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header" data-background-color="rose">
                    <i class="material-icons">person_add</i>
                </div>
                <div class="card-content">
                    <p class="category">Jumlah Customer</p>
                    <h3 class="card-title">{{ dashboardData.customer }}</h3>
                </div> 
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header" data-background-color="rose">
                    <i class="material-icons">store</i>
                </div>
                <div class="card-content">
                    <p class="category">Jumlah Warung</p>
                    <h3 class="card-title"> {{ dashboardData.warung }}</h3>
                </div> 
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header" data-background-color="rose">
                    <i class="material-icons">people</i>
                </div>
                <div class="card-content">
                    <p class="category">Jumlah Komunitas</p>
                    <h3 class="card-title">{{ dashboardData.komunitas }}</h3>
                </div> 
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats"> 
                <div class="card-content">
                    <p class="category">Warung Tervalidasi</p>
                    <h3 class="card-title"> {{ dashboardData.warung_tervalidasi }}</h3>
                </div> 
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats"> 
                <div class="card-content">
                    <p class="category">Komunitas Tervalidasi</p>
                    <h3 class="card-title"> {{ dashboardData.komunitas_tervalidasi }}</h3>
                </div> 
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats"> 
                <div class="card-content">
                    <p class="category">Jumlah Produk</p>
                    <h3 class="card-title"> {{ dashboardData.produk }}</h3>
                </div> 
            </div>
        </div>   
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats"> 
                <div class="card-content">
                    <p class="category">Rata-rata Produk Perwarung</p>
                    <h3 class="card-title"> {{ dashboardData.rata_rata_produk.toFixed(2) }}</h3>
                </div> 
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats"> 
                <div class="card-content">
                    <p class="category">Jumlah Error Log</p>
                    <h3 class="card-title">{{ dashboardData.error_log }}</h3>
                </div> 
            </div>
        </div>     
           <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats"> 
                <div class="card-content">
                    <p class="category">Jumlah Seluruh Pesanan</p>
                    <h3 class="card-title">{{ dashboardData.pesanan }}</h3>
                </div> 
            </div>
        </div>  
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats"> 
                <div class="card-content">
                    <p class="category">Jumlah Pesanan Selesai</p>
                    <h3 class="card-title">{{ dashboardData.pesanan_selesai }}</h3>
                </div> 
            </div>
        </div>
    </div>


    <div class="row" v-else-if="tipeUser.content == 4">   
        <div class="col-md-12">
            <div class="alert alert-info"  v-if="dashboardData.konfirmasi_admin == 0">
                <div class="alert-icon">
                    <i class="material-icons">info_outline</i>
                </div>
                <b>Info : Pendaftaran anda sebagai warung sedang menunggu verifikasi dari admin.</b>
            </div>        
        </div>
        
        <center v-if="dashboardData.setting_aplikasi == 0">
            <img v-bind:src="url+ 'image/warmart_logo.png'"class="img-responsive" width="500" height="160"> 
        </center>
         <center v-else>
           <img v-if="dashboardData.logo_toko_2.foto_ktp != null" v-bind:src="url_picture+'/'+dashboardData.logo_toko_2.foto_ktp"  class="img-responsive" width="500" height="160" /> 
             <img v-bind:src="url+'img/icon_topos.png?v=1'" class="img-responsive" width="500" height="160" v-else>
        </center>

        <center v-if="dashboardData.konfirmasi_admin == 1">
             <div id="tab" class="btn-group" data-toggle="buttons-radio">
                <router-link :to="{name: 'indexProduk'}"  class="btn btn-large btn-info activemenu-nav">
                    <i class="material-icons">
                        store
                    </i>
                        Produk
                </router-link>

                <router-link :to="{name: 'indexPesananWarung'}"  class="btn btn-large btn-info activemenu-nav">
                    <i class="material-icons">
                        archive
                    </i>
                        Pesanan
                </router-link>          
            </div>
        </center>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats"> 
                <div class="card-header" data-background-color="rose">
                    <i class="material-icons">local_atm</i>
                </div>
                <div class="card-content">
                    <h6 class="category">Posisi Kas</h6>
                    <h3 class="card-title">{{ dashboardData.transaksi_kas }}</h3>
                </div> 
            </div>
        </div>  
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats"> 
                <div class="card-header" data-background-color="rose">
                    <i class="material-icons">trending_down</i>
                </div>
                <div class="card-content">
                    <h6 class="category">Kas Masuk</h6>
                    <h3 class="card-title">{{ dashboardData.kas_masuk }}</h3>
                </div> 
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats"> 
                <div class="card-header" data-background-color="rose">
                    <i class="material-icons">trending_up</i>
                </div>
                <div class="card-content">
                    <h6 class="category">Kas Keluar</h6>
                    <h3 class="card-title">{{ dashboardData.kas_keluar }}</h3>
                </div> 
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats"> 
                <div class="card-header" data-background-color="rose">
                    <i class="material-icons">payment</i>
                </div>
                <div class="card-content">
                    <h6 class="category">Nilai Persedian</h6>
                    <h3 class="card-title">{{ dashboardData.total_persedian }}</h3>
                </div> 
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
        </div>
        <div class="col-lg-2 col-md-6 col-sm-6">
            <div class="card card-stats"> 
                <div class="card-header" data-background-color="rose">
                    <i class="material-icons">local_offer</i>
                </div>
                <div class="card-content">
                    <h6 class="category">Produk</h6>
                    <h3 class="card-title">{{ dashboardData.produk_warung }}</h3>
                </div> 
            </div>
        </div> 
        <div class="col-lg-2 col-md-6 col-sm-6">
            <div class="card card-stats"> 
                <div class="card-header" data-background-color="rose">
                    <i class="material-icons">system_update_alt</i>
                </div>
                <div class="card-content">
                    <h6 class="category">Item Masuk</h6>
                    <h3 class="card-title">{{ dashboardData.stok_masuk }}</h3>
                </div> 
            </div>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-6">
            <div class="card card-stats"> 
                <div class="card-header" data-background-color="rose">
                    <i class="material-icons">open_in_new</i>
                </div>
                <div class="card-content">
                    <h6 class="category">Item Keluar</h6>
                    <h3 class="card-title">{{ dashboardData.stok_keluar }}</h3>
                </div> 
            </div>
        </div>
    </div>
    
</template>

<script>
export default {
    data: function () {
        return {
            url: window.location.origin + (window.location.pathname).replace("dashboard", ""),
            url_picture : window.location.origin+(window.location.pathname).replace("dashboard", "foto_ktp_user"),
            dashboardData: [],
            tipeUser : document.head.querySelector('meta[name="tipe-user"]')
        }
    },
    mounted() {
        var app = this;
        if (app.tipeUser.content == 1) {
            app.getDashboardData();
        }
        else if(app.tipeUser.content == 4){
            app.getDashboardWarung();
        }
    },
    methods: {
        getDashboardData(){
            var app = this
            axios.get(app.url+'/dashboard-admin')
            .then(function (resp) {
                app.dashboardData = resp.data;
            })
            .catch(function (resp) {
                console.log(resp);
                alert("Tidak Bisa Memuat Data");
            });
        },
        getDashboardWarung(){
            var app = this
            axios.get(app.url+'/dashboard-warung')
            .then(function (resp) {
                app.dashboardData = resp.data;
            })
            .catch(function (resp) {
                alert("Tidak Bisa Memuat Data");
            });
        }
      
    }
}
</script>

