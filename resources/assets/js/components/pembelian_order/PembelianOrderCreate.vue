  <style scoped>
    .modal {
      overflow-y:auto;
    }
    .pencarian {
      color: red; 
      float: right;
    }
    .form-pembelian{
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
    .btn-icon{
      border-radius: 1px solid;
      padding: 10px 10px;
    }
    .card-pembayaran{
      background-color:#82B1FF;
    }
    .btn-footer{
      padding: 11px 10px;
    }
  </style>

  <template>

    <div class="row"> 

      <div class="col-md-12"> 

        <ul class="breadcrumb"> 
          <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li> 
          <li class="active">Order Pembelian</li> 
        </ul> 


        <!-- MODAL SUPLIER -->
        <div class="modal" id="modal_tambah_suplier" role="dialog" data-backdrop=""> 
          <div class="modal-dialog"> 
            <!-- Modal content--> 
            <div class="modal-content"> 
              <div class="modal-header"> 
                <button type="button" class="close"  v-on:click="closeModalX()" v-shortkey.push="['esc']" @shortkey="closeModalX()"> &times;</button> 
                <h4 class="modal-title"> 
                  <div class="alert-icon"> 
                    <b>Silahkan Isi Supplier!</b> 
                  </div> 
                </h4> 
              </div> 
              <div class="modal-body">
                <form v-on:submit.prevent="saveFormSupplier()" class="form-horizontal"> 
                  <div class="form-group">
                    <label for="nama_suplier" class="col-md-3 control-label">Nama Supplier</label>
                    <div class="col-md-9">
                      <input class="form-control" autocomplete="off" placeholder="Nama Supplier" v-model="tambahSuplier.nama_suplier" type="text" name="nama_suplier" id="nama_suplier"  autofocus="">
                      <span v-if="errors.nama_suplier" id="nama_suplier_error" class="label label-danger">{{ errors.nama_suplier[0] }}</span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="alamat" class="col-md-3 control-label">Alamat</label>
                    <div class="col-md-9">
                      <input class="form-control" autocomplete="off" placeholder="Alamat Supplier" v-model="tambahSuplier.alamat" type="text" name="alamat" id="alamat"  autofocus="">
                      <span v-if="errors.alamat" id="alamat_error" class="label label-danger">{{ errors.alamat[0] }}</span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="no_telp" class="col-md-3 control-label">No. Telpon</label>
                    <div class="col-md-9">
                      <input class="form-control" autocomplete="off" placeholder="No. Telpon Supplier" v-model="tambahSuplier.no_telp" type="text" name="no_telp" id="no_telp"  autofocus="">
                      <span v-if="errors.no_telp" id="no_telp_error" class="label label-danger">{{ errors.no_telp[0] }}</span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="contact_person" class="col-md-3 control-label">Contact Person</label>
                    <div class="col-md-9">
                      <input class="form-control" autocomplete="off" placeholder="Contact Person Supplier" v-model="tambahSuplier.contact_person" type="text" name="contact_person" id="contact_person"  autofocus="">
                      <span v-if="errors.contact_person" id="contact_person_error" class="label label-danger">{{ errors.contact_person[0] }}</span>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-md-10 col-md-offset-3">
                      <button class="btn btn-primary" id="btnSimpanSuplier" type="submit"><i class="material-icons">send</i> Submit</button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">  
              </div> 
            </div>       
          </div> 
        </div> 
        <!-- / MODAL SUPLIER --> 


        <!-- MODAL INPUT PRODUK -->
        <div class="modal" id="modalJumlahProduk" role="dialog" tabindex="-1"  aria-labelledby="myModalLabel" aria-hidden="true" >
          <div class="modal-dialog modal-medium">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close"  v-on:click="closeModalJumlahProduk()" v-shortkey.push="['f9']" @shortkey="closeModalJumlahProduk()"> &times;</button> 
              </div>

              <form class="form-horizontal" v-on:submit.prevent="submitJumlahProduk(inputTbsPembelianOrder.id_produk,inputTbsPembelianOrder.jumlah_produk,inputTbsPembelianOrder.harga_produk,inputTbsPembelianOrder.nama_produk,inputTbsPembelianOrder.satuan_produk)"> 
                <div class="modal-body">
                  <h3 class="text-center"><b>{{inputTbsPembelianOrder.nama_produk}}</b></h3>

                  <div class="form-group">
                    <div class="col-md-4">
                      <input class="form-control" type="number" v-model="inputTbsPembelianOrder.jumlah_produk" placeholder="Isi Jumlah Produk" name="jumlah_produk" id="jumlah_produk" ref="jumlah_produk" autocomplete="off" step="0.01">
                    </div>
                    <div class="col-md-4">
                      <selectize-component v-model="inputTbsPembelianOrder.satuan_produk" :settings="placeholder_satuan" id="satuan" name="satuan" ref='satuan'> 
                        <option v-for="satuans, index in satuan" v-bind:value="satuans.satuan" class="pull-left">{{ satuans.nama_satuan }}</option>
                      </selectize-component>
                    </div>
                    <div class="col-md-4">
                      <money class="form-control" v-model="inputTbsPembelianOrder.harga_produk" v-bind="pemisahTitik" placeholder="Isi Harga Produk" name="harga_produk" id="harga_produk" ref="harga_produk" autocomplete="off" ></money>
                    </div>
                  </div>

                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-simple" v-on:click="closeModalJumlahProduk()" v-shortkey.push="['f9']" @shortkey="closeModalJumlahProduk()">Close(F9)</button>
                  <button type="submit" class="btn btn-info btn-lg">Tambah</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!--    MODAL INPUT PRODUK -->

        <!-- small modal -->
        <div class="modal" id="modalEditSatuan" role="dialog" tabindex="-1"  aria-labelledby="myModalLabel" aria-hidden="true" >
          <div class="modal-dialog modal-medium">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close"  v-on:click="closeModalJumlahProduk()" v-shortkey.push="['f9']" @shortkey="closeModalJumlahProduk()"> &times;</button> 
              </div>

              <form class="form-horizontal" v-on:submit.prevent="subtmitEditSatuan(inputTbsPembelianOrder.id_produk, inputTbsPembelianOrder.id_tbs, inputTbsPembelianOrder.subtotal)"> 
                <div class="modal-body">
                  <h3 class="text-center"><b>{{inputTbsPembelianOrder.nama_produk | capitalize}}</b></h3>

                  <div class="form-group">

                    <div class="col-md-12 col-xs-12 hurufBesar">
                      <selectize-component v-model="inputTbsPembelianOrder.satuan_produk" :settings="placeholder_satuan" id="satuan" name="satuan" ref='satuan'> 
                        <option v-for="satuans, index in satuan" v-bind:value="satuans.satuan" class="pull-left">{{ satuans.nama_satuan }}</option>
                      </selectize-component>
                    </div>
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-simple" v-on:click="closeModalJumlahProduk()" v-shortkey.push="['f9']" @shortkey="closeModalJumlahProduk()">Close(F9)</button>
                  <button type="submit" class="btn btn-info">Tambah</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!--    end small modal -->

        <!-- CARD --> 
        <div class="card" style="margin-bottom: 1px; margin-top: 1px;">

          <div class="card-content"> 

            <h4 class="card-title" style="margin-bottom: 1px; margin-top: 1px;"> Order Pembelian </h4> 
            <div class="row" style="margin-bottom: 1px; margin-top: 1px;"> 

              <div class="col-md-3">
                <div class="card card-produk" style="margin-bottom: 1px; margin-top: 1px;">

                  <div class="form-group" style="margin-right: 10px; margin-left: 10px;">

                    <selectize-component v-model="inputTbsPembelianOrder.produk" :settings="placeholder_produk" id="produk" ref='produk'  > 
                      <option v-for="produks, index in produk" v-bind:value="produks.produk">{{produks.barcode}} || {{produks.kode_produk}} || {{ produks.nama_produk }}</option>
                    </selectize-component>

                  </div>
                  
                  <span v-if="errors.produk" id="produk_error" class="label label-danger">{{ errors.produk[0] }}</span>

                  <span style="display: none;">
                    <input class="form-control" type="text"  v-model="inputTbsPembelianOrder.jumlah_produk"  name="jumlah_produk" id="jumlah_produk"  v-shortkey="['f6']" @shortkey="openSelectizeKas()">
                    <input class="form-control" type="text"  v-model="inputTbsPembelianOrder.harga_produk"  name="harga_produk" id="harga_produk" v-shortkey="['f4']" @shortkey="openSelectizeSuplier()">
                    <input class="form-control" type="text"  v-model="inputTbsPembelianOrder.id_produk_tbs"  name="id_produk_tbs" id="id_produk_tbs" v-shortkey="['f1']" @shortkey="openSelectizeProduk()">
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
                        <th  >Produk</th>
                        <th  style="text-align:right;">Jumlah</th>
                        <th  style="text-align:center;">Satuan</th>
                        <th  style="text-align:right;">Harga</th>
                        <th  style="text-align:right;">Potongan</th>
                        <th  style="text-align:right;">Pajak</th>
                        <th  style="text-align:right;">Subtotal</th>
                        <th  style="text-align:right;">Hapus</th>
                      </tr>
                    </thead>
                    <tbody v-if="tbs_pembelian_orders.length > 0 && loading == false"  class="data-ada">
                      <tr v-for="tbs_pembelian, index in tbs_pembelian_orders" >

                        <td>{{ tbs_pembelian.data_tbs.kode_barang }} - {{ tbs_pembelian.data_tbs.nama_barang | capitalize }}</td>
                        <td>
                          <a href="#create-order-pembelian" v-bind:id="'edit-' + tbs_pembelian.data_tbs.id_tbs_pembelian_order" v-on:click="editEntryJumlah(tbs_pembelian.data_tbs.id_tbs_pembelian_order, index,tbs_pembelian.data_tbs.nama_barang,tbs_pembelian.data_tbs.subtotal)"><p align='right'>{{ tbs_pembelian.data_tbs.jumlah_produk | pemisahTitik }}</p>
                          </a>
                        </td>

                        <td align="center">
                          <a href="#create-order-pembelian" v-bind:id="'edit-' + tbs_pembelian.data_tbs.id_tbs_pembelian_order" v-bind:class="'hurufBesar satuan-' + tbs_pembelian.data_tbs.id_produk" v-bind:data-satuan="''+tbs_pembelian.data_tbs.satuan_id" v-on:click="editSatuanEntry(tbs_pembelian.data_tbs.id_tbs_pembelian_order, index,tbs_pembelian.data_tbs.nama_barang,tbs_pembelian.data_tbs.subtotal, tbs_pembelian.data_tbs.id_produk)">{{ tbs_pembelian.data_tbs.nama_satuan }}</a>
                        </td>

                        <td>
                          <a href="#create-order-pembelian" v-bind:id="'edit-' + tbs_pembelian.data_tbs.id_tbs_pembelian_order" v-on:click="editEntryHarga(tbs_pembelian.data_tbs.id_tbs_pembelian_order, index,tbs_pembelian.data_tbs.nama_barang,tbs_pembelian.data_tbs.subtotal)" v-bind:class="'harga-' + tbs_pembelian.data_tbs.id_produk" v-bind:data-harga="''+tbs_pembelian.data_tbs.harga_produk"><p align='right'>{{ tbs_pembelian.data_tbs.harga_produk | pemisahTitik }}</p>
                          </a>
                        </td>
                        <td>
                          <a href="#create-order-pembelian" v-bind:id="'edit-' + tbs_pembelian.data_tbs.id_tbs_pembelian_order" v-on:click="editEntryPotongan(tbs_pembelian.data_tbs.id_tbs_pembelian_order, index,tbs_pembelian.data_tbs.nama_barang,tbs_pembelian.data_tbs.jumlah_produk,tbs_pembelian.data_tbs.harga_produk,tbs_pembelian.data_tbs.subtotal)"
                          ><p align='right'>{{ tbs_pembelian.data_tbs.potongan | pemisahTitik }} | {{ Math.round(tbs_pembelian.potongan_persen,2) }} %</p>
                        </a>
                      </td>
                      <td>
                        <a href="#create-order-pembelian" v-bind:id="'edit-' + tbs_pembelian.data_tbs.id_tbs_pembelian_order" v-on:click="editEntryTax(tbs_pembelian.data_tbs.id_tbs_pembelian_order, index,tbs_pembelian.data_tbs.nama_barang,tbs_pembelian.data_tbs.jumlah_produk,tbs_pembelian.data_tbs.harga_produk,tbs_pembelian.data_tbs.potongan,tbs_pembelian.ppn_produk,tbs_pembelian.data_tbs.subtotal)" ><p align='right'>{{ tbs_pembelian.data_tbs.tax | pemisahTitik}} | {{ Math.round(tbs_pembelian.tax_persen, 2) }} %</p>
                        </a>
                      </td>
                      <td><p id="table-subtotal" align="right">{{ tbs_pembelian.data_tbs.subtotal | pemisahTitik }}</p></td>
                      <td style="text-align:right;"> 
                        <a href="#create-order-pembelian" class="btn btn-xs btn-danger" v-bind:id="'delete-' + tbs_pembelian.data_tbs.id_tbs_pembelian_order" v-on:click="deleteEntry(tbs_pembelian.data_tbs.id_tbs_pembelian_order, index,tbs_pembelian.data_tbs.nama_barang,tbs_pembelian.data_tbs.subtotal)">Delete</a>
                      </td>
                    </tr>
                  </tbody>          
                  <tbody class="data-tidak-ada"  v-else-if="tbs_pembelian_orders.length == 0 && loading == false" >
                    <tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
                  </tbody>
                </table>  

                <vue-simple-spinner v-if="loading"></vue-simple-spinner>

                <div align="right"><pagination :data="tbsPembelianData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

              </div>

              <p style="color: red; font-style: italic;">*Note : Klik Kolom Jumlah, Harga, Potongan & Tax Untuk Mengubah Nilai.</p>
            </div><!-- COL SM 8 --> 

            <div class="col-md-3"><!-- COL SM 3 --> 

              <div class="card card-stats">
                <div class="card-header" data-background-color="blue">
                  <i class="material-icons">shopping_cart</i>
                </div>
                <div class="card-content">

                  <p class="category"><font style="font-size:20px;">Subtotal</font></p>
                  <h3 class="card-title">
                    <b>
                      <font style="font-size:32px;">{{ inputPembayaranPembelianOrder.subtotal | pemisahTitik }}</font>
                    </b>
                  </h3>
                  
                  <div class="col-md-12 col-xs-12" style="padding-left:0px;padding-top:25px;padding-right: 0px">
                    <p class="category"><font style="font-size:20px;">Supplier</font></p>
                  </div>
                  <div class="col-md-11 col-xs-11" style="padding-left:0px;padding-top:15px;">
                    <b>                      
                      <selectize-component v-model="inputPembayaranPembelianOrder.suplier" :settings="placeholder_suplier" id="suplier" name="suplier" ref='suplier'> 
                        <option v-for="supliers, index in suplier" v-bind:value="supliers.id">{{ supliers.nama_suplier }}</option>
                      </selectize-component>
                    </b>
                  </div>

                  <div class="col-md-1 col-xs-1" style="padding-left:0px;padding-top:15px;">
                    <div class="row" style="margin-top:-10px">
                      <button class="btn btn-primary btn-icon waves-effect waves-light" v-on:click="tambahSupplier()" type="button"> <i class="material-icons" >add</i> </button>
                    </div>
                  </div>

                  <p class="category"><font style="font-size:20px; padding-left:0px;padding-top:25px;padding-right: 0px">Keterangan</font></p>
                  <textarea class="form-control" v-model="inputPembayaranPembelianOrder.keterangan"  name="keterangan" id="keterangan" placeholder="Keterangan .." rows="1">                    
                  </textarea>

                </div>

                <div class="card-footer">
                  <div class="row">
                    <div class="col-md-5 col-xs-5"> 
                      <button type="button" class="btn btn-success btn-footer" id="bayar" v-on:click="selesaiPembelianOrder()" v-shortkey.push="['f2']" @shortkey="selesaiPembelianOrder()"><font style="font-size:15px;">Bayar(F2)</font></button>
                    </div>
                    <div class="col-md-5 col-xs-5">
                      <button type="submit" class="btn btn-danger btn-footer" id="btnBatal" v-on:click="batalPembelian()" v-shortkey.push="['f3']" @shortkey="batalPembelian()"> <font style="font-size:15px;">Batal(F3) </font></button>
                    </div>
                  </div>
                </div>
              </div>

            </div><!-- COL SM 3 -->
          </div><!-- ROW --> 

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
        suplier: [],
        satuan: [],
        tbs_pembelian_orders: [],
        tbsPembelianData : {},
        url : window.location.origin+(window.location.pathname).replace("dashboard", "pembelian-order"),
        url_produk : window.location.origin+(window.location.pathname).replace("dashboard", "produk"),
        url_cek_total_kas : window.location.origin+(window.location.pathname).replace("dashboard", ""),
        url_suplier : window.location.origin+(window.location.pathname).replace("dashboard", "suplier"),
        url_satuan : window.location.origin+(window.location.pathname).replace("dashboard", "pembelian-order/satuan-konversi"),
        inputTbsPembelianOrder: {
          nama_produk : '',
          produk : '',
          id_produk : '',
          jumlah_produk : '',
          harga_produk : '',
          id_tbs : '',
          satuan_produk: ''
        },
        inputPembayaranPembelianOrder:{
          subtotal: 0,
          suplier: '',
          keterangan: ''
        },
        tambahSuplier: {
          nama_suplier : '',
          alamat : '',
          no_telp : '',
          contact_person : '',
        },
        placeholder_produk: {
          placeholder: '--PILIH PRODUK (F1)--',
          sortField: 'text',
          openOnFocus : true
        },
        placeholder_satuan: {
          placeholder: '--PILIH SATUAN--',
          sortField: 'text',
          openOnFocus : true,
        },
        placeholder_suplier: {
          placeholder: '--PILIH SUPPLIER (F4)--',
          sortField: 'text',
          openOnFocus : true
        },
        pemisahTitik: {
          decimal: ',',
          thousands: '.',
          prefix: '',
          suffix: '',
          precision: 0,
          masked: false /* doesn't work with directive */
        },      
        disabled: {
  to: new Date(), // Disable all dates up to specific date
},
pencarian: '',
loading: true,
seen : false,

}

},
mounted() {
  var app = this;
  app.$store.dispatch('LOAD_PRODUK_LIST')
  app.dataSuplier();
  app.getResults();
},
filters: {
  capitalize: function (value) {
    return value.replace(/(^|\s)\S/g, l => l.toUpperCase())
  },
  pemisahTitik: function (value) {
    var angka = [value];
    var numberFormat = new Intl.NumberFormat('es-ES');
    var formatted = angka.map(numberFormat.format);
    return formatted.join('; ');
  }
},
computed : mapState ({    
  produk(){
    return this.$store.getters.produkStok
  }
}),
watch: {
  // whenever question changes, this function will run
  pencarian: function (newQuestion) {
    this.getHasilPencarian();
    this.loading = true;  
  },
  'inputTbsPembelianOrder.produk': function (newQuestion) {
    this.pilihProduk();  
  },
  'inputTbsPembelianOrder.satuan_produk':function(){
    this.hitungHargaKonversi()
  }

},
methods: {
  openSelectizeProduk(){      
    this.$refs.produk.$el.selectize.focus();
  },
  openSelectizeSuplier(){      
    this.$refs.suplier.$el.selectize.focus();
  },
  getResults(page) {
    var app = this; 
    if (typeof page === 'undefined') {
      page = 1;
    }
    axios.get(app.url+'/view-tbs-pembelian?page='+page)
    .then(function (resp) {
      console.log(resp.data.data)
      app.tbs_pembelian_orders = resp.data.data;
      app.tbsPembelianData = resp.data;       
      app.loading = false;
      app.seen = true;
      app.openSelectizeProduk();
      app.dataSuplier();

      if (app.inputPembayaranPembelianOrder.subtotal == 0) { 

        $.each(resp.data.data, function (i, item) {
          app.inputPembayaranPembelianOrder.subtotal += parseFloat(resp.data.data[i].data_tbs.subtotal)
        });
      }

    })
    .catch(function (resp) {
      console.log(resp);
      app.loading = false;
      app.seen = true;
      alert("Tidak Dapat Memuat Pembelian");
    });
  },//END FUNGSI UNTUK PAGINATION TAMPILAN AWAL / DOCUMENT READY 
  getSatuan(id_produk){
    var app = this;
    var satuan_tbs = $(".satuan-"+id_produk).attr("data-satuan");

    axios.get(app.url_satuan+'/'+id_produk)
    .then(function (resp) {

      app.satuan = resp.data;
      if (typeof satuan_tbs == "undefined") {

        $.each(resp.data, function (i, item) {
          if (resp.data[i].id === resp.data[i].satuan_dasar) {
            app.inputTbsPembelianOrder.satuan_produk = resp.data[i].satuan;
          }
        });

      }else{

        $.each(resp.data, function (i, item) {
          if (resp.data[i].id === parseInt(satuan_tbs)) {
            app.inputTbsPembelianOrder.satuan_produk = resp.data[i].satuan;
          }
        });

      }
    })
    .catch(function (resp) {
      console.log(resp);
      alert("Tidak Dapat Memuat Satuan Produk");
    });
  },    
  getHasilPencarian(page){
    var app = this;
    if (typeof page === 'undefined') {
      page = 1;
    }
    axios.get(app.url+'/pencarian-tbs-pembelian?search='+app.pencarian+'&page='+page)
    .then(function (resp) {
      app.tbs_pembelian_orders = resp.data.data;
      app.tbsPembelianData = resp.data;
      app.loading = false;
      app.seen = true;
    })
    .catch(function (resp) {
      console.log(resp);
      alert("Tidak Dapat Memuat Pembelian");
    });
  }, //END FUNGSI UNTUK PAGINATION SEARCH     
  dataSuplier() {
    var app = this;
    axios.get(app.url+'/pilih-suplier').then(function (resp) {
      app.suplier = resp.data;
      $.each(resp.data, function (i, item) {
        if (resp.data[i].nama_suplier == "UMUM") {
          app.inputPembayaranPembelianOrder.suplier  = resp.data[i].id 
        }
      });
    })
    .catch(function (resp) {
      alert("Tidak Bisa Memuat Suplier");
    });
  },//END FUNGSI UNTUK SELECTIZE SUPLIER 
  tambahSupplier(){
    $("#modal_tambah_suplier").show();
    this.$refs.nama_suplier.$el.focus();
  },
  saveFormSupplier() {
    var app = this;
    var newsuplier = app.tambahSuplier;
    axios.post(app.url_suplier, newsuplier)
    .then(function (resp) {
      app.message = 'Menambah Suplier '+ app.tambahSuplier.nama_suplier;
      app.alert(app.message);
      app.tambahSuplier.nama_suplier = '';
      app.tambahSuplier.alamat = '';
      app.tambahSuplier.no_telp = '';
      app.tambahSuplier.contact_person = '';
      app.errors = '';
      app.dataSuplier();
      $("#modal_tambah_suplier").hide();
    })
    .catch(function (resp) {
      app.success = false;
      app.errors = resp.response.data.errors;
    });
  },
  alert(pesan) {
    this.$swal({
      title: "Berhasil ",
      text: pesan,
      icon: "success",
      buttons: false,
      timer: 1000,
    });
  },//alert untuk berhasil proses crud
  deleteEntry(id, index,nama_produk,subtotal_lama) {

    var app = this;
    app.$swal({
      text: "Anda Yakin Ingin Menghapus Produk "+titleCase(nama_produk)+ " ?",
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

  },//END fungsi deleteEntry (alert konfirmasi hapus)
  prosesDelete(id,nama_produk,subtotal_lama){

    var app = this;
    app.loading = true;
    axios.delete(app.url+'/hapus-tbs-pembelian/'+id)
    .then(function (resp) {
      app.getResults();

      var subtotal = parseFloat(app.inputPembayaranPembelianOrder.subtotal) - parseFloat(resp.data.subtotal)
      app.inputPembayaranPembelianOrder.subtotal = subtotal                       
      
      app.alert("Menghapus Produk "+nama_produk);
      app.loading = false;
      app.inputTbsPembelianOrder.id_tbs = ''
    })
    .catch(function (resp) {

      app.loading = false;
      alert("Tidak dapat Menghapus Produk "+nama_produk);
    });
  },//END fungsi prosesDelete
  pilihProduk() {
    if (this.inputTbsPembelianOrder.produk != '') {
      var app = this;
      var produk = app.inputTbsPembelianOrder.produk.split("|");
      var id_produk = produk[0]; 
      var nama_produk = produk[1];
      var harga_produk = produk[2]; 

      this.inputJumlahProduk(id_produk,nama_produk,harga_produk);
      this.getSatuan(id_produk);
    }
  },//END FUNGSI pilihProduk
  inputJumlahProduk(id_produk,nama_produk,harga_produk){
    var app = this
    app.inputTbsPembelianOrder.id_produk = id_produk
    app.inputTbsPembelianOrder.nama_produk = nama_produk  
    var harga_tbs = $(".harga-"+id_produk).attr("data-harga");

    if (typeof harga_tbs === 'undefined'){
      app.inputTbsPembelianOrder.harga_produk = harga_produk;
    }else {
      app.inputTbsPembelianOrder.harga_produk = harga_tbs;
    }
    $("#modalJumlahProduk").show();
    app.$refs.jumlah_produk.focus();
  },
  submitJumlahProduk(id_produk,jumlah_produk,harga_produk,nama_produk,satuan_produk){
    var app = this
    var produk = app.inputTbsPembelianOrder.produk.split("|");
    var harga_tbs = $(".harga-"+produk[0]).attr("data-harga")

    if (typeof harga_tbs === 'undefined'){
      var harga = produk[2];
    }else {
      var harga = harga_tbs;
    }


    if (jumlah_produk == "" || jumlah_produk == 0) {

      app.$swal("Jumlah Produk Tidak Boleh Nol atau kosong!")
      .then((value) => {
        app.$refs.jumlah_produk.focus() 
      })

    }else if (harga_produk == "" || harga_produk == 0) {

      app.$swal("Harga Produk Tidak Boleh Nol atau kosong!")
      .then((value) => {
        app.$refs.harga_produk.focus() 
      })

    }else if (harga != harga_produk) {
      app.konfirmasiPerubahanHarga(id_produk,jumlah_produk,harga_produk,nama_produk,satuan_produk)
    }else{
      var status_harga = 0;
      app.prosesTambahProdukTbs(id_produk,jumlah_produk,harga_produk,nama_produk,satuan_produk,status_harga)
    }

  },//END PROSES TAMBAH PRODUK TBS
  konfirmasiPerubahanHarga(id_produk,jumlah_produk,harga_produk,nama_produk,satuan_produk){
    let app = this
    app.$swal({
      text: "Anda Yakin Ingin Merubah Harga Beli Produk "+titleCase(nama_produk)+ " ?",
      closeOnEsc: true,
      buttons: {
        cancel: true,
        confirm: "OK"                   
      },
    }).then((value) => {
      if (value) {
  var status_harga = 1; // jika master produk, juga diubah
  app.prosesTambahProdukTbs(id_produk,jumlah_produk,harga_produk,nama_produk,satuan_produk,status_harga);
} else {
  var status_harga = 0; // jika master produk, tidak diubah
  app.prosesTambahProdukTbs(id_produk,jumlah_produk,harga_produk,nama_produk,satuan_produk,status_harga);
}
});
  },
  prosesTambahProdukTbs(id_produk,jumlah_produk,harga_produk,nama_produk,satuan_produk,status_harga){

    var app = this;
    var satuan = satuan_produk.split("|");
    app.loading = true;
    axios.get(app.url+'/proses-tambah-tbs-pembelian?id_produk_tbs='+id_produk+'&jumlah_produk='+jumlah_produk+'&harga_produk='+harga_produk+'&satuan='+satuan[0]+'&satuan_dasar='+satuan[2]+'&status_harga='+status_harga)
    .then(function (resp) {
      $("#modalJumlahProduk").hide();
      app.alert("Menambahkan Produk "+titleCase(nama_produk));
      app.loading = false;
      app.getResults();

      if (resp.data.status == 1) {
        var subtotal = (parseInt(app.inputPembayaranPembelianOrder.subtotal) - parseInt(resp.data.subtotal_lama) + parseInt(resp.data.subtotal))
      }else{      
        var subtotal = parseInt(app.inputPembayaranPembelianOrder.subtotal) + parseInt(resp.data.subtotal)
      }

      app.inputPembayaranPembelianOrder.subtotal = subtotal                       
      ;
      app.inputTbsPembelianOrder.id_produk = ''
      app.inputTbsPembelianOrder.nama_produk = ''
      app.inputTbsPembelianOrder.harga_produk = ''
      app.inputTbsPembelianOrder.jumlah_produk = ''
      app.inputTbsPembelianOrder.produk = ''

    })
    .catch(function (resp) {
      app.loading = false;
      alert("Tbs Pembelian tidak bisa ditambahkan");
    });

  },
  editEntryJumlah(id, index,nama_produk,subtotal_lama) {

    var app = this;   
    swal({ 
      title: titleCase(nama_produk), 
      input: 'number', 
      inputPlaceholder : 'Jumlah Produk',         
      html:'Berapa Jumlah Produk Yang Akan Dimasukkan ?', 
      animation: false, 
      showCloseButton: true, 
      showCancelButton: true, 
      focusConfirm: true, 
      confirmButtonText: '<i class="fa fa-thumbs-o-up"></i> OK', 
      confirmButtonAriaLabel: 'Thumbs up, great!', 
      cancelButtonText: '<i class="fa fa-thumbs-o-down">Batal', 
      closeOnConfirm: true, 
      cancelButtonAriaLabel: 'Thumbs down', 
      inputAttributes: { 
        'name': 'edit_qty_produk', 
      }, 
      inputValidator : function (value) { 
        return new Promise(function (resolve, reject) { 
          if (value) { 
            resolve(); 
          }  
          else { 
            reject('Jumlah Harus Di Isi!'); 
          } 
        }) 
      } 
    }).then(function (jumlah_produk) { 
      if (jumlah_produk != "0") { 
        app.loading = true;
        axios.get(app.url+'/proses-edit-jumlah-tbs-pembelian?jumlah_edit_produk='+jumlah_produk+'&id_tbs_pembelian='+id)
        .then(function (resp) {
          app.alert("Mengubah Jumlah Produk "+titleCase(nama_produk));
          app.loading = false;
          app.getResults();      
          var subtotal = (parseInt(app.inputPembayaranPembelianOrder.subtotal) - parseInt(subtotal_lama))  + parseInt(resp.data.subtotal)
          app.inputPembayaranPembelianOrder.subtotal = subtotal                       
          ;
        })
        .catch(function (resp) {
          app.loading = false;
          alert("Jumlah Produk tidak bisa diedit");
        });
      } 
      else { 
        swal('Oops...', 'Jumlah Tidak Boleh 0 !', 'error'); 
        return false; 
      } 
    }); 

  },//END editEntryJumlah
  editSatuanEntry(id, index,nama_produk,subtotal_lama, id_produk) {
    var app = this;
    app.inputTbsPembelianOrder.nama_produk = nama_produk;
    app.inputTbsPembelianOrder.id_tbs = id;
    app.inputTbsPembelianOrder.subtotal = subtotal_lama;
    app.getSatuan(id_produk);
    $("#modalEditSatuan").show();
  },
  subtmitEditSatuan(id_produk, id_tbs, subtotal_lama){

    var app = this;
    app.inputTbsPembelianOrder.produk = id_produk;
    var newSatuan = app.inputTbsPembelianOrder;
    var satuan_produk = app.inputTbsPembelianOrder.satuan_produk.split("|");
    var satuan_tbs = $(".satuan-"+id_produk).attr("data-satuan");

    if (satuan_tbs == satuan_produk[0]) {
      $("#modalEditSatuan").hide();
    }else{

      axios.post(app.url+'/edit-satuan-tbs-pembelian', newSatuan)
      .then(function (resp) {

        var subtotal = (parseInt(app.inputPembayaranPembelianOrder.subtotal) - parseInt(subtotal_lama) + parseInt(resp.data.subtotal))

        function cekTbs(tbs) { 
          return tbs.id_tbs_pembelian === id_tbs
        }


        app.getResults();

        app.inputPembayaranPembelianOrder.subtotal = subtotal.toFixed(2)
        
        app.inputTbsPembelianOrder.id_tbs = ''
        app.openSelectizeProduk() 
        $("#modalEditSatuan").hide();

      })
      .catch(function (resp) {
        console.log(resp);                  
        app.loading = false;
        alert("Tidak Dapat Mengubah Satuan");
      });
    }
  },
  editEntryHarga(id, index,nama_produk,subtotal_lama) {    
    var app = this;   
    swal({ 
      title: titleCase(nama_produk), 
      input: 'number', 
      inputPlaceholder : 'Harga Produk',         
      html:'Berapa Harga Produk Yang Akan Dimasukkan ?', 
      animation: false, 
      showCloseButton: true, 
      showCancelButton: true, 
      focusConfirm: true, 
      confirmButtonText: '<i class="fa fa-thumbs-o-up"></i> OK', 
      confirmButtonAriaLabel: 'Thumbs up, great!', 
      cancelButtonText: '<i class="fa fa-thumbs-o-down">Batal', 
      closeOnConfirm: true, 
      cancelButtonAriaLabel: 'Thumbs down', 
      inputAttributes: { 
        'name': 'edit_harga_produk', 
      }, 
      inputValidator : function (value) { 
        return new Promise(function (resolve, reject) { 
          if (value) { 
            resolve(); 
          }  
          else { 
            reject('Harga Harus Di Isi!'); 
          } 
        }) 
      } 
    }).then(function (harga_produk) { 
      if (harga_produk != "0") { 
        app.loading = true;
        axios.get(app.url+'/proses-edit-harga-tbs-pembelian?harga_edit_produk='+harga_produk+'&id_harga='+id)
        .then(function (resp) {
          app.alert("Mengubah Harga Produk "+titleCase(nama_produk));
          app.loading = false;
          app.getResults();
          var subtotal = (parseInt(app.inputPembayaranPembelianOrder.subtotal) - parseInt(subtotal_lama)) + parseInt(resp.data.subtotal);
          app.inputPembayaranPembelianOrder.subtotal = subtotal                       
          ;

        })
        .catch(function (resp) {
          app.loading = false;
          alert("Harga Produk tidak bisa diedit");
        });
      } 
      else { 
        swal('Oops...', 'Harga Tidak Boleh 0 !', 'error'); 
        return false; 
      } 
    }); 

  },//END editEntryHarga
  editEntryPotongan(id, index,nama_produk,jumlah,harga,subtotal_lama) {    
    var app = this;   
    var subtotal = parseFloat(jumlah) * parseFloat(harga);
    swal({ 
      title: titleCase(nama_produk), 
      input: 'text', 
      inputPlaceholder : 'Potongan Produk',         
      html:'<i>Format : 10 (nominal) || 10% (persen)</i>', 
      animation: false, 
      showCloseButton: true, 
      showCancelButton: true, 
      focusConfirm: true, 
      confirmButtonText: '<i class="fa fa-thumbs-o-up"></i> OK', 
      confirmButtonAriaLabel: 'Thumbs up, great!', 
      cancelButtonText: '<i class="fa fa-thumbs-o-down">Batal', 
      closeOnConfirm: true, 
      cancelButtonAriaLabel: 'Thumbs down', 
      inputAttributes: { 
        'name': 'edit_potongan_produk', 
      }, 
      inputValidator : function (value) { 
        return new Promise(function (resolve, reject) { 
          if (value) { 
            resolve(); 
          }  
          else { 
            reject('Potongan Harus Di Isi!'); 
          } 
        }) 
      } 
    }).then(function (potongan) { 
      var pos = potongan.search("%"); 
      if (pos > 0)  
      {   
        var potongan_produk = potongan; 
        potongan_produk = potongan_produk.replace("%","");       
        if (potongan_produk > 100) { 
          swal('Oops...', 'Potongan Tidak Boleh Lebih Dari 100%!', 'error'); 
        } 
        else if (potongan != "0") { 
          axios.get(app.url+'/cek-persen-potongan-pembelian?potongan_edit_produk='+potongan+'&id_potongan='+id)
          .then(function (resp) {
            if (resp.data == 1) {
              swal({
                title: "Peringatan",
                text:"Potongan Tidak Boleh Lebih Dari 100%!",
              });
            }
            else{
              app.submitEditPotongan(potongan,id,nama_produk,subtotal_lama);
            }
          });
        } 
        else { 
          swal('Oops...', 'Potongan Tidak Boleh 0 !', 'error'); 
        } 

      }else{ 
        if (subtotal < potongan) { 
          swal('Oops...', 'Potongan Tidak Boleh Melebihi Subtotal!', 'error'); 
        } 
        else if (potongan != "0") { 
          app.submitEditPotongan(potongan,id,nama_produk,subtotal_lama);
        } 
        else { 
          swal('Oops...', 'Potongan Tidak Boleh 0 !', 'error'); 
        } 
      } 
    }); 

  },//END SWAL EDIT POTONGAN TBS
  submitEditPotongan(potongan,id,nama_produk,subtotal_lama){
    var app = this;
    app.loading = true;
    axios.get(app.url+'/proses-edit-potongan-tbs-pembelian?potongan_edit_produk='+potongan+'&id_potongan='+id)
    .then(function (resp) {
      app.alert("Mengubah Potongan Produk "+titleCase(nama_produk));
      app.loading = false;
      app.getResults();
      var subtotal = (parseInt(app.inputPembayaranPembelianOrder.subtotal) - parseInt(subtotal_lama)) + parseInt(resp.data.subtotal);
      app.inputPembayaranPembelianOrder.subtotal = subtotal                       
      

    })
    .catch(function (resp) {
      app.loading = false;
      alert("Potongan Produk tidak bisa diedit");
    });
  },//END PROSES UPDATE POTONGAN TBS 

  editEntryTax(id, index,nama_produk,jumlah,harga,potongan,ppn,subtotal_lama){
    var app = this;   
    var subtotal = (parseFloat(jumlah) * parseFloat(harga)) - parseFloat(potongan); 

    if (ppn == '') { 
      var ppn_produk = '<select id="ppn_swal" name="ppn_swal"  class="swal2-input js-selectize-reguler">'+ 
      '<option value="Include" >Include</option>'+ 
      '<option value="Exclude" >Exclude</option>'+ 
      '</select></div>'; 
    }else { 
      var ppn_produk = '<select id="ppn_swal" name="ppn_swal" class="swal2-input js-selectize-reguler">'+ 
      '<option selected="selected" value="'+ppn+'">'+ppn+'</option>'+ 
      '</select></div>'; 
    } 

    swal({ 
      title: titleCase(nama_produk), 
      html:'<i>Format : 10 (nominal) || 10% (persen)</i>'+ 
      '<div class="row">'+ 
      '<div class="col-sm-6 col-xs-6">'+ppn_produk+''+ 
      '<div class="col-sm-6 col-xs-6">'+ 
      '<input type="text" id="tax_swal" v-model="ppn_swal_input" class="swal2-input" placeholder="PAJAK"></div>'+ 
      '</div>', 
      animation: false, 
      showCloseButton: true, 
      showCancelButton: true, 
      confirmButtonText: '<i class="fa fa-thumbs-o-up"></i> OK', 
      confirmButtonAriaLabel: 'Thumbs up, great!', 
      cancelButtonText: '<i class="fa fa-thumbs-o-down">Batal', 
      cancelButtonAriaLabel: 'Thumbs down', 
      preConfirm: function () { 
        return new Promise(function (resolve) { 
          resolve([ 
            $('#tax_swal').val(), 
            $('#ppn_swal').val() 
            ]) 
        }) 
      } 
    }).then(function (result) {   
      if (result[0] == '' || result[0] == 0) { 

        swal('Oops...', 'Pajak Tidak Boleh 0 !', 'error'); 
        return false; 
      }   
      else if (result[1] == '') { 

        swal('Oops...', 'PPN Belum Di Isi', 'error'); 
        return false; 
      }else{ 

        var pajak = result[0]; 
        var pos = pajak.search("%"); 

        if (pos > 0) { 

          pajak = pajak.replace("%",""); 
          if (pajak > 100) { 

            swal('Oops...', 'Pajak Tidak Boleh Lebih Dari 100%!', 'error'); 
            return false; 
          }else{ 
            var pajak = result[0];
            var ppn_edit = result[1];
            app.submitEditTax(pajak,id,nama_produk,ppn_edit,subtotal_lama);
          } 

        }else{ 

          if (subtotal < result[0]) { 

            swal('Oops...', 'Pajak Tidak Boleh Melebihi Subtotal!', 'error'); 
            return false; 
          }else{ 
            var pajak = result[0];
            var ppn_edit = result[1];
            app.submitEditTax(pajak,id,nama_produk,ppn_edit,subtotal_lama);

          } 

        } 
      } 
    }); 
  },//END SWAL EDIT TAX TBS
  submitEditTax(pajak,id,nama_produk,ppn_edit,subtotal_lama){
    var app = this;
    app.loading = true;
    axios.get(app.url+'/proses-edit-tax-tbs-pembelian?tax_edit_produk='+pajak+'&id_tax='+id+'&ppn_produk='+ppn_edit)
    .then(function (resp) {
      app.alert("Mengubah Pajak Produk "+titleCase(nama_produk));
      app.loading = false;
      app.getResults();  

      var subtotal = (parseInt(app.inputPembayaranPembelianOrder.subtotal) - parseInt(subtotal_lama)) + parseInt(resp.data.subtotal);
      app.inputPembayaranPembelianOrder.subtotal = subtotal                       
      


    })
    .catch(function (resp) {
      app.loading = false;
      alert("Pajak Produk tidak bisa diedit");
    });
  },//END METHOD EDIT TAX TBS
  selesaiPembelianOrder(){
    var app = this;
    if (app.inputPembayaranPembelianOrder.suplier === '') {
      app.alertGagal("Silakan Pilih Suplier Terlebih Dahulu.");
      app.openSelectizeSuplier();
    }else{

      var newPembelianOrder = app.inputPembayaranPembelianOrder;
      axios.post(app.url, newPembelianOrder)
      .then(function (resp) {
        app.message = 'Berhasil Menambah Order Pembelian';
        app.alert(app.message);
        window.open('pembelian-order/cetak-besar-order-pembelian/'+resp.data.respons_pembelian,'_blank');
      })
      .catch(function (resp) {
        app.success = false;
      });

    }
  },
  batalPembelian(){
    var app = this;
    app.$swal({
      text: "Anda Yakin Ingin Membatalkan Transaksi Pembelian Ini ?",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {

        app.loading = true;
        axios.post(app.url+'/batal-transaksi-pembelian')
        .then(function (resp) {

          var subtotal = parseInt(app.inputPembayaranPembelianOrder.subtotal) - parseInt(resp.data.subtotal)
          app.getResults();
          app.alert("Membatalkan Transaksi Pembelian");
          app.inputPembayaranPembelianOrder.suplier = ''
          app.inputPembayaranPembelianOrder.subtotal = 0
        })
        .catch(function (resp) {

          app.loading = false;
          alert("Tidak dapat Membatalkan Transaksi Pembelian");
        });

      } else {

        app.$swal.close();

      }
    });
  },//END BATAL PEMBELIAN
  closeModalX(){
    $("#modal_tambah_suplier").hide(); 
  },
  hitungHargaKonversi(){
    var satuan = this.inputTbsPembelianOrder.satuan_produk.split("|");
    var produk = this.inputTbsPembelianOrder.produk.split("|");
    this.inputTbsPembelianOrder.harga_produk = parseFloat(produk[2]) * ( parseFloat(satuan[3]) * parseFloat(satuan[4]) );

  },
  closeModalJumlahProduk(){
    $("#modalJumlahProduk").hide(); 
    $("#modalEditSatuan").hide(); 
    this.openSelectizeProduk();
  },
  alertGagal(pesan) {
    this.$swal({
      title: "Gagal ",
      text: pesan,
      icon: "warning",
      buttons: false,
      timer: 2000,

    });
  }
}
}
</script>