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
  .btn-icon{
    border-radius: 1px solid;
    padding: 10px 10px;
  }
</style><template>
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">

				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li><router-link :to="{name: 'indexReturPenjualan'}">Retur Penjualan</router-link></li>
				<li class="active">Tambah Retur Penjualan</li>
			</ul>

          <div class="modal" id="modal_pilih_retur" role="dialog" data-backdrop=""> 
                  <div class="modal-dialog modal-lg"> 
                         <!-- Modal content--> 
                            <div class="modal-content"> 
                                <div class="modal-header"> 
                                    <button type="button" class="close"  v-on:click="closeModalX()" v-shortkey.push="['esc']" @shortkey="closeModalX()"> &times;</button> 
                                    <h4 class="modal-title"> 
                                        <div class="alert-icon"> 
                                            <b>Silahkan Pilih Faktur Penjualan !</b> 
                                        </div> 
                                    </h4> 
                                </div> 
                                  <div class="modal-body">
                                                <div class=" table-responsive ">
                                                   <div class="pencarian">
                                                    <input type="text" name="pencarian" v-model="pencarianretur" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
                                                </div>

                                                <table class="table table-striped table-hover" v-if="seen">
                                                  <thead class="text-primary">
                                                    <tr>
                                                      <th >No Transaksi</th>
                                                      <th >Produk</th>
                                                      <th style="text-align:right;">Sisa</th>
                                                      <th>Satuan</th>
                                                      <th style="text-align:right;">Harga Produk</th>
                                                      <th style="text-align:right;">Subtotal</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody v-if="dataPelangganRetur.length > 0 && loading == false"  class="data-ada" >
                                                    <tr v-for="dataPelangganReturs, index in dataPelangganRetur" v-bind:id="'retur-' + dataPelangganReturs.id_penjualan" v-on:click="returJualEntry(dataPelangganReturs.id_penjualan, index,dataPelangganReturs.id_produk,dataPelangganReturs.kode_barang,dataPelangganReturs.nama_barang,dataPelangganReturs.jumlah_produk,dataPelangganReturs.satuan)">
                                                      <td>{{ dataPelangganReturs.id_penjualan }}</td>
                                                       <td>{{  dataPelangganReturs.kode_barang }} - {{ dataPelangganReturs.nama_barang  }}</td>
                                                        <td style="text-align:right;">{{ dataPelangganReturs.jumlah_produk | pemisahTitik }}</td>
                                                        <td>{{ dataPelangganReturs.satuan }}</td>
                                                        <td style="text-align:right;">{{ dataPelangganReturs.harga_produk }}</td>
                                                         <th style="text-align:right;">{{ dataPelangganReturs.subtotal }}</th>
                                                    </tr>
                                                  </tbody>          
                                                  <tbody class="data-tidak-ada"  v-else-if="dataPelangganRetur.length == 0 && loading == false" >
                                                    <tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
                                                  </tbody>
                                                </table>  

                                                <vue-simple-spinner v-if="loading"></vue-simple-spinner>
                                                <div align="right"><pagination :data="dataPelangganReturData" v-on:pagination-change-page="pilihPelangganRetur" :limit="6"></pagination></div>
                                              </div>
                                  </div>
                            <div class="modal-footer">  
                           </div> 
                   </div>       
               </div> 
           </div> 
           <!-- / MODAL TOMBOL SELESAI --> 

        <!-- MODAL INSERT TBS --> 
        <div class="modal" id="modalInsertTbs" role="dialog" tabindex="-1"  aria-labelledby="myModalLabel" aria-hidden="true" > 
            <div class="modal-dialog modal-medium"> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close"  v-on:click="closeModalInsertTbs()" v-shortkey.push="['f9']" @shortkey="closeModalInsertTbs()"> &times;</button>  
                    </div> 
 
                    <form class="form-horizontal">  
                        <div class="modal-body"> 
                            <h3 class="text-center"><b>{{formReturJualTbs.nama_produk}}</b></h3> 
 
                            <div class="form-group"> 
                                <div class="col-md-6"> 
                                    <input class="form-control" type="number" v-model="formReturJualTbs.jumlah_retur" placeholder="Isi Jumlah Retur" name="jumlah_retur" id="jumlah_retur" ref="jumlah_retur" autocomplete="off" step="0.01"> 
                                </div> 
                                <div class="col-md-6"> 
                                    <selectize-component v-model="formReturJualTbs.satuan_produk" :settings="placeholder_satuan" id="satuan" name="satuan" ref='satuan'>  
                                        <option v-for="satuans, index in satuan" v-bind:value="satuans.satuan" class="pull-left">{{ satuans.nama_satuan }}</option> 
                                    </selectize-component> 
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

            <!-- / MODAL SELESAI --> 
            <div class="modal" id="modal_selesai" role="dialog" data-backdrop="">
                <div class="modal-dialog"> 
                    <!-- Modal content--> 
                    <div class="modal-content"> 
                        <div class="modal-header"> 
                            <button type="button" class="close"  v-on:click="closeModalSelesai()" v-shortkey.push="['esc']" @shortkey="closeModalSelesai()"> &times;
                            </button> 
                            <h4 class="modal-title"> 
                                <div class="alert-icon"> 
                                    <b>Silahkan Lengkapi Retur Penjualan!</b> 
                                </div> 
                            </h4>
                        </div> 

                        <form class="form-horizontal"  v-on:submit.prevent="selesaiReturPenjualan()"> 
                            <div class="modal-body"> 
                                <div class="card" style="margin-bottom:1px; margin-top:1px; margin-right:1px; margin-left:1px;">

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">                                            
                                            <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                                <font style="color: black">Tanggal</font> 
                                                <datepicker :input-class="'form-control'" placeholder="Tanggal" v-model="inputReturPenjualan.tanggal" ref='tanggal'></datepicker>
                                                <br v-if="errors.tanggal">  <span v-if="errors.tanggal" id="tanggal_error" class="label label-danger">{{ errors.tanggal[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-5 col-xs-10">
                                            <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                                                <font style="color: black">Kas(F4)</font><br>
                                                <selectize-component v-model="inputReturPenjualan.kas" :settings="placeholder_kas" id="kas" ref='kas'> 
                                                    <option v-for="kass, index in kas" v-bind:value="kass.id">{{ kass.nama_kas }}</option>
                                                </selectize-component>                                                
                                                <input class="form-control" type="hidden"  v-model="inputReturPenjualan.kas"  name="id_tbs" id="id_tbs"  v-shortkey="['f4']" @shortkey="openSelectizeKas()">
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
                                                <textarea v-model="inputReturPenjualan.keterangan" class="form-control" placeholder="Keterangan.." id="keterangan" name="keterangan" ref="keterangan"></textarea>
                                            </div>
                                        </div>

                                        <div align="right"  style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <button type="submit" class="btn btn-success btn-lg" id="btnTbs"><font style="font-size:20px;">Selesai</font></button>

                                            <button type="button" class="btn btn-default btn-lg"  v-on:click="closeModalSelesai()" v-shortkey.push="['esc']" @shortkey="closeModalSelesai()"> <font style="font-size:20px;">Tutup(Esc)</font></button>
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

          <div class="card" style="margin-bottom: 1px; margin-top: 1px;" ><!-- CARD --> 
                <div class="card-content"> 
                  <h4 class="card-title" style="margin-bottom: 1px; margin-top: 1px;"> Retur Penjualan </h4> 
                  <div class="row" style="margin-bottom: 1px; margin-top: 1px;"> 

                    <div class="col-md-3">
                      <div class="card card-produk" style="margin-bottom: 1px; margin-top: 1px;">
                        <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                         
                           <selectize-component v-model="inputTbsReturPenjualan.pelanggan" :settings="placeholder_pelanggan"  id="pelanggan" name="pelanggan" ref='pelanggan'> 
                            <option v-for="pelanggans, index in pelanggan" v-bind:value="pelanggans.id">{{ pelanggans.nama_pelanggan }}</option>
                           </selectize-component>
                          <input class="form-control" type="hidden"  v-model="inputTbsReturPenjualan.id_pelanggan"  name="id_tbs" id="id_tbs"  v-shortkey="['f1']" @shortkey="openSelectizePelanggan()">
                            </div><!--/COL MD  3 --> 
                            <span v-if="errors.pelanggan" id="produk_error" class="label label-danger">{{ errors.pelanggan[0] }}</span>
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
                      <th>Transaksi Penjualan</th>
                      <th>Produk</th>
                      <th class="text-right">Jumlah Retur</th>
                      <th class="text-center">Satuan</th>
                      <th class="text-right">Harga</th>
                      <th class="text-right">Potongan</th>
                      <th class="text-right">Subtotal</th>
                      <th class="text-center">Hapus</th>
                      </tr>
                    </thead>
                    <tbody v-if="tbs_retur_penjualan.length > 0 && loading == false"  class="data-ada">
                      <tr v-for="tbs_retur_penjualans, index in tbs_retur_penjualan" >

                        <td>{{ tbs_retur_penjualans.no_faktur_penjualan }}</td>
                         <td>{{ tbs_penjualan.kode_barang }} - {{ tbs_penjualan.nama_barang }}</td>
                          <td style="text-align:right;">{{ tbs_retur_penjualans.jumlah_retur | pemisahTitik }}</td>
                          <td style="text-align:center;">{{ tbs_retur_penjualans.satuan }}</td>
                          <td style="text-align:right;">{{ tbs_retur_penjualans.harga_produk | pemisahTitik }}</td>

                          <td style="text-align:right;">{{ tbs_retur_penjualans.potongan | pemisahTitik }}</td>
                          <td style="text-align:right;">{{ tbs_retur_penjualans.subtotal | pemisahTitik }}</td>
                          <td> 
                         <a href="#create-retur-penjualan" class="btn btn-xs btn-danger" v-bind:id="'delete-' + tbs_retur_penjualans.id" v-on:click="deleteEntry(tbs_retur_penjualans.id, index,tbs_retur_penjualans.jumlah_retur,tbs_retur_penjualans.no_faktur_penjualan)">Delete</a>
                        </td>
                      </tr>
                    </tbody>          
                    <tbody class="data-tidak-ada"  v-else-if="tbs_retur_penjualan.length == 0 && loading == false" >
                      <tr ><td colspan="8"  class="text-center">Tidak Ada Data</td></tr>
                    </tbody>
                  </table>  

                  <vue-simple-spinner v-if="loading"></vue-simple-spinner>

                  <div align="right"><pagination :data="tbsReturPenjualanData" v-on:pagination-change-page="getResults" :limit="8"></pagination></div>

                </div>

                <p style="color: red; font-style: italic;">*Note : Klik Kolom  Potongan , Retur Untuk Mengubah Nilai.</p> 
            </div><!-- COL SM 8 --> 

                    <div class="col-md-3">
                                  <div class="card card-stats">
                                      <div class="card-header" data-background-color="blue">
                                          <i class="material-icons">local_atm</i>
                                      </div>
                                      <div class="card-content">
                                          <p class="category"><font style="font-size:20px;">Subtotal</font></p>
                                          <h3 class="card-title"><b><font style="font-size:32px;">{{ inputReturPenjualan.subtotal | pemisahTitik }}</font></b></h3>
                                      </div>
                                      <div class="card-footer">
                                          <div class="row"> 
                                              <div class="col-md-6 col-xs-6"> 
                                                  <button type="button" class="btn btn-success btn-lg" id="bayar" v-on:click="bayarReturPenjualan()" v-shortkey.push="['f2']" @shortkey="bayarReturPenjualan()"><font style="font-size:20px;">Bayar(F2)</font></button>
                                              </div>
                                              <div class="col-md-6 col-xs-6">
                                                  <button type="submit" class="btn btn-danger btn-lg" id="btnBatal" v-on:click="batalReturPenjualan()" v-shortkey.push="['f3']" @shortkey="batalReturPenjualan()"> <font style="font-size:20px;">Batal(F3) </font></button>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
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
            satuan : [],
		      	tbs_retur_penjualan: [],
			      tbsReturPenjualanData : {},
			      url : window.location.origin+(window.location.pathname).replace("dashboard", "retur-penjualan"),
            url_kas : window.location.origin+(window.location.pathname).replace("dashboard", "penjualan"),
            url_tambah_kas : window.location.origin+(window.location.pathname).replace("dashboard", "kas"),
            url_satuan : window.location.origin+(window.location.pathname).replace("dashboard", "pembelian/satuan-konversi"),         
            dataPelangganRetur : [],
            dataPelangganReturData: {},
		      	placeholder_pelanggan: {
				    placeholder: '--PILIH PELANGGAN (F1)--',
            sortField: 'text',
            openOnFocus : true
			     },
           placeholder_kas: {
                    placeholder: '--PILIH KAS--',
                    sortField: 'text',
                    openOnFocus : true
           },
           placeholder_satuan: { 
                    placeholder: '--PILIH SATUAN--', 
                    sortField: 'text', 
                    openOnFocus : true, 
           }, 
            inputTbsReturPenjualan:{
              pelanggan : '',
              id_pelanggan: '',
            },
            inputReturPenjualan:{
                subtotal: 0,
                kas: '',
                tanggal: new Date,
                keterangan: '',
            },
            formReturJualTbs:{
                jumlah_retur : '',
                nama_produk : '', 
                id_produk : '', 
                id_penjualan: '',
                satuan : '', 
                stok_produk: 0, 
            },
            tambahKas: {
              kode_kas : '',
              nama_kas : '',
              status_kas : 0,
              default_kas : 0
            },
           separator: {
              decimal: ',',
              thousands: '.',
              prefix: '',
              suffix: '',
              precision: 2,
              masked: false /* doesn't work with directive */
          },
      validated:'',
			pencarian: '',
      pencarianretur: '',
			loading: true,
			seen : false
		}
	},
	mounted() {
		var app = this;
		app.getResults();
    app.$store.dispatch('LOAD_PELANGGAN_LIST');
    app.$store.dispatch('LOAD_KAS_LIST');  
	},
    computed : mapState ({    
      pelanggan(){
        return this.$store.getters.pelangganTransaksi
      },
      kas(){
        return this.$store.state.kas
      },
      default_kas: state => state.default_kas
    }),
	watch: {
        // whenever question changes, this function will run
        pencarian: function (newQuestion) {
        	this.getHasilPencarian();
        	this.loading = true;  
        },
        pencarianretur: function (newQuestion) {
          this.pencarianPelangganRetur();
          this.loading = true;
        },
        'inputTbsReturPenjualan.pelanggan': function (newQuestion) {
        	this.pilihPelanggan();  
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
          openSelectizePelanggan(){      
            this.$refs.pelanggan.$el.selectize.setValue("");
            this.$refs.pelanggan.$el.selectize.focus();
          },
          openSelectizeKas(){      
            this.$refs.kas.$el.selectize.focus();
          },
    	   getResults(page) {
        		var app = this;	
        		if (typeof page === 'undefined') {
        			page = 1;
        		}
        		axios.get(app.url+'/view-tbs-retur-penjualan?page='+page)
        		.then(function (resp) {
        			app.tbs_retur_penjualan = resp.data.data;
        			app.tbsReturPenjualanData = resp.data;
              app.openSelectizePelanggan();
        			app.loading = false;
        			app.seen = true;
        		})
        		.catch(function (resp) {
        			console.log(resp);
        			app.loading = false;
        			app.seen = true;
        			alert("Tidak Dapat Memuat Retur Penjualan");
        		});
    	},
    	getHasilPencarian(page){
    		var app = this;
        if (typeof page === 'undefined') {
            page = 1;
        }
        axios.get(app.url+'/pencarian-tbs-retur-penjualan?search='+app.pencarian+'&page='+page)
        .then(function (resp) {
            console.log(resp.data.data);
            app.tbs_retur_penjualan = resp.data.data;
            app.tbsReturPenjualanData = resp.data;
            app.loading = false;
            app.seen = true;
        })
        .catch(function (resp) {
            console.log(resp);
            alert("Tidak Dapat Memuat Retur Penjualan");
        });
    	},
       pilihPelanggan() {
          var app = this;
          var pelanggan = app.inputTbsReturPenjualan.pelanggan;
                if (pelanggan == '') {
                        app.$swal({
                        text: "Silakan Pilih Pelanggan Telebih dahulu!",
                        });
              }else{
                      app.pilihPelangganRetur();
                      $("#modal_pilih_retur").show();
                 }
      },
      pilihPelangganRetur(page){
            var app = this;
            var pelanggan = app.inputTbsReturPenjualan.pelanggan.split("|");
            var id = pelanggan[0];
            if (typeof page === 'undefined') {
                page = 1;
            }
          axios.get(app.url+'/data-pelanggan-retur/'+id+'?page='+page)
                .then(function (resp) {
                app.dataPelangganRetur = resp.data.data;
                app.dataPelangganReturData = resp.data;
                app.loading = false;
                app.seen = true;
            })
            .catch(function (resp) {
                console.log(resp);
                app.loading = false;
                app.seen = true;
                alert("Tidak Dapat Memuat Data Retur");
            });
      },
      pencarianPelangganRetur(page){
            var app = this;
            var pelanggan = app.inputTbsReturPenjualan.pelanggan.split("|");
            var id = pelanggan[0];
            if (typeof page === 'undefined') {
                page = 1;
            }
            axios.get(app.url+'/pencarian-pelanggan-retur/'+id+'?search='+app.pencarianretur+'&page='+page)
            .then(function (resp) {
                app.dataPelangganRetur = resp.data.data;
                app.dataPelangganReturData = resp.data;
                app.loading = false;
                app.seen = true;
            })
            .catch(function (resp) {
                console.log(resp);
                alert("Tidak Dapat Memuat Data Retur");
            });
        },
        returJualEntry(id_penjualan,index,id_produk,kode_barang,nama_barang,jumlah_produk,satuan){
             var app = this;
               $("#modalInsertTbs").show();
               $("#modal_pilih_retur").hide();            
                app.formReturJualTbs.nama_produk = titleCase(nama_barang);
                app.formReturJualTbs.jumlah_retur = jumlah_produk;
                app.formReturJualTbs.id_penjualan = id_penjualan;
                app.formReturJualTbs.id_produk = id_produk;
                app.formReturJualTbs.satuan_produk = satuan;
                app.getSatuan(id_produk);
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
                                app.formReturJualTbs.satuan_produk = resp.data[i].satuan; 
                            } 
                        }); 
 
                    }else{ 
 
                        $.each(resp.data, function (i, item) { 
                            if (resp.data[i].id === parseInt(satuan_tbs)) { 
                                app.formReturJualTbs.satuan_produk = resp.data[i].satuan; 
                            } 
                        }); 
 
                    } 
                }) 
                .catch(function (resp) { 
                    console.log(resp); 
                    alert("Tidak Dapat Memuat Satuan Produk"); 
                }); 
            },
       closeModalX(){
        this.inputTbsReturPenjualan.pelanggan = '', 
        $("#modal_pilih_retur").hide(); 
        $("#modalInsertTbs").hide(); 
      },
      closeModalInsertTbs(){
        $("#modalInsertTbs").hide(); 
        $("#modal_pilih_retur").show(); 
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
       alertGagal(pesan) { 
                this.$swal({ 
                    text: pesan, 
                    icon: "warning", 
                    buttons: false, 
                    timer: 1500 
                }) 
            } ,
    	   alertTbs(pesan) {
    		  this.$swal({
    			text: pesan,
    			icon: "warning",
          buttons: false,
          timer: 1000,
    		});
    	}
    }
}
</script>