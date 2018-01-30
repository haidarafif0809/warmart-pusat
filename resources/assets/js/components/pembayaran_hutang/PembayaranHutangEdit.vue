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
            <ul class="breadcrumb">

                <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                <li><router-link :to="{name: 'indexPembayaranHutang'}">Pembayaran Hutang</router-link></li>
                <li class="active"> Edit Pembayaran Hutang</li>
            </ul>

        <div class="modal" id="modal_tambah_kas" role="dialog" data-backdrop=""> 
                <div class="modal-dialog"> 
                  <!-- Modal content--> 
                  <div class="modal-content"> 
                    <div class="modal-header"> 
                      <button type="button" class="close"  v-on:click="closeModalTambahKas()" v-shortkey.push="['esc']" @shortkey="closeModalTambahKas()"> &times;</button> 
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

          <div class="modal" id="modal_pilih_hutang" role="dialog" data-backdrop=""> 
                  <div class="modal-dialog modal-lg"> 
                         <!-- Modal content--> 
                            <div class="modal-content"> 
                                <div class="modal-header"> 
                                    <button type="button" class="close"  v-on:click="closeModalX()" v-shortkey.push="['esc']" @shortkey="closeModalX()"> &times;</button> 
                                    <h4 class="modal-title"> 
                                        <div class="alert-icon"> 
                                            <b>Silahkan Pilih Faktur Hutang !</b> 
                                        </div> 
                                    </h4> 
                                </div> 
                                  <div class="modal-body">
                                                <div class=" table-responsive ">
                                                   <div class="pencarian">
                                                    <input type="text" name="pencarian" v-model="pencarianhutang" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
                                                </div>

                                                <table class="table table-striped table-hover" v-if="seen">
                                                  <thead class="text-primary">
                                                    <tr>
                                                      <th >No Faktur</th>
                                                      <th >Total Pembelian</th>
                                                      <th  style="text-align:right;">Hutang</th>
                                                      <th  style="text-align:right;">Jatuh Tempo</th>
                                                      <th  style="text-align:right;">Waktu</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody v-if="dataSuplierHutang.length > 0 && loading == false"  class="data-ada" >
                                                    <tr v-for="dataSuplierHutangs, index in dataSuplierHutang" v-bind:id="'bayar-' + dataSuplierHutangs.id_pembelian" v-on:click="bayarHutangEntry(dataSuplierHutangs.id_pembelian, index,dataSuplierHutangs.no_faktur,dataSuplierHutangs.nilai_kredit,dataSuplierHutangs.jatuh_tempo)">
                                                      <td>{{ dataSuplierHutangs.no_faktur }}</td>
                                                       <td>{{ dataSuplierHutangs.total }}</td>
                                                        <td style="text-align:right;">{{ dataSuplierHutangs.nilai_kredit }}</td>
                                                        <td style="text-align:right;">{{ dataSuplierHutangs.tanggal_jatuh_tempo }}</td>
                                                        <td style="text-align:right;">{{ dataSuplierHutangs.waktu }}</td>
                                                    </tr>
                                                  </tbody>          
                                                  <tbody class="data-tidak-ada"  v-else-if="dataSuplierHutang.length == 0 && loading == false" >
                                                    <tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
                                                  </tbody>
                                                </table>  

                                                <vue-simple-spinner v-if="loading"></vue-simple-spinner>
                                                <div align="right"><pagination :data="dataSuplierHutangData" v-on:pagination-change-page="dataSupplierHutang" :limit="6"></pagination></div>
                                              </div>
                                  </div>
                            <div class="modal-footer">  
                           </div> 
                   </div>       
               </div> 
           </div> 
           <!-- / MODAL TOMBOL SELESAI --> 


          <div class="modal" id="modal_form_bayar_hutang" role="dialog" data-backdrop=""> 
                  <div class="modal-dialog"> 
                         <!-- Modal content--> 
                            <div class="modal-content"> 
                                <div class="modal-header"> 
                                    <button type="button" class="close"  v-on:click="closeModalBayarHutang()" v-shortkey.push="['esc']" @shortkey="closeModalBayarHutang()"> &times;</button> 
                                    <h4 class="modal-title"> 
                                        <div class="alert-icon"> 
                                            <b>Silahkan Isi Pembayaran Hutang !</b> 
                                        </div> 
                                    </h4> 
                                </div> 
                        <form class="form-horizontal"  v-on:submit.prevent="saveFormBayarHutang()"> 
                            <div class="modal-body"> 
                                <div class="card" style="margin-bottom:1px; margin-top:1px; margin-right:1px; margin-left:1px;">

                                    <div class="row">
                                      <div class="col-md-6">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">Hutang</font>
                                            <money style="text-align:right; font-size: 30px;" readonly="" class="form-control" id="nilai_kredit" name="nilai_kredit" placeholder="Kredit"  v-model="formBayarHutangTbs.nilai_kredit" v-bind="separator" ></money> 
                                        </div> 
                                      </div>

                                      <div class="col-md-6">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">Potongan</font>
                                            <money style="text-align:right; font-size: 30px;" class="form-control" id="potongan" name="potongan" placeholder="Kredit"  v-model="formBayarHutangTbs.potongan" v-bind="separator" ></money> 
                                        </div>                                        
                                      </div>

                                      <div class="col-md-12">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">Jumlah Bayar(F10)</font>
                                            <money style="text-align:right" class="form-penjualan" v-shortkey.focus="['f10']" id="jumlah_bayar" name="jumlah_bayar" v-model="formBayarHutangTbs.jumlah_bayar" v-bind="separator"  autocomplete="off" ref="jumlah_bayar"></money> 
                                        </div>
                                      </div>
                                    </div>

                                    <div align="right"  style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                        <button type="submit" class="btn btn-success btn-lg" id="btnTbs" ><font style="font-size:20px;">Tambah(Enter)</font></button>
                                        <button type="button" class="btn btn-default btn-lg"  v-on:click="closeModalBayarHutang()" v-shortkey.push="['esc']" @shortkey="closeModalBayarHutang()"> <font style="font-size:20px;">Tutup(Esc)</font></button>
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


            <!-- MODAL EDIT TBS --> 
            <div class="modal" id="modal_form_edit_bayar_hutang" role="dialog" data-backdrop=""> 
                <div class="modal-dialog"> 
                    <!-- Modal content--> 
                    <div class="modal-content"> 
                        <div class="modal-header"> 
                            <button type="button" class="close"  v-on:click="closeModalEditBayarHutang()" v-shortkey.push="['esc']" @shortkey="closeModalEditBayarHutang()"> &times;</button> 
                            <h4 class="modal-title"> 
                                <div class="alert-icon"> 
                                    <b>Edit Faktur Hutang : <span id="faktur_hutang_edit"></span></b> 
                                </div> 
                            </h4> 
                        </div> 
                        <form class="form-horizontal" v-on:submit.prevent="saveFormEditBayarHutang(formEditBayarHutangTbs.jumlah_bayar_lama)"> 
                            <div class="modal-body"> 
                                <div class="card" style="margin-bottom:1px; margin-top:1px; margin-right:1px; margin-left:1px;">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                                <font style="color: black">Hutang</font>
                                                <money style="text-align:right; font-size: 30px;" readonly="" class="form-control" id="nilai_kredit" name="nilai_kredit" placeholder="Kredit"  v-model="formEditBayarHutangTbs.nilai_kredit" v-bind="separator" ></money> 
                                            </div> 
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                                <font style="color: black">Potongan</font>
                                                <money style="text-align:right; font-size: 30px;" class="form-control" id="potongan" name="potongan" autocomplete="off" placeholder="Kredit"  v-model="formEditBayarHutangTbs.potongan" v-bind="separator" ref="potongan_edit"></money> 
                                            </div>                                        
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                                <font style="color: black">Pembayaran(F10)</font>
                                                <money style="text-align:right" class="form-penjualan" v-shortkey.focus="['f10']" id="jumlah_bayar" name="jumlah_bayar" v-model="formEditBayarHutangTbs.jumlah_bayar" v-bind="separator"  autocomplete="off" ref="jumlah_bayar_edit"></money> 
                                            </div>
                                        </div>
                                    </div>

                                    <div align="right"  style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                        <button type="submit" class="btn btn-success btn-lg" id="btnTbs"><font style="font-size:20px;">Tambah(Enter)</font></button>

                                        <button type="button" class="btn btn-default btn-lg"  v-on:click="closeModalEditBayarHutang()" v-shortkey.push="['esc']" @shortkey="closeModalEditBayarHutang()"> <font style="font-size:20px;">Tutup(Esc)</font></button>
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
                            <button type="button" class="close"  v-on:click="closeModalSelesai()" v-shortkey.push="['esc']" @shortkey="closeModalSelesai()"> &times;
                            </button> 
                            <h4 class="modal-title"> 
                                <div class="alert-icon"> 
                                    <b>Silahkan Lengkapi Pembayaran!</b> 
                                </div> 
                            </h4>
                        </div> 

                        <form class="form-horizontal"  v-on:submit.prevent="selesaiPembayaranHutang()"> 
                            <div class="modal-body"> 
                                <div class="card" style="margin-bottom:1px; margin-top:1px; margin-right:1px; margin-left:1px;">

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">                                            
                                            <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                                <font style="color: black">Tanggal</font> 
                                                <datepicker :input-class="'form-control'" placeholder="Jatuh Tempo" v-model="inputPembayaranHutang.tanggal" ref='tanggal'></datepicker>
                                                <br v-if="errors.tanggal">  <span v-if="errors.tanggal" id="tanggal_error" class="label label-danger">{{ errors.tanggal[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-5 col-xs-10">
                                            <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                                                <font style="color: black">Kas(F4)</font><br>
                                                <selectize-component v-model="inputPembayaranHutang.kas" :settings="placeholder_kas" id="kas" ref='kas'> 
                                                    <option v-for="kass, index in kas" v-bind:value="kass.id">{{ kass.nama_kas }}</option>
                                                </selectize-component>                                                
                                                <input class="form-control" type="hidden"  v-model="inputPembayaranHutang.kas"  name="id_tbs" id="id_tbs"  v-shortkey="['f4']" @shortkey="openSelectizeKas()">
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
                                                <textarea v-model="inputPembayaranHutang.keterangan" class="form-control" placeholder="Keterangan.." id="keterangan" name="keterangan" ref="keterangan"></textarea>
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
            <h4 class="card-title" style="margin-bottom: 1px; margin-top: 1px;">Edit Pembayaran Hutang </h4> 
            <div class="row" style="margin-bottom: 1px; margin-top: 1px;"> 

              <div class="col-md-3">
                <div class="card card-produk" style="margin-bottom: 1px; margin-top: 1px;">
                  <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                   
                     <selectize-component v-model="inputTbsPembayaranHutang.suplier" :settings="placeholder_suplier"  id="suplier" name="suplier" ref='suplier'> 
                      <option v-for="supliers, index in suplier" v-bind:value="supliers.id">{{ supliers.nama_suplier }}</option>
                     </selectize-component>
                    <input class="form-control" type="hidden"  v-model="inputTbsPembayaranHutang.id_suplier"  name="id_tbs" id="id_tbs"  v-shortkey="['f1']" @shortkey="openSelectizeSuplier()">
                      </div><!--/COL MD  3 --> 
                      <span v-if="errors.suplier" id="produk_error" class="label label-danger">{{ errors.suplier[0] }}</span>
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
                  <th>Faktur Beli</th>
                  <th>Supplier</th>
                  <th >Jatuh Tempo</th>
                  <th  style="text-align:right;">Hutang</th>
                  <th  style="text-align:right;">Potongan</th>
                  <th  style="text-align:right;">Subtotal</th>
                  <th  style="text-align:right;">Pembayaran</th>
                  <th style="text-align:right;">Sisa Hutang</th>
                  <th style="text-align:right;">Edit</th>
                  <th  style="text-align:right;">Hapus</th>
                </tr>
              </thead>
              <tbody v-if="tbs_pembayaran_hutang.length > 0 && loading == false"  class="data-ada">
                <tr v-for="tbs_pembayaran_hutangs, index in tbs_pembayaran_hutang" >

                  <td>{{ tbs_pembayaran_hutangs.no_faktur_pembelian }}</td>
                   <td>{{ tbs_pembayaran_hutangs.suplier }}</td>
                    <td >{{ tbs_pembayaran_hutangs.jatuh_tempo | tanggal}}</td>
                    <td style="text-align:right;">{{ tbs_pembayaran_hutangs.hutang | pemisahTitik  }}</td>
                    <td style="text-align:right;">{{ tbs_pembayaran_hutangs.potongan | pemisahTitik }}</td>
                     <td style="text-align:right;">{{ tbs_pembayaran_hutangs.subtotal_hutang | pemisahTitik }}</td>
                    <td style="text-align:right;">{{ tbs_pembayaran_hutangs.jumlah_bayar | pemisahTitik }}</td>
                    <td style="text-align:right;">{{ tbs_pembayaran_hutangs.sisa_hutang | pemisahTitik }}</td>
                    <td style="text-align:right;">
                     <a  class="btn btn-xs btn-warning" v-bind:id="'edit-' + tbs_pembayaran_hutangs.id" v-on:click="editEntry(tbs_pembayaran_hutangs.id, index, tbs_pembayaran_hutangs.no_faktur_pembelian,tbs_pembayaran_hutangs.suplier,tbs_pembayaran_hutangs.jumlah_bayar,tbs_pembayaran_hutangs.potongan,tbs_pembayaran_hutangs.hutang)">Edit</a>
                    </td>
                    <td style="text-align:right;"> 
                   <a  class="btn btn-xs btn-danger" v-bind:id="'delete-' + tbs_pembayaran_hutangs.id" v-on:click="deleteEntry(tbs_pembayaran_hutangs.id, index,tbs_pembayaran_hutangs.jumlah_bayar,tbs_pembayaran_hutangs.no_faktur_pembelian)">Delete</a>
                  </td>
                </tr>
              </tbody>          
              <tbody class="data-tidak-ada"  v-else-if="tbs_pembayaran_hutang.length == 0 && loading == false" >
                <tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
              </tbody>
            </table>  

            <vue-simple-spinner v-if="loading"></vue-simple-spinner>

            <div align="right"><pagination :data="tbsPembayaranHutangData" v-on:pagination-change-page="getResults" :limit="8"></pagination></div>

          </div>

          <p style="color: red; font-style: italic;">*Note : Klik Kolom  Potongan , Pembayaran Untuk Mengubah Nilai.</p> 
      </div><!-- COL SM 8 --> 

              <div class="col-md-3">
                            <div class="card card-stats">
                                <div class="card-header" data-background-color="blue">
                                    <i class="material-icons">local_atm</i>
                                </div>
                                <div class="card-content">
                                    <p class="category"><font style="font-size:20px;">Subtotal</font></p>
                                    <h3 class="card-title"><b><font style="font-size:32px;">{{ inputPembayaranHutang.subtotal | pemisahTitik }}</font></b></h3>
                                </div>
                                <div class="card-footer">
                                    <div class="row"> 
                                        <div class="col-md-6 col-xs-6"> 
                                            <button type="button" class="btn btn-success btn-lg" id="bayar" v-on:click="bayarPembayaranHutang()" v-shortkey.push="['f2']" @shortkey="bayarPembayaranHutang()"><font style="font-size:20px;">Bayar(F2)</font></button>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <button type="submit" class="btn btn-danger btn-lg" id="btnBatal" v-on:click="batalPembayaranHutang()" v-shortkey.push="['f3']" @shortkey="batalPembayaranHutang()"> <font style="font-size:20px;">Batal(F3) </font></button>
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
    export default {
        data: function () {
            return {
            errors: [],
            suplier: [],
            kas:[],
            tbs_pembayaran_hutang: [],
            tbsPembayaranHutangData : {},
            dataSuplierHutang : [],
            dataSuplierHutangData: {},
                 url : window.location.origin+(window.location.pathname).replace("dashboard", "pembayaran-hutang"),
                 url_kas : window.location.origin+(window.location.pathname).replace("dashboard", "penjualan"),
                 url_tambah_kas : window.location.origin+(window.location.pathname).replace("dashboard", "kas"),
                placeholder_suplier: {
                placeholder: '--PILIH SUPLIER (F1)--',
                sortField: 'text',
                openOnFocus : true
                 },
                placeholder_kas: {
                    placeholder: '--PILIH KAS--',
                    sortField: 'text',
                    openOnFocus : true
           },
            inputTbsPembayaranHutang:{
              suplier : '',
              id_suplier: '',
            },
            formBayarHutangTbs:{
                nilai_kredit : 0,
                jumlah_bayar : 0,
                potongan : 0,
                id_pembelian:'',
                no_faktur : '',
                no_faktur_pembayaran : '',
            },
            formEditBayarHutangTbs: {
                    id_tbs: '',
                    nilai_kredit :0,
                    potongan :0,
                    jumlah_bayar :0,
                    jumlah_bayar_lama :0,
            },
            inputPembayaranHutang:{
                subtotal: 0,
                kas: '',
                tanggal: new Date,
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
            pencarianhutang:'',
            loading: true,
            seen : false

            }
        },
        mounted() {   
            var app = this;
            app.dataSuplier();
            app.dataKas(); 
            app.getResults();
        },
        watch: {
        // whenever question changes, this function will run
        pencarian: function (newQuestion) {
            this.getHasilPencarian();
            this.loading = true;  
        },
        pencarianhutang: function (newQuestion) {
            this.pencarianSupplierHutang();
            this.loading = true;  
        },
        'inputTbsPembayaranHutang.suplier': function (newQuestion) {
            this.pilihSuplier();  
        },

        'formBayarHutangTbs.potongan':function(){
            this.potonganTbs()
        },
        'formBayarHutangTbs.jumlah_bayar':function(){
            this.jumlahBayarHutang()
        },
        'formEditBayarHutangTbs.potongan':function(){
          this.potonganEditTbs()
        },
        'formEditBayarHutangTbs.jumlah_bayar':function(){
          this.editJumlahBayarHutang()
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
          openSelectizeSuplier(){      
             this.$refs.suplier.$el.selectize.focus();
           },
           openSelectizeKas(){      
                this.$refs.kas.$el.selectize.focus();
            }, 
            potonganTbs(){
            var potonganTbs = this.formBayarHutangTbs.potongan

            if (potonganTbs == '') {
                potonganTbs = 0
            }
            var jumlah_bayar = parseFloat(this.formBayarHutangTbs.nilai_kredit) - parseFloat(potonganTbs)
            this.formBayarHutangTbs.jumlah_bayar = jumlah_bayar.toFixed(2)
        },
        jumlahBayarHutang(){
            var app = this;
            var jumlah_bayar = app.formBayarHutangTbs.jumlah_bayar

            if (jumlah_bayar == '') {
                jumlah_bayar = 0
            }
            var piutang_setelah_diskon = parseFloat(app.formBayarHutangTbs.nilai_kredit) - parseFloat(app.formBayarHutangTbs.potongan)
            var sisa_piutang = parseFloat(piutang_setelah_diskon) - parseFloat(jumlah_bayar);

            if (sisa_piutang < 0) {
                app.alertTbs("Jumlah Bayar Anda Melebihi Total Hutang !")
                app.formBayarHutangTbs.jumlah_bayar = 0;
                app.$refs.jumlah_bayar.$el.focus(); 
            }

        },
        potonganEditTbs(){
               var potonganTbs = this.formEditBayarHutangTbs.potongan

            if (potonganTbs == '') {
                potonganTbs = 0
            }
            var jumlah_bayar = parseFloat(this.formEditBayarHutangTbs.nilai_kredit) - parseFloat(potonganTbs)
            this.formEditBayarHutangTbs.jumlah_bayar = jumlah_bayar.toFixed(2)
    },
    editJumlahBayarHutang(){
              var app = this;
            var jumlah_bayar = app.formEditBayarHutangTbs.jumlah_bayar

            if (jumlah_bayar == '') {
                jumlah_bayar = 0
            }
            var piutang_setelah_diskon = parseFloat(app.formEditBayarHutangTbs.nilai_kredit) - parseFloat(app.formEditBayarHutangTbs.potongan)
            var sisa_piutang = parseFloat(piutang_setelah_diskon) - parseFloat(jumlah_bayar);

            if (sisa_piutang < 0) {
                app.alertTbs("Jumlah Bayar Anda Melebihi Total Hutang !")
                app.formEditBayarHutangTbs.jumlah_bayar = 0;
                app.$refs.jumlah_bayar.$el.focus(); 
            }
    },
        dataKas() {
        var app = this;
        axios.get(app.url+'/pilih-kas').then(function (resp) {
            app.kas = resp.data;  
            $.each(resp.data, function (i, item) {
                if (resp.data[i].default_kas == 1) {
                    app.inputPembayaranHutang.kas = resp.data[i].id 
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
        var id = app.$route.params.id;
        if (typeof page === 'undefined') {
            page = 1;
        }
        axios.get(app.url+'/view-edit-tbs-pembayaran-hutang/'+id+'?page='+page)
        .then(function (resp) {
            app.tbs_pembayaran_hutang = resp.data.data;
            app.tbsPembayaranHutangData = resp.data;
            app.loading = false;
            app.seen = true;
           if (app.inputPembayaranHutang.subtotal == 0) {
                app.getSubtotalTbs();
            }            
             app.openSelectizeSuplier();
        })
        .catch(function (resp) {
            app.loading = false;
            app.seen = true;
            alert("Tidak Dapat Memuat Faktur Pembelian Hutang");
        });
    }, 
    getHasilPencarian(page){
        var app = this;
        var id = app.$route.params.id;
        if (typeof page === 'undefined') {
            page = 1;
        }
        axios.get(app.url+'/pencarian-edit-tbs-pembayaran-hutang/'+id+'?search='+app.pencarian+'&page='+page)
        .then(function (resp) {
            console.log(resp);
            app.tbs_pembayaran_hutang = resp.data.data;
            app.tbsPembayaranHutangData = resp.data;
            app.loading = false;
            app.seen = true;
        })
        .catch(function (resp) {
            console.log(resp);
            alert("Tidak Dapat Memuat Faktur Pembelian Hutang");
        });
    },
    getSubtotalTbs(){
    var app =  this
    var id = app.$route.params.id;
    axios.get(app.url+'/cek-data-tbs-pembayan-hutang/'+id)
    .then(function (resp) {

      app.inputPembayaranHutang.subtotal += resp.data.subtotal;
      app.inputPembayaranHutang.keterangan = resp.data.pembayaran_hutang.keterangan
      app.inputPembayaranHutang.kas = resp.data.pembayaran_hutang.cara_bayar
      app.formBayarHutangTbs.no_faktur_pembayaran = resp.data.pembayaran_hutang.no_faktur_pembayaran

     })
    .catch(function (resp) {
      console.log(resp);
    });
  },   
    dataSuplier() {
            var app = this;
            axios.get(app.url+'/pilih-suplier').then(function (resp) {
                app.suplier = resp.data;
            })
            .catch(function (resp) {
                alert("Tidak Bisa Memuat Supplier");
            });
        }, 
       pilihSuplier() {
            if (this.inputTbsPembayaranHutang.suplier == '') {
                this.$swal({
                    text: "Silakan Pilih Supplier Telebih dahulu!",
                });
            }else{
                var app = this;
                this.dataSupplierHutang();
                $("#modal_pilih_hutang").show();
            }
        },
       dataSupplierHutang(page){
            var app = this;
            var suplier = app.inputTbsPembayaranHutang.suplier.split("|");
            var id = suplier[0];
            if (typeof page === 'undefined') {
                page = 1;
            }
              axios.get(app.url+'/data-suplier-hutang/'+id+'?page='+page)
                .then(function (resp) {
                app.dataSuplierHutang = resp.data.data;
                app.dataSuplierHutangData = resp.data;
                app.loading = false;
                app.seen = true;
            })
            .catch(function (resp) {
                console.log(resp);
                app.loading = false;
                app.seen = true;
                alert("Tidak Dapat Memuat Data hutang");
            });
        },
        bayarHutangEntry(id,index,no_faktur,nilai_kredit,jatuh_tempo){
             var app = this;
             $("#modal_form_bayar_hutang").show();
             $("#modal_pilih_hutang").hide();
                app.formBayarHutangTbs.nilai_kredit = nilai_kredit;
                app.formBayarHutangTbs.jumlah_bayar = nilai_kredit;
                app.formBayarHutangTbs.id_pembelian = id;
                app.formBayarHutangTbs.no_faktur = no_faktur;
                this.$refs.jumlah_bayar.$el.focus();
        },
       saveFormBayarHutang() {
          var app = this;
          var id = app.$route.params.id;
          var newbayarhutang = app.formBayarHutangTbs;

        if (app.formBayarHutangTbs.jumlah_bayar < 0) {
            app.alertTbs("Potongan Anda Melebihi Total Hutang");
            app.loading = false;
            app.inputTbsPembayaranHutang.potongan = 0;
            app.$refs.potongan.$el.focus();
        }else{
                 axios.post(app.url+'/proses-tambah-tbs-edit-pembayaran-hutang/'+id,newbayarhutang)
                  .then(function (resp) {
                     console.log(resp.data)
                    if (resp.data == 0) {
                        app.loading = false;
                        app.getResults();
                        $("#modal_form_bayar_hutang").hide();
                        app.alertTbs("Faktur "+app.formBayarHutangTbs.no_faktur+" Sudah Ada, Silakan Pilih Faktur Hutang Lain!");
                    }else{
                        var subtotal = parseFloat(app.inputPembayaranHutang.subtotal) + parseFloat(resp.data.jumlah_bayar)
                        app.getResults();
                        app.inputPembayaranHutang.subtotal = subtotal.toFixed(2)
                        app.alert("Berhasil Menambahkan Faktur Hutang"+ app.formBayarHutangTbs.no_faktur);
                        app.formBayarHutangTbs.nilai_kredit = 0
                        app.formBayarHutangTbs.jumlah_bayar = 0
                        app.formBayarHutangTbs.potongan = 0
                        app.formBayarHutangTbs.no_faktur = ''
                        $("#modal_form_bayar_hutang").hide();
                        app.loading = false;
                    }
                  })
                  .catch(function (resp) {
                    app.success = false;
                    app.errors = resp.response.data.errors;
                  });
            }
        },
       editEntry(id, index, no_faktur_pembelian, suplier, jumlah_bayar, potongan, hutang) {
        var app = this;
        app.formEditBayarHutangTbs.nilai_kredit = hutang;
        app.formEditBayarHutangTbs.jumlah_bayar = jumlah_bayar;
        app.formEditBayarHutangTbs.jumlah_bayar_lama = jumlah_bayar;
        app.formEditBayarHutangTbs.potongan = potongan;
        app.formEditBayarHutangTbs.id_tbs = id;

        $("#faktur_hutang_edit").text(no_faktur_pembelian+' || '+suplier);
        $("#modal_form_edit_bayar_hutang").show();     
        app.$refs.jumlah_bayar_edit.$el.focus();
    },
    saveFormEditBayarHutang(jumlah_bayar_lama){
        var app = this;
        var newformEditBayarHutangTbs = app.formEditBayarHutangTbs;
        if (app.formEditBayarHutangTbs.jumlah_bayar < 0) {
            app.alertTbs("Potongan Anda Melebihi Total Hutang");
            app.loading = false;
            app.formEditBayarHutangTbs.potongan = 0;
            app.$refs.potongan_edit.$el.focus();
        }else{
            app.loading = true;
            axios.post(app.url+'/edit-jumlah-tbs-edit-pembayaran-hutang', newformEditBayarHutangTbs)
            .then(function (resp) {
                if (resp.data.status == 0) {
                    app.getResults();
                    app.$swal({
                        text: "Potongan Yang Anda Masukan Melebihi Subtotal Hutang!",
                    });
                    app.loading = false;
                    app.formEditBayarHutangTbs.nilai_kredit = ''
                    app.formEditBayarHutangTbs.jumlah_bayar = ''
                    app.formEditBayarHutangTbs.potongan = ''
                    app.formEditBayarHutangTbs.id_tbs = ''
                }else{
                    var subtotal = (parseFloat(app.inputPembayaranHutang.subtotal) - parseFloat(jumlah_bayar_lama)) + parseFloat(resp.data.jumlah_bayar)
                    app.getResults();
                    app.inputPembayaranHutang.subtotal = subtotal.toFixed(2)
                    app.alert("Mengubah Faktur Hutang")
                    app.formEditBayarHutangTbs.nilai_kredit = ''
                    app.formEditBayarHutangTbs.jumlah_bayar = ''
                    app.formEditBayarHutangTbs.potongan = ''
                    app.formEditBayarHutangTbs.id_tbs = ''
                    $("#modal_form_edit_bayar_hutang").hide();
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
      alert(pesan) {
            this.$swal({
                title: "Berhasil ",
                text: pesan,
                icon: "success",
                buttons: false,
                timer: 1000,
            });
        },
     deleteEntry(id, index,jumlah_bayar_lama,no_faktur_pembelian) {
        var app = this;
        app.$swal({
            text: "Anda Yakin Ingin Menghapus Faktur "+no_faktur_pembelian+ " ?",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                this.prosesDelete(id,no_faktur_pembelian,jumlah_bayar_lama);
            } else {
                app.$swal.close();
            }
        });

    },
    prosesDelete(id,no_faktur_pembelian,jumlah_bayar_lama){
        var app = this;
        app.loading = true;
        axios.delete(app.url+'/proses-hapus-tbs-edit-pembayaran-hutang/'+id)
        .then(function (resp) {
            if (resp.data == 0) {
                app.alertTbs("Faktur "+no_faktur_pembelian+" Gagal Dihapus!")
                app.loading = false
            }else{
                var subtotal = parseFloat(app.inputPembayaranHutang.subtotal) - parseFloat(jumlah_bayar_lama)
                app.inputPembayaranHutang.subtotal = subtotal.toFixed(2)
                app.alert("Menghapus Faktur "+no_faktur_pembelian)
                app.getResults();
                app.loading = false
            }
        })
        .catch(function (resp) {
            console.log(resp);
            app.loading = false;
            alert("Tidak dapat Menghapus tbs Pembayaran Hutang");
        });
    },
    batalPembayaranHutang(){
        var app = this
        var id = app.$route.params.id;
        app.$swal({
            text: "Anda Yakin Ingin Membatalkan Transaksi Ini ?",
            buttons: {
                cancel: true,
                confirm: "OK"
            },
        }).then((value) => {
            if (!value) throw null;

            app.loading = true;
            axios.post(app.url+'/proses-batal-edit-pembayaran-hutang/'+id)
            .then(function (resp) {
                app.getResults();
                app.alert("Membatalkan Transaksi Pembayaran Hutang");
                app.inputPembayaranHutang.subtotal = 0
                app.$router.replace('/pembayaran-hutang');
            })
            .catch(function (resp) {
                app.loading = false;
                alert("Tidak dapat Membatalkan Transaksi Pembayaran Hutang");
            });
        });
    },
       bayarPembayaranHutang(){        
        $("#modal_selesai").show();
        
    },
    selesaiPembayaranHutang(){
        this.$swal({
            text: "Anda Yakin Ingin Menyelesaikan Transaksi Ini ?",
            buttons: {
                cancel: true,
                confirm: "OK"                   
            },
        }).then((value) => {
            if (!value) throw null;
            this.prosesSelesaiPembayaranHutang(value);
        });
    },
    prosesSelesaiPembayaranHutang(value){
        var app = this;
        var id = app.$route.params.id;
        var newinputPembayaranHutang = app.inputPembayaranHutang;
        app.loading = true;

        axios.patch(app.url+'/'+id,newinputPembayaranHutang)
        .then(function (resp) {

            if (resp.data.respons == 0) {
                app.alertTbs("Gagal : Terjadi Kesalahan , Silakan Coba Lagi!");
                app.loading = false;
            } else if (resp.data == 0) {
                app.alertTbs("Anda Belum Memasukan Faktur Pembelian Hutang");
                app.loading = false;
            }else{
                app.alert("Menyelesaikan Transaksi Pembayaran Hutang");
                app.inputPembayaranHutang.tanggal = new Date;
                app.inputPembayaranHutang.keterangan = ''
                app.inputPembayaranHutang.subtotal = 0

                $("#modal_selesai").hide();
                app.loading = false;                
                app.$router.replace('/pembayaran-hutang');
            }
            })
            .catch(function (resp) {
                console.log(resp);              
                app.loading = false;
                alert("Tidak dapat Menyelesaikan Transaksi Pembayaran Hutang");        
                app.errors = resp.response.data.errors;
            });
        },
          tambahModalKas(){
         var app = this;
          $("#modal_tambah_kas").show();
           $("#modal_selesai").hide();
        },
        saveFormKas() {
          var app = this;
          var newkas = app.tambahKas;
          axios.post(app.url_tambah_kas, newkas)
          .then(function (resp) {
            app.message = 'Menambah '+ app.tambahKas.nama_kas;
            app.alert(app.message);
            app.tambahKas.kode_kas = ''
            app.tambahKas.nama_kas = ''
            app.tambahKas.status_kas = 0
            app.tambahKas.default_kas = 0
            app.errors = '';
            $("#modal_tambah_kas").hide();
             $("#modal_selesai").show();
             app.dataKas();
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
       closeModalX(){
        $("#modal_pilih_hutang").hide(); 
        },
        closeModalBayarHutang(){
        $("#modal_form_bayar_hutang").hide();
        $("#modal_pilih_hutang").show();    
        },
        closeModalEditBayarHutang(){
          $("#modal_form_edit_bayar_hutang").hide();
        },
         closeModalSelesai(){
          $("#modal_selesai").hide(); 
        },
        closeModalTambahKas(){
        $("#modal_selesai").show();
        $("#modal_tambah_kas").hide();    
        },
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
