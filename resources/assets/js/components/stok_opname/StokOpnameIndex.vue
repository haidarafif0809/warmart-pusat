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
                <li class="active">Stok Opname</li>
            </ul>

            <div class="card">
                <div class="card-header card-header-icon" data-background-color="purple">
                    <i class="material-icons">local_offer</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title"> Stok Opname</h4>

                    <div class="toolbar">
                        <router-link :to="{name: 'createStokOpname'}" class="btn btn-primary" style="padding-bottom:10px"><i class="material-icons">add</i>  Stok Opname</router-link>

                        <div class="pencarian">
                            <input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
                        </div>
                    </div>

                    <br>
                    <div class=" table-responsive ">
                        <table class="table table-striped table-hover ">
                            <thead class="text-primary">
                                <tr>

                                    <th>No. Faktur</th>
                                    <th>Produk</th>
                                    <th>Stok Komputer</th>
                                    <th>Stok Fisik</th>
                                    <th>Selisih Fisik</th>
                                    <th>Selisih Harga</th>
                                    <th>Petugas</th>
                                    <th>Keterangan</th>
                                    <th>Waktu</th>
                                    <th>Aksi</th>

                                </tr>
                            </thead>
                            <tbody v-if="stokOpname.length"  class="data-ada">
                                <tr v-for="stokOpname, index in stokOpname" >
                                    <td>{{ stokOpname.no_faktur }}</td>
                                    <td>{{ stokOpname.no_faktur }}</td>
                                    <td>{{ stokOpname.no_faktur }}</td>
                                    <td>{{ stokOpname.no_faktur }}</td>
                                    <td>{{ stokOpname.no_faktur }}</td>
                                    <td>{{ stokOpname.no_faktur }}</td>
                                    <td>{{ stokOpname.no_faktur }}</td>
                                    <td>{{ stokOpname.no_faktur }}</td>
                                    <td>{{ stokOpname.no_faktur }}</td>
                                    <td>
                                        <router-link :to="{name: 'editstokOpname', params: {id: stokOpname.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + stokOpname.id" > Edit
                                        </router-link>

                                        <a v-if="stokOpname.status_transaksi == 0" href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + stokOpname.id" v-on:click="deleteEntry(stokOpname.id, index,stokOpname.nama_kategori_transaksi)">Delete
                                        </a>

                                        <a v-else href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + stokOpname.id" v-on:click="gagalHapus(stokOpname.id, index,stokOpname.nama_kategori_transaksi)">Delete
                                        </a>
                                    </td>
                                </tr>
                            </tbody>					
                            <tbody class="data-tidak-ada" v-else>
                                <tr ><td colspan="10"  class="text-center">Tidak Ada Data</td></tr>
                            </tbody>
                        </table>	

                        <vue-simple-spinner v-if="loading"></vue-simple-spinner>

                        <div align="right"><pagination :data="stokOpnameData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

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
                stokOpname: [],
                stokOpnameData: {},
                url : window.location.origin+(window.location.pathname).replace("dashboard", "stok-opname"),
                pencarian: '',
                loading: true
            }
        },
        mounted() {
            var app = this;
            app.getResults();
        },
        watch: {
            pencarian: function (newQuestion) {
                this.getHasilPencarian()  
            }
        },
        methods: {
            getResults(page) {
                var app = this; 
                if (typeof page === 'undefined') {
                    page = 1;
                }
                axios.get(app.url+'/view?page='+page)
                .then(function (resp) {
                    app.stokOpname = resp.data.data;
                    app.stokOpnameData = resp.data;
                    app.loading = false;
                })
                .catch(function (resp) {
                    console.log(resp);
                    app.loading = false;
                    alert("Tidak Dapat Memuat Stok Opname");
                });
            },
            getHasilPencarian(page){
                var app = this;
                if (typeof page === 'undefined') {
                    page = 1;
                }
                axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
                .then(function (resp) {
                    app.stokOpname = resp.data.data;
                    app.stokOpnameData = resp.data;
                    app.loading = false;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Tidak Dapat Memuat Stok Opname");
                });
            },
        }
    }
</script>