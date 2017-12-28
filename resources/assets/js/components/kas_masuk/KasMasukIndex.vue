<style scoped>
.pencarian {
  color: red; 
  float: right;
}
</style>

<template>  
    <div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                <li class="active">Kas Masuk</li>
            </ul>
            
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="purple">
                    <i class="material-icons">assignment_return</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title"> Kas Masuk</h4>

                    <div class="toolbar">
                        <router-link :to="{name: 'createKasMasuk'}" class="btn btn-primary"><i class="material-icons">add</i>  Kas Masuk</router-link>

                        <div class="pencarian">
                            <input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control" autocomplete="">
                        </div>
                    </div>

                    <br>
                    <div class=" table-responsive ">
                        <table class="table table-striped table-hover ">
                            <thead class="text-primary">
                                <tr>
                                    <th>No Transaksi</th>
                                    <th>Kas</th>
                                    <th>Kategori</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody v-if="kasmasuks.length > 0 && loading == false"  class="data-ada">
                                <tr v-for="kas_masuk, index in kasmasuks" >
                                    <td>{{ kas_masuk.kas_masuk.no_faktur }}</td>
                                     <td>{{ kas_masuk.kas_masuk.nama_kas }}</td>
                                     <td>{{ kas_masuk.kas_masuk.nama_kategori_transaksi }}</td>
                                     <td>{{ kas_masuk.kas_masuk.jumlah | pemisahTitik }}</td>
                                     <td>{{ kas_masuk.kas_masuk.keterangan }}</td>
                                     <td>
                                        <router-link :to="{name: 'editKasMasuk', params: {id: kas_masuk.kas_masuk.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + kas_masuk.kas_masuk.id" > Edit
                                        </router-link>

                                        <a v-if="kas_masuk.status_transaksi == 0" href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + kas_masuk.kas_masuk.id" v-on:click="deleteEntry(kas_masuk.kas_masuk.id, index,kas_masuk.kas_masuk.nama_kas)">Delete
                                        </a>
                                        <a v-if="kas_masuk.status_transaksi == 1" href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + kas_masuk.kas_masuk.id" v-on:click="gagalHapus(kas_masuk.kas_masuk.id, index,kas_masuk.kas_masuk.nama_kas)">Delete
                                        </a>
                                    </td>
                                </tr>
                            </tbody>                    
                            <tbody class="data-tidak-ada" v-else-if="kasmasuks.length == 0 && loading == false">
                                <tr ><td colspan="6"  class="text-center">Tidak Ada Data</td></tr>
                            </tbody>
                    </table>    

                 <vue-simple-spinner v-if="loading"></vue-simple-spinner>

                 <div align="right"><pagination :data="kasmasuksData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

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
            kasmasuks: [],
            kasmasuksData: {},
            url : window.location.origin+(window.location.pathname).replace("dashboard", "kas-masuk"),
            url_delete : window.location.origin+(window.location.pathname).replace("dashboard", "kas_masuk"),
            pencarian: '',
            loading: true
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
        }
    },
    filters: {
        pemisahTitik: function (value) {
            return new Intl.NumberFormat().format(value)
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
                app.kasmasuks = resp.data.data;
                app.kasmasuksData = resp.data;
                app.loading = false;
            })
            .catch(function (resp) {
                console.log(resp);
                app.loading = false;
                alert("Tidak Dapat Memuat Kas Masuk");
            });
        },
        getHasilPencarian(page){
            var app = this;
            if (typeof page === 'undefined') {
                page = 1;
            }
            axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
            .then(function (resp) {
                app.kasmasuks = resp.data.data;
                app.kasmasuksData = resp.data;
                app.loading = false;
            })
            .catch(function (resp) {
                console.log(resp);
                alert("Tidak Dapat Memuat Kas Masuk");
            });
        },
        alert(pesan) {
            this.$swal({
                title: "Berhasil ",
                text: pesan,
                icon: "success",
            });
        },
        deleteEntry(id, index,nama_kas) {

            this.$swal({
                title: "Konfirmasi Hapus",
                text : "Anda Yakin Ingin Menghapus "+nama_kas+" ?",
                icon : "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                  var app = this;
                  axios.delete(app.url_delete+'/' + id)
                  .then(function (resp) {
                    app.getResults();
                    swal("Kas Masuk Berhasil Dihapus!  ", {
                      icon: "success",
                    });
                  })
                  .catch(function (resp) {
                    app.$router.replace('/kas-masuk/');
                    swal("Gagal Menghapus Kas Masuk!", {
                      icon: "warning",
                    });
                  });
               }
               this.$router.replace('/kas-masuk/');
            });
        },
         gagalHapus(id, index,nama_kas) {
            this.$swal({
                title: "Warning",
                text: "Kas  '"+nama_kas+"' akan Minus , jika terjadi penghapusan",
                icon: "warning",
            });
        }
    }
}
</script>