    <style scoped>
    .pencarian {
        color: red; 
        float: right;
        padding-bottom: 10px;
    }
    </style>

    <template>	
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                    <li class="active">Kategori Transaksi</li>
                </ul>

                <div class="card">
                    <div class="card-header card-header-icon" data-background-color="purple">
                        <i class="material-icons">local_offer</i>
                    </div>
                    <div class="card-content">
                        <h4 class="card-title"> Kategori Transaksi</h4>

                        <div class="toolbar">
                            <router-link :to="{name: 'createKategoriTransaksi'}" class="btn btn-primary" style="padding-bottom:10px" v-if="otoritas.tambah_kategori_transaksi == 1">
                                <i class="material-icons">add</i>  Kategori Transaksi
                            </router-link>

                            <button id="btnFilter" class="btn btn-info collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" v-shortkey="['f2']" @shortkey="filterPeriode()" @click="filterPeriode()">
                                <i class="material-icons">date_range</i> Filter Periode (F2)
                            </button>

                            <div class="pencarian">
                                <input v-if="seen" type="text" name="pencarian" v-model="pencarianFilter" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
                                <input v-else type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
                            </div>
                        </div>

                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <datepicker :input-class="'form-control'" placeholder="Dari Tanggal" v-model="filter.dari_tanggal" name="dari_tanggal" v-bind:id="'dari_tanggal'"></datepicker>             
                                    </div>
                                    <div class="form-group col-md-2">
                                        <datepicker :input-class="'form-control'" placeholder="Sampai Tanggal" v-model="filter.sampai_tanggal" name="sampai_tanggal" v-bind:id="'sampai_tanggal'"></datepicker>
                                    </div>

                                    <div class="col-md-2">
                                        <button class="btn btn-primary" id="btnSubmit" type="submit" style="margin: 0px 0px;" @click="submitFilterPeriode()"><i class="material-icons">search</i> Cari</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" table-responsive ">
                            <table class="table table-striped table-hover" v-if="seen">
                                <thead class="text-primary">
                                    <tr>

                                        <th>Kategori Transaksi</th>
                                        <th style="text-align: right;">Transaksi Masuk</th>
                                        <th style="text-align: right;">Transaksi Keluar</th>

                                    </tr>
                                </thead>
                                <tbody v-if="filterKategoriTransaksi.length"  class="data-ada">
                                    <tr v-for="filterKategoriTransaksi, index in filterKategoriTransaksi" >
                                        <td>{{ filterKategoriTransaksi.nama_kategori_transaksi }}</td>
                                        <td align="right">{{ filterKategoriTransaksi.transaksi_masuk | pemisahTitik }}</td>
                                        <td align="right">{{ filterKategoriTransaksi.transaksi_keluar | pemisahTitik }}</td>
                                    </tr>
                                </tbody>                    
                                <tbody class="data-tidak-ada" v-else>
                                    <tr ><td colspan="3"  class="text-center">Tidak Ada Data</td></tr>
                                </tbody>
                            </table>

                            <table class="table table-striped table-hover" v-else>
                                <thead class="text-primary">
                                    <tr>

                                        <th>Kategori Transaksi</th>
                                        <th v-if="otoritas.hapus_kategori_transaksi == 1 || otoritas.edit_kategori_transaksi == 1">Aksi</th>

                                    </tr>
                                </thead>
                                <tbody v-if="kategoriTransaksi.length"  class="data-ada">
                                    <tr v-for="kategoriTransaksi, index in kategoriTransaksi" >
                                        <td>{{ kategoriTransaksi.nama_kategori_transaksi }}</td>
                                        <td>
                                            <router-link :to="{name: 'editKategoriTransaksi', params: {id: kategoriTransaksi.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + kategoriTransaksi.id" v-if="otoritas.edit_kategori_transaksi == 1"> Edit
                                            </router-link>
                                            <a v-if="kategoriTransaksi.status_transaksi == 0 && otoritas.hapus_kategori_transaksi == 1" href="#kategori-transaksi" class="btn btn-xs btn-danger" v-bind:id="'delete-' + kategoriTransaksi.id" v-on:click="deleteEntry(kategoriTransaksi.id, index,kategoriTransaksi.nama_kategori_transaksi)">Delete
                                            </a>

                                            <a v-else-if="otoritas.hapus_kategori_transaksi == 1" href="#kategori-transaksi" class="btn btn-xs btn-danger" v-bind:id="'delete-' + kategoriTransaksi.id" v-on:click="gagalHapus(kategoriTransaksi.id, index,kategoriTransaksi.nama_kategori_transaksi)">Delete
                                            </a>
                                            
                                        </td>
                                    </tr>
                                </tbody>					
                                <tbody class="data-tidak-ada" v-else>
                                    <tr ><td colspan="2"  class="text-center">Tidak Ada Data</td></tr>
                                </tbody>
                            </table>	

                            <vue-simple-spinner v-if="loading"></vue-simple-spinner>

                            <div align="right"><pagination :data="kategoriTransaksiData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>
                            <div align="right"><pagination :data="filterKategoriTransaksiData" v-on:pagination-change-page="prosesFilterPeriode" :limit="4"></pagination></div>

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
                kategoriTransaksi: [],
                kategoriTransaksiData: {},
                otoritas: {},
                filterKategoriTransaksi: [],
                filterKategoriTransaksiData: {},
                filter: {
                    dari_tanggal: '',
                    sampai_tanggal: new Date(),
                    tipe: 0
                },
                url : window.location.origin+(window.location.pathname).replace("dashboard", "kategori-transaksi"),
                pencarian: '',
                pencarianFilter: '',
                loading: true,
                seen: false
            }
        },
        mounted() {
            var app = this;
            var awal_tanggal = new Date();
            awal_tanggal.setDate(1);
            app.filter.dari_tanggal = awal_tanggal;
            app.getResults();
        },
        watch: {
    // whenever question changes, this function will run
    pencarian: function (newQuestion) {
        this.getHasilPencarian()  
    },
    pencarianFilter: function (newQuestion) {
        this.pencarianFilterPeriode()  
    }
},
filters: {
    pemisahTitik: function (value) {
        var angka = [value];
        var numberFormat = new Intl.NumberFormat('es-ES');
        var formatted = angka.map(numberFormat.format);
        return formatted.join('; ');
    }
},
methods: {
    filterPeriode(){
        $("#btnFilter").click();
        this.getResults();
    },
    getResults(page) {
        var app = this;	
        if (typeof page === 'undefined') {
            page = 1;
        }
        axios.get(app.url+'/view?page='+page)
        .then(function (resp) {
            app.kategoriTransaksi = resp.data.data;
            app.kategoriTransaksiData = resp.data;
            app.otoritas = resp.data.otoritas.original;
            app.loading = false;
            app.seen = false;
        })
        .catch(function (resp) {
            console.log(resp);
            app.loading = false;
            app.seen = false;
            alert("Tidak Dapat Memuat Kategori Transaksi");
        });
    },
    getHasilPencarian(page){
        var app = this;
        if (typeof page === 'undefined') {
            page = 1;
        }
        axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
        .then(function (resp) {
            app.kategoriTransaksi = resp.data.data;
            app.kategoriTransaksiData = resp.data;
            app.otoritas = resp.data.otoritas.original;
            app.loading = false;
        })
        .catch(function (resp) {
            console.log(resp);
            alert("Tidak Dapat Memuat Kategori Transaksi");
        });
    },
    alert(pesan) {
        this.$swal({
            title: "Berhasil ",
            text: pesan,
            icon: "success",
        });
    },
    deleteEntry(id, index,name) {
        swal({
            title: "Konfirmasi Hapus",
            text : "Anda Yakin Ingin Menghapus "+name+" ?",
            icon : "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                var app = this;
                axios.delete(app.url+'/' + id)
                .then(function (resp) {
                    app.getResults();
                    swal("Kategori Transaksi Berhasil Dihapus!  ", {
                        icon: "success",
                    });
                })
                .catch(function (resp) {
                    app.$router.replace('/kategori-transaksi/');
                    swal("Gagal Menghapus Kategori Transaksi!", {
                        icon: "warning",
                    });
                });
            }
            this.$router.replace('/kategori-transaksi/');
        });
    },
    gagalHapus(id, index,nama_kategori_transaksi) {
        this.$swal({
            title: "Gagal ",
            text: "Kategori Transaksi '"+nama_kategori_transaksi+"' Sudah Terpakai",
            icon: "warning",
        });
    },
    submitFilterPeriode(){
        var app = this;
        app.prosesFilterPeriode();
    },
    prosesFilterPeriode(page) {
        var app = this; 
        var newFilter = app.filter;
        if (typeof page === 'undefined') {
            page = 1;
        }
        app.loading = true,
        axios.post(app.url+'/filter-periode?page='+page, newFilter)
        .then(function (resp) {
            app.filterKategoriTransaksi = resp.data.data;
            app.filterKategoriTransaksiData = resp.data;
            app.loading = false;
            app.seen = true;
            console.log(resp);
        })
        .catch(function (resp) {
            console.log(resp);
            alert("Tidak Dapat Memuat Kategori Transaksi");
        });
    },
    pencarianFilterPeriode(page){
        var app = this;
        app.filter.tipe = 1;
        var newFilter = app.filter;
        if (typeof page === 'undefined') {
            page = 1;
        }
        axios.post(app.url+'/pencarian-periode?search='+app.pencarianFilter+'&page='+page, newFilter)
        .then(function (resp) {
            console.log(resp);
            app.filterKategoriTransaksi = resp.data.data;
            app.filterKategoriTransaksiData = resp.data;
            app.loading = false;
            app.seen = true;
        })
        .catch(function (resp) {
            console.log(resp);
            alert("Tidak Dapat Memuat Kategori Transaksi");
        });
    },
}
}
</script>