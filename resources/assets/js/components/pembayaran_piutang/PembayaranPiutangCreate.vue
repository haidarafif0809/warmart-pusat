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

</style>

<template>


    <div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb" style="margin-bottom: 1px; margin-top: 1px;">
                <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                <li class="active">Pembayaran Piutang</li>

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
                        <form class="form-horizontal" > 
                            <div class="modal-body"> 
                                <div class="card" style="margin-bottom:1px; margin-top:1px; margin-right:1px; margin-left:1px;">

                                    <div class="row">
                                      <div class="col-md-6">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">Piutang</font>
                                            <money style="text-align:right; font-size: 30px;" readonly="" class="form-control" id="piutang" name="piutang" placeholder="Kredit"  v-model="inputTbsPembayaranPiutang.piutang" v-bind="separator" ></money> 
                                        </div> 
                                      </div>

                                      <div class="col-md-6">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">Potongan</font>
                                            <money style="text-align:right; font-size: 30px;" class="form-control" id="potongan" name="potongan" placeholder="Kredit"  v-model="inputTbsPembayaranPiutang.potongan" v-bind="separator" ></money> 
                                        </div>                                        
                                      </div>

                                      <div class="col-md-12">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">Jumlah Bayar(F10)</font>
                                            <money style="text-align:right" class="form-penjualan" v-shortkey.focus="['f10']" id="jumlah_bayar" name="jumlah_bayar" v-model="inputTbsPembayaranPiutang.jumlah_bayar" v-bind="separator"  autocomplete="off" ref="jumlah_bayar"></money> 
                                        </div>
                                      </div>
                                    </div>

                                    <div align="right"  style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                        <button type="button" class="btn btn-success btn-lg" id="btnTbs" v-on:click="tambahTbsPembayaranPiutang()" v-shortkey.push="['shift']" @shortkey="tambahTbsPembayaranPiutang()"><font style="font-size:20px;">Tambah</font></button>

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

    <div class="card" style="margin-bottom: 1px; margin-top: 1px;">
        <div class="card-content">

            <h4 class="card-title" style="margin-bottom: 1px; margin-top: 1px;"> Pembayaran Piutang </h4>

            <div class="row" style="margin-bottom: 1px; margin-top: 1px;">

                <div class="col-md-3 col-xs-9">
                    <div class="card card-produk" style="margin-bottom: 1px; margin-top: 1px;">

                      <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                        <selectize-component v-model="inputTbsPembayaranPiutang.penjualan_piutang" :settings="placeholder_piutang" id="penjualan_piutang" ref='penjualan_piutang' v-shortkey.focus="['f1']"> 
                            <option v-for="piutangs, index in penjualan_piutang" v-bind:value="piutangs.id">{{piutangs.no_faktur_penjualan}} || {{ piutangs.nama_pelanggan }}</option>
                        </selectize-component>
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

                            <th>No. Faktur Penjualan</th>
                            <th> Pelanggan </th>
                            <th class="text-center">Tanggal JT</th>
                            <th class="text-right">Piutang</th>
                            <th class="text-right">Potongan</th>
                            <th class="text-right">Total</th>
                            <th class="text-right">Jumlah Bayar</th>
                            <th class="text-center">Hapus</th>

                        </tr>
                    </thead>
                    <tbody v-if="tbs_pembayaran_piutang.length"  class="data-ada">
                        <tr v-for="tbs_pembayaran_piutang, index in tbs_pembayaran_piutang" >

                            <td>{{ tbs_pembayaran_piutang.no_faktur_penjualan }}</td>
                            <td>{{ tbs_pembayaran_piutang.pelanggan }}</td>
                            <td align="center">{{ tbs_pembayaran_piutang.jatuh_tempo | tanggal }}</td>
                            <td align="right">{{ tbs_pembayaran_piutang.piutang | pemisahTitik }}</td>

                            <td align="right">
                              <a href="#create-penjualan" v-bind:id="'edit-' + tbs_pembayaran_piutang.id_tbs_pembayaran_piutang" v-on:click="potonganEntry(tbs_pembayaran_piutang.id_tbs_pembayaran_piutang, index,tbs_pembayaran_piutang.pelanggan_id, tbs_pembayaran_piutang.total)">{{ tbs_pembayaran_piutang.potongan | pemisahTitik }}</a>
                            </td>

                            <td align="right">{{ tbs_pembayaran_piutang.total | pemisahTitik }}</td>

                            <td align="right">
                                <a href="#create-penjualan" v-bind:id="'edit-' + tbs_pembayaran_piutang.id_tbs_pembayaran_piutang" v-on:click="editEntry(tbs_pembayaran_piutang.id_tbs_pembayaran_piutang, index, tbs_pembayaran_piutang.pelanggan_id,tbs_pembayaran_piutang.total)">{{ tbs_pembayaran_piutang.jumlah_bayar | pemisahTitik }}</a>
                            </td>

                            <td align="center">
                              <a href="#create-penjualan" class="btn btn-xs btn-danger" v-bind:id="'delete-' + tbs_pembayaran_piutang.id_tbs_pembayaran_piutang" v-on:click="deleteEntry(tbs_pembayaran_piutang.id_tbs_pembayaran_piutang, index,tbs_pembayaran_piutang.pelanggan_id,tbs_pembayaran_piutang.total)">Delete</a>
                            </td>

                        </tr>
                    </tbody>                    
                    <tbody class="data-tidak-ada" v-else>
                        <tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
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
                      <div class="col-md-6 col-xs-6"> 
                    <button type="button" class="btn btn-success btn-lg" id="bayar" v-on:click="bayarPembayaranPiutang()" v-shortkey.push="['f2']" @shortkey="bayarPembayaranPiutang()"><font style="font-size:20px;">Bayar(F2)</font></button>
                     </div>
                    <div class="col-md-6 col-xs-6">
                    <button type="submit" class="btn btn-danger btn-lg" id="btnBatal" v-on:click="batalPembayaranPiutang()" v-shortkey.push="['f3']" @shortkey="batalPembayaranPiutang()"> <font style="font-size:20px;">Batal(F3) </font></button>
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
                no_faktur_penjualan : '',
                pelanggan_id : '',
                piutang : '',
                potongan : '',
                jumlah_bayar : '',
                jatuh_tempo : '',
            },
            pembayaranPiutang : {
                pelanggan : '0',
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
            placeholder_piutang: {
                placeholder: 'Cari Piutang (F1) ...'
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

        if (app.pembayaranPiutang.subtotal == 0) {
            $.each(resp.data.data, function (i,item) {
               app.pembayaranPiutang.subtotal += parseFloat(resp.data.data[i].total)
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
    axios.get(app.url+'/pencarian-tbs-pembayaran-piutang?search='+app.pencarian+'&page='+page)
    .then(function (resp) {
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

    axios.post(app.url_piutang+'/proses-tambah-tbs-pembayaran-piutang',newTbsPembayaranPiutang)
    .then(function (resp) {
          console.log(resp.data)
       if (resp.data == 0) {
            app.loading = false;
            app.inputTbsPembayaranPiutang.penjualan_piutang = ''
            app.getResults();
            $("#modal_tbs").hide();
            app.alertTbs("Faktur "+app.inputTbsPembayaranPiutang.no_faktur_penjualan+" Sudah Ada, Silakan Pilih Faktur Piutang Lain!");

        }else{
            var subtotal = parseFloat(app.pembayaranPiutang.subtotal) + parseFloat(resp.data.jumlah_bayar)

            app.getResults();
            app.pembayaranPiutang.subtotal = subtotal.toFixed(2)
            app.alert("Berhasil Menambahkan Faktur Piutang"+ app.inputTbsPembayaranPiutang.no_faktur_penjualan);
            app.inputTbsPembayaranPiutang.piutang = 0
            app.inputTbsPembayaranPiutang.jumlah_bayar = 0
            app.inputTbsPembayaranPiutang.no_faktur_penjualan = ''
            app.inputTbsPembayaranPiutang.pelanggan_id = ''
            app.inputTbsPembayaranPiutang.jatuh_tempo = ''

            $("#modal_tbs").hide();
            app.loading = false;
        }

    })
    .catch(function (resp) { 

        console.log(resp);              
        app.loading = false;
        alert("Tidak dapat Menambahkan Faktur Penjualan Piutang");        
        app.errors = resp.response.data.errors;
    });
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
            confirm: "OK"                   
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

        app.inputTbsPembayaranPiutang.id_tbs = id;
        app.inputTbsPembayaranPiutang.jumlah_produk = value;
        var newinputTbsPembayaranPiutang = app.inputTbsPembayaranPiutang;
        app.loading = true;
        axios.post(app.url+'/edit-jumlah-tbs-penjualan', newinputTbsPembayaranPiutang)
        .then(function (resp) {

            var subtotal = (parseFloat(app.pembayaranPiutang.subtotal) - parseFloat(subtotal_lama)) + parseFloat(resp.data.subtotal)

            app.getResults()
            app.pembayaranPiutang.subtotal = subtotal.toFixed(2)
            app.pembayaranPiutang.total_akhir = subtotal.toFixed(2)
            app.potonganPersen()
            app.alert("Mengubah Jumlah Produk "+nama_produk)
            app.loading = false;
            app.inputTbsPembayaranPiutang.jumlah_produk = ''
            app.inputTbsPembayaranPiutang.id_tbs = ''

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
        text : 'Format : 10 (nominal) || 10% (persen)',
        content: {
            element: "input",
            attributes: {
                placeholder: "Edit Potongan Produk",
                type: "text",
            },
        },
        buttons: {
            cancel: true,
            confirm: "OK"                   
        },

    }).then((value) => {
        if (!value) throw null;
        this.editPotonganProdukPenjualan(value,id,nama_produk,subtotal_lama);
    });

},
editPotonganProdukPenjualan(value,id,nama_produk,subtotal_lama){

    var app = this;

    app.inputTbsPembayaranPiutang.id_tbs = id;
    app.inputTbsPembayaranPiutang.potongan_produk = value;
    var newinputTbsPembayaranPiutang = app.inputTbsPembayaranPiutang;

    app.loading = true;
    axios.post(app.url+'/edit-potongan-tbs-penjualan', newinputTbsPembayaranPiutang)
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

            var subtotal = (parseFloat(app.pembayaranPiutang.subtotal) - parseFloat(subtotal_lama)) + parseFloat(resp.data.subtotal)

            app.getResults()
            app.pembayaranPiutang.subtotal = subtotal.toFixed(2)
            app.pembayaranPiutang.total_akhir = subtotal.toFixed(2)           
            app.potonganPersen()
            app.alert("Mengubah Potongan Produk "+nama_produk)
            app.loading = false
            app.inputTbsPembayaranPiutang.potongan_produk = ''
            app.inputTbsPembayaranPiutang.id_tbs = ''

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
    axios.delete(app.url+'/proses-hapus-tbs-penjualan/'+id)
    .then(function (resp) {

        if (resp.data == 0) {

            app.alertTbs("Produk "+nama_produk+" Gagal Dihapus!")
            app.loading = false

        }else{
            var subtotal = parseFloat(app.pembayaranPiutang.subtotal) - parseFloat(subtotal_lama)
            app.getResults()
            app.pembayaranPiutang.subtotal = subtotal.toFixed(2)
            app.pembayaranPiutang.total_akhir = subtotal.toFixed(2)
            app.potonganPersen()
            app.alert("Menghapus Produk "+nama_produk)
            app.loading = false
            app.inputTbsPembayaranPiutang.id_tbs = ''  
            app.inputTbsPembayaranPiutang.penjualan_piutang = ''  
        }


    })
    .catch(function (resp) {

        console.log(resp);
        app.loading = false;
        alert("Tidak dapat Menghapus Produk "+nama_produk);
    });
},
closeModal(){
    this.inputTbsPembayaranPiutang.penjualan_piutang = '';
    $("#modal_tbs").hide();
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
        timer: 2000
    });
}
}
}
</script>
