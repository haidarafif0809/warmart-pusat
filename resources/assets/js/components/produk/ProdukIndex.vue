    <style scoped>
    .pencarian {
        color: red; 
        float: right;
    }
    .v-step__content[data-v-fca314f4] {
        margin: 0;
    }
    </style>

    <template>  
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                    <li class="active">Produk</li>
                </ul>

                <!--MODAL PINTAS TAMBAH KAS-->
                <div class="modal" id="modal_import" role="dialog" data-backdrop=""> 
                    <div class="modal-dialog">
                        <!-- Modal content--> 
                        <div class="modal-content"> 
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"> <i class="material-icons">close</i></button> 
                                <h4 class="modal-title"> 
                                    <div class="alert-icon" style="font-weight: bold;"> 
                                        Import Produk
                                    </div> 
                                </h4> 
                            </div>                         
                            <form v-on:submit.prevent="importExcel()" class="form-horizontal">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <p style="font-weight: bold;">
                                            Download <a :href="urlTemplate">Template</a> Excel Untuk Import Produk.
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

                <div class="card">
                    <div class="card-header card-header-icon" data-background-color="purple">
                        <i class="material-icons">dns</i>
                    </div>
                    <div class="card-content">
                        <h4 class="card-title"> Produk</h4>

                        <div class="toolbar">
                            <div class="row">
                                <div class="panel panel-default">
                                    <router-link :to="urlForm" class="btn btn-primary" id="step-0"><i class="material-icons">add</i>  Produk</router-link>

                                    <!-- IMPORT EXCEL -->
                                    <button id="btnFilter" class="btn btn-info" data-toggle="modal" data-target="#modal_import">
                                        <i class="material-icons">file_upload</i> Import Excel
                                    </button>

                                    <!--DOWNLOAD EXCEL-->
                                    <a href="#" class='btn btn-warning' id="btnExcel" target='blank'><i class="material-icons">file_download</i> Download Excel</a>                                </div>
                                </div>
                            </div>

                            <br>
                            <div class=" table-responsive ">
                                <div class="pencarian">
                                    <input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control" autocomplete="">
                                </div>

                                <table class="table table-striped table-hover ">
                                    <thead class="text-primary">
                                        <tr>
                                            <th>Barcode</th>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Satuan</th>
                                            <th>Harga Beli</th>
                                            <th>Harga Jual 1</th>
                                            <th>Harga Jual 2</th>
                                            <th>Status</th>
                                            <th>Kategori</th>
                                            <th id="step-1">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="produk.length"  class="data-ada">
                                        <tr v-for="produk, index in produk" >
                                            <td>{{ produk.produk.kode_barcode }}</td>
                                            <td>{{ produk.produk.kode_barang }}</td>
                                            <td>{{ produk.nama_produk }}</td>
                                            <td>{{ produk.produk.satuan.nama_satuan}}</td>
                                            <td>{{ produk.harga_beli }}</td>
                                            <td>{{ produk.harga_jual }}</td>
                                            <td>{{ produk.harga_jual2 }}</td>
                                            <td v-if="produk.produk.status_aktif == 1">Aktif</td>
                                            <td v-else>Tidak Aktif</td>
                                            <td>{{ produk.produk.kategori_barang.nama_kategori_barang }}</td>
                                            <td>

                                                <a :href="urlLihat+produk.produk.id" class="btn btn-xs btn-info" id="lihatDeskripsi" type="button"> Deskripsi</a>

                                                <router-link :to="{name: 'editProduk', params: {id: produk.produk.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + produk.produk.id" > Edit
                                                </router-link>

                                                <a v-if="produk.status_produk == 0" href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + produk.produk.id" v-on:click="deleteEntry(produk.produk.id, index, produk.nama_produk)">Delete
                                                </a>

                                                <a v-else href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + produk.produk.id" v-on:click="gagalHapus(produk.produk.id, index, produk.prosnama_produk)">Delete
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>                    
                                    <tbody class="data-tidak-ada" v-else>
                                        <tr ><td colspan="9"  class="text-center">Tidak Ada Data</td></tr>
                                    </tbody>
                                </table>    

                                <vue-simple-spinner v-if="loading"></vue-simple-spinner>

                                <div align="right"><pagination :data="produkData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

                            </div>

                            <v-tour name="myTour" :steps="steps">
                                <template slot-scope="tour">
                                    <transition name="fade">
                                        <v-step
                                        v-if="tour.currentStep === index"
                                        v-for="(step, index) of tour.steps"
                                        :key="index"
                                        :step="step"
                                        :previous-step="tour.previousStep"
                                        :next-step="tour.nextStep"
                                        :stop="tour.stop"
                                        :isFirst="tour.isFirst"
                                        :isLast="tour.isLast"
                                        >
                                        <template v-if="tour.currentStep === 0">
                                            <div slot="actions">
                                                <router-link :to="urlForm" data-v-fca314f4="" class="v-step__button" @click="tambahProduk()">Lanjutkan</router-link>
                                            </div>
                                        </template>
                                    </v-step>
                                </transition>
                            </template>
                        </v-tour>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script>
    export default {
        data: function () {
            return {
                produk: [],
                produkData: {},
                url : window.location.origin+(window.location.pathname).replace("dashboard", "produk"),
                urlLihat : window.location.origin+(window.location.pathname).replace("dashboard", "produk/lihat-deskripsi-produk/"),
                urlForm: "create-produk",
                urlTemplate : window.location.origin+(window.location.pathname).replace("dashboard", "produk/template-excel"),
                urlDownloadExcel : window.location.origin+(window.location.pathname).replace("dashboard", "produk/export-excel"),
                pencarian: '',
                loading: true,
                steps: [
                {
                    target: '#step-0',  // We're using document.querySelector() under the hood
                    content: 'Klik Tombol <strong>Produk</strong>, <br> Anda Bisa Menambahkan Produk Baru Untuk Toko Anda!',
                    params: {
                        placement: 'right',
                    }
                }
                ]
            }
        },
        mounted() {
            var app = this;
            app.getResults();
            if (app.$route.fullPath == "/produk?tour") {
                this.$tours['myTour'].start();
                app.urlForm = 'create-produk?tour';
            }

            $("#btnExcel").attr("href", app.urlDownloadExcel);
        },
        watch: {
    // whenever question changes, this function will run
    pencarian: function (newQuestion) {
        this.getHasilPencarian()  
    }
},

methods: {
    tambahProduk(){

        this.$tours['myTour'].stop();
    },
    getResults(page) {
        var app = this; 
        if (typeof page === 'undefined') {
            page = 1;
        }
        axios.get(app.url+'/view?page='+page)
        .then(function (resp) {
            app.produk = resp.data.data;
            app.produkData = resp.data;
            app.loading = false;
        })
        .catch(function (resp) {
            app.loading = false;
            alert("Tidak Dapat Memuat Produk");
        });
    },
    getHasilPencarian(page){
        var app = this;
        if (typeof page === 'undefined') {
            page = 1;
        }
        axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
        .then(function (resp) {
            app.produk = resp.data.data;
            app.produkData = resp.data;
            app.loading = false;
        })
        .catch(function (resp) {
            alert("Tidak Dapat Memuat Produk");
        });
    },
    alert(pesan) {
        this.$swal({
            title: "Berhasil ",
            text: pesan,
            icon: "success",
        });
    },
    deleteEntry(id, index,nama_barang) {
        swal({
            title: "Konfirmasi Hapus",
            text : "Anda Yakin Ingin Menghapus "+nama_barang+" ?",
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
                    swal("Produk Berhasil Dihapus!  ", {
                        icon: "success",
                    });
                })
                .catch(function (resp) {
                    app.$router.replace('/produk/');
                    swal("Gagal Menghapus produk!", {
                        icon: "warning",
                    });
                });
            }
            this.$router.replace('/produk/');
        });
    },
    gagalHapus(id, index,nama_barang) {
        this.$swal({
            title: "Gagal ",
            text: ""+nama_barang+" Tidak Bisa Dihapus Karena Sudah Terpakai",
            icon: "warning",
        });

        this.$router.replace('/produk/');
    },
    importExcel(){
        var app = this;
        let newExcel = new FormData();
        let file = document.getElementById('excel').files[0];

        if (file != undefined) {
            newExcel.append('excel', file)
        }else{
            app.alertGagal("Silakan Pilih File Dahulu.");
            return;
        }
        app.loadingData();

        axios.post(app.url+'/import-excel', newExcel).then(function (resp){            
            console.log(resp.data);
            app.$swal.close();

            if (resp.data.pesanError !== undefined || resp.data.pesanErrorStatus !== undefined) {
                console.log(resp)
                return swal({
                    title: 'Gagal!',
                    type: 'warning',
                    html: '<div style="text-align: left; font-size: 14px;">'+ resp.data.pesanError +' <br> '+ resp.data.pesanErrorStatus+'</div>',
                });
            }
            
            $("#excel").val('');
            $("#modal_import").hide();
            app.alertImport(resp.data.jumlahProduk + ' Produk Berhasil Diimport.');
            app.getResults();
        }).catch(function (resp){
            app.$swal.close();
            if (resp.response.data.errors != undefined) {
                app.errors = resp.response.data.errors.excel[0];
            }
            else {
                app.errors = "Terjadi Kesalahan Pada Proses Import!";
            }

            app.alertGagal(app.errors);
        });
    },
    alertImport(pesan) {
        this.$swal({
            text: pesan,
            icon: "success",
            buttons: false,
            timer: 2000
        });
    },
    alertGagal(pesan) {
        this.$swal({
            text: pesan,
            icon: "warning",
            buttons: false,
            timer: 2000
        });
    },
    loadingData(){
        this.$swal({
            title: "Sedang Memproses Data ...",
            text: "Harap Tunggu!",
            icon: "info",
            buttons:  false,
            closeOnClickOutside: false,
            closeOnEsc: false,

        });
    },
}
}
</script>