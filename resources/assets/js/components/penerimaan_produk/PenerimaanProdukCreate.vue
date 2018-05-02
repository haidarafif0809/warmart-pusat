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
          <li><router-link :to="{name: 'indexPenerimaanProduk'}">Penerimaan Produk</router-link></li> 
          <li class="active">Form Penerimaan Produk</li> 
        </ul>

        <!-- small modal -->
        <div class="modal" id="modalEditSatuan" role="dialog" tabindex="-1"  aria-labelledby="myModalLabel" aria-hidden="true" >
          <div class="modal-dialog modal-medium">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close"  v-on:click="closeModalJumlahProduk()" v-shortkey.push="['f9']" @shortkey="closeModalJumlahProduk()"> &times;</button> 
              </div>

              <form class="form-horizontal" v-on:submit.prevent="subtmitEditSatuan(inputTbsPenerimaanProduk.id_produk, inputTbsPenerimaanProduk.id_tbs, inputTbsPenerimaanProduk.subtotal)"> 
                <div class="modal-body">
                  <h3 class="text-center"><b>{{inputTbsPenerimaanProduk.nama_produk | capitalize}}</b></h3>

                  <div class="form-group">

                    <div class="col-md-12 col-xs-12 hurufBesar">
                      <selectize-component v-model="inputTbsPenerimaanProduk.satuan_produk" :settings="placeholder_satuan" id="satuan" name="satuan" ref='satuan'> 
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

            <h4 class="card-title" style="margin-bottom: 1px; margin-top: 1px;"> Penerimaan Produk </h4> 
            <div class="row" style="margin-bottom: 1px; margin-top: 1px;"> 

              <div class="col-md-3">
                <div class="card card-produk" style="margin-bottom: 1px; margin-top: 1px;">

                  <div class="form-group" style="margin-right: 10px; margin-left: 10px;">

                    <selectize-component v-model="inputTbsPenerimaanProduk.suplier" :settings="placeholder_suplier" id="suplier" ref='suplier'  > 
                      <option v-for="suplier, index in supliers" v-bind:value="suplier.order">
                        {{suplier.faktur_order}} || {{suplier.suplier_order}}</option>
                      </selectize-component>

                    </div>

                    <span v-if="errors.suplier" id="produk_error" class="label label-danger">{{ errors.produk[0] }}</span>

                    <span style="display: none;">
                      <input class="form-control" type="text"  v-model="inputTbsPenerimaanProduk.id_suplier"  name="id_suplier" id="id_suplier" v-shortkey="['f1']" @shortkey="openSelectizeSuplier()">
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
                          <th>Produk</th>
                          <th style="text-align:right;">Jumlah</th>
                          <th style="text-align:center;">Satuan</th>
                        </tr>
                      </thead>
                      <tbody v-if="tbs_penerimaan_produks.length > 0 && loading == false"  class="data-ada">
                        <tr v-for="tbs_pembelian, index in tbs_penerimaan_produks" >

                          <td>{{ tbs_pembelian.data_tbs.kode_barang }} - {{ tbs_pembelian.data_tbs.nama_barang | capitalize }}</td>
                          <td>
                            <a href="#create-order-pembelian" v-bind:id="'edit-' + tbs_pembelian.data_tbs.id_tbs_pembelian_order" v-on:click="editEntryJumlah(tbs_pembelian.data_tbs.id_tbs_pembelian_order, index,tbs_pembelian.data_tbs.nama_barang,tbs_pembelian.data_tbs.subtotal)"><p align='right'>{{ tbs_pembelian.data_tbs.jumlah_produk | pemisahTitik }}</p>
                            </a>
                          </td>

                          <td align="center">
                            <a href="#create-order-pembelian" v-bind:id="'edit-' + tbs_pembelian.data_tbs.id_tbs_pembelian_order" v-bind:class="'hurufBesar satuan-' + tbs_pembelian.data_tbs.id_produk" v-bind:data-satuan="''+tbs_pembelian.data_tbs.satuan_id" v-on:click="editSatuanEntry(tbs_pembelian.data_tbs.id_tbs_pembelian_order, index,tbs_pembelian.data_tbs.nama_barang,tbs_pembelian.data_tbs.subtotal, tbs_pembelian.data_tbs.id_produk)">{{ tbs_pembelian.data_tbs.nama_satuan }}</a>
                          </td>

                        </tr>
                      </tbody>          
                      <tbody class="data-tidak-ada"  v-else-if="tbs_penerimaan_produks.length == 0 && loading == false" >
                        <tr ><td colspan="3"  class="text-center">Tidak Ada Data</td></tr>
                      </tbody>
                    </table>  

                    <vue-simple-spinner v-if="loading"></vue-simple-spinner>

                    <div align="right"><pagination :data="tbsPenerimaanProdukData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

                  </div>
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
                          <font style="font-size:32px;">{{ inputPembayaranPenerimaanProduk.subtotal | pemisahTitik }}</font>
                        </b>
                      </h3>

                      <p class="category"><font style="font-size:20px;">Supplier</font></p>
                      <h3 class="card-title">
                        <b>
                          <font style="font-size:15px;" v-if="inputPembayaranPenerimaanProduk.suplier == ''"> Tidak Ada Supplier </font>
                          <font style="font-size:15px;" v-else>{{ inputPembayaranPenerimaanProduk.suplier }}</font>
                        </b>
                      </h3>

                      <p class="category"><font style="font-size:20px; padding-left:0px;padding-top:25px;padding-right: 0px">Keterangan</font></p>
                      <textarea class="form-control" v-model="inputPembayaranPenerimaanProduk.keterangan" name="keterangan" id="keterangan" placeholder="Keterangan .." rows="1">                    
                      </textarea>


                      <input class="form-control" type="text"  v-model="inputPembayaranPenerimaanProduk.no_faktur"  name="no_faktur" id="no_faktur">

                    </div>
                    <div class="card-footer">
                      <div class="row">
                        <div class="col-md-5 col-xs-5"> 
                          <button type="button" class="btn btn-success btn-footer" id="bayar" v-on:click="selesaiPenerimaanProduk()" v-shortkey.push="['f2']" @shortkey="selesaiPenerimaanProduk()"><font style="font-size:15px;">Bayar(F2)</font></button>
                        </div>
                        <div class="col-md-5 col-xs-5">
                          <button type="submit" class="btn btn-danger btn-footer" id="btnBatal" v-on:click="batalPenerimaanProduk(inputPembayaranPenerimaanProduk.suplier, inputPembayaranPenerimaanProduk.no_faktur)" v-shortkey.push="['f3']" @shortkey="batalPenerimaanProduk(inputPembayaranPenerimaanProduk.suplier, inputPembayaranPenerimaanProduk.no_faktur)"> <font style="font-size:15px;">Batal(F3) </font></button>
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
            satuan: [],
            tbs_penerimaan_produks: [],
            tbsPenerimaanProdukData : {},
            url : window.location.origin+(window.location.pathname).replace("dashboard", "penerimaan-produk"),
            inputTbsPenerimaanProduk: {
              suplier: '',
              nama_produk: '',
              id_tbs: '',
              subtotal: '',
              satuan_produk: '',
            },
            inputPembayaranPenerimaanProduk:{
              subtotal: 0,
              suplier: '',
              keterangan: '',
              no_faktur: ''
            },
            placeholder_suplier: {
              placeholder: '--PILIH SUPLIER (F1)--',
              sortField: 'text',
              openOnFocus : true
            },
            placeholder_satuan: {
              placeholder: '--PILIH SATUAN--',
              sortField: 'text',
              openOnFocus : true,
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
              to: new Date(),
            },
            pencarian: '',
            loading: true,
            seen : false,

          }

        },
        mounted() {
          var app = this;
          app.$store.dispatch('LOAD_SUPLIER_ORDER_LIST');
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
          supliers(){
            return this.$store.state.suplier_order
          }
        }),
        watch: {

          pencarian: function (newQuestion) {
            this.getHasilPencarian();
            this.loading = true;  
          },
          'inputTbsPenerimaanProduk.suplier': function (newQuestion) {
            this.pilihSuplier();
          }

        },
        methods: {
          openSelectizeSuplier(){      
            this.$refs.suplier.$el.selectize.focus();
          },
          getResults(page) {
            var app = this; 
            if (typeof page === 'undefined') {
              page = 1;
            }
            axios.get(app.url+'/view-tbs-penerimaan-produk?page='+page)
            .then(function (resp) {
              console.log(resp.data.data)
              app.tbs_penerimaan_produks = resp.data.data;
              app.tbsPenerimaanProdukData = resp.data;       
              app.loading = false;
              app.seen = true;
              app.openSelectizeSuplier();

              if (app.inputPembayaranPenerimaanProduk.subtotal == 0) { 

                $.each(resp.data.data, function (i, item) {
                  app.inputPembayaranPenerimaanProduk.subtotal += parseFloat(resp.data.data[i].data_tbs.subtotal)
                });
              }

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
            if (typeof page === 'undefined') {
              page = 1;
            }
            axios.get(app.url+'/pencarian-tbs-penerimaan-produk?search='+app.pencarian+'&page='+page)
            .then(function (resp) {
              app.tbs_penerimaan_produks = resp.data.data;
              app.tbsPenerimaanProdukData = resp.data;
              app.loading = false;
              app.seen = true;
            })
            .catch(function (resp) {
              console.log(resp);
              alert("Tidak Dapat Memuat Pembelian");
            });
          },
          pilihSuplier() {
            if (this.inputTbsPenerimaanProduk.suplier != '') {
              var app = this;
              var dataOrder = app.inputTbsPenerimaanProduk.suplier.split("|");
              var id_order = dataOrder[0]; 
              var suplier_id = dataOrder[1];
              var faktur_order = dataOrder[2]; 
              var suplier_order = dataOrder[3]; 
              var keterangan_order = dataOrder[4]; 

              app.inputPembayaranPenerimaanProduk.suplier = suplier_order;
              app.inputPembayaranPenerimaanProduk.suplier_id = suplier_id;
              app.inputPembayaranPenerimaanProduk.keterangan = keterangan_order;
              app.inputPembayaranPenerimaanProduk.no_faktur = faktur_order;
              this.getPembelianOrder(id_order,suplier_id,faktur_order,suplier_order);
            }
          },
          getPembelianOrder(id_order,suplier_id,faktur_order,suplier_order){

            var app = this;
            app.loading = true;

            axios.get(app.url+'/proses-tbs-penerimaan-produk?id_order='+id_order+'&suplier_id='+suplier_id+'&faktur_order='+faktur_order)
            .then(function (resp) {
              app.alert("Menerima Order Dari Supplier "+titleCase(suplier_order));
              app.loading = false;
              app.getResults();
              app.inputPembayaranPenerimaanProduk.subtotal = resp.data.subtotal;
              app.inputTbsPenerimaanProduk.suplier = '';

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
                  var subtotal = (parseInt(app.inputPembayaranPenerimaanProduk.subtotal) - parseInt(subtotal_lama))  + parseInt(resp.data.subtotal)
                  app.inputPembayaranPenerimaanProduk.subtotal = subtotal                       
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

          },
          editSatuanEntry(id, index,nama_produk,subtotal_lama, id_produk) {
            var app = this;
            app.inputTbsPenerimaanProduk.nama_produk = nama_produk;
            app.inputTbsPenerimaanProduk.id_tbs = id;
            app.inputTbsPenerimaanProduk.subtotal = subtotal_lama;
            $("#modalEditSatuan").show();
          },
          subtmitEditSatuan(id_produk, id_tbs, subtotal_lama){

            var app = this;
            app.inputTbsPenerimaanProduk.suplier = id_produk;
            var newSatuan = app.inputTbsPenerimaanProduk;
            var satuan_produk = app.inputTbsPenerimaanProduk.satuan_produk.split("|");
            var satuan_tbs = $(".satuan-"+id_produk).attr("data-satuan");

            if (satuan_tbs == satuan_produk[0]) {
              $("#modalEditSatuan").hide();
            }else{

              axios.post(app.url+'/edit-satuan-tbs-pembelian', newSatuan)
              .then(function (resp) {

                var subtotal = (parseInt(app.inputPembayaranPenerimaanProduk.subtotal) - parseInt(subtotal_lama) + parseInt(resp.data.subtotal))

                function cekTbs(tbs) { 
                  return tbs.id_tbs_pembelian === id_tbs
                }


                app.getResults();

                app.inputPembayaranPenerimaanProduk.subtotal = subtotal.toFixed(2)

                app.inputTbsPenerimaanProduk.id_tbs = ''
                app.openSelectizeSuplier() 
                $("#modalEditSatuan").hide();

              })
              .catch(function (resp) {
                console.log(resp);                  
                app.loading = false;
                alert("Tidak Dapat Mengubah Satuan");
              });
            }
          },
          selesaiPenerimaanProduk(){
            var app = this;

            var newPenerimaanProduk = app.inputPembayaranPenerimaanProduk;

            if (newPenerimaanProduk.subtotal == 0) {

              app.message = 'Maaf Anda Belum Melakukan Penerimaan Produk.';
              app.alertGagal(app.message);

            }else if(newPenerimaanProduk.suplier == ''){
              app.message = 'Supplier Tidak Boleh Kosong, Silakan Pilih Supplier Dahulu..';
              app.alertGagal(app.message);
            }else{

              axios.post(app.url, newPenerimaanProduk)
              .then(function (resp) {
                app.getResults();
                app.inputPembayaranPenerimaanProduk.suplier = ''
                app.inputPembayaranPenerimaanProduk.no_faktur = ''
                app.inputPembayaranPenerimaanProduk.keterangan = ''
                app.inputPembayaranPenerimaanProduk.subtotal = 0
                app.message = 'Berhasil Menerima Produk Supplier '+newPenerimaanProduk.suplier;
                app.alert(app.message);
                app.$store.dispatch('LOAD_SUPLIER_ORDER_LIST');
                // window.open('pembelian-order/cetak-besar-order-pembelian/'+resp.data.respons_pembelian,'_blank');
              })
              .catch(function (resp) {
                app.success = false;
              });

            }

          },
          batalPenerimaanProduk(supplier, no_faktur){
            var app = this;
            app.$swal({
              text: `Anda Yakin Ingin Membatalkan Penerimaan Produk Supplier ${supplier} ?`,
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {

                app.loading = true;
                axios.get(app.url+'/batal-penerimaan-produk?no_faktur='+no_faktur)
                .then(function (resp) {

                  var subtotal = parseInt(app.inputPembayaranPenerimaanProduk.subtotal) - parseInt(resp.data.subtotal)
                  app.getResults();
                  app.alert("Membatalkan Transaksi Pembelian");
                  app.inputPembayaranPenerimaanProduk.suplier = ''
                  app.inputPembayaranPenerimaanProduk.no_faktur = ''
                  app.inputPembayaranPenerimaanProduk.keterangan = ''
                  app.inputPembayaranPenerimaanProduk.subtotal = 0
                  
                })
                .catch(function (resp) {

                  app.loading = false;
                  alert("Tidak dapat Membatalkan Transaksi Penerimaan Produk");

                });

              } else {

                app.$swal.close();

              }
            });
          },
          closeModalJumlahProduk(){
            $("#modalEditSatuan").hide(); 
            this.openSelectizeSuplier();
          },
          alertGagal(pesan) {
            this.$swal({
              title: "Gagal ",
              text: pesan,
              icon: "warning",
              buttons: false,
              timer: 2000,

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
          },
        }
      }
    </script>