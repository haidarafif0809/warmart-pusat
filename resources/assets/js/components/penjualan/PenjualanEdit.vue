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

</style>

<template>


    <div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb" style="margin-bottom: 1px; margin-top: 1px;">
                <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                <li><router-link :to="{name: 'indexPenjualan'}">Lap. Penjualan</router-link></li>
                <li class="active">Edit Penjualan</li>
            </ul>

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
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                                                <label class="label-control">Pelanggan(F4)</label><br>
                                                <selectize-component v-model="penjualan.pelanggan" :settings="placeholder_pelanggan" id="pelanggan" ref='pelanggan' v-shortkey.focus="['f4']"> 
                                                  <option v-for="pelanggans, index in pelanggan" v-bind:value="pelanggans.id">{{ pelanggans.nama_pelanggan }}</option>
                                              </selectize-component>
                                              <br v-if="errors.pelanggan">  <span v-if="errors.pelanggan" id="pelanggan_error" class="label label-danger">{{ errors.pelanggan[0] }}</span>
                                          </div>
                                      </div>
                                      <div class="col-md-6 col-xs-12">

                                          <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                                            <label class="label-control">Kas(F6)</label><br>
                                            <selectize-component v-model="penjualan.kas" :settings="placeholder_kas" id="kas" ref='kas' v-shortkey.focus="['f6']" > 
                                                <option v-for="kass, index in kas" v-bind:value="kass.id">{{ kass.nama_kas }}</option>
                                            </selectize-component>
                                            <br v-if="errors.kas">   <span v-if="errors.kas" id="kas_error" class="label label-danger">{{ errors.kas[0] }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-3 col-xs-6">
                                        <div class="form-group" style="margin-right: 1px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px; width:130px;">
                                            <label class="label-control">Potongan(F7)</label>  
                                            <money class="form-subtotal" v-model="penjualan.potongan_faktur" v-bind="separator" v-shortkey.focus="['f7']"></money>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-xs-6">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 1px; margin-bottom: 1px; margin-top: 1px;">
                                            <label class="label-control">(%)(F8)</label>    
                                            <input type="number" class="form-subtotal" value="0" v-model="penjualan.potongan_persen" v-on:blur="potonganPersen" v-shortkey.focus="['f8']" />
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <label class="label-control">Jatuh Tempo(F9)</label> 
                                            <datepicker :input-class="'form-control'" placeholder="Jatuh Tempo" v-model="penjualan.jatuh_tempo" v-shortkey.focus="['f9']" ></datepicker>
                                            <br v-if="errors.jatuh_tempo">  <span v-if="errors.jatuh_tempo" id="jatuh_tempo_error" class="label label-danger">{{ errors.jatuh_tempo[0] }}</span>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">Total Akhir</font>
                                            <money class="form-penjualan" readonly="" id="total_akhir" name="total_akhir" placeholder="Total Akhir"  v-model="penjualan.total_akhir" v-bind="separator" ></money> 
                                        </div>

                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">Pembayaran(F10)</font>
                                            <money class="form-penjualan" v-shortkey.focus="['f10']" id="pembayaran" name="pembayaran" placeholder="Pembayaran"  v-model="penjualan.pembayaran" v-bind="separator" autocomplete="off" ref="pembayaran"></money> 
                                        </div>

                                    </div>
                                    <div class="col-md-6 col-xs-12">

                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                         <font style="color: black">Kembalian</font>
                                         <money readonly="" class="form-penjualan" id="kembalian" name="kembalian" placeholder="Kembalian"  v-model="penjualan.kembalian" v-bind="separator" ></money> 
                                     </div>

                                     <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                         <font style="color: black">Kredit</font>
                                         <money readonly="" class="form-penjualan" id="kredit" name="kredit" placeholder="Kredit"  v-model="penjualan.kredit" v-bind="separator" ></money> 
                                     </div>
                                 </div>
                             </div>

                             <div align="right"  style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                               <button v-if="penjualan.kembalian >= 0 && penjualan.kredit == 0" type="button" class="btn btn-success" id="btnSelesai" v-on:click="selesaiPenjualan()" v-shortkey.push="['alt']" @shortkey="selesaiPenjualan()"><i class="material-icons">credit_card</i>Tunai(Alt)</button>

                               <button v-if="penjualan.kredit > 0" type="button" class="btn btn-success" id="btnSelesai" v-on:click="selesaiPenjualan()" v-shortkey.push="['alt']" @shortkey="selesaiPenjualan()"><i class="material-icons">credit_card</i> Piutang(Alt)</button>

                               <button type="button" class="btn btn-default"  v-on:click="closeModal()" v-shortkey.push="['esc']" @shortkey="closeModal()"><i class="material-icons">close</i> Tutup(Esc)</button>
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
        <h4 class="card-title" style="margin-bottom: 1px; margin-top: 1px;">Edit Penjualan #{{id_penjualan_pos}}</h4>

        <div class="row" style="margin-bottom: 1px; margin-top: 1px;">

            <div class="col-md-3">
                <div class="card card-produk" style="margin-bottom: 1px; margin-top: 1px;">

                  <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                    <selectize-component v-model="inputTbsPenjualan.produk" :settings="placeholder_produk" id="produk" ref='produk' v-shortkey.focus="['f1']"> 
                        <option v-for="produks, index in produk" v-bind:value="produks.produk">{{ produks.nama_produk }}</option>
                    </selectize-component>
                </div>  

                <span style="display: none;">
                    <input class="form-control" type="hidden"  v-model="inputTbsPenjualan.jumlah_produk"  name="jumlah_produk" id="jumlah_produk">
                    <input class="form-control" type="hidden"  v-model="inputTbsPenjualan.potongan_produk"  name="potongan_produk" id="potongan_produk">
                    <input class="form-control" type="hidden"  v-model="inputTbsPenjualan.id_tbs"  name="id_tbs" id="id_tbs">
                    <input class="form-control" type="text"  v-model="penjualan.potongan"  name="potongan" id="potongan">
                </span>

            </div>
        </div>
    </div>


    <!--TABEL TBS ITEM  MASUK -->
    <div class="row">

        <div class="col-md-8">
            <div class=" table-responsive ">
              <div class="pencarian">
                <input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
            </div>
            <table class="table table-striped table-hover" v-if="seen">
                <thead class="text-primary">
                    <tr>

                        <th>Produk</th>
                        <th align="right">Jumlah</th>
                        <th align="right">Harga</th>
                        <th align="right">Potongan</th>
                        <th align="right">Subtotal</th>
                        <th>Hapus</th>

                    </tr>
                </thead>
                <tbody v-if="tbs_penjualan.length"  class="data-ada">
                    <tr v-for="tbs_penjualan, index in tbs_penjualan" >

                        <td>{{ tbs_penjualan.kode_produk }} - {{ tbs_penjualan.nama_produk }}</td>

                        <td align="center">
                            <a v-bind:href="'#edit-penjualan/'+tbs_penjualan.id_penjualan_pos"  v-bind:id="'edit-' + tbs_penjualan.id_edit_tbs_penjualans" v-on:click="editEntry(tbs_penjualan.id_edit_tbs_penjualans, index,tbs_penjualan.nama_produk,tbs_penjualan.subtotal)">{{ new Intl.NumberFormat().format(tbs_penjualan.jumlah_produk) }},00</a>
                        </td>

                        <td align="center">{{ new Intl.NumberFormat().format(tbs_penjualan.harga_produk) }},00</td>

                        <td align="center"><a v-bind:href="'#edit-penjualan/'+tbs_penjualan.id_penjualan_pos"  v-bind:id="'edit-' + tbs_penjualan.id_edit_tbs_penjualans" v-on:click="potonganEntry(tbs_penjualan.id_edit_tbs_penjualans, index,tbs_penjualan.nama_produk,tbs_penjualan.subtotal)">{{ tbs_penjualan.potongan }}</a></td>

                        <td align="center"> {{ new Intl.NumberFormat().format(tbs_penjualan.subtotal) }},00</td>

                        <td><a v-bind:href="'#edit-penjualan/'+tbs_penjualan.id_penjualan_pos"  class="btn btn-xs btn-danger" v-bind:id="'delete-' + tbs_penjualan.id_edit_tbs_penjualans" v-on:click="deleteEntry(tbs_penjualan.id_edit_tbs_penjualans, index,tbs_penjualan.nama_produk,tbs_penjualan.subtotal)">Delete</a></td>
                    </tr>
                </tbody>                    
                <tbody class="data-tidak-ada" v-else>
                    <tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
                </tbody>
            </table>    

            <vue-simple-spinner v-if="loading"></vue-simple-spinner>

            <div align="right"><pagination :data="tbsPenjualanData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

        </div>
    </div>
    <div class="col-md-4">

        <div class="card card-stats">
            <div class="card-header" data-background-color="blue">
                <i class="material-icons">shopping_cart</i>
            </div>
            <div class="card-content">
                <p class="category">Subtotal</p>
                <h3 class="card-title">{{ new Intl.NumberFormat().format(penjualan.subtotal) }},00</h3>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-success" id="bayar" v-on:click="bayarPenjualan()" v-shortkey.push="['f2']" @shortkey="bayarPenjualan()"><i class="material-icons">payment</i>Bayar(F2)</button>
                <button type="submit" class="btn btn-danger" id="btnBatal" v-on:click="batalPenjualan()" v-shortkey.push="['f3']" @shortkey="batalPenjualan()"><i class="material-icons">cancel</i> Batal(F3) </button>
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
            produk: [],
            pelanggan: [],
            kas: [],
            tbs_penjualan: [],
            tbsPenjualanData : {},
            url : window.location.origin+(window.location.pathname).replace("dashboard", "penjualan"),
            url_produk : window.location.origin+(window.location.pathname).replace("dashboard", "produk"),
            inputTbsPenjualan: {
                produk : '',
                jumlah_produk : '',
                potongan_produk : '',
                id_tbs : '',
            },
            penjualan : {
                pelanggan : '',
                kas : '',
                jatuh_tempo : '',
                subtotal : 0,
                potongan : 0,
                potongan_faktur : 0,
                potongan_persen : 0,
                total_akhir : 0,
                pembayaran : 0,
                kembalian: 0,
                kredit: 0,
            }, 
            placeholder_produk: {
                placeholder: 'Cari Produk (F1) ...'
            },
            placeholder_pelanggan: {
                placeholder: '--PILIH PELANGGAN (F4)--'
            },
            placeholder_kas: {
                placeholder: '--PILIH KAS--'
            },
            pencarian: '',
            loading: true,
            seen : false,
            id_penjualan_pos : 0,
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
    app.dataProduk();
    app.dataPelanggan();
    app.dataKas();
    app.getResults();
    app.id_penjualan_pos = app.$route.params.id;
},
watch: {
    // whenever question changes, this function will run
    pencarian: function (newQuestion) {
        this.getHasilPencarian()
        this.loading = true
    },
    'inputTbsPenjualan.produk': function () {
        this.pilihProduk()
    },
    'penjualan.pembayaran':function (val){
        if (val == '') {
            val = 0
        }
        this.hitungKembalian(val)
    },
    'penjualan.potongan_faktur':function(){
        this.potonganFaktur()
    }

},
methods: {
    getDataPenjualan(){

        var app = this
        var id = app.$route.params.id;
        axios.get(app.url+'/cek-data-tbs-penjualan/'+id)
        .then(function (resp) {

            app.penjualan.pelanggan = resp.data.pelanggan_id
            app.penjualan.kas = resp.data.id_kas
            app.penjualan.jatuh_tempo = resp.data.tanggal_jt_tempo
            app.penjualan.pembayaran = resp.data.tunai
            if (app.penjualan.subtotal > 0) {
                app.penjualan.potongan = resp.data.potongan
                app.penjualan.potongan_faktur = resp.data.potongan                
            }
            
        })
        .catch(function (resp) {

            console.log(resp);
            alert("Tidak Dapat Memuat Penjualan");

        });
    },
    hitungKembalian(val){
        var kembalian = parseFloat(val) - parseFloat(this.penjualan.total_akhir);   
        if (kembalian >= 0) {

            this.penjualan.kembalian = kembalian 
            this.penjualan.kredit = 0

        }else{

          this.penjualan.kembalian = 0  
          this.penjualan.kredit = parseFloat(this.penjualan.total_akhir) -parseFloat(val)

      }        
  },
  potonganPersen(){

    var potonganPersen = this.penjualan.potongan_persen

    if (potonganPersen > 100) {

        this.alertTbs("Potongan Tidak Bisa Lebih Dari 100%")
        this.penjualan.total_akhir = this.penjualan.subtotal
        this.penjualan.potongan_faktur = 0
        this.penjualan.potongan_persen = 0
        this.penjualan.potongan = 0
        this.hitungKembalian(this.penjualan.pembayaran)

    }else{

        if (potonganPersen == '') {
            potonganPersen = 0
        }

        var potongan_nominal = parseFloat(this.penjualan.subtotal) * (parseFloat(potonganPersen) / 100) 
        var total_akhir = parseFloat(this.penjualan.subtotal,10) - parseFloat(potongan_nominal,10)

        this.penjualan.potongan_faktur = potongan_nominal
        this.penjualan.total_akhir = total_akhir 
        this.penjualan.potongan = potongan_nominal
        this.hitungKembalian(this.penjualan.pembayaran)

    }
},
potonganFaktur(){
   var potonganFaktur = this.penjualan.potongan_faktur
   if (potonganFaktur == '') {
    potonganFaktur = 0
}
var potongan_persen = (parseFloat(potonganFaktur) / parseFloat(this.penjualan.subtotal)) * 100
var total_akhir = parseFloat(this.penjualan.subtotal) - parseFloat(potonganFaktur)

if (potongan_persen > 100) {

    this.alertTbs("Potongan Tidak Bisa Lebih Dari 100%")
    this.penjualan.total_akhir = this.penjualan.subtotal
    this.penjualan.potongan_faktur = 0
    this.penjualan.potongan_persen = 0
    this.penjualan.potongan = 0        
    this.hitungKembalian(this.penjualan.pembayaran)

}else{
    this.penjualan.potongan_persen = potongan_persen.toFixed(2)
    this.penjualan.total_akhir = total_akhir
    this.penjualan.potongan = potonganFaktur
    this.hitungKembalian(this.penjualan.pembayaran)
}

},
getResults(page) {
    var app = this; 
    var id = app.$route.params.id;
    if (typeof page === 'undefined') {
        page = 1;
    }
    axios.get(app.url+'/view-edit-tbs-penjualan/'+id+'?page='+page)
    .then(function (resp) {
        app.tbs_penjualan = resp.data.data;
        app.tbsPenjualanData = resp.data;
        app.loading = false;
        app.seen = true;

        if (app.penjualan.subtotal == 0) {        

            $.each(resp.data.data, function (i,item) {

             app.penjualan.subtotal += resp.data.data[i].subtotal
             app.penjualan.total_akhir += resp.data.data[i].subtotal
             app.penjualan.kredit += resp.data.data[i].subtotal
             app.getDataPenjualan();

         }); 
        }


    })
    .catch(function (resp) {
        console.log(resp);
        app.loading = false;
        app.seen = true;
        alert("Tidak Dapat Memuat Penjualan");
    });
}, 
getHasilPencarian(page){
    var app = this;
    var id = app.$route.params.id;
    if (typeof page === 'undefined') {
        page = 1;
    }
    axios.get(app.url+'/pencarian-edit-tbs-penjualan/'+id+'?search='+app.pencarian+'&page='+page)
    .then(function (resp) {
        app.tbs_penjualan = resp.data.data;
        app.tbsPenjualanData = resp.data;
        app.loading = false;
        app.seen = true;
    })
    .catch(function (resp) {
        console.log(resp);
        alert("Tidak Dapat Memuat Penjualan");
    });
},    
dataProduk() {
    var app = this;
    axios.get(app.url_produk+'/pilih-produk').then(function (resp) {
        app.produk = resp.data;
    })
    .catch(function (resp) {

        console.log(resp);
        alert("Tidak Bisa Memuat Produk");
    });
},   
dataPelanggan() {
    var app = this;
    axios.get(app.url+'/pilih-pelanggan').then(function (resp) {
        app.pelanggan = resp.data;
    })
    .catch(function (resp) {

        console.log(resp);
        alert("Tidak Bisa Memuat Pelanggan");
    });
},   
dataKas() {
    var app = this;
    axios.get(app.url+'/pilih-kas').then(function (resp) {
        app.kas = resp.data;   
        
    })
    .catch(function (resp) {

        console.log(resp);
        alert("Tidak Bisa Memuat Kas");
    });
},pilihProduk() {
    if (this.inputTbsPenjualan.produk != '') {

        var app = this;
        var produk = app.inputTbsPenjualan.produk.split("|");
        var nama_produk = produk[1];
        this.isiJumlahProduk(nama_produk);
    }
},
isiJumlahProduk(nama_produk){
    var app = this;
    app.$swal({
        title: nama_produk,
        content: {
            element: "input",
            attributes: {
                placeholder: "Jumlah Produk",
                type: "number",
            },
        },
        buttons: {
            cancel: true,
            confirm: "OK"                   
        }


    }).then((value) => {
        if (!value) throw null;
        this.submitProdukPenjualan(value);
    });
},
submitProdukPenjualan(value){

    if (value == 0) {

        this.$swal({
            text: "Jumlah Produk Tidak Boleh Nol!",
        });

    }else{

        var app = this;
        var id = app.$route.params.id;
        var produk = app.inputTbsPenjualan.produk.split("|");
        var nama_produk = produk[1];

        app.inputTbsPenjualan.jumlah_produk = value;
        var newinputTbsPenjualan = app.inputTbsPenjualan;
        app.loading = true;
        axios.post(app.url+'/proses-tambah-edit-tbs-penjualan/'+id, newinputTbsPenjualan)
        .then(function (resp) {

            if (resp.data == 0) {

                app.alertTbs("Produk "+nama_produk+" Sudah Ada, Silakan Pilih Produk Lain!");
                app.loading = false;

            }else{

                var subtotal = parseInt(app.penjualan.subtotal) + parseInt(resp.data.subtotal)
                app.getResults();
                app.penjualan.subtotal = subtotal                        
                app.penjualan.total_akhir  = subtotal 
                app.potonganPersen()
                app.alert("Menambahkan Produk "+nama_produk)
                app.loading = false
                app.inputTbsPenjualan.jumlah_produk = ''

            }

        })
        .catch(function (resp) {

            console.log(resp);                  
            app.loading = false;
            alert("Tidak dapat Menambahkan Produk");
        });
    }
},
editEntry(id, index,nama_produk,subtotal_lama) {    
    var app = this;     
    app.$swal({
        title: nama_produk,
        content: {
            element: "input",
            attributes: {
                placeholder: "Edit Jumlah Produk",
                type: "number",
            },
        },
        buttons: {
            cancel: true,
            confirm: "Submit"                   
        },

    }).then((value) => {
        if (!value) throw null;
        this.editJumlahProdukPenjualan(value,id,nama_produk,subtotal_lama);
    });

},
editJumlahProdukPenjualan(value,id,nama_produk,subtotal_lama){
    if (value == 0) {

        this.$swal({
            text: "Jumlah Produk Tidak Boleh Nol!",
        });

    }else{

        var app = this;

        app.inputTbsPenjualan.id_tbs = id;
        app.inputTbsPenjualan.jumlah_produk = value;
        var newinputTbsPenjualan = app.inputTbsPenjualan;
        app.loading = true;
        axios.post(app.url+'/edit-jumlah-edit-tbs-penjualan', newinputTbsPenjualan)
        .then(function (resp) {

            var subtotal = (parseInt(app.penjualan.subtotal) - parseInt(subtotal_lama)) + parseInt(resp.data.subtotal)

            app.getResults()
            app.penjualan.subtotal = subtotal
            app.penjualan.total_akhir = subtotal
            app.potonganPersen()
            app.alert("Mengubah Jumlah Produk "+nama_produk)
            app.loading = false;
            app.inputTbsPenjualan.jumlah_produk = ''
            app.inputTbsPenjualan.id_tbs = ''

        })
        .catch(function (resp) { 

            console.log(resp);                  
            app.loading = false;
            alert("Tidak dapat Mengubah Jumlah Produk");
        });
    }
},
potonganEntry(id, index,nama_produk,subtotal_lama) {    
    var app = this;     
    app.$swal({
        title: nama_produk,
        text : 'Sertakan % Jika Ingin Potongan Dalam Bentuk Persentase',
        content: {
            element: "input",
            attributes: {
                placeholder: "Edit Potongan Produk",
                type: "text",
            },
        },
        buttons: {
            cancel: true,
            confirm: "Submit"                   
        },

    }).then((value) => {
        if (!value) throw null;
        this.editPotonganProdukPenjualan(value,id,nama_produk,subtotal_lama);
    });

},
editPotonganProdukPenjualan(value,id,nama_produk,subtotal_lama){

    var app = this;

    app.inputTbsPenjualan.id_tbs = id;
    app.inputTbsPenjualan.potongan_produk = value;
    var newinputTbsPenjualan = app.inputTbsPenjualan;

    app.loading = true;
    axios.post(app.url+'/edit-potongan-edit-tbs-penjualan', newinputTbsPenjualan)
    .then(function (resp) {

        if (resp.data.status == 0) {

            app.$swal({
                text: "Tidak dapat Mengubah Potongan Produk, Periksa Kembali Inputan Anda!",
            });            
            app.loading = false;

        }else if (resp.data.status == 1) {

            app.$swal({
                text: "Potongan Yang Anda Masukan Melebihi Subtotal!",
            });
            app.loading = false;

        }else{

            var subtotal = (parseInt(app.penjualan.subtotal) - parseInt(subtotal_lama)) + parseInt(resp.data.subtotal)

            app.getResults()
            app.penjualan.subtotal = subtotal
            app.penjualan.total_akhir = subtotal
            app.potonganPersen()
            app.alert("Mengubah Potongan Produk "+nama_produk)
            app.loading = false
            app.inputTbsPenjualan.potongan_produk = ''
            app.inputTbsPenjualan.id_tbs = ''

        }


    })
    .catch(function (resp) { 

        console.log(resp);                  
        app.loading = false;
    });

},
deleteEntry(id, index,nama_produk,subtotal_lama) {

    var app = this;
    app.$swal({
        text: "Anda Yakin Ingin Menghapus Produk "+nama_produk+ " ?",
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
    axios.delete(app.url+'/proses-hapus-edit-tbs-penjualan/'+id)
    .then(function (resp) {

        if (resp.data == 0) {

            app.alertTbs("Produk "+nama_produk+" Gagal Dihapus!")
            app.loading = false

        }else{
            var subtotal = parseInt(app.penjualan.subtotal) - parseInt(subtotal_lama)
            app.getResults()
            app.penjualan.subtotal = subtotal
            app.penjualan.total_akhir = subtotal
            app.potonganPersen()
            app.alert("Menghapus Produk "+nama_produk)
            app.loading = false
            app.inputTbsPenjualan.id_tbs = ''  
        }


    })
    .catch(function (resp) {

        console.log(resp);
        app.loading = false;
        alert("Tidak dapat Menghapus Produk "+nama_produk);
    });
},
batalPenjualan(){

    var app = this;
    app.$swal({
        text: "Anda Yakin Ingin Membatalkan Transaksi Ini ?",
        buttons: {
            cancel: true,
            confirm: "OK"                   
        },

    }).then((value) => {

        if (!value) throw null;

        var id = app.$route.params.id;
        app.loading = true;
        axios.post(app.url+'/proses-batal-edit-penjualan/'+id)
        .then(function (resp) {

            app.getResults();
            app.alert("Membatalkan Transaksi Edit Penjualan");
            app.penjualan.pelanggan = ''
            app.penjualan.subtotal = 0
            app.penjualan.jatuh_tempo = ''
            app.penjualan.potongan_persen = 0
            app.penjualan.potongan_faktur = 0
            app.penjualan.total_akhir = 0
            app.penjualan.pembayaran = 0
            app.hitungKembalian(app.penjualan.pembayaran)
            app.$router.replace('/penjualan');

        })
        .catch(function (resp) {

            console.log(resp);
            app.loading = false;
            alert("Tidak dapat Membatalkan Transaksi Edit Penjualan");
        });

    });
    
},
selesaiPenjualan(){
    this.$swal({
        text: "Anda Yakin Ingin Menyelesaikan Transaksi Ini ?",
        buttons: {
            cancel: true,
            confirm: "Submit"                   
        },

    }).then((value) => {

        if (!value) throw null;

        this.prosesSelesaiPenjualan(value);

    });
},
prosesSelesaiPenjualan(value){

    var app = this;
    var id = app.$route.params.id;
    var newPenjualan = app.penjualan;
    app.loading = true;

    axios.patch(app.url+'/'+id,newPenjualan)
    .then(function (resp) {

        if (resp.data == 0) {

            app.alertTbs("Anda Belum Memasukan Produk");
            app.loading = false;

        }else if(resp.data.respons == 1){

            app.alertTbs("Gagal : Stok " + resp.data.nama_produk + " Tidak Mencukupi Untuk di Jual, Sisa Produk = "+resp.data.stok_produk);
            app.loading = false;

        }else{

            app.getResults();
            app.alert("Menyelesaikan Edit Penjualan");
            app.penjualan.pelanggan = ''
            app.penjualan.subtotal = 0
            app.penjualan.jatuh_tempo = ''
            app.penjualan.potongan_persen = 0
            app.penjualan.potongan_faktur = 0
            app.penjualan.total_akhir = 0
            app.penjualan.pembayaran = 0
            app.hitungKembalian(app.penjualan.pembayaran)
            $("#modal_selesai").hide();             
            window.open('penjualan/cetak-kecil-penjualan/'+id,'_blank');
            app.$router.replace('/penjualan');

        }

    })
    .catch(function (resp) {  

        app.loading = false;
        console.log(resp);  
        alert("Tidak dapat Menyelesaikan Transaksi Penjualan");        
        app.errors = resp.response.data.errors;
    });

},
bayarPenjualan(){
    $("#modal_selesai").show();     
    this.$refs.pembayaran.$el.focus()
},
closeModal(){

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
    });
}
}
}
</script>
