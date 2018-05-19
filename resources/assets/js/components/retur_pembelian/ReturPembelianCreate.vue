<style scoped>
    .modal {
        overflow-y:auto;
    }
    .pencarian {
        color: red; 
        float: right;
    }
    .form-penjualan{
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 3px solid #555;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 30px;
    }
    .form-subtotal{
        width: 100%;
        margin: 8px 0;
        padding: 5px;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    .card-produk{
        background-color:#82B1FF;
    }

    .card-pembayaran{
        background-color:#82B1FF;
    }
    .btn-icon{
        border-radius: 1px solid;
        padding: 10px 10px;
    }
    .hurufBesar{
        text-transform: uppercase;
    }
    .table>thead>tr>th {
        border-bottom-width: 1px;
        font-size: 1em;
        font-weight: 300;
    }
    .table>tbody>tr>td {
        border-bottom-width: 1px;
        font-size: 0.9em;
        font-weight: 300;
        color: black;
    }
</style>

<template>
    <div class="row">
        <div class="col-md-12">

          <ul class="breadcrumb"> 
            <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li> 
            <li><router-link :to="{name: 'indexReturPembelian'}">Retur Pembelian</router-link></li> 
            <li class="active">Tambah Retur Pembelian</li> 
        </ul> 

        <!-- MODEL DATA PEMBELIAN -->
        <div class="modal" id="modal_pembelian" role="dialog" data-backdrop=""> 
            <div class="modal-dialog"> 
                <!-- Modal content--> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close"  v-on:click="closeModal()" v-shortkey.push="['esc']" @shortkey="closeModal()"> &times;</button> 
                        <h4 class="modal-title"> 
                            <div class="alert-icon"> 
                                <b>Pembelian Supplier {{inputTbsRetur.supplier}}</b> 
                            </div> 
                        </h4> 
                    </div> 
                    <form class="form-horizontal" > 
                        <div class="modal-body"> 
                            <div class=" table-responsive ">
                                <div class="pencarian">
                                    <input type="text" name="pencarian" v-model="pencarianPembelian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
                                </div>

                                <table class="table table-striped table-hover" v-if="seen">
                                    <thead class="text-primary">
                                        <tr>
                                            <th >Produk</th>
                                            <th style="text-align:right;">Sisa</th>
                                            <th style="text-align:center;">Satuan</th>
                                            <th style="text-align:right;">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="pembelians.length > 0 && loading == false"  class="data-ada">
                                        <tr v-for="data, index in pembelians" @click="insertTbs(data.pembelian.id_produk, data.pembelian.nama_barang, data.stok_produk, data.pembelian.satuan_id, data.pembelian.harga_beli, data.pembelian.subtotal)">

                                            <td>{{ data.pembelian.nama_barang | capitalize }}</td>
                                            <td align="right">{{ data.stok_produk | pemisahTitik }}</td>
                                            <td align="center">{{ data.pembelian.nama_satuan | hurufBesar }}</td>
                                            <td align="right">{{ data.pembelian.harga_beli | pemisahTitik }}</td>
                                        </tr>
                                    </tbody>          
                                    <tbody class="data-tidak-ada"  v-else-if="pembelians.length == 0 && loading == false">
                                        <tr ><td colspan="6"  class="text-center">Tidak Ada Data</td></tr>
                                    </tbody>
                                </table>

                                <vue-simple-spinner v-if="loading"></vue-simple-spinner>

                                <div align="right">
                                    <pagination :data="pembelianDatas" v-on:pagination-change-page="getDataPembelian" :limit="4">
                                    </pagination>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-sm"  v-on:click="closeModal()" v-shortkey.push="['esc']" @shortkey="closeModal()"> <font style="font-size:15px;">Tutup(Esc)</font></button>
                        </div> 
                    </form>
                </div>       
            </div> 
        </div> 
        <!-- / MODAL DATA PEMBELIAN -->

        

        <!-- MODAL INSERT TBS -->
        <div class="modal" id="modalInsertTbs" role="dialog" tabindex="-1"  aria-labelledby="myModalLabel" aria-hidden="true" >
            <div class="modal-dialog modal-medium">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close"  v-on:click="closeModalInsertTbs()" v-shortkey.push="['f9']" @shortkey="closeModalInsertTbs()"> &times;</button> 
                    </div>

                    <form class="form-horizontal" v-on:submit.prevent="submitInsertTbs(inputTbsRetur.jumlah_retur, inputTbsRetur.nama_produk)"> 
                        <div class="modal-body">
                            <h3 class="text-center"><b>{{inputTbsRetur.nama_produk}}</b></h3>

                            <div class="form-group">
                                <div class="col-md-4">
                                    <input class="form-control" type="number" v-model="inputTbsRetur.jumlah_retur" placeholder="Isi Jumlah Retur" name="jumlah_retur" id="jumlah_retur" ref="jumlah_retur" autocomplete="off" step="0.01">
                                </div>
                                <div class="col-md-4">
                                    <selectize-component v-model="inputTbsRetur.satuan_produk" :settings="placeholder_satuan" id="satuan" name="satuan" ref='satuan'> 
                                        <option v-for="satuans, index in satuan" v-bind:value="satuans.satuan" class="pull-left">{{ satuans.nama_satuan }}</option>
                                    </selectize-component>
                                </div>
                                <div class="col-md-4">
                                    <money class="form-control" v-model="inputTbsRetur.harga_produk" v-bind="separator" placeholder="Isi Harga Produk" readonly name="harga_produk" id="harga_produk" ref="harga_produk" autocomplete="off" ></money>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-simple" v-on:click="closeModalInsertTbs()" v-shortkey.push="['f9']" @shortkey="closeModalInsertTbs()">Close(F9)</button>
                            <button type="submit" class="btn btn-info btn-lg">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- MODAL INSERT TBS -->


        <!-- MODAL SELESAI -->
        <div class="modal" id="modal_selesai" role="dialog" data-backdrop=""> 
            <div class="modal-dialog"> 
                <!-- Modal content--> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close"  v-on:click="closeModal()" v-shortkey.push="['esc']" @shortkey="closeModal()"> &times;</button> 
                        <h4 class="modal-title"> 
                            <div class="alert-icon"> 
                                <b>Silahkan Lengkapi Pembayaran!</b> 
                            </div> 
                        </h4> 
                    </div> 
                    <form class="form-horizontal" > 
                        <div class="modal-body"> 
                            <div class="card" style="margin-bottom:1px; margin-top:1px; margin-right:1px; margin-left:1px;">

                                <div class="row">
                                    <div class="col-md-5 col-xs-10">
                                        <div class="form-group" style="margin-right: 1px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black; margin: 8px 0">Kas(F6)</font>
                                            <div style="margin-top: 8px">
                                                <selectize-component style="margin: 8px 0px" v-model="returPembelian.kas" :settings="placeholder_kas" id="kas" ref='kas'>  
                                                    <option v-for="kass, index in kas" v-bind:value="kass.id">{{ kass.nama_kas }}</option>
                                                </selectize-component>
                                            </div>
                                            <br v-if="errors.kas">   <span v-if="errors.kas" id="kas_error" class="label label-danger">{{ errors.kas[0] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-xs-1" style="padding-left:0px">
                                        <div class="form-group">
                                            <div class="row" style="margin-top:11px">
                                                <button class="btn btn-primary btn-icon waves-effect waves-light" v-on:click="tambahModalKas()" type="button"> <i class="material-icons" >add</i> </button>
                                            </div>
                                        </div>
                                    </div>                                    

                                    <div class="col-md-3 col-xs-6">
                                        <div class="form-group" style="margin-right: 1px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px; width:130px;">
                                            <font style="color: black">Potongan(F7)</font>  
                                            <money style="text-align:right" class="form-subtotal" v-model="returPembelian.potongan_faktur" v-bind="separator" v-shortkey.focus="['f7']"></money>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-xs-6">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 1px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">(%)(F8)</font>    
                                            <money style="text-align:right" class="form-subtotal" v-model="returPembelian.potongan_faktur" v-bind="separator" v-shortkey.focus="['f8']"></money>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">Potong Hutang</font>
                                            <money style="text-align:right" readonly="" class="form-penjualan" id="potong_hutang" name="potong_hutang" placeholder="Potong Hutang"  v-model="returPembelian.potong_hutang" v-bind="separator" ></money> 
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">Total Akhir</font>
                                            <money style="text-align:right" class="form-penjualan" readonly="" id="total_akhir" name="total_akhir" placeholder="Total Akhir"  v-model="returPembelian.total_akhir" v-bind="separator" ></money> 
                                        </div>    
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">Pembayaran(F10)</font>
                                            <money style="text-align:right" class="form-penjualan" v-shortkey.focus="['f10']" id="pembayaran" name="pembayaran" placeholder="Pembayaran"  v-model="returPembelian.pembayaran" v-bind="separator"  autocomplete="off" ref="pembayaran"></money> 
                                        </div>
                                    </div>
                                </div>

                                <div align="right"  style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                    <button v-if="returPembelian.kembalian >= 0 && returPembelian.kredit == 0" type="button" class="btn btn-success btn-sm" id="btnSelesai" v-on:click="selesaiPenjualan()" v-shortkey.push="['alt', 'x']" @shortkey="selesaiPenjualan()"><font style="font-size:15px;">Tunai(Alt + X)</font></button>

                                    <button v-if="returPembelian.kredit > 0" type="button" class="btn btn-success btn-sm" id="btnSelesai" v-on:click="selesaiPenjualan()" v-shortkey.push="['alt', 'x']" @shortkey="selesaiPenjualan()"><font style="font-size:15px;">Piutang(Alt + X)</font></button>

                                    <button type="button" class="btn btn-default btn-sm"  v-on:click="closeModal()" v-shortkey.push="['esc']" @shortkey="closeModal()"> <font style="font-size:15px;">Tutup(Esc)</font></button>
                                </div>

                            </div> 
                        </div>
                        <div class="modal-footer">
                        </div> 
                    </form>
                </div>       
            </div> 
        </div> 
        <!-- / MODAL TOMBOL SELESAI --> 

        <div class="card" style="margin-bottom: 1px; margin-top: 1px;" >
            <div class="card-content">
                <h4 class="card-title" style="margin-bottom: 1px; margin-top: 1px;"> Retur Pembelian </h4>

                <div class="row" style="margin-bottom: 1px; margin-top: 1px;">
                    <div class="col-md-3">
                        <div class="card card-produk" style="margin-bottom: 1px; margin-top: 1px;">
                            <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                                <selectize-component v-model="inputTbsRetur.supplier" :settings="placeholder_supplier" id="supplier" ref='supplier'>
                                    <option v-for="suplier, index in supliers" v-bind:value="suplier.id">
                                        {{suplier.nama_suplier}}
                                    </option>
                                </selectize-component>
                            </div>
                            <span v-if="errors.supplier" id="produk_error" class="label label-danger">
                                {{ errors.supplier[0] }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-9">
                        <div class=" table-responsive ">
                            <div class="pencarian">
                                <input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
                            </div>

                            <table class="table table-striped table-hover" v-if="seen">
                                <thead class="text-primary">
                                    <tr>
                                        <th >Produk</th>
                                        <th style="text-align:right;">Jumlah Retur</th>
                                        <th style="text-align:center;">Satuan</th>
                                        <th style="text-align:right;">Harga</th>
                                        <th style="text-align:right;">Potongan</th>
                                        <th style="text-align:right;">Pajak</th>
                                        <th style="text-align:right;">Subtotal</th>
                                        <th style="text-align:right;">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody v-if="tbsReturPembelians.length > 0 && loading == false"  class="data-ada">
                                    <tr v-for="tbs_retur_pembelian, index in tbsReturPembelians" >
                                        <td>
                                            {{ tbs_retur_pembelian.data_tbs.kode_barang }} - {{ tbs_retur_pembelian.data_tbs.nama_barang | capitalize }}
                                        </td>
                                        <td align="right">{{ tbs_retur_pembelian.data_tbs.jumlah_retur | pemisahTitik }}</td>
                                        <td align="center">{{ tbs_retur_pembelian.data_tbs.nama_satuan }}</td>
                                        <td align="right">{{ tbs_retur_pembelian.data_tbs.harga_produk | pemisahTitik }}</td>
                                        <td align="right">{{ tbs_retur_pembelian.data_tbs.potongan | pemisahTitik }} | {{ Math.round(tbs_retur_pembelian.potongan_persen,2) }} %</td>
                                        <td align="right">{{ tbs_retur_pembelian.data_tbs.tax | pemisahTitik }} | {{ Math.round(tbs_retur_pembelian.tax_persen,2) }} %</td>
                                        <td align="right">{{ tbs_retur_pembelian.data_tbs.subtotal | pemisahTitik }}</td>
                                        <td style="text-align:right;">
                                            <a href="#create-retur-pembelian" class="btn btn-xs btn-danger" v-bind:id="'delete-' + tbs_retur_pembelian.data_tbs.id_tbs_retur_pembelian" v-on:click="deleteEntry(tbs_retur_pembelian.data_tbs.id_tbs_retur_pembelian, index,tbs_retur_pembelian.data_tbs.nama_barang,tbs_retur_pembelian.data_tbs.subtotal)">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>          
                                <tbody class="data-tidak-ada"  v-else-if="tbsReturPembelians.length == 0 && loading == false">
                                    <tr ><td colspan="8"  class="text-center">Tidak Ada Data</td></tr>
                                </tbody>
                            </table>

                            <vue-simple-spinner v-if="loading"></vue-simple-spinner>

                            <div align="right">
                                <pagination :data="tbsReturPembelianDatas" v-on:pagination-change-page="getTbs" :limit="4">
                                </pagination>
                            </div>
                        </div>                     
                    </div>

                    <div class="col-md-3">
                        <div class="card card-stats">
                            <div class="card-header" data-background-color="blue">
                                <i class="material-icons">shopping_cart</i>
                            </div>
                            <div class="card-content">
                                <p class="category"><font style="font-size:20px;">Subtotal</font></p>
                                <h3 class="card-title"><b><font style="font-size:32px;">
                                    {{ returPembelian.subtotal | pemisahTitik }}</font></b>
                                </h3>
                            </div>
                            <div class="card-footer">
                                <div class="row"> 
                                    <div class="col-md-6 col-xs-6"> 
                                        <button type="button" class="btn btn-success" id="bayar" v-on:click="bayarPenjualan()" v-shortkey.push="['f2']" @shortkey="bayarPenjualan()"><font style="font-size:13px;">Bayar(F2)</font></button>
                                    </div>
                                    <div class="col-md-6 col-xs-6">
                                        <button type="submit" class="btn btn-danger" id="btnBatal" v-on:click="batalPenjualan()" v-shortkey.push="['f3']" @shortkey="batalPenjualan()">
                                            <font style="font-size:13px;">Batal(F3) </font>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <span style="display: none;">

                    <input class="form-control" type="hidden"  v-model="returPembelian.supplier"  name="supplier" id="supplier" v-shortkey="['f1']" @shortkey="openSelectizeSupplier()">
                    <input class="form-control" type="hidden"  v-model="returPembelian.kas"  name="kas" id="kas" v-shortkey="['f6']" @shortkey="openSelectizeKas()">


                </span>
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
                errors: [],
                satuan: [],
                tbsReturPembelians: [],
                tbsReturPembelianDatas : {},
                pembelians: [],
                pembelianDatas : {},
                url : window.location.origin+(window.location.pathname).replace("dashboard", "retur-pembelian"),
                url_satuan : window.location.origin+(window.location.pathname).replace("dashboard", "pembelian/satuan-konversi"),        
                pencarian: '',
                pencarianPembelian: '',
                loading: true,
                seen : false,
                separator: {
                    decimal: ',',
                    thousands: '.',
                    prefix: '',
                    suffix: '',
                    precision: 0,
                    masked: false
                },
                placeholder_supplier:{
                    placeholder: 'Cari Supplier (F1) ...',
                    sortField: 'text',
                    openOnFocus : true,
                },
                placeholder_kas:{
                    placeholder: '--PILIH KAS--',
                    sortField: 'text',
                    openOnFocus : true,
                },
                placeholder_satuan: {
                    placeholder: '--PILIH SATUAN--',
                    sortField: 'text',
                    openOnFocus : true,
                },
                inputTbsRetur: {
                    supplier: '',
                    nama_produk : '',
                    id_produk : '',
                    jumlah_retur : '',
                    harga_produk : '',
                    harga_beli : '',
                    satuan_produk: '',
                    stok_produk: 0,
                },
                inputPembayaranRetur: {
                    keterangan: ''
                },
                returPembelian: {
                    kas: '',
                    supplier: '',
                    subtotal : 0,
                    potongan_faktur : 0,
                    potongan_persen : 0,
                    total_akhir : 0,
                    potong_hutang : 0,
                    pembayaran : 0,
                    keterangan: 0,                    
                }
            }
        },
        computed : mapState ({    
            supliers(){
                return this.$store.state.supplier
            },
            kas(){
                return this.$store.state.kas
            },
            default_kas: state => state.default_kas
        }),
        mounted() {
            var app = this;
            app.$store.dispatch('LOAD_SUPPLIER_LIST');
            app.$store.dispatch('LOAD_KAS_LIST')
            app.getTbs();
        },
        filters: {
            pemisahTitik: function (value) {
                var angka = [value];
                var numberFormat = new Intl.NumberFormat('es-ES');
                var formatted = angka.map(numberFormat.format);
                return formatted.join('; ');
            },
            capitalize: function (value) {
                return value.replace(/(^|\s)\S/g, l => l.toUpperCase())
            },
            hurufBesar: function (value) {
                return value.toUpperCase()
            },
        },
        watch: {
            pencarian: function (newQuestion) {
                this.getPencarianTbs()
                this.loading = true
            },
            pencarianPembelian: function (newQuestion) {
                this.getPencarianPembelian()
                this.loading = true
            },
            'inputTbsRetur.supplier': function () {
                if (this.inputTbsRetur.supplier != '') {
                    this.getDataPembelian()
                }
            },
            'inputTbsRetur.satuan_produk':function(){
                this.hitungHargaKonversi(this.inputTbsRetur.harga_beli)
            }
        },
        methods: {
            openSelectizeSupplier(){      
                this.$refs.supplier.$el.selectize.focus();
            },
            openSelectizeKas(){      
                this.$refs.kas.$el.selectize.focus();
            },
            getDataPembelian(page) {
                var app = this;
                var supplier = app.inputTbsRetur.supplier;
                if (typeof page === 'undefined') {
                    page = 1;
                }

                axios.get(app.url+'/data-pembelian?supplier='+supplier+'&page='+page)
                .then( (resp) => {
                    console.log(resp.data)
                    app.pembelians = resp.data.data
                    app.pembelianDatas = resp.data
                    app.loading = false
                    app.seen = true
                    $("#modal_pembelian").show();
                })
                .catch( (err) => {
                    console.log(err);
                    app.loading = false;
                    app.seen = true;
                    alert("Tidak Dapat Memuat Data Pembelian");
                })
            },
            getPencarianPembelian(page) {
                var app = this;
                var supplier = app.inputTbsRetur.supplier;
                if (typeof page === 'undefined') {
                    page = 1;
                }

                axios.get(app.url+'/pencarian-data-pembelian?search='+app.pencarianPembelian+'&supplier='+supplier+'&page='+page)
                .then( (resp) => {
                    console.log(resp.data)
                    app.pembelians = resp.data.data
                    app.pembelianDatas = resp.data
                    app.loading = false
                    app.seen = true
                })
                .catch( (err) => {
                    console.log(err);
                    app.loading = false;
                    app.seen = true;
                    alert("Tidak Dapat Memuat Data Pembelian");
                })
            },
            insertTbs(id_produk, nama_barang, stok_produk, satuan_id, harga_produk, subtotal) {
                var app = this;

                if (stok_produk > 0) {
                    app.getInsertTbs(id_produk,nama_barang,stok_produk,satuan_id,harga_produk,subtotal);                    
                    app.getSatuan(id_produk);
                }else{
                    app.alertGagal("Stok "+titleCase(nama_barang)+" Tidak Mencukupi.")
                }
            },
            getInsertTbs(id_produk,nama_barang,stok_produk,satuan_id,harga_produk,subtotal) {
                var app = this
                app.inputTbsRetur.nama_produk = titleCase(nama_barang)
                app.inputTbsRetur.id_produk = id_produk
                app.inputTbsRetur.harga_produk = harga_produk
                app.inputTbsRetur.harga_beli = harga_produk
                app.inputTbsRetur.satuan_produk = satuan_id

                app.closeModal();
                $("#modalInsertTbs").show();
                app.$refs.jumlah_retur.focus();
            },
            submitInsertTbs(jumlah_retur, nama_produk){
                var app = this

                if (jumlah_retur == "" || jumlah_retur == 0) {

                    app.$swal("Jumlah Retur Tidak Boleh Nol atau kosong!")
                    .then((value) => {
                        app.$refs.jumlah_retur.focus() 
                    })

                }else{
                    app.prosesInsertTbs(jumlah_retur, nama_produk)
                }

            },
            prosesInsertTbs(jumlah_retur, nama_produk) {
                var app = this;
                var newTbs = app.inputTbsRetur;

                app.loading = true;
                axios.post(app.url+'/proses-tambah-tbs-retur-pembelian', newTbs)
                .then(function (resp) {
                    $("#modalInsertTbs").hide();
                    app.alert("Menambahkan Produk "+titleCase(nama_produk));
                    app.loading = false;
                    app.getTbs();

                    if (resp.data.status == 1) {
                        var subtotal = (parseInt(app.returPembelian.subtotal) - parseInt(resp.data.subtotal_lama) + parseInt(resp.data.subtotal))
                    }else{      
                        var subtotal = parseInt(app.returPembelian.subtotal) + parseInt(resp.data.subtotal)
                    }

                    app.returPembelian.subtotal = subtotal                       
                    app.returPembelian.total_akhir  = subtotal
                    app.inputTbsRetur.id_produk = ''
                    app.inputTbsRetur.nama_produk = ''
                    app.inputTbsRetur.harga_produk = ''
                    app.inputTbsRetur.jumlah_retur = ''
                    app.inputTbsRetur.supplier = ''

                })
                .catch(function (resp) {
                    console.log(resp)
                    app.loading = false;
                    alert("Gagal Menambahkan Retur Pembelian");
                });
            },
            getTbs(page) {
                var app = this; 
                if (typeof page === 'undefined') {
                    page = 1;
                }

                axios.get(app.url+'/view-tbs?page='+page)
                .then( (resp) => {
                    console.log(resp.data)
                    app.tbsReturPembelians = resp.data.data
                    app.tbsReturPembelianDatas = resp.data
                    app.loading = false
                    app.seen = true
                    app.returPembelian.kas = app.default_kas
                    app.openSelectizeSupplier();

                    if (app.returPembelian.subtotal == 0) {          
                        app.getSubtotal(); 
                    }
                })
                .catch( (err) => {
                    console.log(err);
                    app.loading = false;
                    app.seen = true;
                    alert("Tidak Dapat Memuat Retur Pembelian");
                })
            },
            getSubtotal(){
                var app =  this;
                var jenis_tbs = 1;
                axios.get(app.url+'/subtotal-tbs')
                .then(function (resp) {
                    app.returPembelian.subtotal += resp.data.subtotal;
                    app.returPembelian.total_akhir += resp.data.subtotal;
                })
                .catch(function (resp) {
                    console.log(resp);
                });
            }, 
            getPencarianTbs(page) {
                var app = this; 
                if (typeof page === 'undefined') {
                    page = 1;
                }

                axios.get(app.url+'/pencarian-tbs?search='+app.pencarian+'&page='+page)
                .then( (resp) => {
                    console.log(resp.data)
                    app.tbsReturPembelians = resp.data.data
                    app.tbsReturPembelianDatas = resp.data
                    app.loading = false
                    app.seen = true
                    app.returPembelian.kas = app.default_kas
                })
                .catch( (err) => {
                    console.log(err);
                    app.loading = false;
                    app.seen = true;
                    alert("Tidak Dapat Memuat Retur Pembelian");
                })
            },
            getSatuan(id_produk){
                var app = this;
                var satuan_tbs = $(".satuan-"+id_produk).attr("data-satuan");

                axios.get(app.url_satuan+'/'+id_produk)
                .then(function (resp) {

                    app.satuan = resp.data;
                    if (typeof satuan_tbs == "undefined") {

                        $.each(resp.data, function (i, item) {
                            if (resp.data[i].id === resp.data[i].satuan_dasar) {
                                app.inputTbsRetur.satuan_produk = resp.data[i].satuan;
                            }
                        });

                    }else{

                        $.each(resp.data, function (i, item) {
                            if (resp.data[i].id === parseInt(satuan_tbs)) {
                                app.inputTbsRetur.satuan_produk = resp.data[i].satuan;
                            }
                        });

                    }
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Tidak Dapat Memuat Satuan Produk");
                });
            },
            deleteEntry(id, index,nama_produk,subtotal_lama) {
                var app = this;
                app.$swal({
                    text: `Anda Yakin Ingin Menghapus Produk ${titleCase(nama_produk)} ?`,
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        this.prosesDelete(id,nama_produk,subtotal_lama);
                    } else {
                        app.$swal.close();
                    }
                });
            },
            prosesDelete(id,nama_produk,subtotal_lama){
                var app = this;
                app.loading = true;
                axios.delete(app.url+'/hapus-tbs/'+id)
                .then(function (resp) {
                    app.getTbs();

                    var subtotal = parseFloat(app.returPembelian.subtotal) - parseFloat(resp.data.subtotal)
                    app.returPembelian.subtotal = subtotal                       
                    app.returPembelian.total_akhir  = subtotal
                    app.alert("Menghapus Produk "+titleCase(nama_produk));
                    app.loading = false;
                })
                .catch(function (resp) {
                    console.log(resp)
                    app.loading = false;
                    alert("Tidak Dapat Menghapus Produk "+titleCase(nama_produk));
                });
            },
            hitungHargaKonversi(harga_beli){

                var satuan = this.inputTbsRetur.satuan_produk.split("|");

                this.inputTbsRetur.harga_produk = parseFloat(harga_beli) * ( parseFloat(satuan[3]) * parseFloat(satuan[4]) );
            },
            bayarPenjualan(){
                $("#modal_selesai").show(); 
                this.$refs.pembayaran.$el.focus()
            },
            closeModal(){
                $("#modal_selesai").hide(); 
                $("#modal_pembelian").hide(); 
            },
            closeModalInsertTbs(){
                $("#modalInsertTbs").hide();
                $("#modal_pembelian").show(); 
            },
            alert(pesan) {
                this.$swal({
                    text: pesan,
                    icon: "success",
                    buttons: false,
                    timer: 1500
                })
            },
            alertGagal(pesan) {
                this.$swal({
                    text: pesan,
                    icon: "warning",
                    buttons: false,
                    timer: 1500
                })
            }
        }
    }
</script>