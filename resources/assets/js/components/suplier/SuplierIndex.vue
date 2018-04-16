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

                <!--MODAL IMPORT-->
        <div class="modal" id="modal_import" role="dialog" data-backdrop=""> 
          <div class="modal-dialog">
            <!-- Modal content--> 
            <div class="modal-content"> 
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"> <i class="material-icons">close</i></button> 
                <h4 class="modal-title"> 
                  <div class="alert-icon" style="font-weight: bold;"> 
                    Import Supplier
                  </div> 
                </h4> 
              </div>                         
              <form v-on:submit.prevent="importExcel()" class="form-horizontal">
                <div class="modal-body">
                  <div class="form-group">
                    <p style="font-weight: bold;">
                      Download <a :href="urlTemplate">Template</a> Excel Untuk Import Supplier.
                    </p>
                  </div>
                  <div class="form-group form-file-upload">
                    <input type="file" id="excel" multiple="">
                    <div class="input-group">
                      <input type="text" readonly="" class="form-control" placeholder="Browse File...">
                      <span class="input-group-btn input-group-s">
                        <button type="button" class="btn btn-just-icon btn-round btn-primary">
                          <i class="material-icons">attach_file</i>
                        </button>
                      </span>
                    </div>
                  </div>
                  <div class="form-group">                                
                    <button class="btn btn-primary" id="btnImport" type="submit"><i class="material-icons">file_upload</i> Import</button>
                  </div>

                  <p style="color: red; font-style: italic;">*Note : Kolom Yang Berwarna Wajib Diisi.</p>
                </div>
                <div class="modal-footer">  
                  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="material-icons">close</i> Batal</button> 
                </div> 
              </form>
            </div>       
          </div> 
        </div> 
        <!-- / MODAL IMPORT EXCEL --> 

                <div class="card-content">
                    <h4 class="card-title"> Supplier</h4>

                    <div class="toolbar">
                        <router-link :to="{name: 'createSuplier'}" class="btn btn-primary"><i class="material-icons">add</i>  Supplier</router-link>
                        <button id="btnImport" class="btn btn-info" data-toggle="modal" data-target="#modal_import">
                        <i class="material-icons">file_upload</i> Import Excel
                        </button></p>
                    </div>
                    <div class=" table-responsive ">
                               <div class="pencarian">
                             <input type="text" class="form-control pencarian" autocomplete="off" name="pencarian" v-model="pencarian" placeholder="Pencarian" >
                            </div>
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
            urlTemplate : window.location.origin+(window.location.pathname).replace("dashboard", "suplier/template-excel"),
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
        },
        importExcel(){
            var app = this;
            let newExcel = new FormData();
            let file = document.getElementById('excel').files[0];

            if (file != undefined) {
                newExcel.append('excel', file)
            } else {
                swal("Silakan Pilih File Dahulu.");
                return;
            }

            // ambil ekstensinya
            let ext = file.name.split('.');
            ext = ext.pop();

            if (ext != 'xlsx' && ext != 'xls') {
                swal({
                    title: 'Gagal!',
                    type: 'warning',
                    text: 'File harus berekstensi .xlsx atau .xls',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                axios.post(app.url+'/import-excel', newExcel)
                .then((resp) => {
                    if (resp.data.error.length == 0) {
                        swal({
                            title: 'Berhasil!',
                            type: 'success',
                            text: 'Berhasil mengimport ' + resp.data.jumlah_data + ' data dari excel',
                            showConfirmButton: false,
                            timer: 2000
                        });                        
                    } else {
                        swal({
                            title: 'Gagal!',
                            type: 'warning',
                            html: resp.data.error,
                        });                        
                    }
                    // $("#excel").val('');
                    $("#modal_import").hide();
                    app.getResults();
                })
                .catch((resp) => {
                    if (resp.response.data.errors != undefined) {
                        app.errors = resp.response.data.errors.excel[0];
                    } else {
                        app.errors = "Terjadi Kesalahan Pada Proses Import!";
                    }

                    swal(app.errors);
                });
            }
        },
    }
}
</script>