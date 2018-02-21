<style scoped>
    .pencarian {
        color: red; 
        float: right;
        padding-bottom: 10px;
    }    
    .card-produk{
        background-color:#82B1FF;
    }
    .text-kanan{
        text-align:right;
    }
</style>

<template>  
    <div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                <li class="active">Stok Opname</li>
            </ul>

            <!-- small modaMODAL STOK OPNAME -->
            <div class="modal" id="modalJumlahProduk" role="dialog" tabindex="-1"  aria-labelledby="myModalLabel" aria-hidden="true" >
                <div class="modal-dialog modal-medium">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close"  v-on:click="closeModalJumlahProduk()" v-shortkey.push="['f9']" @shortkey="closeModalJumlahProduk()"> &times;</button> 
                        </div>

                        <form class="form-horizontal" v-on:submit.prevent="submitProduk(inputStokOpname.jumlah_produk)"> 
                            <div class="modal-body text-center">
                                <h3><b>{{inputStokOpname.nama_produk}}</b> </h3>
                                <input class="form-control" type="number" v-model="inputStokOpname.jumlah_produk" placeholder="Isi Jumlah Produk" name="jumlah_produk" id="jumlah_produk" ref="jumlah_produk" autocomplete="off" step="0.01">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-simple"   v-on:click="closeModalJumlahProduk()" v-shortkey.push="['f9']" @shortkey="closeModalJumlahProduk()">Close(F9)</button>
                                <button type="button" class="btn btn-info btn-lg"   v-on:click="submitProduk(inputStokOpname.jumlah_produk)">Tambah</button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
            <!--    end MODAL STOK OPNAME -->

            <div class="card">
                <div class="card-header card-header-icon" data-background-color="purple">
                    <i class="material-icons">local_offer</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title"> Stok Opname</h4>

                    <div class="col-md-3 col-xs-9">
                        <div class="card card-produk" style="margin-bottom: 1px; margin-top: 1px;">

                            <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                                <selectize-component v-model="inputStokOpname.produk" :settings="placeholder_produk" id="produk" ref='produk' >
                                    <option v-for="produks, index in produk" v-bind:value="produks.produk">{{produks.barcode}} || {{produks.kode_produk}} || {{ produks.nama_produk }}</option>
                                </selectize-component>
                                <input class="form-control" type="hidden"  v-model="inputStokOpname.produk"  name="produk" id="produk" v-shortkey="['f1']" @shortkey="openSelectizeProduk()">
                            </div>  
                        </div>
                    </div>                   

                    <br>
                    <div class="col-md-12 col-xs-12">
                        <div class=" table-responsive ">
                            <div class="pencarian">
                                <input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
                            </div>
                            <table class="table table-striped table-hover ">
                                <thead class="text-primary">
                                    <tr>

                                        <th>No. Faktur</th>
                                        <th>Produk</th>
                                        <th class="text-kanan">Stok Komputer</th>
                                        <th class="text-kanan">Stok Fisik</th>
                                        <th class="text-kanan">Selisih Fisik</th>
                                        <th class="text-kanan">Harga</th>
                                        <th class="text-kanan">Selisih Harga</th>
                                        <th style="text-align:center">Waktu</th>
                                        <th>Aksi</th>

                                    </tr>
                                </thead>
                                <tbody v-if="stokOpname.length"  class="data-ada">
                                    <tr v-for="stokOpname, index in stokOpname" >
                                        <td>{{ stokOpname.stok_opname.no_faktur }}</td>
                                        <td>{{ stokOpname.nama_produk }}</td>
                                        <td align="right">{{ stokOpname.stok_opname.stok_sekarang | pemisahTitik}}</td>
                                        <td align="right">{{ stokOpname.stok_opname.jumlah_fisik | pemisahTitik}}</td>
                                        <td align="right">{{ stokOpname.stok_opname.selisih_fisik | pemisahTitik}}</td>
                                        <td align="right">{{ stokOpname.stok_opname.harga | pemisahTitik}}</td>

                                        <td align="right" v-if="stokOpname.stok_opname.selisih_fisik < 0">
                                            (-){{ stokOpname.stok_opname.total * -1 | pemisahTitik}}
                                        </td>
                                        <td align="right" v-else>
                                            (+){{ stokOpname.stok_opname.total | pemisahTitik}}
                                        </td>

                                        <td align="center">{{ stokOpname.stok_opname.created_at | tanggal}}</td>
                                        <td>
                                            <router-link :to="{name: 'editstokOpname.stok_opname', params: {id: stokOpname.stok_opname.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + stokOpname.stok_opname.id" > Edit
                                            </router-link>

                                            <a v-if="stokOpname.stok_opname.status_transaksi == 0" href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + stokOpname.stok_opname.id" v-on:click="deleteEntry(stokOpname.stok_opname.id, index,stokOpname.stok_opname.nama_kategori_transaksi)">Delete
                                            </a>

                                            <a v-else href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + stokOpname.stok_opname.id" v-on:click="gagalHapus(stokOpname.stok_opname.id, index,stokOpname.stok_opname.nama_kategori_transaksi)">Delete
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
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    export default {
        data: function () {
            return {
                stokOpname: [],
                stokOpnameData: {},
                inputStokOpname: {
                    produk: '',
                    nama_produk: '',
                    jumlah_produk: '',
                    harga_produk: '',
                },
                placeholder_produk:{
                    placeholder: 'Cari Produk (F1) ...'
                },
                url : window.location.origin+(window.location.pathname).replace("dashboard", "stok-opname"),
                pencarian: '',
                loading: true
            }
        },
        mounted() {
            var app = this;
            app.$store.dispatch('LOAD_PRODUK_LIST')
            app.getResults();
        },
        filters: {
            pemisahTitik: function (value) {
                var angka = [value];
                var numberFormat = new Intl.NumberFormat('es-ES');
                var formatted = angka.map(numberFormat.format);
                return formatted.join('; ');
            },
            tanggal: function (value) {
                return moment(String(value)).format('DD/MM/YYYY hh:mm')
            }
        },
        watch: {
            pencarian: function (newQuestion) {
                this.getHasilPencarian()  
            },
            'inputStokOpname.produk': function(){
                this.pilihProduk()
            }
        },
        computed : mapState ({
            produk(){
                return this.$store.getters.produk_barang
            }
        }),
        methods: {
            openSelectizeProduk(){
                this.$refs.produk.$el.selectize.focus();
            },
            getResults(page) {
                var app = this; 
                if (typeof page === 'undefined') {
                    page = 1;
                }
                axios.get(app.url+'/view?page='+page)
                .then(function (resp) {
                    console.log(resp.data)
                    app.stokOpname = resp.data.data;
                    app.stokOpnameData = resp.data;
                    app.loading = false;
                    app.openSelectizeProduk();
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
            pilihProduk() {
                if (this.inputStokOpname.produk != '') {
                    var app = this;
                    var produk = app.inputStokOpname.produk.split("|");
                    var nama_produk = produk[1];

                    this.inputJumlahProduk(nama_produk);
                }
            },
            inputJumlahProduk(nama_produk){
                var app = this
                app.inputStokOpname.nama_produk = nama_produk;
                $("#modalJumlahProduk").show();
                app.$refs.jumlah_produk.focus(); 
            },
            submitProduk(value){

                if (value == '') {
                    this.$swal("Jumlah Produk Tidak Boleh Kosong!").then((value) => {
                        this.$refs.jumlah_produk.focus(); 
                    });
                }else{
                    var app = this;
                    var data_produk = app.inputStokOpname.produk.split("|");
                    var nama_produk = data_produk[1];
                    app.inputStokOpname.produk = data_produk[0];
                    app.inputStokOpname.harga_produk = data_produk[2];
                    var newInputStokOpname = app.inputStokOpname;
                    app.loading = true;
                    axios.post(app.url, newInputStokOpname)
                    .then(function (resp) {

                        app.alert("Menambahkan Produk "+nama_produk)      
                        app.getResults()
                        app.loading = false
                        app.inputStokOpname.jumlah_produk = ''
                        app.inputStokOpname.nama_produk = ''
                        app.inputStokOpname.produk = ''
                        $("#modalJumlahProduk").hide();
                    })
                    .catch(function (resp) {
                      console.log(resp);                  
                      app.loading = false;
                      app.inputStokOpname.jumlah_produk = ''
                      app.inputStokOpname.nama_produk = ''
                      app.inputStokOpname.produk = ''
                      $("#modalJumlahProduk").hide();
                      alert("Tidak Dapat Menambahkan Produk "+nama_produk);
                  });
                }
            },
            closeModalJumlahProduk(){  
                $("#modalJumlahProduk").hide();
                this.inputStokOpname.produk = '';
                this.openSelectizeProduk();
            },
            alert(pesan) {
              this.$swal({
                title: "Berhasil ",
                text: pesan,
                icon: "success",
                buttons: false,
                timer: 1000,

            });
          }
      }
  }
</script>