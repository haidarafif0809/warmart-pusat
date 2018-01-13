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
                <li class="active">Supplier</li>
            </ul>
            
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="purple">
                    <i class="material-icons">assignment_return</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title"> Supplier</h4>

                    <div class="toolbar">
                        <router-link :to="{name: 'createSuplier'}" class="btn btn-primary"><i class="material-icons">add</i>  Supplier</router-link>

                        <div class="pencarian">
                            <input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control" autocomplete="">
                        </div>
                    </div>

                    <br>
                    <div class=" table-responsive ">
                        <table class="table table-striped table-hover ">
                            <thead class="text-primary">
                                <tr>
                                    <th>Nama</th>
                                    <th>No. Telpon</th>
                                    <th>Alamat</th>
                                    <th>Contact Person</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody v-if="suplier.length"  class="data-ada">
                                <tr v-for="suplier, index in suplier" >
                                     <td>{{ suplier.suplier.nama_suplier }}</td>
                                     <td>{{ suplier.suplier.no_telp }}</td>
                                     <td>{{ suplier.suplier.alamat }}</td>
                                     <td>{{ suplier.suplier.contact_person }}</td>
                                     <td>
                                        <router-link :to="{name: 'editSuplier', params: {id: suplier.suplier.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + suplier.suplier.id" > Edit
                                        </router-link>

                                        <a v-if="suplier.status_suplier == 0" href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + suplier.suplier.id" v-on:click="deleteEntry(suplier.suplier.id, index,suplier.suplier.nama_suplier)">Delete
                                        </a>

                                        <a v-else href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + suplier.suplier.id" v-on:click="gagalHapus(suplier.suplier.id, index,suplier.suplier.nama_suplier)">Delete
                                        </a>
                                    </td>
                                </tr>
                            </tbody>                    
                            <tbody class="data-tidak-ada" v-else>
                                <tr ><td colspan="2"  class="text-center">Tidak Ada Data</td></tr>
                            </tbody>
                    </table>    

                 <vue-simple-spinner v-if="loading"></vue-simple-spinner>

                 <div align="right"><pagination :data="suplierData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

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
            suplier: [],
            suplierData: {},
            url : window.location.origin+(window.location.pathname).replace("dashboard", "suplier"),
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

    methods: {
        getResults(page) {
            var app = this; 
            if (typeof page === 'undefined') {
                page = 1;
            }
            axios.get(app.url+'/view?page='+page)
            .then(function (resp) {
                app.suplier = resp.data.data;
                app.suplierData = resp.data;
                app.loading = false;
            })
            .catch(function (resp) {
                console.log(resp);
                app.loading = false;
                alert("Tidak Dapat Memuat Suplier");
            });
        },
        getHasilPencarian(page){
            var app = this;
            if (typeof page === 'undefined') {
                page = 1;
            }
            axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
            .then(function (resp) {
                app.suplier = resp.data.data;
                app.suplierData = resp.data;
                app.loading = false;
            })
            .catch(function (resp) {
                console.log(resp);
                alert("Tidak Dapat Memuat Suplier");
            });
        },
        alert(pesan) {
            this.$swal({
                title: "Berhasil ",
                text: pesan,
                icon: "success",
            });
        },
        deleteEntry(id, index,nama_suplier) {
            this.$swal({
                title: "Konfirmasi Hapus",
                text : "Anda Yakin Ingin Menghapus "+nama_suplier+" ?",
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
                    swal("Suplier Berhasil Dihapus!  ", {
                      icon: "success",
                    });
                  })
                  .catch(function (resp) {
                    app.$router.replace('/suplier/');
                    swal("Gagal Menghapus Suplier!", {
                      icon: "warning",
                    });
                  });
               }
               this.$router.replace('/suplier/');
            });
        },
         gagalHapus(id, index,nama_suplier) {
            this.$swal({
                title: "Gagal ",
                text: "Suplier '"+nama_suplier+"' Sudah Terpakai",
                icon: "warning",
            });
        }
    }
}
</script>