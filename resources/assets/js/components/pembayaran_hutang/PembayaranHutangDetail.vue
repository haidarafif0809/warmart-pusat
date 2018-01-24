<style scoped>
    .pencarian {
        color: red; 
        float: right;
    }    
    .table>thead>tr>th {
        border-bottom-width: 1px;
        font-size: 1em;
        font-weight: 300;
    }
    .card-stats .card-header i {
        font-size: 36px;
        line-height: 36px;
        width: 36px;
        height: 36px;
    }
</style>

<template>
    <div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                <li><router-link :to="{name: 'indexPembayaranHutang'}">Pembayaran Hutang</router-link></li>
                <li class="active">Detail Pembayaran Hutang</li>
            </ul>
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="purple">
                    <i class="material-icons">local_atm</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title"> Detail Pembayaran Hutang </h4>
                    <div class="toolbar">
                        <p> <router-link :to="{name: 'indexPembayaranHutang'}" class="btn btn-primary">Kembali</router-link></p>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class=" table-responsive ">

                                <div class="pencarian">
                                    <input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
                                </div>

                                <table class="table table-striped table-hover" v-if="seen">
                                    <thead class="text-primary">
                                        <tr>
                                              <th>Faktur Beli</th>
                                              <th> Supplier </th>
                                              <th >Jatuh Tempo</th>
                                              <th  style="text-align:right;">Hutang</th>
                                              <th  style="text-align:right;">Potongan</th>
                                              <th  style="text-align:right;">Subtotal</th>
                                              <th  style="text-align:right;">Pembayaran</th>
                                              <th style="text-align:right;">Sisa Hutang</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="detailPembayaranHutang.length"  class="data-ada">
                                        <tr v-for="detailPembayaranHutang, index in detailPembayaranHutang" >

                                            <td>{{ detailPembayaranHutang.no_faktur_pembelian }}</td>
                                            <td>{{ detailPembayaranHutang.suplier }}</td>
                                            <td >{{ detailPembayaranHutang.jatuh_tempo | tanggal }}</td>
                                            <td align="right">{{ detailPembayaranHutang.hutang | pemisahTitik }}</td>
                                            <td align="right">{{ detailPembayaranHutang.potongan | pemisahTitik }}</td>
                                            <td align="right">{{ detailPembayaranHutang.total | pemisahTitik }}</td>
                                            <td align="right">{{ detailPembayaranHutang.jumlah_bayar | pemisahTitik }}</td>
                                            <td align="right">{{ detailPembayaranHutang.sisa_hutang | pemisahTitik }}</td>

                                        </tr>
                                    </tbody>                    
                                    <tbody class="data-tidak-ada" v-else>
                                        <tr ><td colspan="8"  class="text-center">Tidak Ada Data</td></tr>
                                    </tbody>
                                </table>    

                                <vue-simple-spinner v-if="loading"></vue-simple-spinner>

                                <div align="right"><pagination :data="detailPembayaranHutangData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card card-stats">
                                <div class="card-header" data-background-color="blue">
                                    <i class="material-icons">local_atm</i>
                                </div>
                                <div class="card-content">
                                    <p class="category">Total Keseluruhan</p>
                                    <h3 class="card-title">{{ subtotal | pemisahTitik }}</h3>
                                </div>
                                <div class="card-footer">

                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

</template>


<script>
    export default {
        data: function () {
            return {
                errors: [],
                detailPembayaranHutang: [],
                detailPembayaranHutangData : {},
                url : window.location.origin+(window.location.pathname).replace("dashboard", "pembayaran-hutang"),
                pencarian: '',
                loading: true,
                seen : false,    
                subtotal : 0,         
            }
        },
        mounted() {   
            var app = this;
            app.getResults();
        },
        watch: {
    // whenever question changes, this function will run
    pencarian: function (newQuestion) {
        this.getHasilPencarian()
        this.loading = true
    }
},
filters: {
    pemisahTitik: function (value) {      
        var angka = [value];
        var numberFormat = new Intl.NumberFormat('es-ES');
        var formatted = angka.map(numberFormat.format);
        return formatted.join('; ');
    },
    tanggal: function (value) {
        return moment(String(value)).format('DD/MM/YYYY')
    }
},
methods: {
    getResults(page) {
        var app = this; 
        var id = app.$route.params.id;
        if (typeof page === 'undefined') {
            page = 1;
        }
        axios.get(app.url+'/view-detail-pembayaran-hutang/'+id+'?page='+page)
        .then(function (resp) {
            app.detailPembayaranHutang = resp.data.data;
            app.detailPembayaranHutangData = resp.data;
            app.loading = false;
            app.seen = true;

            $.each(resp.data.data, function (i, item) {
                app.subtotal += parseFloat(resp.data.data[i].jumlah_bayar) 
            });
        })
        .catch(function (resp) {
            console.log(resp);
            app.loading = false;
            app.seen = true;
            alert("Tidak Dapat Memuat Detail Pembayaran Hutang");
        });
    }, 
    getHasilPencarian(page){
        var app = this;
        var id = app.$route.params.id;
        if (typeof page === 'undefined') {
            page = 1;
        }
        axios.get(app.url+'/pencarian-detail-pembayaran-hutang/'+id+'?search='+app.pencarian+'&page='+page)
        .then(function (resp) {
            app.detailPembayaranHutang = resp.data.data;
            app.detailPembayaranHutangData = resp.data;
            app.loading = false;
            app.seen = true;
        })
        .catch(function (resp) {
            console.log(resp);
            alert("Tidak Dapat Memuat Detail Pembayaran Hutang");
        });
    }
}
}
</script>

