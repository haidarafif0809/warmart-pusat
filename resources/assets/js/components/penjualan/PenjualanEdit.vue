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
        <li><router-link :to="{name: 'indexPenjualan'}">Lap. Penjualan</router-link></li>
        <li class="active">Edit Penjualan</li>
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
                    <input class="form-control" autocomplete="off" placeholder="Kode Kas" v-model="tambahKas.kode_kas" type="text" name="kode_kas" id="kode_kas"  autofocus="" ref="kode_kas">
                    <span v-if="errors.kode_kas" id="kode_kas_error" class="label label-danger">{{ errors.kode_kas[0] }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="nama_kas" class="col-md-3 control-label">Nama Kas</label>
                  <div class="col-md-9">
                    <input class="form-control" autocomplete="off" placeholder="Nama Kas" v-model="tambahKas.nama_kas" type="text" name="nama_kas" id="nama_kas" ref="nama_kas" >
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
                     <font style="color: black">Pelanggan(F4)</font><br>
                     <selectize-component v-model="penjualan.pelanggan" :settings="placeholder_pelanggan" id="pelanggan" ref='pelanggan'> 
                      <option v-for="pelanggans, index in pelanggan" v-bind:value="pelanggans.id">{{ pelanggans.pelanggan }}</option>
                    </selectize-component>
                    <br v-if="errors.pelanggan">  <span v-if="errors.pelanggan" id="pelanggan_error" class="label label-danger">{{ errors.pelanggan[0] }}</span>
                  </div>
                </div>
                <div class="col-md-5 col-xs-10">
                  <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                    <font style="color: black">Kas(F6)</font><br>
                    <selectize-component v-model="penjualan.kas" :settings="placeholder_kas" id="kas" ref='kas'> 
                      <option v-for="kass, index in kas" v-bind:value="kass.id">{{ kass.nama_kas }}</option>
                    </selectize-component>
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
            </div>

            <div class="row">

              <div class="col-md-3 col-xs-6">
                <div class="form-group" style="margin-right: 1px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px; width:130px;">
                  <font style="color: black">Potongan(F7)</font>  
                  <money class="form-subtotal" style="text-align:right" v-model="penjualan.potongan_faktur" v-bind="separator" v-shortkey.focus="['f7']"></money>
                </div>
              </div>
              <div class="col-md-3 col-xs-6">
                <div class="form-group" style="margin-right: 10px; margin-left: 1px; margin-bottom: 1px; margin-top: 1px;">
                  <font style="color: black">(%)(F8)</font>      
                  <input type="number" class="form-subtotal" style="text-align:right" value="0" v-model="penjualan.potongan_persen" v-on:blur="potonganPersen" v-shortkey.focus="['f8']" />
                </div>
              </div>

              <div class="col-md-6 col-xs-12">
                <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                  <font style="color: black">Jatuh Tempo</font> 
                  <datepicker :input-class="'form-control'" placeholder="Jatuh Tempo" v-model="penjualan.jatuh_tempo"></datepicker>
                  <br v-if="errors.jatuh_tempo">  <span v-if="errors.jatuh_tempo" id="jatuh_tempo_error" class="label label-danger">{{ errors.jatuh_tempo[0] }}</span>
                </div>
              </div>

            </div>

            <div class="row">
              <div class="col-md-6 col-xs-12">
                <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                  <font style="color: black">Total Akhir</font>
                  <money class="form-penjualan" readonly=""  style="text-align:right" id="total_akhir" name="total_akhir" placeholder="Total Akhir"  v-model="penjualan.total_akhir" v-bind="separator" ></money> 
                </div>

                <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                  <font style="color: black">Pembayaran(F10)</font>
                  <money class="form-penjualan" style="text-align:right" v-shortkey.focus="['f10']" id="pembayaran" name="pembayaran" placeholder="Pembayaran"  v-model="penjualan.pembayaran" v-bind="separator" autocomplete="off" ref="pembayaran"></money> 
                </div>

              </div>
              <div class="col-md-6 col-xs-12">

               <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                 <font style="color: black">Kredit</font>
                 <money readonly="" style="text-align:right" class="form-penjualan" id="kredit" name="kredit" placeholder="Kredit"  v-model="penjualan.kredit" v-bind="separator" ></money> 
               </div>                                       

               <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                 <font style="color: black">Kembalian</font>
                 <money readonly="" style="text-align:right" class="form-penjualan" id="kembalian" name="kembalian" placeholder="Kembalian"  v-model="penjualan.kembalian" v-bind="separator" ></money> 
               </div>

             </div>
           </div>

           <div align="right"  style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
             <button v-if="penjualan.kembalian >= 0 && penjualan.kredit == 0" type="button" class="btn btn-success btn-lg" id="btnSelesai" v-on:click="selesaiPenjualan()" v-shortkey.push="['alt']" @shortkey="selesaiPenjualan()"><font style="font-size:20px;">Tunai(Alt)</font></button>

             <button v-if="penjualan.kredit > 0" type="button" class="btn btn-success btn-lg" id="btnSelesai" v-on:click="selesaiPenjualan()" v-shortkey.push="['alt']" @shortkey="selesaiPenjualan()"><font style="font-size:20px;"> Piutang(Alt)</font></button>

             <button type="button" class="btn btn-default btn-lg"  v-on:click="closeModal()" v-shortkey.push="['esc']" @shortkey="closeModal()"><font style="font-size:20px;"> Tutup(Esc)</font></button>
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


<!-- small modal --> 
<div class="modal" id="modalJumlahProduk" role="dialog" tabindex="-1"  aria-labelledby="myModalLabel" aria-hidden="true" > 
  <div class="modal-dialog modal-medium"> 
    <div class="modal-content"> 
      <div class="modal-header"> 
        <button type="button" class="close"  v-on:click="closeModalJumlahProduk()" v-shortkey.push="['f9']" @shortkey="closeModalJumlahProduk()"> &times;</button>  
      </div> 
      <form class="form-horizontal" v-on:submit.prevent="submitProdukPenjualan(inputTbsPenjualan.jumlah_produk)">  
        <div class="modal-body text-center"> 
          <h3><b>{{inputTbsPenjualan.nama_produk}}</b> </h3> 
          <input class="form-control" type="number" v-model="inputTbsPenjualan.jumlah_produk" placeholder="Isi Jumlah Produk" name="jumlah_produk" id="jumlah_produk" ref="jumlah_produk" autocomplete="off" step="0.01"> 
        </div> 
        <div class="modal-footer"> 
          <button type="button" class="btn btn-simple"   v-on:click="closeModalJumlahProduk()" v-shortkey.push="['f9']" @shortkey="closeModalJumlahProduk()">Close(F9)</button> 
          <button type="button" class="btn btn-info btn-lg"   v-on:click="submitProdukPenjualan(inputTbsPenjualan.jumlah_produk)">Tambah</button> 
        </div> 
      </form> 
    </div> 
  </div> 
</div> 
<!--    end small modal --> 



<div class="modal" id="modal_setting" role="dialog" data-backdrop=""> 
  <div class="modal-dialog"> 
    <!-- Modal content--> 
    <div class="modal-content"> 
      <div class="modal-header"> 
        <button type="button" class="close" data-dismiss="modal"> <i class="material-icons">close</i></button> 
        <h4 class="modal-title"> 
          <div class="alert-icon"> 
            <b>Setting Penjualan POS</b> {{setting_penjualan_pos.jumlah_produk}}
          </div> 
        </h4> 
      </div> 
      <form class="form-horizontal" > 
        <div class="modal-body"> 
          <div class="card" style="margin-bottom:1px; margin-top:1px;">

            <table class="table" style="margin-bottom:10px; margin-top:10px; margin-right:10px; margin-left:10px;">

              <tbody style="margin-bottom:10px; margin-top:10px; margin-right:10px; margin-left:10px;">
                <tr>
                  <td class="text-primary"><b># Jumlah Otomatis Satu ?</b> </td>
                  <td class="text-primary"><b>:</b> </td>
                  <td class="text-primary">
                    <div class="togglebutton"> 
                      <label> 
                        <input type="checkbox" v-model="setting_penjualan_pos.jumlah_produk" value="1"> 
                        <b v-if="setting_penjualan_pos.jumlah_produk == true">Ya</b> 
                        <b v-if="setting_penjualan_pos.jumlah_produk == false">Tidak</b> 
                      </label> 
                    </div>   </td>
                  </tr><br>

                  <tr>
                    <td class="text-primary"><b># Stok Boleh Minus ? </b> </td>
                    <td class="text-primary"><b>:</b> </td>
                    <td class="text-primary">
                      <div class="togglebutton">
                        <label>
                          <input type="checkbox" v-model="setting_penjualan_pos.stok" value="1">
                          <b v-if="setting_penjualan_pos.stok == true">Ya</b>
                          <b v-if="setting_penjualan_pos.stok == false">Tidak</b>
                        </label>
                      </div>  
                    </td>
                  </tr>

                  <tr>
                    <td class="text-primary"><b># Harga Jual</b> </td>
                    <td class="text-primary"><b>:</b> </td>
                    <td class="text-primary">
                      <b> 
                        <div class="form-group" style="margin-right:110px;">
                          <selectize-component :settings="hargaJual" v-model="setting_penjualan_pos.harga_jual" id="setting_harga_jual" ref='setting_harga_jual'> 
                            <option v-bind:value="1">Harga Jual 1</option>
                            <option v-bind:value="2">Harga Jual 2</option>
                          </selectize-component>
                        </div>
                      </b> 
                    </td>
                  </tr>

                </tbody>
              </table>  


              <div align="right" class="form-group" style="margin-right:10px;">
                <button type="button" class="btn btn-primary btn-lg" v-on:click="simpanSetting"><font style="font-size:20px;">Simpan</font></button>
                <button type="button" class="btn btn-default btn-lg close" data-dismiss="modal"> <font style="font-size:20px;">Batal</font></button>
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

        <div class="col-md-3 col-xs-9">
          <div class="card card-produk" style="margin-bottom: 1px; margin-top: 1px;">

            <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
              <selectize-component v-model="inputTbsPenjualan.produk" :settings="placeholder_produk" id="produk" ref='produk' > 
                <option v-for="produks, index in produk" v-bind:value="produks.produk">{{produks.barcode}} || {{produks.kode_produk}} || {{ produks.nama_produk }}</option>
              </selectize-component>
            </div>  

            <span style="display: none;">
              <input class="form-control" type="hidden"  v-model="inputTbsPenjualan.jumlah_produk"  name="jumlah_produk" id="jumlah_produk">
              <input class="form-control" type="hidden"  v-model="inputTbsPenjualan.potongan_produk"  name="potongan_produk" id="potongan_produk" v-shortkey="['f6']" @shortkey="openSelectizeKas()">
              <input class="form-control" type="hidden"  v-model="inputTbsPenjualan.id_tbs"  name="id_tbs" id="id_tbs" v-shortkey="['f4']" @shortkey="openSelectizePelanggan()">
              <input class="form-control" type="text"  v-model="penjualan.potongan"  name="potongan" id="potongan" v-shortkey="['f1']" @shortkey="openSelectizeProduk()">
            </span>

          </div>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-5"></div>
        <div class="col-md-1 col-xs-1">                
          <button class="btn btn-primary btn-round btn-fab btn-fab-mini" data-toggle="modal" data-target="#modal_setting">
            <i class="material-icons">settings</i>
          </button><b>Setting</b>
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

                  <th>Produk</th>
                  <th class="text-right">Jumlah</th>
                  <th class="text-center">Satuan</th>
                  <th class="text-right">Harga</th>
                  <th class="text-right">Potongan</th>
                  <th class="text-right">Subtotal</th>
                  <th class="text-center">Hapus</th>

                </tr>
              </thead>
              <tbody v-if="tbs_penjualan.length"  class="data-ada">
                <tr v-for="tbs_penjualan, index in tbs_penjualan" >

                  <td>{{ tbs_penjualan.kode_produk }} - {{ tbs_penjualan.nama_produk }}</td>

                  <td align="right">
                    <a v-bind:href="'#edit-penjualan/'+tbs_penjualan.id_penjualan_pos"  v-bind:id="'edit-' + tbs_penjualan.id_edit_tbs_penjualans" v-on:click="editEntry(tbs_penjualan.id_edit_tbs_penjualans, index,tbs_penjualan.nama_produk,tbs_penjualan.subtotal)">{{ new Intl.NumberFormat().format(tbs_penjualan.jumlah_produk) }}</a>
                  </td>

                  <td align="center">{{ tbs_penjualan.satuan }}</td>

                  <td align="right">{{ new Intl.NumberFormat().format(tbs_penjualan.harga_produk) }}</td>

                  <td align="right"><a v-bind:href="'#edit-penjualan/'+tbs_penjualan.id_penjualan_pos"  v-bind:id="'edit-' + tbs_penjualan.id_edit_tbs_penjualans" v-on:click="potonganEntry(tbs_penjualan.id_edit_tbs_penjualans, index,tbs_penjualan.nama_produk,tbs_penjualan.subtotal)">{{ tbs_penjualan.potongan }}</a></td>

                  <td align="right"> {{ new Intl.NumberFormat().format(tbs_penjualan.subtotal) }}</td>

                  <td align="center"><a v-bind:href="'#edit-penjualan/'+tbs_penjualan.id_penjualan_pos"  class="btn btn-xs btn-danger" v-bind:id="'delete-' + tbs_penjualan.id_edit_tbs_penjualans" v-on:click="deleteEntry(tbs_penjualan.id_edit_tbs_penjualans, index,tbs_penjualan.nama_produk,tbs_penjualan.subtotal)">Delete</a></td>
                </tr>
              </tbody>                    
              <tbody class="data-tidak-ada" v-else>
                <tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
              </tbody>
            </table>    

            <vue-simple-spinner v-if="loading"></vue-simple-spinner>

          </div>
        </div>
        <div class="col-md-3">

          <div class="card card-stats">
            <div class="card-header" data-background-color="blue">
              <i class="material-icons">shopping_cart</i>
            </div>
            <div class="card-content">
              <p class="category">Subtotal</p>
              <h3 class="card-title"><b><font style="font-size:32px;">{{ new Intl.NumberFormat().format(penjualan.subtotal) }}</font></b></h3>
            </div>
            <div class="card-footer">
              <div class="row"> 
                <div class="col-md-6 col-xs-6"> 
                  <button type="button" class="btn btn-success btn-lg" id="bayar" v-on:click="bayarPenjualan()" v-shortkey.push="['f2']" @shortkey="bayarPenjualan()"><font style="font-size:20px;">Bayar(F2)</font></button>
                </div>
                <div class="col-md-6 col-xs-6">
                  <button type="submit" class="btn btn-danger btn-lg" id="btnBatal" v-on:click="batalPenjualan()" v-shortkey.push="['f3']" @shortkey="batalPenjualan()"> <font style="font-size:20px;">Batal(F3) </font></button>
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

import { mapState } from 'vuex';
export default {
  data: function () {
    return {
      errors: [],
      tbs_penjualan: [],
      url : window.location.origin+(window.location.pathname).replace("dashboard", "penjualan"),
      url_produk : window.location.origin+(window.location.pathname).replace("dashboard", "produk"),
      url_tambah_kas : window.location.origin+(window.location.pathname).replace("dashboard", "kas"),

      inputTbsPenjualan: {
        nama_produk : '', 
        produk : '',
        jumlah_produk : '',
        potongan_produk : '',
        id_tbs : '',
      },
      penjualan : {
        pelanggan : 0,
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
      setting_penjualan_pos :{
        jumlah_produk : true,
        stok : true,
        harga_jual : 1
      },
      hargaJual: {
        placeholder: '--HARGA JUAL--'
      },
      tambahKas: {
        kode_kas : '',
        nama_kas : '',
        status_kas : 0,
        default_kas : 0
      },
      placeholder_produk: {
       placeholder: 'Cari Produk (F1) ...',
       sortField: 'text',
       maxOptions : 8,
       scrollDuration : 10,
       loadThrottle : 150,
       openOnFocus : false
     },
     placeholder_pelanggan: {
      placeholder: '--PILIH PELANGGAN (F4)--',
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
  app.$store.dispatch('LOAD_PRODUK_LIST')
  app.$store.dispatch('LOAD_PELANGGAN_LIST')
  app.$store.dispatch('LOAD_KAS_LIST')
  app.getResults();
  app.dataSettingPenjualanPos();
  app.id_penjualan_pos = app.$route.params.id;
},
computed : mapState ({    
  produk(){
    return this.$store.state.produk
  },
  pelanggan(){
    return this.$store.getters.pelangganTransaksi
  },
  kas(){
    return this.$store.state.kas
  }
}),
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

    openSelectizeProduk(){      
      this.$refs.produk.$el.selectize.focus();
    },
    openSelectizePelanggan(){      
      this.$refs.pelanggan.$el.selectize.focus();
    },
    openSelectizeKas(){      
      this.$refs.kas.$el.selectize.focus();
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
      app.tbs_penjualan = resp.data;
      app.loading = false;
      app.seen = true;
      app.openSelectizeProduk();

      if (app.penjualan.subtotal == 0) {   

       $.each(resp.data, function (i, item) {
         app.penjualan.subtotal += parseFloat(resp.data[i].subtotal)
         app.penjualan.total_akhir += parseFloat(resp.data[i].subtotal)
         app.penjualan.kredit += parseFloat(resp.data[i].subtotal)
       });
       app.getDataPenjualan();
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
  getDataPenjualan(){

    var app = this
    var id = app.$route.params.id;
    axios.get(app.url+'/cek-data-tbs-penjualan/'+id)
    .then(function (resp) {

     app.penjualan.pelanggan = resp.data.penjualan.pelanggan_id
     app.penjualan.kas = resp.data.penjualan.id_kas
     app.penjualan.jatuh_tempo = resp.data.penjualan.tanggal_jt_tempo
     app.penjualan.pembayaran = resp.data.penjualan.tunai

     if (app.penjualan.subtotal > 0) {
      app.penjualan.potongan = resp.data.penjualan.potongan
      app.penjualan.potongan_faktur = resp.data.penjualan.potongan                
    }

  })
    .catch(function (resp) {

      console.log(resp);
      alert("Tidak Dapat Memuat Penjualan");

    });
  },  
  pilihProduk() {
    if (this.inputTbsPenjualan.produk != '') {

      var app = this;
      var produk = app.inputTbsPenjualan.produk.split("|");
      var nama_produk = produk[1];
      var jumlah_produk = 1;

      if (this.setting_penjualan_pos.jumlah_produk == 1 || this.setting_penjualan_pos.jumlah_produk == true) {
        this.submitProdukPenjualan(jumlah_produk);
      }else{
        //this.isiJumlahProduk(nama_produk);//
        this.inputJumlahProduk(nama_produk);
      }    
    }
  },
  inputJumlahProduk(nama_produk){
    var app = this
    app.inputTbsPenjualan.nama_produk = nama_produk
    $("#modalJumlahProduk").show();
    app.$refs.jumlah_produk.focus(); 
  },
  tambahModalKas(){
   $("#modal_tambah_kas").show();
   $("#modal_selesai").hide();
   this.$refs.kode_kas.focus(); 
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
    app.$store.dispatch('LOAD_KAS_LIST')
    $("#modal_tambah_kas").hide();
    $("#modal_selesai").show();
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
isiJumlahProduk(nama_produk){
  var app = this;
  app.$swal({
    title: nama_produk,
    content: {
      element: "input",
      attributes: {
        placeholder: "Jumlah Produk",
        type: "number"
      },
    },
    closeOnEsc: true,
    closeOnClickOutside: false,
    buttons: {
      confirm: "OK"                   
    }


  }).then((value) => {
    if (value == ''){
      value = 1
    }
    this.submitProdukPenjualan(value);
  });
},
submitProdukPenjualan(value){

  if (value == 0) {

    this.$swal("Jumlah Produk Tidak Boleh Nol!")
    .then((value) => {
      this.$refs.jumlah_produk.focus(); 
    });

  }else{

    var app = this;
    var id = app.$route.params.id;
    var produk = app.inputTbsPenjualan.produk.split("|");
    var nama_produk = produk[1];

    app.inputTbsPenjualan.jumlah_produk = value;
    var newinputTbsPenjualan = app.inputTbsPenjualan;
    axios.post(app.url+'/proses-tambah-edit-tbs-penjualan/'+id, newinputTbsPenjualan)
    .then(function (resp) {

     if (resp.data.harga_jual == 0 || resp.data.harga_jual == '') {

      app.alertTbs("Harga Produk "+nama_produk+" 0!");
      app.inputTbsPenjualan.jumlah_produk = ''
      app.inputTbsPenjualan.produk = ''

    }else if (resp.data == 0) {

      app.alertTbs("Produk "+nama_produk+" Sudah Ada, Silakan Pilih Produk Lain!");
      app.inputTbsPenjualan.jumlah_produk = ''
      app.inputTbsPenjualan.produk = ''

    }else{

      var subtotal = parseFloat(app.penjualan.subtotal) + parseFloat(resp.data.subtotal)

      function cekTbs(tbs) { 
        return tbs.id_edit_tbs_penjualans === resp.data.id_edit_tbs_penjualans;
      }

      var index = app.tbs_penjualan.findIndex(cekTbs)        

      if (index >= 0) {
        app.tbs_penjualan[index].jumlah_produk = resp.data.jumlah_produk
        app.tbs_penjualan[index].subtotal = resp.data.subtotalKeseluruhan
      }else{

       app.tbs_penjualan.push(resp.data)
       app.tbs_penjualan.sort(function (descending) {
        return descending.id_edit_tbs_penjualans;
      });

     }
     app.openSelectizeProduk()

     app.penjualan.subtotal = subtotal.toFixed(2)                        
     app.penjualan.total_akhir  = subtotal.toFixed(2) 
     app.potonganPersen()
     app.inputTbsPenjualan.jumlah_produk = ''
     app.inputTbsPenjualan.produk = ''
     $("#modalJumlahProduk").hide(); 

   }

 })
    .catch(function (resp) {

      console.log(resp);                  
      app.loading = false;
      app.inputTbsPenjualan.jumlah_produk = ''
      app.inputTbsPenjualan.produk = ''
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

    app.inputTbsPenjualan.id_tbs = id;
    app.inputTbsPenjualan.jumlah_produk = value;
    var newinputTbsPenjualan = app.inputTbsPenjualan;
    axios.post(app.url+'/edit-jumlah-edit-tbs-penjualan', newinputTbsPenjualan)
    .then(function (resp) {

      var subtotal = (parseFloat(app.penjualan.subtotal) - parseFloat(subtotal_lama)) + parseFloat(resp.data.subtotal)
      function cekTbs(tbs) { 
        return tbs.id_edit_tbs_penjualans === id
      }

      var index = app.tbs_penjualan.findIndex(cekTbs) 

      app.tbs_penjualan[index].jumlah_produk = app.inputTbsPenjualan.jumlah_produk
      app.tbs_penjualan[index].subtotal = resp.data.subtotal      
      app.penjualan.subtotal = subtotal.toFixed(2)
      app.penjualan.total_akhir = subtotal.toFixed(2)
      app.potonganPersen()
      app.inputTbsPenjualan.jumlah_produk = ''
      app.inputTbsPenjualan.id_tbs = ''

    })
    .catch(function (resp) { 

      console.log(resp);     
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

  app.inputTbsPenjualan.id_tbs = id;
  app.inputTbsPenjualan.potongan_produk = value;
  var newinputTbsPenjualan = app.inputTbsPenjualan;

  axios.post(app.url+'/edit-potongan-edit-tbs-penjualan', newinputTbsPenjualan)
  .then(function (resp) {

    if (resp.data.status == 0) {

      app.$swal({
        text: "Tidak dapat Mengubah Potongan Produk, Periksa Kembali Inputan Anda!",
      });            

    }else if (resp.data.status == 1) {

      app.$swal({
        text: "Potongan Yang Anda Masukan Melebihi Subtotal!",
      });

    }else{

      var subtotal = (parseFloat(app.penjualan.subtotal) - parseFloat(subtotal_lama)) + parseFloat(resp.data.subtotal)
      function cekTbs(tbs) { 
        return tbs.id_edit_tbs_penjualans === id
      }

      var index = app.tbs_penjualan.findIndex(cekTbs)

      app.tbs_penjualan[index].potongan = resp.data.potongan
      app.tbs_penjualan[index].subtotal = resp.data.subtotal
      app.penjualan.subtotal = subtotal.toFixed(2)
      app.penjualan.total_akhir = subtotal.toFixed(2)
      app.potonganPersen()
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
  axios.delete(app.url+'/proses-hapus-edit-tbs-penjualan/'+id)
  .then(function (resp) {

    if (resp.data == 0) {

      app.alertTbs("Produk "+nama_produk+" Gagal Dihapus!")


    }else{
      var subtotal = parseFloat(app.penjualan.subtotal) - parseFloat(subtotal_lama)
      function cekTbs(tbs) { 
        return tbs.id_edit_tbs_penjualans === id
      }

      var index = app.tbs_penjualan.findIndex(cekTbs)
      app.tbs_penjualan.splice(index,1)
      app.penjualan.subtotal = subtotal.toFixed(2)
      app.penjualan.total_akhir = subtotal.toFixed(2)
      app.potonganPersen()
      app.alert("Menghapus Produk "+nama_produk)
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
      app.alert("Membatalkan Transaksi Edit Penjualan");
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

    }else if(resp.data.respons == 2){

      app.alertTbs("Gagal : Terjadi Kesalahan , Silakan Coba Lagi!");
      app.loading = false;

    }else{

      app.alert("Menyelesaikan Edit Penjualan");
      window.open('penjualan/cetak-kecil-penjualan/'+resp.data.respons_penjualan,'_blank');   
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
simpanSetting(){

  var app = this
  var newSettingPenjualanPos = app.setting_penjualan_pos;


  axios.post(app.url+'/proses-setting-penjualan-pos',newSettingPenjualanPos)
  .then(function (resp) {
    app.alert("Menyimpan Setting Penjualan POS");        
    $("#modal_setting").hide(); 
  })
  .catch(function (resp) {
    console.log(resp);
    alert("Tidak dapat Menyimpan Setting Penjualan POS");
  });
  

},
dataSettingPenjualanPos() {
  var app = this; 

  axios.get(app.url+'/cek-setting-penjualan-pos')
  .then(function (resp) {

    if (resp.data.status == 1) {

      app.setting_penjualan_pos.jumlah_produk = resp.data.jumlah_produk;
      app.setting_penjualan_pos.stok = resp.data.stok;
      app.setting_penjualan_pos.harga_jual = resp.data.harga_jual;
    }

  })
  .catch(function (resp) {
    console.log(resp);
    alert("Tidak Dapat Memuat Penjualan");
  });
},
closeModal(){

  $("#modal_selesai").hide(); 
},
closeModalX(){
  $("#modal_tambah_kas").hide(); 
  $("#modal_selesai").show(); 
},
closeModalJumlahProduk(){   
  $("#modalJumlahProduk").hide();  
  this.openSelectizeProduk(); 
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
    timer: 1000,
  });
}
}
}
</script>
