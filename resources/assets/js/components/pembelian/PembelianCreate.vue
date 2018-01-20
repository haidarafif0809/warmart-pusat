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

</style>
<template>
<div class="row"> 
	<div class="col-md-12"> 
		<ul class="breadcrumb"> 
			<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li> 
			<li><router-link :to="{name: 'indexPembelian'}">Pembelian</router-link></li> 
			<li class="active">Tambah Pembelian</li> 
		</ul> 

          <div class="modal" id="modal_tambah_kas" role="dialog" data-backdrop=""> 
                <div class="modal-dialog"> 
                    <!-- Modal content--> 
                    <div class="modal-content"> 
                        <div class="modal-header"> 
                            <button type="button" class="close"  v-on:click="closeModalX()" v-shortkey.push="['esc']" @shortkey="closeModalX()"> &times;</button> 
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
                              <input class="form-control" autocomplete="off" placeholder="Kode Kas" v-model="tambahKas.kode_kas" type="text" name="kode_kas" id="kode_kas"  autofocus="">
                              <span v-if="errors.kode_kas" id="kode_kas_error" class="label label-danger">{{ errors.kode_kas[0] }}</span>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="nama_kas" class="col-md-3 control-label">Nama Kas</label>
                            <div class="col-md-9">
                              <input class="form-control" autocomplete="off" placeholder="Nama Kas" v-model="tambahKas.nama_kas" type="text" name="nama_kas" id="nama_kas"  >
                              <span v-if="errors.nama_kas" id="nama_kas_error" class="label label-danger">{{ errors.nama_kas[0] }}</span>
                            </div>
                          </div>
                          <div class="form-group">
                          <label for="nama_kas" class="col-md-3 control-label">Tampil Transaksi</label>
                               <div class="togglebutton col-md-9">
                                     <label>
                                         <b>No</b>  <input type="checkbox" v-model="tambahKas.status_kas" value="1" name="status_kas" id="status_kas"><b>Yes</b>
                                         </label>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label for="nama_kas" class="col-md-3 control-label">Default Kas</label>
                                              <div class="togglebutton col-md-9">
                                              <label>
                                                  <b>No</b>  <input type="checkbox" v-on:change="defaultKas()" v-model="tambahKas.default_kas" value="1" name="default_kas" id="default_kas"><b>Yes</b>
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
                    <div class="modal-footer">  
                   </div> 
           </div>       
       </div> 
   </div> 
   <!-- / MODAL TOMBOL SELESAI --> 

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
           <!-- / MODAL TOMBOL SELESAI --> 

           <div class="modal" id="modal_selesai" role="dialog" data-backdrop=""> 
                <div class="modal-dialog"> 
                    <!-- Modal content--> 
                    <div class="modal-content"> 
                        <div class="modal-header"> 
                            <button type="button" class="close"  v-on:click="closeModalX()" v-shortkey.push="['esc']" @shortkey="closeModalX()"> &times;</button> 
                            <h4 class="modal-title"> 
                                <div class="alert-icon"> 
                                    <b>Silahkan Lengkapi Pembayaran!</b> 
                                </div> 
                            </h4> 
                        </div> 
                        <form class="form-horizontal" v-on:submit.prevent="saveForm()" > 
                            <div class="modal-body"> 
                                <div class="card" style="margin-bottom:1px; margin-top:1px; margin-right:1px; margin-left:1px;">

                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 1px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">Potongan(F7)</font>  
                                            <money style="text-align:right;" class="form-subtotal" v-model="inputPembayaranPembelian.potongan_faktur" v-bind="separator" v-shortkey.focus="['f7']"></money>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 1px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">(%)(F8)</font>    
                                            <input type="text" class="form-subtotal" value="0" v-model="inputPembayaranPembelian.potongan_persen" v-on:blur="hitungPotonganPersen" v-shortkey.focus="['f8']" />
                                        </div>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">Jatuh Tempo(F9)</font> 
                                            <datepicker :input-class="'form-control'" placeholder="Isi Bila Ada Jatuh Tempo" v-model="inputPembayaranPembelian.jatuh_tempo" v-shortkey.focus="['f9']" ></datepicker>
                                            <br v-if="errors.jatuh_tempo">  <span v-if="errors.jatuh_tempo" id="jatuh_tempo_error" class="label label-danger">{{ errors.jatuh_tempo[0] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                           <font style="color: black">Keterangan</font> 
                                           <textarea class="form-control" v-model="inputPembayaranPembelian.keterangan"  name="keterangan" id="keterangan" placeholder="Isi Disini" rows="1"></textarea> 
                                            <br v-if="errors.keterangan">  <span v-if="errors.keterangan" id="jatuh_tempo_error" class="label label-danger">{{ errors.keterangan[0] }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">Total Akhir</font>
                                            <money style="text-align:right;" class="form-pembelian" readonly="" id="total_akhir" name="total_akhir" placeholder="Total Akhir"  v-model="inputPembayaranPembelian.total_akhir" v-bind="separator" ></money> 
                                        </div>

                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">Pembayaran(F10)</font>
                                            <money style="text-align:right;" class="form-pembelian" v-shortkey.focus="['f10']" id="pembayaran" name="pembayaran" placeholder="Pembayaran"  v-model="inputPembayaranPembelian.pembayaran" v-bind="separator" autocomplete="off" ref="pembayaran"></money> 
                                        </div>

                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                     <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                         <font style="color: black">Kredit</font>
                                         <money style="text-align:right;" readonly="" class="form-pembelian" id="kredit" name="kredit" placeholder="Kredit"  v-model="inputPembayaranPembelian.kredit" v-bind="separator" ></money> 
                                     </div>

                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                         <font style="color: black">Kembalian</font>
                                         <money style="text-align:right;" readonly="" class="form-pembelian" id="kembalian" name="kembalian" placeholder="Kembalian"  v-model="inputPembayaranPembelian.kembalian" v-bind="separator" ></money> 
                                     </div>


                                 </div>
                             </div>

                              <input type="hidden"  name="status_pembelian" id="status_pembelian" v-model="inputPembayaranPembelian.status_pembelian">
                              <input type="hidden" name="ppn" id="ppn" v-model="inputPembayaranPembelian.ppn">
                              <input type="hidden" name="potongan" id="potongan" v-model="inputPembayaranPembelian.potongan" >

                             <div align="right"  style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                               <button v-if="inputPembayaranPembelian.kembalian >= 0 && inputPembayaranPembelian.kredit == 0" v-shortkey.push="['alt']" @shortkey="saveForm()" type="submit" class="btn btn-success btn-lg" id="btnSelesai" ><font style="font-size:20px;">Tunai(Alt)</font></button>

                               <button v-if="inputPembayaranPembelian.kredit > 0" type="submit" class="btn btn-success btn-lg" v-shortkey.push="['alt']" @shortkey="saveForm()" id="btnSelesai" ><font style="font-size:20px;">Hutang(Alt)</font> </button>

                               <button type="button" class="btn btn-default btn-lg"  v-on:click="closeModalX()" v-shortkey.push="['esc']" @shortkey="closeModalX()"><font style="font-size:20px;"> Tutup(Esc)</font></button>
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
            <h4 class="card-title" style="margin-bottom: 1px; margin-top: 1px;"> Pembelian </h4> 
            <div class="row" style="margin-bottom: 1px; margin-top: 1px;"> 

              <div class="col-md-3">
                <div class="card card-produk" style="margin-bottom: 1px; margin-top: 1px;">
                  <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                   
                    <selectize-component v-model="inputTbsPembelian.produk" :settings="placeholder_produk" id="produk" ref='produk'  > 
                      <option v-for="produks, index in produk" v-bind:value="produks.produk">{{produks.barcode}} || {{produks.kode_produk}} || {{ produks.nama_produk }}</option>
                      </selectize-component>
                      </div><!--/COL MD  3 --> 
                      <span v-if="errors.produk" id="produk_error" class="label label-danger">{{ errors.produk[0] }}</span>

                <span style="display: none;">
                <input class="form-control" type="text"  v-model="inputTbsPembelian.jumlah_produk"  name="jumlah_produk" id="jumlah_produk"  v-shortkey="['f6']" @shortkey="openSelectizeKas()">
                <input class="form-control" type="text"  v-model="inputTbsPembelian.harga_produk"  name="harga_produk" id="harga_produk" v-shortkey="['f4']" @shortkey="openSelectizeSuplier()">
                <input class="form-control" type="text"  v-model="inputTbsPembelian.id_produk_tbs"  name="id_produk_tbs" id="id_produk_tbs" v-shortkey="['f1']" @shortkey="openSelectizeProduk()">
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
                  <th  style="text-align:right;">Harga</th>
                  <th  style="text-align:right;">Potongan</th>
                  <th  style="text-align:right;">Pajak</th>
                  <th  style="text-align:right;">Subtotal</th>
                  <th  style="text-align:right;">Hapus</th>
                </tr>
              </thead>
              <tbody v-if="tbs_pembelians.length > 0 && loading == false"  class="data-ada">
                <tr v-for="tbs_pembelian, index in tbs_pembelians" >

                  <td>{{ tbs_pembelian.kode_produk }} - {{ tbs_pembelian.nama_produk }}</td>
                  <td>
                    <a href="#create-pembelian" v-bind:id="'edit-' + tbs_pembelian.id_tbs_pembelian" v-on:click="editEntryJumlah(tbs_pembelian.id_tbs_pembelian, index,tbs_pembelian.nama_produk,tbs_pembelian.subtotal)"><p align='right'>{{ tbs_pembelian.jumlah_produk_pemisah }}</p>
                    </a>
                  </td>
                  <td>
                    <a href="#create-pembelian" v-bind:id="'edit-' + tbs_pembelian.id_tbs_pembelian" v-on:click="editEntryHarga(tbs_pembelian.id_tbs_pembelian, index,tbs_pembelian.nama_produk,tbs_pembelian.subtotal)" ><p align='right'>{{ tbs_pembelian.harga_pemisah }}</p>
                    </a>
                  </td>
                  <td>
                    <a href="#create-pembelian" v-bind:id="'edit-' + tbs_pembelian.id_tbs_pembelian" v-on:click="editEntryPotongan(tbs_pembelian.id_tbs_pembelian, index,tbs_pembelian.nama_produk,tbs_pembelian.jumlah_produk,tbs_pembelian.harga_produk,tbs_pembelian.subtotal)"
                    ><p align='right'>{{ tbs_pembelian.potongan }} | {{ Math.round(tbs_pembelian.potongan_persen,2) }} %</p>
                    </a>
                  </td>
                  <td>
                    <a href="#create-pembelian" v-bind:id="'edit-' + tbs_pembelian.id_tbs_pembelian" v-on:click="editEntryTax(tbs_pembelian.id_tbs_pembelian, index,tbs_pembelian.nama_produk,tbs_pembelian.jumlah_produk,tbs_pembelian.harga_produk,tbs_pembelian.potongan,tbs_pembelian.ppn_produk,tbs_pembelian.subtotal)" ><p align='right'>{{ tbs_pembelian.tax}} | {{ Math.round(tbs_pembelian.tax_persen, 2) }} %</p>
                    </a>
                  </td>
                  <td><p id="table-subtotal" align="right">{{ tbs_pembelian.subtotal_tbs }}</p></td>
                  <td style="text-align:right;"> 
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

      <div class="col-md-3"><!-- COL SM 4 --> 
        <div class="card card-stats"><!-- CARD --> 
            <div class="card-header" data-background-color="blue">
                <i class="material-icons">shopping_cart</i>
            </div>
          <div class="card-content"> 
              <p class="category"><h4>Subtotal</h4></p>
              <h3 class="card-title"><b><money class="form-subtotal" style="text-align:right;" v-model="inputPembayaranPembelian.subtotal" readonly v-bind="separator" ></money></b></h3>
            <div class="row"> 
              <div class="col-md-10 col-xs-10"> 
                   <h4>Suplier </h4> 
                  <selectize-component v-model="inputPembayaranPembelian.suplier" :settings="placeholder_suplier" id="suplier" name="suplier" ref='suplier'> 
                      <option v-for="supliers, index in suplier" v-bind:value="supliers.id">{{ supliers.nama_suplier }}</option>
                  </selectize-component>
              </div> 
              <div class="col-md-1 col-xs-1" style="padding-left:0px;padding-top:43;">
                <div class="row" style="margin-top:-10px">
                <button class="btn btn-primary btn-icon waves-effect waves-light" v-on:click="tambahSupplier()" type="button"> <i class="material-icons" >add</i> </button>
                </div>
             </div>
              </div><!-- end row-->
              <div class="row">
                <div class="col-md-10 col-xs-10" > 
                      <h4>Kas </h4> 
                      <div v-if="tbsPembelianData.kas_default == 0">
                      <selectize-component style="text-align:left;" v-model="inputPembayaranPembelian.cara_bayar" :settings="placeholder_cara_bayar"  id="cara_bayar" name="cara_bayar" ref='cara_bayar'> 
                      <option v-for="cara_bayars, index in cara_bayar" v-bind:value="cara_bayars.id" >{{ cara_bayars.nama_kas }}</option>
                      </selectize-component>
                      <br>
                      <span class="label label-danger"><router-link :to="{name: 'indexKas'}" class="menu-nav">Tambah Kas Disini</router-link></span> 
                      </div>
                      <div v-else>
                      <selectize-component style="text-align:left;" v-model="inputPembayaranPembelian.cara_bayar" :settings="placeholder_cara_bayar" id="cara_bayar" name="cara_bayar" ref='cara_bayar'> 
                      <option v-for="cara_bayars, index in cara_bayar" v-bind:value="cara_bayars.id">{{ cara_bayars.nama_kas }}</option>
                      </selectize-component>
                      </div>
                </div> 
              <div class="col-md-1 col-xs-1" style="padding-left:0px;padding-top:43;">
                <div class="row" style="margin-top:-10px">
                <button class="btn btn-primary btn-icon waves-effect waves-light" v-on:click="tambahModalKas()" type="button"> <i class="material-icons" >add</i> </button>
                </div>
             </div>
              </div> 
              
          </div> 
          
          <div class="card-footer">
		          	 <div class="row"> 
		              <div class="col-md-6 col-xs-6"> 
		                <button type="button btn-lg"  class="btn btn-success" id="bayar" v-on:click="selesaiPembelian()" v-shortkey.push="['f2']" @shortkey="selesaiPembelian()" ><font style="font-size:20px;">Bayar(F2)</font></button>
		              </div>
		              <div class="col-md-6 col-xs-6"> 
		                <button type="submit btn-lg"  class="btn btn-danger" id="btnBatal" v-on:click="batalPembelian()" v-shortkey.push="['f3']" @shortkey="batalPembelian()" ><font style="font-size:20px;">Batal(F3)</font>  </button>
		            </div>
		        </div>
            </div>
        </div>             
      </div><!-- COL SM 4 --> 
    </div><!-- ROW --> 
  </div>
</div>

	</div> 
</div> 
</template>
<style type="text/css">
.card-pembayaran{
    background-color:#82B1FF;
}
</style>

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
      url_suplier : window.location.origin+(window.location.pathname).replace("dashboard", "suplier"),
      url_tambah_kas : window.location.origin+(window.location.pathname).replace("dashboard", "kas"),
			inputTbsPembelian: {
				produk : '',
				jumlah_produk : '',
				harga_produk : '',
				id_tbs : '',
			},
			inputPembayaranPembelian:{
				potongan_persen: 0,
				potongan_faktur: 0,
				subtotal: 0,
				pembayaran: 0,
				total_akhir: 0,
				kembalian: 0,
				kredit: 0,
				jatuh_tempo: '',
				keterangan: '',
				subtotal_number_format:0,	
				suplier: '',
				cara_bayar: '',
				status_pembelian: '',
				ppn: '',
				potongan: 0,
			},
      tambahSuplier: {
        nama_suplier : '',
        alamat : '',
        no_telp : '',
        contact_person : '',
      },
      tambahKas: {
        kode_kas : '',
        nama_kas : '',
        status_kas : 0,
        default_kas : 0
      },
			placeholder_produk: {
				placeholder: '--PILIH PRODUK (F1)--',
        sortField: 'text',
        openOnFocus : true
			},
			placeholder_suplier: {
				placeholder: '--PILIH SUPPLIER (F4)--',
        sortField: 'text',
        openOnFocus : true
			},
			placeholder_cara_bayar: {
				placeholder: '--PILIH CARA BAYAR (F6)--',
        sortField: 'text',
        openOnFocus : true
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
	},
	watch: {
        // whenever question changes, this function will run
        pencarian: function (newQuestion) {
        	this.getHasilPencarian();
        	this.loading = true;  
        },
        'inputTbsPembelian.produk': function (newQuestion) {
        	this.pilihProduk();  
        },
        'inputPembayaranPembelian.pembayaran':function (val){
        if (val == '') {
            val = 0
        }
        this.hitungKembalian(val)
    	},
    	'inputPembayaranPembelian.potongan_faktur':function(){
        this.hitungPotonganFaktur()
    }

    },
    methods: {
          openSelectizeProduk(){      
          this.$refs.produk.$el.selectize.focus();
        },
        openSelectizeSuplier(){      
          this.$refs.suplier.$el.selectize.focus();
        },
        openSelectizeKas(){      
          this.$refs.cara_bayar.$el.selectize.focus();
        },
    	getResults(page) {
    		var app = this;	
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		axios.get(app.url+'/view-tbs-pembelian?page='+page)
    		.then(function (resp) {
    			app.tbs_pembelians = resp.data.data;
    			app.tbsPembelianData = resp.data; 			
    			app.loading = false;
    			app.seen = true;
          app.openSelectizeProduk();

                if (app.inputPembayaranPembelian.subtotal == 0) {         
                    $.each(resp.data.data, function (i,item) { 
                       app.inputPembayaranPembelian.subtotal += parseFloat(resp.data.data[i].subtotal)
                       app.inputPembayaranPembelian.total_akhir += parseFloat(resp.data.data[i].subtotal)
                       app.inputPembayaranPembelian.kredit += parseFloat(resp.data.data[i].subtotal)
                    console.log(resp.data.data[i].subtotal);
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
    	getHasilPencarian(page){
    		var app = this;
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		axios.get(app.url+'/pencarian-tbs-pembelian?search='+app.pencarian+'&page='+page)
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
    	}, //END FUNGSI UNTUK PAGINATION SEARCH     
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
              $.each(resp.data, function (i, item) {
                  if (resp.data[i].nama_suplier == "UMUM") {
                      app.inputPembayaranPembelian.suplier  = resp.data[i].id 
                  }
                });
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
      tambahSupplier(){
       $("#modal_tambah_suplier").show();
       this.$refs.nama_suplier.$el.focus();
      },
      tambahModalKas(){
       $("#modal_tambah_kas").show();
       this.$refs.kode_kas.$el.focus(); 
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
    saveFormKas() {
      var app = this;
      var newkas = app.tambahKas;
      axios.post(app.url_tambah_kas, newkas)
      .then(function (resp) {
        app.message = 'Menambah Kategori Transaksi '+ app.tambahKas.nama_kas;
        app.alert(app.message);
        app.tambahKas.kode_kas = ''
        app.tambahKas.nama_kas = ''
        app.tambahKas.status_kas = 0
        app.tambahKas.default_kas = 0
        app.errors = '';
         app.dataCaraBayar();
        $("#modal_tambah_kas").hide();

      })
      .catch(function (resp) {
        app.success = false;
        app.errors = resp.response.data.errors;
      });
    },
    defaultKas() {
         var app = this;
          var toogle = app.tambahKas.default_kas;

         if (toogle == true) {
          app = this;
      app.$swal({
      title: "Konfirmasi",
      text: "Apakah Anda Yakin Ingin Mengubah Kas Utama ?",
      icon: "warning",
      buttons: true,
      dangerMode: true,
      })
      .then((confirm) => {
        if (confirm) {
        toogle.prop('checked', true);
        } else {
        toogle.prop('checked', false);
      }
      });
         }  
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
    		axios.delete(app.url+'/hapus-tbs-pembelian/'+id)
    		.then(function (resp) {
    			app.getResults();
				if (app.inputPembayaranPembelian.subtotal != 0) {    
				var subtotal = parseInt(app.inputPembayaranPembelian.subtotal) - parseInt(resp.data.subtotal)
				app.inputPembayaranPembelian.subtotal = subtotal                       
               	app.inputPembayaranPembelian.total_akhir  = subtotal 
               	app.inputPembayaranPembelian.kredit  = subtotal
               	}
    			app.alert("Menghapus Produk "+nama_produk);
    			app.loading = false;
    			app.inputTbsPembelian.id_tbs = ''
    		})
    		.catch(function (resp) {

    			app.loading = false;
    			alert("Tidak dapat Menghapus Produk "+nama_produk);
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
				swal({
				  title: titleCase(nama_produk),
				  html:
				    '<div class="col-sm-6  col-xs-6"><lable>Jumlah</lable><br><input type="number" id="swal-input1" class="swal2-input" autofocus></div>' +
				    '<div class="col-sm-6  col-xs-6"><lable>Harga</lable><br><input type="number" id="swal-input2" class="swal2-input" value="'+harga_produk+'"></div>',
				  	allowEnterKey : false,
					showCloseButton: true, 
					showCancelButton: true,                        
					focusConfirm: false, 
					confirmButtonText:'<i class="fa fa-thumbs-o-up"></i> OK', 
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
											
											axios.get(app.url+'/cek-tbs-pembelian?id='+id_produk)
											.then(function (resp) {
											    	if (resp.data > 0) {
											        		swal({
											                title: "Peringatan",
											                text:"Produk "+titleCase(nama_produk)+" Sudah Ada, Silakan Pilih Produk Lain !",
											               });
											        }
											        else{
			    											var jumlah_produk = result[0];
			    											var harga_produk = result[1];
									                       app.submitJumlahProduk(id_produk,jumlah_produk,harga_produk,nama_produk);
											        }
										    });
										  } else {
										    app.$swal.close();
										  }
										});
							}else{ 
								$("#id_produk_tbs").val(id_produk); 
								$("#jumlah_produk").val(result[0]); 
								$("#harga_produk").val(harga_produk); 
										axios.get(app.url+'/cek-tbs-pembelian?id='+id_produk)
										.then(function (resp) {
											    	if (resp.data > 0) {
											        		swal({
											                title: "Peringatan",
											                text:"Produk "+titleCase(nama_produk)+" Sudah Ada, Silakan Pilih Produk Lain !",
											               });
											        }
											        else{
											        		var jumlah_produk = result[0];
			    											app.submitJumlahProduk(id_produk,jumlah_produk,harga_produk,nama_produk);
											        }
										    });
							} 
						});
		},//END fungsi isiJumlahProduk
		submitJumlahProduk(id_produk,jumlah_produk,harga_produk,nama_produk){
				var app = this;
				app.loading = true;
					 axios.get(app.url+'/proses-tambah-tbs-pembelian?id_produk_tbs='+id_produk+'&jumlah_produk='+jumlah_produk+'&harga_produk='+harga_produk)
						.then(function (resp) {
							app.alert("Menambahkan Produk "+titleCase(nama_produk));
							app.loading = false;
							app.getResults();
							if (app.inputPembayaranPembelian.subtotal != 0) { 
							var subtotal = parseInt(app.inputPembayaranPembelian.subtotal) + parseInt(resp.data.subtotal)
							app.inputPembayaranPembelian.subtotal = subtotal                       
                			app.inputPembayaranPembelian.total_akhir  = subtotal 
                			app.inputPembayaranPembelian.kredit  = subtotal
                			}

							})
							.catch(function (resp) {
							app.loading = false;
							alert("Tbs Pembelian tidak bisa ditambahkan");
					});
		},//END PROSES TAMBAH PRODUK TBS
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
							if (app.inputPembayaranPembelian.subtotal != 0) {    
							var subtotal = (parseInt(app.inputPembayaranPembelian.subtotal) - parseInt(subtotal_lama)) + parseInt(resp.data.subtotal);
							app.inputPembayaranPembelian.subtotal = subtotal                       
                			app.inputPembayaranPembelian.total_akhir  = subtotal 
                			app.inputPembayaranPembelian.kredit  = subtotal
                			}
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
							if (app.inputPembayaranPembelian.subtotal != 0) {    
							var subtotal = (parseInt(app.inputPembayaranPembelian.subtotal) - parseInt(subtotal_lama)) + parseInt(resp.data.subtotal);
							app.inputPembayaranPembelian.subtotal = subtotal                       
                			app.inputPembayaranPembelian.total_akhir  = subtotal 
                			app.inputPembayaranPembelian.kredit  = subtotal
                			}

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
							if (app.inputPembayaranPembelian.subtotal != 0) {    
							var subtotal = (parseInt(app.inputPembayaranPembelian.subtotal) - parseInt(subtotal_lama)) + parseInt(resp.data.subtotal);
							app.inputPembayaranPembelian.subtotal = subtotal                       
                			app.inputPembayaranPembelian.total_akhir  = subtotal 
                			app.inputPembayaranPembelian.kredit  = subtotal
                			}

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
						axios.get(app.url+'/cek-persen-tax-pembelian?tax_edit_produk='+result[0]+'&id_tax='+id+'&ppn_produk='+result[1])
						.then(function (resp) {
									if (resp.data == 1) {
									swal({
										title: "Peringatan",
										text:"Pajak Tidak Boleh Lebih Dari 100%!",
										});
									}
								else{
								var pajak = result[0];
								var ppn_edit = result[1];
								app.submitEditTax(pajak,id,nama_produk,ppn_edit,subtotal_lama);
								}
						});
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
							if (app.inputPembayaranPembelian.subtotal != 0) {    
							var subtotal = (parseInt(app.inputPembayaranPembelian.subtotal) - parseInt(subtotal_lama)) + parseInt(resp.data.subtotal);
							app.inputPembayaranPembelian.subtotal = subtotal                       
                			app.inputPembayaranPembelian.total_akhir  = subtotal 
                			app.inputPembayaranPembelian.kredit  = subtotal
                			}

					})
					.catch(function (resp) {
					app.loading = false;
					alert("Pajak Produk tidak bisa diedit");
				});
    },//END METHOD EDIT TAX TBS
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

    					var subtotal = parseInt(app.inputPembayaranPembelian.subtotal) - parseInt(resp.data.subtotal)
						app.getResults();
    					app.alert("Membatalkan Transaksi Pembelian");
    					   app.inputPembayaranPembelian.suplier = ''
				            app.inputPembayaranPembelian.subtotal = 0
				            app.inputPembayaranPembelian.jatuh_tempo = ''
				            app.inputPembayaranPembelian.potongan_persen = 0
				            app.inputPembayaranPembelian.potongan_faktur = 0
				            app.inputPembayaranPembelian.total_akhir = 0
				            app.inputPembayaranPembelian.pembayaran = 0
				            app.hitungKembalian(app.inputPembayaranPembelian.pembayaran)
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
    	selesaiPembelian(){
    		var app = this;
    		if (app.inputPembayaranPembelian.suplier == '') { 
					swal('Oops...', 'Suplier Belum Dipilih!', 'error'); 
				}
			else if (app.inputPembayaranPembelian.cara_bayar == '') { 
				 swal('Oops...', 'Cara Barang Belum Dipilih!', 'error'); 
			} 
			else{ 
				$("#modal_selesai").show();
				this.$refs.pembayaran.$el.focus(); 
			} 
    	},//END SWAL selesaiPembelian
    	btnCloseModal(){
    		$("#modal_selesai").hide(); 
    	},
    	closeModalX(){
    		$("#modal_selesai").hide();
        $("#modal_tambah_suplier").hide(); 
        $("#modal_tambah_kas").hide();  
    	},
    	hitungPotonganPersen(){

			    var potonganPersen = this.inputPembayaranPembelian.potongan_persen

			    if (potonganPersen > 100) {

					swal('Oops...','Potongan Tidak Bisa Lebih Dari 100%','error'); 
			        this.inputPembayaranPembelian.total_akhir = this.inputPembayaranPembelian.subtotal;
			        this.inputPembayaranPembelian.potongan_faktur = 0
			        this.inputPembayaranPembelian.potongan_persen = 0
			        this.inputPembayaranPembelian.potongan = 0
					this.hitungKembalian(this.inputPembayaranPembelian.pembayaran)
			    
			    }else{

			        if (potonganPersen == '') {
			            potonganPersen = 0
			        }

			    var potongan_nominal = parseFloat(this.inputPembayaranPembelian.subtotal) * (parseFloat(potonganPersen)) / 100; 
			    var total_akhir = parseFloat(this.inputPembayaranPembelian.subtotal,10) - parseFloat(potongan_nominal,10);

			        this.inputPembayaranPembelian.potongan_faktur = potongan_nominal.toFixed(2)
			        this.inputPembayaranPembelian.total_akhir = total_akhir.toFixed(2)
			        this.inputPembayaranPembelian.kredit = total_akhir.toFixed(2)  
			        this.inputPembayaranPembelian.potongan = potongan_nominal
			        this.hitungKembalian(this.inputPembayaranPembelian.pembayaran)

			    }
		},
		hitungPotonganFaktur(){

		    var potonganFaktur = this.inputPembayaranPembelian.potongan_faktur;

		    if (potonganFaktur == '') {
		        potonganFaktur = 0
		    }
		    var potongan_persen = (parseFloat(potonganFaktur)) / parseFloat(this.inputPembayaranPembelian.subtotal) * 100;
		    var total_akhir = parseFloat(this.inputPembayaranPembelian.subtotal) - parseFloat(potonganFaktur);

		    if (potongan_persen > 100) {
		    	swal('Oops...','Potongan Tidak Bisa Lebih Dari 100%','error'); 
		        this.inputPembayaranPembelian.total_akhir = this.inputPembayaranPembelian.subtotal;
		        this.inputPembayaranPembelian.potongan_faktur = 0
		        this.inputPembayaranPembelian.potongan_persen = 0
		        this.inputPembayaranPembelian.potongan = 0
		        this.hitungKembalian(this.inputPembayaranPembelian.pembayaran)

		    }else{
		      this.inputPembayaranPembelian.potongan_persen = potongan_persen.toFixed(2)
		      this.inputPembayaranPembelian.total_akhir = total_akhir.toFixed(2)
		      this.inputPembayaranPembelian.kredit = total_akhir.toFixed(2)
		      this.inputPembayaranPembelian.potongan = potonganFaktur
		      this.hitungKembalian(this.inputPembayaranPembelian.pembayaran);
		  }

		},
		hitungKembalian(val){
		    var kembalian = parseFloat(val) - parseFloat(this.inputPembayaranPembelian.total_akhir);   
		    if (kembalian >= 0) {

		        this.inputPembayaranPembelian.kembalian = kembalian 
		        this.inputPembayaranPembelian.kredit = 0
		        this.inputPembayaranPembelian.status_pembelian = "Tunai";
		        $("#btn-tunai-pembelian").show();
				$("#btn-hutang-pembelian").hide();
		    }else{
		      this.inputPembayaranPembelian.kembalian = 0  
		      this.inputPembayaranPembelian.kredit = parseFloat(this.inputPembayaranPembelian.total_akhir) - parseFloat(val);
		      this.inputPembayaranPembelian.status_pembelian = "Hutang";
		      	$("#btn-tunai-pembelian").hide();
				$("#btn-hutang-pembelian").show();
		  }        
		},
    	saveForm(){
    	    var app = this;
			var status_pembelian = app.inputPembayaranPembelian.status_pembelian;
			var jatuh_tempo = app.inputPembayaranPembelian.jatuh_tempo;
			if ((status_pembelian == 'Hutang' || status_pembelian == '') && jatuh_tempo == '') {
				swal("Oops...","Jatuh Tempo Belum Diisi!","error");
				this.$refs.jatuh_tempo.$el.focus();
			}else{
			app.$router.replace('/create-pembelian');
			app.prosesTransaksiSelesai();
			}
			
    },//akhir btn bayar tunai
    prosesTransaksiSelesai(){
    		var app = this;
    		var kas = app.inputPembayaranPembelian.cara_bayar;
			var pembayaran = app.inputPembayaranPembelian.pembayaran;
			if (pembayaran == '') {
				pembayaran = 0;
			}
			axios.get(app.url+'/cek-total-kas-pembelian?kas='+kas)
			.then(function (resp) {
			if (resp.data.total_kas == '' || resp.data.total_kas == null) {
			 	var total_kas = 0;
			}else{
				var total_kas = resp.data.total_kas;
			}
			var data_produk_pembelian = resp.data.data_produk_pembelian;
			var hitung_sisa_kas = parseFloat(total_kas) - parseFloat(pembayaran);
			if (hitung_sisa_kas >= 0) {
					if (data_produk_pembelian == 0){
						swal('Oops...','Belum Ada Produk Yang Diinputkan','error'); 
					}
					else{
						var newPembelian = app.inputPembayaranPembelian;
						axios.post(app.url, newPembelian)
						.then(function (resp) {
						app.message = 'Berhasil Menambah Pembelian';
						app.alert(app.message);
						app.$router.replace('/pembelian');
						window.open('pembelian/cetak-besar-pembelian/'+resp.data.respons_pembelian,'_blank');
						})
						.catch(function (resp) {
						app.success = false;
						});
					}
			}else{
				swal('Oops...','Kas Anda Tidak Cukup Untuk Melakukan Pembayaran','error');
			}
    	});
    }//akhir prosesTransaksiSelesai
 }
}
</script>