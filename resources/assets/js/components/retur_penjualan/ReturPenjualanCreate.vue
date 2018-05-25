<style scoped>
    .modal {
    overflow-y:auto;
}
.pencarian {
  color: red; 
  float: right;
}
.filter {
  float: left;
  padding-bottom: 15px;
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

        <!-- MODAL KAS -->
        <div class="modal" id="modal_tambah_kas" role="dialog" data-backdrop=""> 
            <div class="modal-dialog"> 
                <!-- Modal content--> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close"  v-on:click="closeModalKas()"> &times;</button> 
                        <h4 class="modal-title"> 
                            <div class="alert-icon"> 
                                <b>Silahkan Isi Kas!</b> 
                            </div> 
                        </h4> 
                    </div> 
                    <div class="modal-body">
                        <form v-on:submit.prevent="saveFormKas()" class="form-horizontal"> 
                            <div class="form-group">
                                <label for="kode_kas" class="col-md-3 control-label">Kode Kas</label>
                                <div class="col-md-9">
                                    <input class="form-control" autocomplete="off" placeholder="Kode Kas" v-model="tambahKas.kode_kas" type="text" name="kode_kas" id="kode_kas"  autofocus="" ref="kode_kas">
                                    <span v-if="errors.kode_kas" id="kode_kas_error" class="label label-danger">{{ errors.kode_kas[0] }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nama_kas" class="col-md-3 control-label">Nama Kas</label>
                                <div class="col-md-9">
                                    <input class="form-control" autocomplete="off" placeholder="Nama Kas" v-model="tambahKas.nama_kas" type="text" name="nama_kas" id="nama_kas"  ref="nama_kas">
                                    <span v-if="errors.nama_kas" id="nama_kas_error" class="label label-danger">{{ errors.nama_kas[0] }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nama_kas" class="col-md-3 control-label">Tampil Transaksi</label>
                                <div class="togglebutton col-md-9">
                                    <label>
                                        <b>No</b>  <input type="checkbox" v-model="tambahKas.status_kas" value="1" name="status_kas" id="status_kas" ref="status_kas"><b>Yes</b>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nama_kas" class="col-md-3 control-label">Default Kas</label>
                                <div class="togglebutton col-md-9">
                                    <label>
                                        <b>No</b>  <input type="checkbox" v-on:change="defaultKas()" v-model="tambahKas.default_kas" value="1" name="default_kas" id="default_kas" ref="default_kas"><b>Yes</b>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-9 col-md-offset-3">
                                    <p style="color: red; font-style: italic;">*Note : Hanya 1 Kas yang dijadikan default.</p>
                                    <button class="btn btn-primary" id="btnSimpanKas" type="submit"><i class="material-icons">send</i> Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>       
            </div> 
        </div> 
        <!-- END MODAL KAS -->

        <!-- MODAL SELESAI -->
        <div class="modal" id="modal_selesai" role="dialog" data-backdrop=""> 
            <div class="modal-dialog"> 
                <!-- Modal content--> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close"  v-on:click="closeModalPembayaran()" v-shortkey.push="['esc']" @shortkey="closeModalPembayaran()"> &times;</button> 
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
                                                <selectize-component style="margin: 8px 0px" v-model="inputReturPenjualan.kas" :settings="placeholder_kas" id="kas" ref='kas'>  
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
                                            <money style="text-align:right" class="form-subtotal" v-model="inputReturPenjualan.potongan_faktur" v-bind="separator" v-shortkey.focus="['f7']"></money>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-xs-6">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 1px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">(%)(F8)</font>                                            
                                            <input style="text-align:right" type="number" class="form-subtotal" value="0" v-model="inputReturPenjualan.potongan_persen" v-on:blur="potonganPersen" v-shortkey.focus="['f8']" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">Total Akhir</font>
                                            <money style="text-align:right" class="form-penjualan" readonly="" id="total_akhir" name="total_akhir" placeholder="Total Akhir"  v-model="inputReturPenjualan.total_akhir" v-bind="separator" ></money> 
                                        </div>    
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">Pembayaran(F10)</font>
                                            <money style="text-align:right" class="form-penjualan" v-shortkey.focus="['f10']" id="pembayaran" name="pembayaran" placeholder="Pembayaran"  v-model="inputReturPenjualan.pembayaran" v-bind="separator"  autocomplete="off" ref="pembayaran"></money> 
                                        </div>
                                    </div>
                                </div>

                                <div align="right"  style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                    <button type="button" class="btn btn-success btn-sm" id="btnSelesai" v-on:click="selesaiRetur()" v-shortkey.push="['alt', 'x']" @shortkey="selesaiRetur()"><font style="font-size:15px;">Tunai(Alt + X)</font></button>

                                    <button type="button" class="btn btn-default btn-sm"  v-on:click="closeModalPembayaran()" v-shortkey.push="['esc']" @shortkey="closeModalPembayaran()"> <font style="font-size:15px;">Tutup(Esc)</font></button>
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

          <div class="modal" id="modal_pilih_retur" role="dialog" data-backdrop=""> 
                  <div class="modal-dialog modal-lg"> 
                         <!-- Modal content--> 
                            <div class="modal-content"> 
                                <div class="modal-header"> 
                                    <button type="button" class="close"  v-on:click="closeModalX()" > &times;</button> 
                                    <h4 class="modal-title"> 
                                        <div class="alert-icon"> 
                                            <b>Silahkan Pilih Faktur Penjualan !</b> 
                                        </div> 
                                    </h4> 
                                </div> 
                                  <div class="modal-body">
                                                <div class=" table-responsive ">
                                        
                                              <div class="col-sm-4 filter">     
                                              <label>Jenis Penjualan</label>
                                                  <selectize-component v-model="inputTbsReturPenjualan.jenis_penjualan" :settings="placeholder_penjualan" id="jenis_penjualan" ref="jenis_penjualan" > 
                                                    <option v-bind:value="0" > Penjualan POS </option>
                                                    <option v-bind:value="1" > Penjualan Online </option>
                                                  </selectize-component>
                                                </div>

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
                                                    <tr v-for="dataPelangganReturs, index in dataPelangganRetur" v-bind:id="'retur-' + dataPelangganReturs.id_penjualan" v-on:click="returJualEntry(dataPelangganReturs.id_penjualan, index,dataPelangganReturs.id_produk,dataPelangganReturs.kode_barang,dataPelangganReturs.nama_barang,dataPelangganReturs.jumlah_produk,dataPelangganReturs.satuan,dataPelangganReturs.harga_produk,dataPelangganReturs.jumlah_jual)">
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
                                    <div align="right"  style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                          <button type="button" class="btn btn-default btn-sm"  v-on:click="closeModalPembayaran()" v-shortkey.push="['esc']" @shortkey="closeModalPembayaran()"> <font style="font-size:15px;">Tutup(Esc)</font></button>
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
 
                    <form class="form-horizontal"  v-on:submit.prevent="saveFormReturJual()">  
                        <div class="modal-body"> 
                            <h3 class="text-center"><b>{{inputTbsReturPenjualan.nama_produk}}</b></h3> 
 
                            <div class="form-group"> 
                                <div class="col-md-6"> 
                                    <input class="form-control" type="number" v-model="inputTbsReturPenjualan.jumlah_retur" placeholder="Isi Jumlah Retur" name="jumlah_retur" id="jumlah_retur" ref="jumlah_retur" autocomplete="off" step="0.01"> 
                                </div> 
                                <div class="col-md-6"> 
                                    <selectize-component v-model="inputTbsReturPenjualan.satuan" :settings="placeholder_satuan" id="satuan" name="satuan" ref='satuan'>  
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

                        <td># {{ tbs_retur_penjualans.no_faktur_penjualan }}</td>
                         <td>{{ tbs_retur_penjualans.kode_barang }} - {{ tbs_retur_penjualans.nama_barang }}</td>
                         <td align="right"> 
                           <a href="#create-retur-penjualan" v-bind:id="'edit-' + tbs_retur_penjualans.id" v-on:click="editJumlah(tbs_retur_penjualans.id, index,tbs_retur_penjualans.nama_barang,tbs_retur_penjualans.subtotal,tbs_retur_penjualans.jumlah_jual)"> 
                            {{ tbs_retur_penjualans.jumlah_retur | pemisahTitik }} 
                            </a> 
                         </td> 
                          <td style="text-align:center;">{{ tbs_retur_penjualans.satuan }}</td>
                          <td style="text-align:right;">{{ tbs_retur_penjualans.harga_produk | pemisahTitik }}</td>
                          <td style="text-align:right;"> 
                                <a href="#create-retur-penjualan" v-bind:id="'edit-' + tbs_retur_penjualans.id" v-on:click="editPotongan(tbs_retur_penjualans.id, index,tbs_retur_penjualans.nama_barang,tbs_retur_penjualans.subtotal)"> 
                                   {{ tbs_retur_penjualans.potongan  }} 
                                 </a> 
                          </td> 
                          <td style="text-align:right;">{{ tbs_retur_penjualans.subtotal | pemisahTitik }}</td>
                          <td style="text-align:right;"> 
                         <a  href="#create-retur-penjualan" class="btn btn-xs btn-danger" v-bind:id="'delete-' + tbs_retur_penjualans.id" v-on:click="deleteEntry(tbs_retur_penjualans.id, index,tbs_retur_penjualans.subtotal,tbs_retur_penjualans.nama_barang,tbs_retur_penjualans.no_faktur_penjualan)">Delete</a>
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
                                                  <button type="button" class="btn btn-danger btn-lg" id="btnBatal" v-on:click="batalReturPenjualan()" v-shortkey.push="['f3']" @shortkey="batalReturPenjualan()"> <font style="font-size:20px;">Batal(F3) </font></button>
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
            url_kas : window.location.origin+(window.location.pathname).replace("dashboard", "kas"),
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
           placeholder_penjualan: {
            placeholder: '--JENIS PENJUALAN--'
            }, 
            inputTbsReturPenjualan:{
              pelanggan : '',
              id_pelanggan: '',
              jenis_penjualan: 0,
              jumlah_retur : '',
              jumlah_jual : '',
              nama_produk : '', 
              id_produk : '', 
              id_penjualan: '',
              satuan : '', 
              stok_produk: 0,
              harga_produk:'',
            },
            inputReturPenjualan:{
                subtotal: 0,
                pelanggan : '',
                kas: '',
                tanggal: new Date,
                keterangan: '',
                potongan_faktur : 0,
                potongan_persen : 0,
                total_akhir : 0,
                potong_hutang : 0,
                pembayaran : 0,
                keterangan: '',  
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
          if (this.inputTbsReturPenjualan.pelanggan != '') {
              this.pilihPelanggan();  
          }
        },
        'inputTbsReturPenjualan.jenis_penjualan': function (newQuestion) {
          if (this.inputTbsReturPenjualan.jenis_penjualan != '') {
              this.pilihPelangganRetur();  
          }
        },
        'inputReturPenjualan.potongan_faktur':function(){
                this.potonganFaktur();
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
            this.$store.dispatch('LOAD_PELANGGAN_LIST');
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
              if (app.inputReturPenjualan.subtotal == 0) {
                app.getSubtotalTbs();
              }
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
          var jenis_penjualan = app.inputTbsReturPenjualan.jenis_penjualan;
                if (pelanggan == '') {
                        app.$swal({
                        text: "Silakan Pilih Pelanggan Telebih dahulu!",
                        });
              }else{
                axios.get(app.url+'/cek-pelanggan-double').then(function (resp) {
                    if(resp.data.data_tbs > 0){
                        console.log(resp.data.data_pelanggan.id_pelanggan)
                            if(resp.data.data_pelanggan.id_pelanggan != pelanggan){
                                  app.$swal({
                                    text: "Transaksi tidak boleh dari satu Pelanggan !!",
                                  }); 
                            }else{
                                app.pilihPelangganRetur();
                                $("#modal_pilih_retur").show();
                            }
                      }
                      else{
                            app.pilihPelangganRetur();
                            $("#modal_pilih_retur").show();
                      }
               })
              .catch(function (resp) {
                  app.loading = false;
                  alert(resp);
              });
        }
      },
      pilihPelangganRetur(page){
            var app = this;
            var pelanggan = app.inputTbsReturPenjualan.pelanggan.split("|");
            var jenis_penjualan = app.inputTbsReturPenjualan.jenis_penjualan;
            var id = pelanggan[0];
            if (typeof page === 'undefined') {
                page = 1;
            }
            app.loading = true;
            axios.get(app.url+'/data-pelanggan-retur/'+id+'/'+jenis_penjualan+'?page='+page)
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
            var jenis_penjualan = app.inputTbsReturPenjualan.jenis_penjualan;
            var id = pelanggan[0];
            if (typeof page === 'undefined') {
                page = 1;
            }
            axios.get(app.url+'/pencarian-pelanggan-retur/'+id+'/'+jenis_penjualan+'?search='+app.pencarianretur+'&page='+page)
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
        returJualEntry(id_penjualan,index,id_produk,kode_barang,nama_barang,jumlah_produk,satuan,harga_produk,jumlah_jual){
             var app = this;
               $("#modalInsertTbs").show();
               $("#modal_pilih_retur").hide();            
                app.inputTbsReturPenjualan.nama_produk = titleCase(nama_barang);
                app.inputTbsReturPenjualan.jumlah_retur = jumlah_produk;
                app.inputTbsReturPenjualan.jumlah_jual = jumlah_jual;
                app.inputTbsReturPenjualan.id_penjualan = id_penjualan;
                app.inputTbsReturPenjualan.id_produk = id_produk;
                app.inputTbsReturPenjualan.satuan = satuan;
                app.inputTbsReturPenjualan.harga_produk = harga_produk;
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
                                app.inputTbsReturPenjualan.satuan = resp.data[i].satuan; 
                            } 
                        }); 
 
                    }else{ 
 
                        $.each(resp.data, function (i, item) { 
                            if (resp.data[i].id === parseInt(satuan_tbs)) { 
                                app.inputTbsReturPenjualan.satuan = resp.data[i].satuan; 
                            } 
                        }); 
 
                    } 
                }) 
                .catch(function (resp) { 
                    console.log(resp); 
                    alert("Tidak Dapat Memuat Satuan Produk"); 
                }); 
            },
        saveFormReturJual() {
                var app = this;
                var newreturjual = app.inputTbsReturPenjualan;
                  if (app.inputTbsReturPenjualan.jumlah_retur > app.inputTbsReturPenjualan.jumlah_jual){
                        app.loading = false;
                        app.getResults();
                        $("#modalInsertTbs").hide();
                        $("#modal_pilih_retur").hide(); 
                        app.alertTbs("Jumlah Retur yang Anda masukan melebihi Jumlah Jual !");
                  }else{
                      axios.post(app.url+'/proses-tambah-tbs-retur-penjualan',newreturjual)
                      .then(function (resp) {
                         console.log(resp.data)
                        if (resp.data == 0) {
                            app.loading = false;
                            app.getResults();
                            $("#modalInsertTbs").hide();
                            $("#modal_pilih_retur").hide(); 
                            app.alertTbs("Produk "+app.inputTbsReturPenjualan.nama_produk+" Sudah Ada, Silakan Pilih Produk dari Faktur Lain! ");
                        }else{
                            var subtotal = parseFloat(app.inputReturPenjualan.subtotal) + parseFloat(resp.data.subtotal)
                            app.getResults();
                            app.inputReturPenjualan.subtotal = subtotal.toFixed(2)
                            app.inputReturPenjualan.total_akhir = subtotal.toFixed(2)
                            app.alert("Berhasil Menambahkan Tbs Retur Penjualan"+ app.inputTbsReturPenjualan.nama_produk);
                            $("#modalInsertTbs").hide();
                            $("#modal_pilih_retur").hide(); 
                            app.loading = false;
                        }
                      })
                      .catch(function (resp) {
                        app.success = false;
                        app.errors = resp.response.data.errors;
                      });
                  }
        },
        getSubtotalTbs(){
        var app =  this;
        var jenis_tbs = 1;
        axios.get(app.url+'/subtotal-tbs-retur-penjualan/'+jenis_tbs)
        .then(function (resp) {
         app.inputReturPenjualan.subtotal += resp.data.subtotal;
         app.inputReturPenjualan.total_akhir += resp.data.subtotal;
         })
        .catch(function (resp) {
          console.log(resp);
        });
      }, 
      deleteEntry(id,index,subtotals,nama_produk,no_faktur_penjualan) {
        var app = this;
        app.$swal({
            text: "Anda Yakin Ingin Menghapus Produk "+titleCase(nama_produk)+ " Faktur "+no_faktur_penjualan+ " ?",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                this.prosesDelete(id,no_faktur_penjualan,subtotals,nama_produk);
            } else {
                app.$swal.close();
            }
        });

    },
    prosesDelete(id,no_faktur_penjualan,subtotals,nama_produk){
        var app = this;
        app.loading = true;
        axios.delete(app.url+'/proses-hapus-tbs-retur-penjualan/'+id)
        .then(function (resp) {
            if (resp.data == 0) {
                app.alertGagal("Faktur "+no_faktur_penjualan+" Produk "+titleCase(nama_produk)+" Gagal Dihapus!")
                app.loading = false
            }else{
                var subtotal = parseFloat(app.inputReturPenjualan.subtotal) - parseFloat(subtotals)
                app.inputReturPenjualan.subtotal = subtotal.toFixed(2)
                app.inputReturPenjualan.total_akhir = subtotal.toFixed(2)
                app.alert("Menghapus Faktur "+no_faktur_penjualan+" Produk "+titleCase(nama_produk))
                app.getResults();
                app.loading = false
            }
        })
        .catch(function (resp) {
            console.log(resp);
            app.loading = false;
            alert("Tidak dapat Menghapus tbs Retur Penjualan");
        });
    },
    editJumlah(id, index,nama_produk,subtotal_lama,jumlah_jual) { 
                var app = this;    
                swal({  
                    title: titleCase(nama_produk),  
                    input: 'number',  
                    inputPlaceholder : 'Jumlah Retur',          
                    html:'Berapa Jumlah Retur Yang Akan Dimasukkan ?',  
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
                                reject('Jumlah Retur Harus Di Isi!');  
                            }  
                        })  
                    }  
                }).then(function (jumlah_retur) { 

                    if (jumlah_retur != "0") {  
                            app.loading = true; 
                            if (jumlah_retur > jumlah_jual){
                                app.loading = false;
                                app.getResults();
                                $("#modalInsertTbs").hide();
                                $("#modal_pilih_retur").hide(); 
                                app.alertTbs("Jumlah Retur yang Anda masukan melebihi Jumlah Jual !");
                            }else{
                              axios.get(app.url+'/proses-edit-jumlah-retur?jumlah_retur='+jumlah_retur+'&id_tbs='+id) 
                                .then(function (resp) { 
                                    app.alert("Mengubah Jumlah Retur "+titleCase(nama_produk)); 
                                    app.loading = false; 
                                    app.getResults();       
                                    var subtotal = (parseInt(app.inputReturPenjualan.subtotal) - parseInt(subtotal_lama))  + parseInt(resp.data.subtotal) 
                                   app.inputReturPenjualan.subtotal = subtotal.toFixed(2)
                                   app.inputReturPenjualan.total_akhir = subtotal.toFixed(2)
                                }) 
                                .catch(function (resp) { 
                                    app.loading = false; 
                                    alert("Jumlah Retur Tidak Bisa Diedit"); 
                                }); 
                            }   
                    }  
                    else {  
                        swal('Oops...', 'Jumlah Tidak Boleh 0 !', 'error');  
                        return false;  
                    }  
                });  
    }, 
    editPotongan(id, index,nama_produk,subtotal_lama) { 
                var app = this;      
                app.$swal({ 
                    title: titleCase(nama_produk), 
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
 
                }).then((potongan) => { 
                    if (!potongan) throw null; 
                    this.submitEditPotongan(potongan,id,nama_produk,subtotal_lama); 
                }); 
     }, 
     submitEditPotongan(potongan,id,nama_produk,subtotal_lama){ 
                var app = this; 
 
                app.inputTbsReturPenjualan.id_tbs = id; 
                app.inputTbsReturPenjualan.potongan_produk = potongan; 
                var newinputTbsReturPenjualan = app.inputTbsReturPenjualan; 
 
                axios.post(app.url+'/proses-potongan-tbs', newinputTbsReturPenjualan) 
                .then(function (resp) { 
                    if (resp.data.status == 0) { 
                        app.$swal({ 
                            text: "Tidak Dapat Mengubah Potongan Produk, Periksa Kembali Inputan Anda!", 
                        }); 
                    }else if (resp.data.status == 1) { 
                        app.$swal({ 
                            text: "Potongan Yang Anda Masukan Melebihi Subtotal!", 
                        }); 
                    }else{ 
                        var subtotal = (parseFloat(app.inputReturPenjualan.subtotal) - parseFloat(subtotal_lama)) + parseFloat(resp.data.subtotal) 
                        app.alert("Mengubah Potongan Retur "+titleCase(nama_produk)); 
                        app.loading = false; 
                        app.inputReturPenjualan.subtotal = subtotal.toFixed(2)
                        app.inputReturPenjualan.total_akhir = subtotal.toFixed(2) 
                        app.getResults(); 
                    } 
                }) 
                .catch(function (resp) {  
                    console.log(resp);     
                    alert("Tidak dapat Mengubah Potongan Produk"); 
                }); 
    }, 
    potonganPersen(){
                var app = this;
                var potonganPersen = app.inputReturPenjualan.potongan_persen

                if (potonganPersen > 100) {
                    app.alertGagal("Potongan Tidak Bisa Lebih Dari 100%")
                    app.inputReturPenjualan.total_akhir = app.inputReturPenjualan.subtotal
                    app.inputReturPenjualan.potongan_faktur = 0
                    app.inputReturPenjualan.potongan_persen = 0
                }else{
                    if (potonganPersen == '') {
                        potonganPersen = 0
                    }

                    var potongan_nominal = parseFloat(app.inputReturPenjualan.subtotal) * (parseFloat(potonganPersen) / 100) 
                    var total_akhir = parseFloat(app.inputReturPenjualan.subtotal,10) - parseFloat(potongan_nominal,10)

                    app.inputReturPenjualan.potongan_faktur = potongan_nominal
                    app.inputReturPenjualan.total_akhir = total_akhir
                }
    },
    potonganFaktur(){
                var app = this;
                var potonganFaktur = app.inputReturPenjualan.potongan_faktur

                if (potonganFaktur == '') {
                    potonganFaktur = 0
                }
                var potongan_persen = (parseFloat(potonganFaktur) / parseFloat(app.inputReturPenjualan.subtotal)) * 100
                var total_akhir = parseFloat(app.inputReturPenjualan.subtotal) - parseFloat(potonganFaktur)

                if (potongan_persen > 100) {
                    app.alertGagal("Potongan Tidak Bisa Lebih Dari 100%")
                    app.inputReturPenjualan.total_akhir = app.inputReturPenjualan.subtotal
                    app.inputReturPenjualan.potongan_faktur = 0
                    app.inputReturPenjualan.potongan_persen = 0

                }else{
                    app.inputReturPenjualan.potongan_persen = potongan_persen.toFixed(2)
                    app.inputReturPenjualan.total_akhir = total_akhir
                }

    },
    saveFormKas() {
                var app = this;
                var newKas = app.tambahKas;
                $("#modal_tambah_kas").hide();
                axios.post(app.url_kas, newKas)
                .then(function (resp) {
                    app.message = 'Menambah Kas '+ app.tambahKas.nama_kas;
                    app.alert(app.message);
                    app.tambahKas.kode_kas = ''
                    app.tambahKas.nama_kas = ''
                    app.tambahKas.status_kas = 0
                    app.tambahKas.default_kas = 0
                    app.errors = '';
                    app.$store.dispatch('LOAD_KAS_LIST')
                    $("#modal_selesai").show();
                })
                .catch(function (resp) {
                    app.success = false;
                    app.errors = resp.response.data.errors;
                });
    },
    batalReturPenjualan(){
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
        axios.post(app.url+'/proses-batal-retur-penjualan')
        .then(function (resp) {
          app.inputReturPenjualan.subtotal = 0
          app.getResults();
          app.alert("Membatalkan Transaksi Retur Penjualan");

        })
        .catch(function (resp) {
          console.log(resp);
          app.loading = false;
          alert("Tidak dapat Membatalkan Transaksi Retur Penjualan");
        });

      });

    },
    selesaiRetur(){ 
                this.$swal({ 
                    text: "Anda Yakin Ingin Menyelesaikan Transaksi Ini ?", 
                    buttons: { 
                        cancel: true, 
                        confirm: "OK"                    
                    }, 
 
                }).then((value) => { 
                    if (!value) throw null; 
                    this.prosesSelesaiRetur(value); 
                }); 
    }, 
    prosesSelesaiRetur(value){ 
                var app = this; 
                var newRetur = app.inputReturPenjualan; 
                app.loading = true; 
                console.log(app.inputReturPenjualan.kas) 
                if (app.inputReturPenjualan.kas == '') { 
                    app.loading = false;     
                    app.$swal("Silakan Pilih Kas Terlebih Dahulu").then((value) => { 
                        app.openSelectizeKas(); 
                    }); 
                }else{ 
                    app.closeModalPembayaran(); 
                    axios.post(app.url,newRetur).then(function (resp) { 
                        if (resp.data == 0) { 
                            app.alertGagal("Anda Belum Memasukan Produk"); 
                            app.loading = false; 
                        } 
                        else if(resp.data.respons == 1){ 
                            app.alertGagal("Gagal : Stok " + resp.data.nama_produk + " Tidak Mencukupi Untuk di Jual, Sisa Produk = "+resp.data.stok_produk); 
                            app.loading = false; 
                        }else if(resp.data.respons == 2){ 
                            app.alertGagal("Gagal : Terjadi Kesalahan , Silakan Coba Lagi!"); 
                            app.loading = false; 
                        }else{ 
                            app.getResults(); 
                            app.alert("Menyelesaikan Transaksi Retur Penjualan"); 
                            app.inputReturPenjualan.supplier = '' 
                            app.inputReturPenjualan.subtotal = 0 
                            app.inputReturPenjualan.potongan_persen = 0 
                            app.inputReturPenjualan.potongan_faktur = 0 
                            app.inputReturPenjualan.total_akhir = 0 
                            app.inputReturPenjualan.pembayaran = 0 
                            app.inputTbsReturPenjualan.supplier = ''    
                            // window.open('penjualan/cetak-kecil-penjualan/'+resp.data.respons_penjualan,'_blank'); 
                            app.loading = false; 
                        } 
                    }) 
                    .catch(function (resp) { 
                        console.log(resp); 
                        app.loading = false; 
                        alert("Tidak Dapat Menyelesaikan Transaksi Retur Pembelian");         
                        app.errors = resp.response.data.errors; 
                    }); 
                } 
      }, 
      bayarReturPenjualan(){
          $("#modal_selesai").show(); 
          this.$refs.pembayaran.$el.focus()
      },
      tambahModalKas(){
                $("#modal_tambah_kas").show();
                $("#modal_selesai").hide();
                this.$refs.kode_kas.focus(); 
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
    	},      
      closeModalX(){
        this.inputTbsReturPenjualan.pelanggan = '', 
        $("#modal_pilih_retur").hide(); 
        $("#modalInsertTbs").hide(); 
      },
      closeModalPembayaran(){
          $("#modal_selesai").hide(); 
          $("#modalInsertTbs").hide();
          $("#modal_pilih_retur").hide();
      },
      closeModalInsertTbs(){
        $("#modalInsertTbs").hide(); 
        $("#modal_pilih_retur").show(); 
      },
     closeModalKas(){
          $("#modal_tambah_kas").hide(); 
          $("#modal_selesai").show(); 
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