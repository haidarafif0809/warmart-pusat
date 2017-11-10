<template>

    <div class="row">   
        <center>
            <img v-bind:src="url+ 'image/warmart_logo.png'"class="img-responsive" width="500" height="160"> 
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
                    <h3 class="card-title"> {{ dashboardData.rata_rata_produk }}</h3>
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
    </div>
    
</template>

<script>
export default {
    data: function () {
        return {
            url: window.location.origin + (window.location.pathname).replace("dashboard", ""),
            dashboardData: []
        }
    },
    mounted() {
        var app = this;
        app.getDashboardData();

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
                alert("Could not load Data");
            });
        }
    }
}
</script>

