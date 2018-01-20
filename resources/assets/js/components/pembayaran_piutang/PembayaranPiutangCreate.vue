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
            <ul class="breadcrumb" style="margin-bottom: 1px; margin-top: 1px;">
                <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                <li><router-link :to="{name: 'indexPembayaranPiutang'}">Pembayaran Piutang</router-link></li>
                <li class="active">Tambah Pembayaran Piutang</li>

            </ul>

            <!-- MODAL TBS --> 
            <div class="modal" id="modal_tbs" role="dialog" data-backdrop=""> 
                <div class="modal-dialog"> 
                    <!-- Modal content--> 
                    <div class="modal-content"> 
                        <div class="modal-header"> 
                            <button type="button" class="close"  v-on:click="closeModal()" v-shortkey.push="['esc']" @shortkey="closeModal()"> &times;</button> 
                            <h4 class="modal-title"> 
                                <div class="alert-icon"> 
                                    <b>Pembayaran Piutang <span id="faktur_piutang"></span></b> 
                                </div> 
                            </h4> 
                        </div> 
                        <form class="form-horizontal" v-on:submit.prevent="tambahTbsPembayaranPiutang()"> 
                            <div class="modal-body"> 
                                <div class="card" style="margin-bottom:1px; margin-top:1px; margin-right:1px; margin-left:1px;">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                                <font style="color: black">Total Piutang</font>
                                                <money style="text-align:right; font-size: 30px;" readonly="" class="form-control" id="piutang" name="piutang" placeholder="Kredit"  v-model="inputTbsPembayaranPiutang.piutang" v-bind="separator" ></money> 
                                            </div> 
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                                <font style="color: black">Potongan</font>
                                                <money style="text-align:right; font-size: 30px;" class="form-control" id="potongan" name="potongan" autocomplete="off" placeholder="Kredit"  v-model="inputTbsPembayaranPiutang.potongan" v-bind="separator" ref="potongan"></money> 
                                            </div>                                        
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                                <font style="color: black">Pembayaran(F9)</font>
                                                <money style="text-align:right" class="form-penjualan" v-shortkey.focus="['f9']" id="jumlah_bayar" name="jumlah_bayar" v-model="inputTbsPembayaranPiutang.jumlah_bayar" v-bind="separator"  autocomplete="off" ref="jumlah_bayar"></money> 
                                            </div>
                                        </div>
                                    </div>

                                    <div align="right"  style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                        <button type="submit" class="btn btn-success btn-lg" id="btnTbs"><font style="font-size:20px;">Tambah</font></button>

                                        <button type="button" class="btn btn-default btn-lg"  v-on:click="closeModal()" v-shortkey.push="['esc']" @shortkey="closeModal()"> <font style="font-size:20px;">Tutup(Esc)</font></button>
                                    </div>

                                </div> 
                            </div>
                            <div class="modal-footer">

                            </div> 
                        </form>
                    </div>       
                </div> 
            </div> 
            <!-- / MODAL TBS --> 

            <!-- MODAL EDIT TBS --> 
            <div class="modal" id="modal_edit_tbs" role="dialog" data-backdrop=""> 
                <div class="modal-dialog"> 
                    <!-- Modal content--> 
                    <div class="modal-content"> 
                        <div class="modal-header"> 
                            <button type="button" class="close"  v-on:click="closeModal()" v-shortkey.push="['esc']" @shortkey="closeModal()"> &times;</button> 
                            <h4 class="modal-title"> 
                                <div class="alert-icon"> 
                                    <b>Edit Faktur Piutang : <span id="faktur_piutang_edit"></span></b> 
                                </div> 
                            </h4> 
                        </div> 
                        <form class="form-horizontal" v-on:submit.prevent="editTbsPembayaranPiutang(inputEditTbsPembayaranPiutang.jumlah_bayar_lama)"> 
                            <div class="modal-body"> 
                                <div class="card" style="margin-bottom:1px; margin-top:1px; margin-right:1px; margin-left:1px;">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                                <font style="color: black">Total Piutang</font>
                                                <money style="text-align:right; font-size: 30px;" readonly="" class="form-control" id="piutang" name="piutang" placeholder="Kredit"  v-model="inputEditTbsPembayaranPiutang.piutang" v-bind="separator" ></money> 
                                            </div> 
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                                <font style="color: black">Potongan</font>
                                                <money style="text-align:right; font-size: 30px;" class="form-control" id="potongan" name="potongan" autocomplete="off" placeholder="Kredit"  v-model="inputEditTbsPembayaranPiutang.potongan" v-bind="separator" ref="potongan_edit"></money> 
                                            </div>                                        
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                                <font style="color: black">Pembayaran(F10)</font>
                                                <money style="text-align:right" class="form-penjualan" v-shortkey.focus="['f10']" id="jumlah_bayar_edit" name="jumlah_bayar_edit" v-model="inputEditTbsPembayaranPiutang.jumlah_bayar" v-bind="separator"  autocomplete="off" ref="jumlah_bayar_edit"></money> 
                                            </div>
                                        </div>
                                    </div>

                                    <div align="right"  style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                        <button type="submit" class="btn btn-success btn-lg" id="btnTbs"><font style="font-size:20px;">Tambah</font></button>

                                        <button type="button" class="btn btn-default btn-lg"  v-on:click="closeModal()" v-shortkey.push="['esc']" @shortkey="closeModal()"> <font style="font-size:20px;">Tutup(Esc)</font></button>
                                    </div>

                                </div> 
                            </div>
                            <div class="modal-footer">

                            </div> 
                        </form>
                    </div>       
                </div> 
            </div> 
            <!-- / MODAL EDIT TBS --> 

            <!-- / MODAL SELESAI --> 
            <div class="modal" id="modal_selesai" role="dialog" data-backdrop="">
                <div class="modal-dialog"> 
                    <!-- Modal content--> 
                    <div class="modal-content"> 
                        <div class="modal-header"> 
                            <button type="button" class="close"  v-on:click="closeModal()" v-shortkey.push="['esc']" @shortkey="closeModal()"> &times;
                            </button> 
                            <h4 class="modal-title"> 
                                <div class="alert-icon"> 
                                    <b>Silahkan Lengkapi Pembayaran!</b> 
                                </div> 
                            </h4>
                        </div> 

                        <form class="form-horizontal"  v-on:submit.prevent="selesaiPembayaranPiutang()"> 
                            <div class="modal-body"> 
                                <div class="card" style="margin-bottom:1px; margin-top:1px; margin-right:1px; margin-left:1px;">

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">                                            
                                            <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                                <font style="color: black">Tanggal</font> 
                                                <datepicker :input-class="'form-control'" placeholder="Jatuh Tempo" v-model="pembayaranPiutang.tanggal" ref='tanggal'></datepicker>
                                                <br v-if="errors.tanggal">  <span v-if="errors.tanggal" id="tanggal_error" class="label label-danger">{{ errors.tanggal[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-5 col-xs-10">
                                            <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                                                <font style="color: black">Kas(F4)</font><br>
                                                <selectize-component v-model="pembayaranPiutang.kas" :settings="placeholder_kas" id="kas" ref='kas'> 
                                                    <option v-for="kass, index in kas" v-bind:value="kass.id">{{ kass.nama_kas }}</option>
                                                </selectize-component>                                                
                                                <input class="form-control" type="hidden"  v-model="pembayaranPiutang.kas"  name="id_tbs" id="id_tbs"  v-shortkey="['f4']" @shortkey="openSelectizeKas()">
                                                <br v-if="errors.kas">
                                                <span v-if="errors.kas" id="kas_error" class="label label-danger">{{ errors.kas[0] }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-1 col-xs-1" style="padding-left:0px">
                                            <div class="form-group">
                                                <div class="row" style="margin-top:11px">
                                                    <button class="btn btn-primary btn-icon waves-effect waves-light" v-on:click="tambahModalKas()" type="button"> <i class="material-icons" >add</i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                                                <font style="color: black">Keterangan</font><br>
                                                <textarea v-model="pembayaranPiutang.keterangan" class="form-control" placeholder="Keterangan.." id="keterangan" name="keterangan" ref="keterangan"></textarea>
                                            </div>
                                        </div>

                                        <div align="right"  style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <button type="submit" class="btn btn-success btn-lg" id="btnTbs"><font style="font-size:20px;">Selesai</font></button>

                                            <button type="button" class="btn btn-default btn-lg"  v-on:click="closeModal()" v-shortkey.push="['esc']" @shortkey="closeModal()"> <font style="font-size:20px;">Tutup(Esc)</font></button>
                                        </div>
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

            <div class="card" style="margin-bottom: 1px; margin-top: 1px;">
                <div class="card-content">

                    <h4 class="card-title" style="margin-bottom: 1px; margin-top: 1px;"> Pembayaran Piutang </h4>

                    <div class="row" style="margin-bottom: 1px; margin-top: 1px;">

                        <div class="col-md-3 col-xs-9">
                            <div class="card card-produk" style="margin-bottom: 1px; margin-top: 1px;">

                                <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                                    <selectize-component v-model="inputTbsPembayaranPiutang.penjualan_piutang" :settings="placeholder_piutang" id="penjualan_piutang" ref='penjualan_piutang'> 
                                        <option v-for="piutangs, index in penjualan_piutang" v-bind:value="piutangs.id">{{piutangs.no_faktur_penjualan}} || {{ piutangs.nama_pelanggan }}</option>
                                    </selectize-component>
                                    <input class="form-control" type="hidden"  v-model="inputTbsPembayaranPiutang.id_tbs"  name="id_tbs" id="id_tbs"  v-shortkey="['f1']" @shortkey="openSelectizeFakturPiutang()">
                                </div>
                            </div>
                        </div>
                    </div>


                    <!--TABEL TBS ITEM  MASUK -->
                    <div class="row">

                        <div class="col-md-9">
                            <div class=" table-responsive ">
                                <div class="pencarian">
                                    <input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
                                </div>
                                <table class="table table-striped table-hover" v-if="seen">
                                    <thead class="text-primary">
                                        <tr>

                                            <th>No. Faktur</th>
                                            <th> Pelanggan </th>
                                            <th class="text-center">Tanggal JT</th>
                                            <th class="text-right">Piutang</th>
                                            <th class="text-right">Potongan</th>
                                            <th class="text-right">Subtotal</th>
                                            <th class="text-right">Pembayaran</th>
                                            <th class="text-right">Sisa</th>
                                            <th class="text-center">Edit</th>
                                            <th class="text-center">Hapus</th>

                                        </tr>
                                    </thead>
                                    <tbody v-if="tbs_pembayaran_piutang.length"  class="data-ada">
                                        <tr v-for="tbs_pembayaran_piutang, index in tbs_pembayaran_piutang" >

                                            <td>{{ tbs_pembayaran_piutang.no_faktur_penjualan }}</td>
                                            <td>{{ tbs_pembayaran_piutang.pelanggan }}</td>
                                            <td align="center">{{ tbs_pembayaran_piutang.jatuh_tempo | tanggal }}</td>
                                            <td align="right">{{ tbs_pembayaran_piutang.piutang | pemisahTitik }}</td>
                                            <td align="right">{{ tbs_pembayaran_piutang.potongan | pemisahTitik }}</td>
                                            <td align="right">{{ tbs_pembayaran_piutang.total | pemisahTitik }}</td>
                                            <td align="right">{{ tbs_pembayaran_piutang.jumlah_bayar | pemisahTitik }}</td>
                                            <td align="right">{{ tbs_pembayaran_piutang.sisa_piutang | pemisahTitik }}</td>

                                            <td align="center">
                                                <a href="#create-pembayaran-piutang" class="btn btn-xs btn-success" v-bind:id="'edit-' + tbs_pembayaran_piutang.id_tbs_pembayaran_piutang" v-on:click="editEntry(tbs_pembayaran_piutang.id_tbs_pembayaran_piutang, index, tbs_pembayaran_piutang.no_faktur_penjualan,tbs_pembayaran_piutang.pelanggan,tbs_pembayaran_piutang.jumlah_bayar,tbs_pembayaran_piutang.potongan,tbs_pembayaran_piutang.piutang)">Edit</a>
                                            </td>

                                            <td align="center">
                                                <a href="#create-pembayaran-piutang" class="btn btn-xs btn-danger" v-bind:id="'delete-' + tbs_pembayaran_piutang.id_tbs_pembayaran_piutang" v-on:click="deleteEntry(tbs_pembayaran_piutang.id_tbs_pembayaran_piutang, index,tbs_pembayaran_piutang.jumlah_bayar,tbs_pembayaran_piutang.no_faktur_penjualan)">Delete</a>
                                            </td>

                                        </tr>
                                    </tbody>                    
                                    <tbody class="data-tidak-ada" v-else>
                                        <tr ><td colspan="10"  class="text-center">Tidak Ada Data</td></tr>
                                    </tbody>
                                </table>    

                                <vue-simple-spinner v-if="loading"></vue-simple-spinner>

                                <div align="right"><pagination :data="tbsPembayaranPiutangData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

                            </div>
                        </div>
                        <div class="col-md-3">

                            <div class="card card-stats">
                                <div class="card-header" data-background-color="blue">
                                    <i class="material-icons">local_atm</i>
                                </div>
                                <div class="card-content">
                                    <p class="category"><font style="font-size:20px;">Subtotal</font></p>
                                    <h3 class="card-title"><b><font style="font-size:32px;">{{ pembayaranPiutang.subtotal | pemisahTitik }}</font></b></h3>
                                </div>
                                <div class="card-footer">
                                    <div class="row"> 
                                        <div class="col-md-5 col-xs-5"> 
                                            <button type="button" class="btn btn-success" id="bayar" v-on:click="bayarPembayaranPiutang()" v-shortkey.push="['f2']" @shortkey="bayarPembayaranPiutang()" style="padding: 10px 15px;">Bayar(F2)</button>
                                        </div>
                                        <div class="col-md-5 col-xs-5">
                                            <button type="submit" class="btn btn-danger" id="btnBatal" v-on:click="batalPembayaranPiutang()" v-shortkey.push="['f3']" @shortkey="batalPembayaranPiutang()" style="padding: 10px 15px;"> Batal(F3)</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p style="color: red; font-style: italic;">*Note : Klik Kolom Jumlah, Harga, & Potongan Untuk Mengubah Nilai.</p>      


                </div><!-- / PANEL BODY -->

            </div>
        </div>
    </div>

</template>

<script>
    export default {
        data: function () {
            return {
                errors: [],
                kas: [],
                penjualan_piutang: [],
                pelanggan: [],
                kas: [],
                tbs_pembayaran_piutang: [],
                tbsPembayaranPiutangData : {},
                url : window.location.origin+(window.location.pathname).replace("dashboard", "penjualan"),
                url_piutang : window.location.origin+(window.location.pathname).replace("dashboard", "pembayaran-piutang"),
                url_tambah_kas : window.location.origin+(window.location.pathname).replace("dashboard", "kas"),

                inputTbsPembayaranPiutang: {
                    penjualan_piutang: '',
                    id_tbs: '',
                    no_faktur_penjualan : '',
                    pelanggan_id : '',
                    piutang : '',
                    potongan : '',
                    potongan_tbs : '',
                    jumlah_bayar : '',
                    jatuh_tempo : '',
                },
                inputEditTbsPembayaranPiutang: {
                    id_tbs: '',
                    piutang : '',
                    potongan : '',
                    jumlah_bayar : '',
                    jumlah_bayar_lama : '',
                },
                pembayaranPiutang: {
                    kas: '',
                    tanggal: new Date,
                    keterangan: '',
                    subtotal: 0,
                }, 
                placeholder_piutang: {
                    placeholder: 'Cari Piutang (F1) ...',
                    sortField: 'text',
                    openOnFocus : true
                },
                placeholder_kas: {
                    placeholder: '--PILIH KAS--',
                    sortField: 'text',
                    openOnFocus : true
                },
                pencarian: '',
                loading: true,
                seen : false,
                separator: {
                    decimal: ',',
                    thousands: '.',
                    prefix: '',
                    suffix: '',
                    precision: 2,
                    masked: false /* doesn't work with directive */
                }

            }
        },
        mounted() {   
            var app = this;
            app.dataPiutang();
            app.dataKas(); 
            app.getResults();
        },
        watch: {
// whenever question changes, this function will run
pencarian: function (newQuestion) {
    this.getHasilPencarian()
    this.loading = true
},
'inputTbsPembayaranPiutang.penjualan_piutang': function () {
    this.pilihFakturPiutang()
},
'inputTbsPembayaranPiutang.potongan':function(){
    this.potonganTbs()
},
'inputTbsPembayaranPiutang.jumlah_bayar':function(){
    this.jumlahBayarPiutang()
},
'inputEditTbsPembayaranPiutang.potongan':function(){
    this.potonganEditTbs()
},
'inputEditTbsPembayaranPiutang.jumlah_bayar':function(){
    this.editJumlahBayarPiutang()
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
    openSelectizeFakturPiutang(){      
        this.$refs.penjualan_piutang.$el.selectize.focus();
    },   
    openSelectizeKas(){      
        this.$refs.kas.$el.selectize.focus();
    },   
    dataKas() {
        var app = this;
        axios.get(app.url_piutang+'/pilih-kas').then(function (resp) {
            app.kas = resp.data;   

            $.each(resp.data, function (i, item) {
                if (resp.data[i].default_kas == 1) {
                    app.pembayaranPiutang.kas = resp.data[i].id 
                }

            });

        })
        .catch(function (resp) {

            console.log(resp);
            alert("Tidak Bisa Memuat Kas");
        });
    },
    getResults(page) {
        var app = this; 
        if (typeof page === 'undefined') {
            page = 1;
        }
        axios.get(app.url_piutang+'/view-tbs-pembayaran-piutang?page='+page)
        .then(function (resp) {
            app.tbs_pembayaran_piutang = resp.data.data;
            app.tbsPembayaranPiutangData = resp.data;
            app.loading = false;
            app.seen = true;
            app.openSelectizeFakturPiutang();

            if (app.pembayaranPiutang.subtotal == 0) {
                $.each(resp.data.data, function (i,item) {
                    app.pembayaranPiutang.subtotal += parseFloat(resp.data.data[i].jumlah_bayar)
                }); 
            }
        })
        .catch(function (resp) {
            app.loading = false;
            app.seen = true;
            alert("Tidak Dapat Memuat Faktur Penjualan Piutang");
        });
    }, 
    getHasilPencarian(page){
        var app = this;
        if (typeof page === 'undefined') {
            page = 1;
        }
        axios.get(app.url_piutang+'/pencarian-tbs-pembayaran-piutang?search='+app.pencarian+'&page='+page)
        .then(function (resp) {
            console.log(resp);
            app.tbs_pembayaran_piutang = resp.data.data;
            app.tbsPembayaranPiutangData = resp.data;
            app.loading = false;
            app.seen = true;
        })
        .catch(function (resp) {
            console.log(resp);
            alert("Tidak Dapat Memuat Faktur Penjualan Piutang");
        });
    },    
    dataPiutang() {
        var app = this;
        axios.get(app.url_piutang+'/pilih-penjualan-piutang').then(function (resp) {
            app.penjualan_piutang = resp.data;
        })
        .catch(function (resp) {
            alert("Tidak Bisa Memuat Penjualan Piutang");
        });
    },
    getDataFakturPiutang(id) {
        var app = this;
        axios.get(app.url_piutang+'/data-penjualan-piutang/'+id).then(function (resp) {
            app.inputTbsPembayaranPiutang.piutang = resp.data.kredit;
            app.inputTbsPembayaranPiutang.jumlah_bayar = resp.data.kredit;
            app.inputTbsPembayaranPiutang.no_faktur_penjualan = resp.data.no_faktur;
            app.inputTbsPembayaranPiutang.pelanggan_id = resp.data.pelanggan_id;
            app.inputTbsPembayaranPiutang.jatuh_tempo = resp.data.tanggal_jt_tempo;
        })
        .catch(function (resp) {
            console.log(resp);
            alert("Tidak Bisa Memuat Penjualan Piutang");
        });
    },
    pilihFakturPiutang() {
        var app = this;
        var id = app.inputTbsPembayaranPiutang.penjualan_piutang;

        if (app.inputTbsPembayaranPiutang.penjualan_piutang != '') {
            app.getDataFakturPiutang(id);
            $("#modal_tbs").show();      
            app.$refs.jumlah_bayar.$el.focus(); 
        }
    },
    tambahTbsPembayaranPiutang(){
        var app = this;
        var newTbsPembayaranPiutang = app.inputTbsPembayaranPiutang;
        app.loading = true;

        if (app.inputTbsPembayaranPiutang.jumlah_bayar < 0) {
            app.alertTbs("Potongan Anda Melebihi Total Piutang");
            app.loading = false;
            app.inputTbsPembayaranPiutang.potongan = 0;
            app.$refs.potongan.$el.focus();
        }else{

            axios.post(app.url_piutang+'/proses-tambah-tbs-pembayaran-piutang',newTbsPembayaranPiutang)
            .then(function (resp) {

                if (resp.data != 0) {
                    var subtotal = parseFloat(app.pembayaranPiutang.subtotal) + parseFloat(resp.data.jumlah_bayar)

                    app.getResults();
                    app.pembayaranPiutang.subtotal = subtotal.toFixed(2)
                    app.alert("Berhasil Menambahkan Faktur Piutang"+ app.inputTbsPembayaranPiutang.no_faktur_penjualan);
                    app.inputTbsPembayaranPiutang.penjualan_piutang = '';
                    app.inputTbsPembayaranPiutang.piutang = 0
                    app.inputTbsPembayaranPiutang.jumlah_bayar = 0
                    app.inputTbsPembayaranPiutang.potongan = 0
                    app.inputTbsPembayaranPiutang.no_faktur_penjualan = ''
                    app.inputTbsPembayaranPiutang.pelanggan_id = ''
                    app.inputTbsPembayaranPiutang.jatuh_tempo = ''

                    $("#modal_tbs").hide();
                    app.loading = false;
                }else{
                    $("#modal_tbs").hide();
                    app.alertTbs("Faktur "+app.inputTbsPembayaranPiutang.no_faktur_penjualan+" Sudah Ada, Silakan Pilih Faktur Piutang Lain!");
                    app.inputTbsPembayaranPiutang.penjualan_piutang = ''
                    app.getResults();
                    app.loading = false;
                }

            })
            .catch(function (resp) { 

                console.log(resp);              
                app.loading = false;
                alert("Tidak dapat Menambahkan Faktur Penjualan Piutang");        
                app.errors = resp.response.data.errors;
            });

        }
    },
    potonganTbs(){
        var potonganTbs = this.inputTbsPembayaranPiutang.potongan

        if (potonganTbs == '') {
            potonganTbs = 0
        }
        var jumlah_bayar = parseFloat(this.inputTbsPembayaranPiutang.piutang) - parseFloat(potonganTbs)
        this.inputTbsPembayaranPiutang.jumlah_bayar = jumlah_bayar.toFixed(2)
    },
    jumlahBayarPiutang(){
        var app = this;
        var jumlah_bayar = app.inputTbsPembayaranPiutang.jumlah_bayar

        if (jumlah_bayar == '') {
            jumlah_bayar = 0
        }
        var piutang_setelah_diskon = parseFloat(app.inputTbsPembayaranPiutang.piutang) - parseFloat(app.inputTbsPembayaranPiutang.potongan)
        var sisa_piutang = parseFloat(piutang_setelah_diskon) - parseFloat(jumlah_bayar);

        if (sisa_piutang < 0) {
            app.alertTbs("Jumlah Bayar Anda Melebihi Total Piutang !")
            app.inputTbsPembayaranPiutang.jumlah_bayar = 0;
            app.$refs.jumlah_bayar.$el.focus(); 
        }
    },
    potonganEditTbs(){
        var potonganTbs = this.inputEditTbsPembayaranPiutang.potongan

        if (potonganTbs == '') {
            potonganTbs = 0
        }
        var jumlah_bayar = parseFloat(this.inputEditTbsPembayaranPiutang.piutang) - parseFloat(potonganTbs)
        this.inputEditTbsPembayaranPiutang.jumlah_bayar = jumlah_bayar.toFixed(2)
    },
    editJumlahBayarPiutang(){
        var app = this;
        var jumlah_bayar = app.inputEditTbsPembayaranPiutang.jumlah_bayar

        if (jumlah_bayar == '') {
            jumlah_bayar = 0
        }
        var piutang_setelah_diskon = parseFloat(app.inputEditTbsPembayaranPiutang.piutang) - parseFloat(app.inputEditTbsPembayaranPiutang.potongan)
        var sisa_piutang = parseFloat(piutang_setelah_diskon) - parseFloat(jumlah_bayar);

        if (sisa_piutang < 0) {
            app.alertTbs("Jumlah Bayar Anda Melebihi Total Piutang !")
            app.inputEditTbsPembayaranPiutang.jumlah_bayar = 0;
            app.$refs.jumlah_bayar_edit.$el.focus(); 
        }
    },
    editEntry(id, index, no_faktur_penjualan, pelanggan, jumlah_bayar, potongan, piutang) {
        var app = this;

        app.inputEditTbsPembayaranPiutang.piutang = piutang;
        app.inputEditTbsPembayaranPiutang.jumlah_bayar = jumlah_bayar;
        app.inputEditTbsPembayaranPiutang.jumlah_bayar_lama = jumlah_bayar;
        app.inputEditTbsPembayaranPiutang.potongan = potongan;
        app.inputEditTbsPembayaranPiutang.id_tbs = id;

        $("#faktur_piutang_edit").text(no_faktur_penjualan+' || '+pelanggan);
        $("#modal_edit_tbs").show();     
        app.$refs.jumlah_bayar_edit.$el.focus();
    },
    editTbsPembayaranPiutang(jumlah_bayar_lama){
        var app = this;
        var newinputEditTbsPembayaranPiutang = app.inputEditTbsPembayaranPiutang;

        if (app.inputEditTbsPembayaranPiutang.jumlah_bayar < 0) {
            app.alertTbs("Potongan Anda Melebihi Total Piutang");
            app.loading = false;
            app.inputEditTbsPembayaranPiutang.potongan = 0;
            app.$refs.potongan_edit.$el.focus();
        }else{
            app.loading = true;
            axios.post(app.url_piutang+'/edit-jumlah-tbs-pembayaran-piutang', newinputEditTbsPembayaranPiutang)
            .then(function (resp) {
                if (resp.data.status == 0) {
                    app.getResults()
                    app.$swal({
                        text: "Potongan Yang Anda Masukan Melebihi Subtotal Piutang!",
                    });
                    app.loading = false;
                    app.inputEditTbsPembayaranPiutang.piutang = ''
                    app.inputEditTbsPembayaranPiutang.jumlah_bayar = ''
                    app.inputEditTbsPembayaranPiutang.potongan = ''
                    app.inputEditTbsPembayaranPiutang.id_tbs = ''

                }else{
                    var subtotal = (parseFloat(app.pembayaranPiutang.subtotal) - parseFloat(jumlah_bayar_lama)) + parseFloat(resp.data.jumlah_bayar)

                    app.getResults()
                    app.pembayaranPiutang.subtotal = subtotal.toFixed(2)
                    app.alert("Mengubah Faktur Piutang")
                    app.inputEditTbsPembayaranPiutang.piutang = ''
                    app.inputEditTbsPembayaranPiutang.jumlah_bayar = ''
                    app.inputEditTbsPembayaranPiutang.potongan = ''
                    app.inputEditTbsPembayaranPiutang.id_tbs = ''

                    $("#modal_edit_tbs").hide();
                    app.loading = false;
                }
            })
            .catch(function (resp) { 
                console.log(resp);                  
                app.loading = false;
                alert("Tidak Dapat Mengubah Potongan");
            });
        }
    },
    deleteEntry(id, index,jumlah_bayar_lama,no_faktur_penjualan) {

        var app = this;
        app.$swal({
            text: "Anda Yakin Ingin Menghapus Faktur "+no_faktur_penjualan+ " ?",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                this.prosesDelete(id,no_faktur_penjualan,jumlah_bayar_lama);
            } else {
                app.$swal.close();
            }
        });

    },
    prosesDelete(id,no_faktur_penjualan,jumlah_bayar_lama){
        var app = this;
        app.loading = true;
        axios.delete(app.url_piutang+'/proses-hapus-tbs-pembayaran-piutang/'+id)
        .then(function (resp) {

            if (resp.data == 0) {
                app.alertTbs("Faktur "+no_faktur_penjualan+" Gagal Dihapus!")
                app.loading = false
            }else{
                var subtotal = parseFloat(app.pembayaranPiutang.subtotal) - parseFloat(jumlah_bayar_lama)
                app.getResults()
                app.pembayaranPiutang.subtotal = subtotal.toFixed(2)
                app.alert("Menghapus Faktur "+no_faktur_penjualan)
                app.loading = false
            }
        })
        .catch(function (resp) {
            console.log(resp);
            app.loading = false;
            alert("Tidak dapat Menghapus Faktur "+no_faktur_penjualan);
        });
    },
    batalPembayaranPiutang(){
        var app = this
        app.$swal({
            text: "Anda Yakin Ingin Membatalkan Transaksi Ini ?",
            buttons: {
                cancel: true,
                confirm: "OK"
            },
        }).then((value) => {

            if (!value) throw null;

            app.loading = true;
            axios.post(app.url_piutang+'/proses-batal-pembayaran-piutang')
            .then(function (resp) {
                app.getResults();
                app.alert("Membatalkan Transaksi Pembayaran Piutang");
                app.pembayaranPiutang.subtotal = 0
            })
            .catch(function (resp) {
                app.loading = false;
                alert("Tidak dapat Membatalkan Transaksi Pembayaran Piutang");
            });

        });
    },
    bayarPembayaranPiutang(){        
        $("#modal_selesai").show();
        this.$refs.keterangan.$el.focus()
    },
    selesaiPembayaranPiutang(){
        this.$swal({
            text: "Anda Yakin Ingin Menyelesaikan Transaksi Ini ?",
            buttons: {
                cancel: true,
                confirm: "OK"                   
            },
        }).then((value) => {
            if (!value) throw null;
            this.prosesSelesaiPembayaranPiutang(value);
        });
    },
    prosesSelesaiPembayaranPiutang(value){
        var app = this;
        var newPembayaranPiutang = app.pembayaranPiutang;
        app.loading = true;

        axios.post(app.url_piutang,newPembayaranPiutang)
        .then(function (resp) {

            if (resp.data == 0) {
                app.alertTbs("Anda Belum Memasukan Faktur Penjualan Piutang");
                app.loading = false;
            }else{
                app.alert("Menyelesaikan Transaksi Pembayaran Piutang");
                app.pembayaranPiutang.tanggal = new Date;
                app.pembayaranPiutang.keterangan = ''
                app.pembayaranPiutang.subtotal = 0

                $("#modal_selesai").hide();
                app.loading = false;                
                app.$router.replace('/pembayaran-piutang');
            }
        })
        .catch(function (resp) {
            console.log(resp);              
            app.loading = false;
            alert("Tidak dapat Menyelesaikan Transaksi Pembayaran Penjualan");        
            app.errors = resp.response.data.errors;
        });
    },
    closeModal(){
        this.inputTbsPembayaranPiutang.penjualan_piutang = '';
        this.inputTbsPembayaranPiutang.piutang = 0
        this.inputTbsPembayaranPiutang.jumlah_bayar = 0
        this.inputTbsPembayaranPiutang.potongan = 0
        this.inputEditTbsPembayaranPiutang.piutang = 0
        this.inputEditTbsPembayaranPiutang.jumlah_bayar = 0
        this.inputEditTbsPembayaranPiutang.potongan = 0
        $("#modal_tbs").hide();
        $("#modal_edit_tbs").hide();
        $("#modal_selesai").hide();
    },
    alertTbs(pesan) {
        this.$swal({
            text: pesan,
            icon: "warning",
        });
    },
    alert(pesan) {
        this.$swal({
            title: "Berhasil ",
            text: pesan,
            icon: "success",
            buttons: false,
            timer: 1000
        });
    }
}
}
</script>
