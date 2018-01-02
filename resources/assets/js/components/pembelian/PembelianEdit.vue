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
    <ul class="breadcrumb"> 
      <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li> 
      <li><router-link :to="{name: 'indexPembelian'}">Pembelian</router-link></li> 
      <li class="active">Edit Pembelian </li> 
    </ul> 


<div class="modal" id="modal_selesai" role="dialog" data-backdrop=""> 
  <div class="modal-dialog"> 
    <!-- Modal content--> 
    <div class="modal-content"> 
      <div class="modal-header"> 
        <button type="button" class="close" data-dismiss="modal" v-on:click="closeModalX()" id="closeModalX">&times;</button> 
        <h4 class="modal-title"> 
          <div class="alert-icon"> 
            <b>Silahkan Lengkapi Pembayaran!</b> 
          </div> 
        </h4> 
      </div> 
      <form class="form-horizontal" v-on:submit.prevent="saveForm()"> 
      <div class="modal-body"> 
        <div class="row"> 
          <div class="col-md-6 col-xs-6"> 
            <h5>Subtotal</h5> 
              <money v-bind="separator"  name="subtotal"  id="subtotal" autocomplete="off"  readonly="" v-model="inputPembayaranPembelian.subtotal"  class="form-control" style="height: 40px; width:90%; font-size:23px;"></money> 
          </div> 
          <div class="col-md-3  col-xs-3"> 
            <h5>Disc(%)</h5> 
          <input type="text"  name="potongan_persen" id="potongan_persen" autocomplete="off"  v-model="inputPembayaranPembelian.potongan_persen" class="form-control" style="height: 40px;width:90%;font-size:20px;" > 
          </div> 
          <div class="col-md-3 col-xs-3"> 
            <h5>Disc(Rp)</h5> 
            <money v-bind="separator"  name="potongan_faktur" id="potongan_faktur" autocomplete="off"  v-model="inputPembayaranPembelian.potongan_faktur" class="form-control" style="height: 40px;width:90%;font-size:20px;" ></money> 
          </div> 
        </div>  
        <div class="row"> 
          <div class="col-md-6  col-xs-6"> 
            <h5>Total Akhir</h5> 
            <money v-bind="separator"  name="total_akhir"  id="total_akhir" autocomplete="off" readonly="" v-model="inputPembayaranPembelian.total_akhir"  class="form-control" style="height: 40px; width:90%; font-size:25px;"></money> 
          </div> 
          <div class="col-md-6  col-xs-6 card-pembayaran"> 
            <h5><i class="material-icons">info_outline</i> Pembayaran </h5> 
            <money v-bind="separator" name="pembayaran" id="pembayaran" autocomplete="off"  v-model="inputPembayaranPembelian.pembayaran"  class="form-control" style="height: 40px; width:90%; font-size:25px;"></money> 
          </div> 
        </div> 


        <div class="row"> 
          <div class="col-md-6  col-xs-6"> 
            <h5>Kembalian</h5> 
            <money v-bind="separator"  name="kembalian" id="kembalian" autocomplete="off" readonly="" v-model="inputPembayaranPembelian.kembalian"  class="form-control" style="height: 40px; width:90%; font-size:25px;"></money> 
          </div> 
          <div class="col-md-6  col-xs-6"> 
            <h5>Kredit</h5> 
            <money v-bind="separator"  name="kredit"  id="kredit" autocomplete="off" readonly="" v-model="inputPembayaranPembelian.kredit"  class="form-control" style="height: 40px; width:90%; font-size:25px;"></money> 
          </div> 

        </div> 

        <div class="row"> 
          <div class="col-md-6  col-xs-6"> 
            <h5>Jatuh Tempo</h5> 
            <datepicker :input-class="'form-control'" placeholder="Tanggal Lahir" v-model="inputPembayaranPembelian.jatuh_tempo" name="uniquename" v-bind:id="'jatuh_tempo'"  ></datepicker>
          </div> 
          <div class="col-md-6  col-xs-6"> 
            <h5>Keterangan</h5> 
            <textarea class="form-control" name="keterangan" id="keterangan" placeholder="...." rows="1"></textarea> 
          </div> 
        </div> 
        <span> 
          <input type="hidden"  name="status_pembelian" id="status_pembelian" v-model="inputPembayaranPembelian.status_pembelian">
          <input type="hidden" name="ppn" id="ppn" v-model="inputPembayaranPembelian.ppn">
          <input type="hidden" name="potongan" id="potongan" v-model="inputPembayaranPembelian.potongan" >
        </span> 
      </div> 
      <div class="modal-footer">  
        <button type="submit"  id="btn-tunai-pembelian" class="btn btn-success"  style="display: none;"><i class="material-icons">credit_card</i> Tunai</button> 
        <button type="submit"  id="btn-hutang-pembelian" class="btn btn-success"  ><i class="material-icons">credit_card</i> Hutang</button> 
        <button type="button" class="btn btn-default" data-dismiss="modal" v-on:click="btnCloseModal()" id="btnCloseModal"><i class="material-icons">close</i> Close</button> 
      </div> 
      </form>
    </div>       
  </div> 
</div> 
<!-- / MODAL TOMBOL SELESAI --> 

        <div class="card" style="margin-bottom: 1px; margin-top: 1px;" ><!-- CARD --> 
          <div class="card-content"> 
            <h4 class="card-title" style="margin-bottom: 1px; margin-top: 1px;"> Edit Pembelian {{ inputTbsPembelian.no_faktur }}</h4> 
            <div class="row" style="margin-bottom: 1px; margin-top: 1px;"> 

              <div class="col-md-3">
                <div class="card card-produk" style="margin-bottom: 1px; margin-top: 1px;">
                  <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                   
                    <selectize-component v-model="inputTbsPembelian.produk" :settings="placeholder_produk" id="produk" ref='produk' v-shortkey.focus="['f1']" > 
                      <option v-for="produks, index in produk" v-bind:value="produks.produk">{{ produks.nama_produk }}</option>
                      </selectize-component>
                      </div><!--/COL MD  3 --> 
                      <span v-if="errors.produk" id="produk_error" class="label label-danger">{{ errors.produk[0] }}</span>

                <span style="display: none;">
                <input class="form-control" type="text"  v-model="inputTbsPembelian.jumlah_produk"  name="jumlah_produk" id="jumlah_produk">
                <input class="form-control" type="text"  v-model="inputTbsPembelian.harga_produk"  name="harga_produk" id="harga_produk">
                <input class="form-control" type="text"  v-model="inputTbsPembelian.id_produk_tbs"  name="id_produk_tbs" id="id_produk_tbs">
                <input class="form-control" type="text"  v-model="inputTbsPembelian.no_faktur"   name="no_faktur" id="no_faktur">
                </span>
            </div>
          </div>
          </div>

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
                  <th>Jumlah</th>
                  <th>Harga</th>
                  <th>Potongan</th>
                  <th>Pajak</th>
                  <th>Subtotal</th>
                  <th>Hapus</th>
                </tr>
              </thead>
              <tbody v-if="tbs_pembelians.length > 0 && loading == false"  class="data-ada">
                <tr v-for="tbs_pembelian, index in tbs_pembelians" >

                  <td>{{ tbs_pembelian.kode_produk }} - {{ tbs_pembelian.nama_produk }}</td>
                  <td>
                    <a href="#create-pembelian" v-bind:id="'edit-' + tbs_pembelian.id_tbs_pembelian" v-on:click="editEntryJumlah(tbs_pembelian.id_tbs_pembelian, index,tbs_pembelian.nama_produk)"><p align='right'>{{ tbs_pembelian.jumlah_produk_pemisah }}</p>
                    </a>
                  </td>
                  <td>
                    <a href="#create-pembelian" v-bind:id="'edit-' + tbs_pembelian.id_tbs_pembelian" v-on:click="editEntryHarga(tbs_pembelian.id_tbs_pembelian, index,tbs_pembelian.nama_produk)" ><p align='right'>{{ tbs_pembelian.harga_pemisah }}</p>
                    </a>
                  </td>
                  <td>
                    <a href="#create-pembelian" v-bind:id="'edit-' + tbs_pembelian.id_tbs_pembelian" v-on:click="editEntryPotongan(tbs_pembelian.id_tbs_pembelian, index,tbs_pembelian.nama_produk,tbs_pembelian.jumlah_produk,tbs_pembelian.harga_produk)"
                    ><p align='right'>{{ Math.round(tbs_pembelian.potongan,2) }} | {{ Math.round(tbs_pembelian.potongan_persen,2) }} %</p>
                    </a>
                  </td>
                  <td>
                    <a href="#create-pembelian" v-bind:id="'edit-' + tbs_pembelian.id_tbs_pembelian" v-on:click="editEntryTax(tbs_pembelian.id_tbs_pembelian, index,tbs_pembelian.nama_produk,tbs_pembelian.jumlah_produk,tbs_pembelian.harga_produk,tbs_pembelian.potongan,tbs_pembelian.ppn_produk)" ><p align='right'>{{ Math.round(tbs_pembelian.tax,2) }} | {{ Math.round(tbs_pembelian.tax_persen, 2) }} %</p>
                    </a>
                  </td>
                  <td><p id="table-subtotal" align="right">{{ tbs_pembelian.subtotal_tbs }}</p></td>
                  <td> 
                    <a href="#create-pembelian" class="btn btn-xs btn-danger" v-bind:id="'delete-' + tbs_pembelian.id_tbs_pembelian" v-on:click="deleteEntry(tbs_pembelian.id_tbs_pembelian, index,tbs_pembelian.nama_produk)">Delete</a>
                  </td>
                </tr>
              </tbody>          
              <tbody class="data-tidak-ada"  v-else-if="tbs_pembelians.length == 0 && loading == false" >
                <tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
              </tbody>
            </table>  

            <vue-simple-spinner v-if="loading"></vue-simple-spinner>

            <div align="right"><pagination :data="tbsPembelianData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

          </div>

          <p style="color: red; font-style: italic;">*Note : Klik Kolom Jumlah, Harga, Potongan & Tax Untuk Mengubah Nilai.</p> 
      </div><!-- COL SM 8 --> 

      <div class="col-md-4"><!-- COL SM 4 --> 
        <div class="card card-stats"><!-- CARD --> 

          <div class="card-content"> 
            <div class="row"> 
              <div class="col-md-6 col-xs-12"> 
                  <h4>Suplier</h4> 
                  <selectize-component v-model="inputPembayaranPembelian.suplier" :settings="placeholder_suplier" id="suplier" name="suplier" ref='suplier'> 
                      <option v-for="supliers, index in suplier" v-bind:value="supliers.id">{{ supliers.nama_suplier }}</option>
                  </selectize-component>
              </div> 
              <div class="col-md-6 col-xs-12"> 
                    <h4>Kas</h4> 
                      <div v-if="tbsPembelianData.kas_default == 0">
                      <selectize-component v-model="inputPembayaranPembelian.cara_bayar" :settings="placeholder_cara_bayar" id="cara_bayar" name="cara_bayar" ref='cara_bayar'> 
                      <option v-for="cara_bayars, index in cara_bayar" v-bind:value="cara_bayars.id" >{{ cara_bayars.nama_kas }}</option>
                      </selectize-component>
                      <br>
                      <span class="label label-danger"><router-link :to="{name: 'indexKas'}" class="menu-nav">Tambah Kas Disini</router-link></span> 
                      </div>
                      <div v-else>
                      <selectize-component v-model="inputPembayaranPembelian.cara_bayar" :settings="placeholder_cara_bayar" id="cara_bayar" name="cara_bayar" ref='cara_bayar'> 
                      <option v-for="cara_bayars, index in cara_bayar" v-bind:value="cara_bayars.id">{{ cara_bayars.nama_kas }}</option>
                      </selectize-component>
                      </div>
                </div> 
              </div> 
          </div> 
          <div class="card-footer">
                <button type="button" class="btn btn-success" id="bayar"  ><i class="material-icons">payment</i>Bayar(F2)</button>
                <button type="submit" class="btn btn-danger" id="btnBatal" ><i class="material-icons">cancel</i> Batal(F3) </button>
            </div>
        </div>             
      </div><!-- COL SM 4 --> 
    </div><!-- ROW --> 
  </div>
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
      suplier: [],
      cara_bayar: [],
      tbs_pembelians: [],
      tbsPembelianData : {},
      url : window.location.origin+(window.location.pathname).replace("dashboard", "pembelian"),
      url_produk : window.location.origin+(window.location.pathname).replace("dashboard", "produk"),
      url_kas : window.location.origin+(window.location.pathname).replace("dashboard", "penjualan"),
      url_cek_total_kas : window.location.origin+(window.location.pathname).replace("dashboard", ""),
      url_edit : window.location.origin+(window.location.pathname).replace("dashboard", "edit-pembelian"), 
      inputTbsPembelian: {
        produk : '',
        jumlah_produk : '',
        harga_produk : '',
        id_tbs : '',
        no_faktur : ''
      },
      inputPembayaranPembelian:{
        potongan_persen: 0.00,
        potongan_faktur: 0.00,
        subtotal: 0.00,
        pembayaran: 0.00,
        total_akhir: 0.00,
        kembalian: 0.00,
        kredit: 0.00,
        jatuh_tempo: '',
        keterangan: '',
        subtotal_number_format:0.00,  
        suplier: '',
        cara_bayar: '',
        status_pembelian: '',
        ppn: '',
        potongan: 0.00,
        
      },
      placeholder_produk: {
        placeholder: '--PILIH PRODUK--'
      },
      placeholder_suplier: {
        placeholder: '--PILIH SUPPLIER--'
      },
      placeholder_cara_bayar: {
        placeholder: '--PILIH CARA BAYAR--'
      },
      separator: {
              decimal: ',',
              thousands: '.',
              prefix: '',
              suffix: '',
              precision: 2,
              masked: false /* doesn't work with directive */
          },
      pencarian: '',
      id_pembelian : 0,
      loading: true,
      seen : false,
    }
  },
  mounted() {
    var app = this;
    app.dataProduk();
    app.dataSuplier();
    app.dataCaraBayar();
    app.getResults();
    app.id_pembelian = app.$route.params.id;

  },
  watch: {
            pencarian: function (newQuestion) {
          this.getHasilPencarian();
          this.loading = true;  
        },
         'inputTbsPembelian.produk': function () {
        this.pilihProduk()
        },
  },
  methods: { 
       getResults(page) {
        var app = this; 
        var id = app.$route.params.id;
        if (typeof page === 'undefined') {
         page = 1;
       }
       axios.get(app.url+'/view-edit-tbs-pembelian/'+id+'?page='+page)
       .then(function (resp) {
         app.tbs_pembelians = resp.data.data;
         app.tbsPembelianData = resp.data;
         app.inputTbsPembelian.no_faktur = resp.data.no_faktur;
         app.inputPembayaranPembelian.subtotal = resp.data.subtotal;
         app.inputPembayaranPembelian.total_akhir = resp.data.subtotal;
         app.inputPembayaranPembelian.kredit = resp.data.subtotal;   
         app.loading = false;
         app.seen = true;

       })
       .catch(function (resp) {
         console.log(resp);
         app.loading = false;
         app.seen = true;
         alert("Tidak Dapat Memuat Pembelian");

       });
     },
    getHasilPencarian(page){
      var app = this;
      var id = app.$route.params.id;
      if (typeof page === 'undefined') {
       page = 1;
     }
     axios.get(app.url+'/pencarian-edit-tbs-pembelian/'+id+'?search='+app.pencarian+'&page='+page)
     .then(function (resp) {
       app.tbs_pembelians = resp.data.data;
       app.tbsPembelianData = resp.data;
       app.loading = false;
       app.seen = true;
     })
     .catch(function (resp) {
       console.log(resp);
       alert("Tidak Dapat Memuat Pembelian");
     });

   }, 
   getFakturPembelian(){
    var app = this;
    var id = app.$route.params.id;
    axios.get(app.url+'/ambil-faktur-pembelian/'+id).then(function (resp) {

      app.inputTbsPembelian.no_faktur = resp.data.no_faktur; 
      app.inputPembayaranPembelian.keterangan = resp.data.keterangan;        

    })
    .catch(function (resp) {

     app.loading = false;
     app.seen = true;
     alert("Tidak Bisa Memuat Pembelian");

   });

  },
 btnCloseModal(){
        $("#modal_selesai").hide(); 
  },
  closeModalX(){
        $("#modal_selesai").hide(); 
  }, 
  dataProduk() {
        var app = this;
        axios.get(app.url_produk+'/pilih-produk').then(function (resp) {
          app.produk = resp.data;
        })
        .catch(function (resp) {
          alert("Tidak Bisa Memuat Produk");
        });
      },//END FUNGSI UNTUK SELECTIZE PRODUK 
  dataSuplier() {
        var app = this;
        axios.get(app.url+'/pilih-suplier').then(function (resp) {
          app.suplier = resp.data;
        })
        .catch(function (resp) {
          alert("Tidak Bisa Memuat Suplier");
        });
      },//END FUNGSI UNTUK SELECTIZE SUPLIER 
  dataCaraBayar() {
        var app = this;
        axios.get(app.url_kas+'/pilih-kas').then(function (resp) {
            app.cara_bayar = resp.data;
            $.each(resp.data, function (i, item) {
                  if (resp.data[i].default_kas == 1) {
                      app.inputPembayaranPembelian.cara_bayar  = resp.data[i].id 
                  }
            });
        })
        .catch(function (resp) {
          alert("Tidak Bisa Memuat Kas");
        });
  },//END FUNGSI UNTUK SELECTIZE CARABAYAR 
  deleteEntry(id, index,nama_produk) { 
 
        var app = this; 
        app.$swal({ 
          text: "Anda Yakin Ingin Menghapus Produk "+nama_produk+ " ?", 
          buttons: true, 
          dangerMode: true, 
        }) 
        .then((willDelete) => { 
          if (willDelete) { 
            this.prosesDelete(id,nama_produk); 
          } else { 
            app.$swal.close(); 
          } 
        }); 
 
      },//END fungsi deleteEntry (alert konfirmasi hapus) 
      prosesDelete(id,nama_produk){ 
        var app = this; 
        app.loading = true; 
        axios.delete(app.url_edit+'/hapus-tbs-pembelian/'+id) 
        .then(function (resp) { 
          app.getResults(); 
          app.alert("Menghapus Produk "+nama_produk); 
          app.loading = false; 
          app.inputTbsPembelian.id_tbs = ''
        }) 
        .catch(function (resp) { 
          app.loading = false; 
        }); 
      },//END fungsi prosesDelete 
     pilihProduk() {
        if (this.inputTbsPembelian.produk == '') {
          this.$swal({
            text: "Silakan Pilih Produk Telebih dahulu!",
          });
        }
        else if(this.inputTbsPembelian.jumlah_produk == ''){

          var app = this;
          var produk = app.inputTbsPembelian.produk.split("|");
          var id_produk = produk[0]; 
          var nama_produk = produk[1];
          var harga_produk = produk[2]; 
          var jumlah = $("#jumlah_produk").val(); 
          this.isiJumlahProduk(id_produk,nama_produk,harga_produk);

        }
        else if (this.inputTbsPembelian.jumlah_produk == '' && this.inputTbsPembelian.produk == ''){

        }
      },//END FUNGSI pilihProduk
      isiJumlahProduk(id_produk,nama_produk,harga_produk){
        var app = this;   
        var no_faktur = app.inputTbsPembelian.no_faktur; 
        swal({
          title: titleCase(nama_produk),
          html:
            '<div class="col-sm-6  col-xs-6"><lable>Jumlah</lable><br><input type="number" id="swal-input1" class="swal2-input" autofocus></div>' +
            '<div class="col-sm-6  col-xs-6"><lable>Harga</lable><br><input type="number" id="swal-input2" class="swal2-input" value="'+harga_produk+'"></div>',
            allowEnterKey : false,
          showCloseButton: true, 
          showCancelButton: true,                        
          focusConfirm: false, 
          confirmButtonText:'<i class="fa fa-thumbs-o-up"></i> Submit', 
          confirmButtonAriaLabel: 'Thumbs up, great!', 
          cancelButtonText:'<i class="fa fa-thumbs-o-down"> Batal', 
          closeOnConfirm: false, 
          cancelButtonAriaLabel: 'Thumbs down', 
          preConfirm: function () { 
            return new Promise(function (resolve) { 
              resolve([ 
                $('#swal-input1').val(), 
                $('#swal-input2').val() 
                ]) 
            }) 
          }
        }).then(function (result) { 

          if (result[0] == '' || result[0] == 0) { 

            swal('Oops...', 'Jumlah Produk Tidak Boleh 0 atau Kosong !', 'error'); 
            return false; 
          }else if (result[1] == '' || result[1] == 0) { 

            swal('Oops...', 'Harga Produk Tidak Boleh 0 atau Kosong !', 'error'); 
            return false; 
          }
          else if (result[1] != harga_produk) { 
                    app.$swal({
                      title: "Konfirmasi",
                      text: "Anda Yakin Ingin Merubah Harga Beli Produk "+titleCase(nama_produk)+ "?",
                      icon: "warning",
                      buttons: true,
                      dangerMode: true,
                    })
                    .then((willDelete) => {
                      if (willDelete) {
                        $("#id_produk_tbs").val(id_produk); 
                        $("#jumlah_produk").val(result[0]); 
                        $("#harga_produk").val(result[1]);
                        var jumlah_produk = result[0];
                        var harga_produk = result[1];

                        app.submitJumlahProduk(id_produk,jumlah_produk,harga_produk,nama_produk,no_faktur);

                      } else {
                        app.$swal.close();
                      }
                    });
              }else{ 
                $("#id_produk_tbs").val(id_produk); 
                $("#jumlah_produk").val(result[0]); 
                $("#harga_produk").val(harga_produk); 

                 var jumlah_produk = result[0];
                 app.submitJumlahProduk(id_produk,jumlah_produk,harga_produk,nama_produk,no_faktur);

              } 
            });
    },//END fungsi isiJumlahProduk 
     submitJumlahProduk(id_produk,jumlah_produk,harga_produk,nama_produk,no_faktur){
                      var app = this;
                      var id_pembelian = app.id_pembelian;
                      app.loading = true;
                      axios.get(app.url_edit+'/proses-tambah-tbs-pembelian?id_produk_tbs='+id_produk+'&jumlah_produk='+jumlah_produk+'&harga_produk='+harga_produk+'&no_faktur='+no_faktur)
                        .then(function (resp) {
                            if (resp.data == 0) {
                                  swal({
                                      title: "Peringatan",
                                      text:"Produk "+titleCase(nama_produk)+" Sudah Ada, Silakan Pilih Produk Lain !",
                                     });
                                  app.loading = false;
                              }
                              else{
                                      app.alert("Menambahkan Produk "+titleCase(nama_produk));
                                      app.loading = false;
                                      app.getResults();
                                      app.$router.replace('/edit-pembelian/'+id_pembelian);
                              }
              });
    },//END PROSES TAMBAH PRODUK TBS
    alert(pesan) {
        this.$swal({
          title: "Berhasil ",
          text: pesan,
          icon: "success",
        });
      },//alert untuk berhasil proses crud
}

}
</script>