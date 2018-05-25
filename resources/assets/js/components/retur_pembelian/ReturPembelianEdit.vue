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
        padding: 5px;
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
    .hurufBesar{
        text-transform: uppercase;
    }
    .table>thead>tr>th {
        border-bottom-width: 1px;
        font-size: 1em;
        font-weight: 300;
    }
    .table>tbody>tr>td {
        border-bottom-width: 1px;
        font-size: 0.9em;
        font-weight: 300;
        color: black;
    }
</style>

<template>
    <div class="row">
        <div class="col-md-12">

          <ul class="breadcrumb"> 
            <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li> 
            <li><router-link :to="{name: 'indexReturPembelian'}">Retur Pembelian</router-link></li> 
            <li class="active">Tambah Retur Pembelian</li> 
        </ul> 

        <!-- MODEL DATA PEMBELIAN -->
        <div class="modal" id="modal_pembelian" role="dialog" data-backdrop=""> 
            <div class="modal-dialog"> 
                <!-- Modal content--> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close"  v-on:click="closeModal()" v-shortkey.push="['esc']" @shortkey="closeModal()"> &times;</button> 
                        <h4 class="modal-title"> 
                            <div class="alert-icon"> 
                                <b>Pembelian Supplier {{inputTbsRetur.supplier}}</b> 
                            </div> 
                        </h4> 
                    </div>  
                    <form class="form-horizontal" > 
                        <div class="modal-body"> 
                            <div class=" table-responsive ">
                                <div class="pencarian">
                                    <input type="text" name="pencarian" v-model="pencarianPembelian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
                                </div>

                                <table class="table table-striped table-hover" v-if="seen">
                                    <thead class="text-primary">
                                        <tr>
                                            <th >Produk</th>
                                            <th style="text-align:right;">Sisa</th>
                                            <th style="text-align:center;">Satuan</th>
                                            <th style="text-align:right;">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="pembelians.length > 0 && loading == false"  class="data-ada">
                                        <tr v-for="data, index in pembelians" @click="insertTbs(data.pembelian.id_produk, data.pembelian.nama_barang, data.stok_produk, data.pembelian.satuan_id, data.pembelian.harga_beli, data.pembelian.subtotal)">

                                            <td>{{ data.pembelian.nama_barang | capitalize }}</td>
                                            <td align="right">{{ data.stok_produk | pemisahTitik }}</td>
                                            <td align="center">{{ data.pembelian.nama_satuan | hurufBesar }}</td>
                                            <td align="right">{{ data.pembelian.harga_beli | pemisahTitik }}</td>
                                        </tr>
                                    </tbody>          
                                    <tbody class="data-tidak-ada"  v-else-if="pembelians.length == 0 && loading == false">
                                        <tr ><td colspan="6"  class="text-center">Tidak Ada Data</td></tr>
                                    </tbody>
                                </table>

                                <vue-simple-spinner v-if="loading"></vue-simple-spinner>

                                <div align="right">
                                    <pagination :data="pembelianDatas" v-on:pagination-change-page="getDataPembelian" :limit="4">
                                    </pagination>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-sm"  v-on:click="closeModal()" v-shortkey.push="['esc']" @shortkey="closeModal()"> <font style="font-size:15px;">Tutup(Esc)</font></button>
                        </div> 
                    </form>
                </div>       
            </div> 
        </div> 
        <!-- / MODAL DATA PEMBELIAN -->

        

        <!-- MODAL INSERT TBS -->
        <div class="modal" id="modalInsertTbs" role="dialog" tabindex="-1"  aria-labelledby="myModalLabel" aria-hidden="true" >
            <div class="modal-dialog modal-medium">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close"  v-on:click="closeModalInsertTbs()" v-shortkey.push="['f9']" @shortkey="closeModalInsertTbs()"> &times;</button> 
                    </div>

                    <form class="form-horizontal" v-on:submit.prevent="submitInsertTbs(inputTbsRetur.jumlah_retur, inputTbsRetur.nama_produk)"> 
                        <div class="modal-body">
                            <h3 class="text-center"><b>{{inputTbsRetur.nama_produk}}</b></h3>

                            <div class="form-group">
                                <div class="col-md-4">
                                    <input class="form-control" type="number" v-model="inputTbsRetur.jumlah_retur" placeholder="Isi Jumlah Retur" name="jumlah_retur" id="jumlah_retur" ref="jumlah_retur" autocomplete="off" step="0.01">
                                </div>
                                <div class="col-md-4">
                                    <selectize-component v-model="inputTbsRetur.satuan_produk" :settings="placeholder_satuan" id="satuan" name="satuan" ref='satuan'> 
                                        <option v-for="satuans, index in satuan" v-bind:value="satuans.satuan" class="pull-left">{{ satuans.nama_satuan }}</option>
                                    </selectize-component>
                                </div>
                                <div class="col-md-4">
                                    <money class="form-control" v-model="inputTbsRetur.harga_produk" v-bind="separator" placeholder="Isi Harga Produk" readonly name="harga_produk" id="harga_produk" ref="harga_produk" autocomplete="off" ></money>
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


        <!-- MODAL SELESAI -->
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
                                    <div class="col-md-5 col-xs-10">
                                        <div class="form-group" style="margin-right: 1px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black; margin: 8px 0">Kas(F6)</font>
                                            <div style="margin-top: 8px">
                                                <selectize-component style="margin: 8px 0px" v-model="returPembelian.kas" :settings="placeholder_kas" id="kas" ref='kas'>  
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
                                            <money style="text-align:right" class="form-subtotal" v-model="returPembelian.potongan_faktur" v-bind="separator" v-shortkey.focus="['f7']"></money>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-xs-6">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 1px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">(%)(F8)</font>                                            
                                            <input style="text-align:right" type="number" class="form-subtotal" value="0" v-model="returPembelian.potongan_persen" v-on:blur="potonganPersen" v-shortkey.focus="['f8']" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">Potong Hutang</font>
                                            <money style="text-align:right" readonly="" class="form-penjualan" id="potong_hutang" name="potong_hutang" placeholder="Poton
                                            g Hutang"  v-model="returPembelian.potong_hutang" v-bind="separator" ></money> 
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">Total Akhir</font>
                                            <money style="text-align:right" class="form-penjualan" readonly="" id="total_akhir" name="total_akhir" placeholder="Total Akhir"  v-model="returPembelian.total_akhir" v-bind="separator" ></money> 
                                        </div>    
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group" style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                            <font style="color: black">Pembayaran(F10)</font>
                                            <money style="text-align:right" class="form-penjualan" v-shortkey.focus="['f10']" id="pembayaran" name="pembayaran" placeholder="Pembayaran"  v-model="returPembelian.pembayaran" v-bind="separator"  autocomplete="off" ref="pembayaran" :disabled="disable">            
                                            </money> 
                                        </div>
                                    </div>
                                </div>

                                <div align="right"  style="margin-right: 10px; margin-left: 10px; margin-bottom: 1px; margin-top: 1px;">
                                    <button type="button" class="btn btn-success btn-sm" id="btnSelesai" v-on:click="selesaiRetur()" v-shortkey.push="['alt', 'x']" @shortkey="selesaiRetur()"><font style="font-size:15px;">Tunai(Alt + X)</font></button>

                                    <button type="button" class="btn btn-default btn-sm"  v-on:click="closeModal()" v-shortkey.push="['esc']" @shortkey="closeModal()"> <font style="font-size:15px;">Tutup(Esc)</font></button>
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

        <div class="card" style="margin-bottom: 1px; margin-top: 1px;" >
            <div class="card-content">
                <h4 class="card-title" style="margin-bottom: 1px; margin-top: 1px;"> Retur Pembelian </h4>

                <div class="row" style="margin-bottom: 1px; margin-top: 1px;">
                    <div class="col-md-3">
                        <div class="card card-produk" style="margin-bottom: 1px; margin-top: 1px;">
                            <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                                <selectize-component v-model="inputTbsRetur.supplier" :settings="placeholder_supplier" id="supplier" ref='supplier'>
                                    <option v-for="suplier, index in supliers" v-bind:value="suplier.id">
                                        {{suplier.nama_suplier}}
                                    </option>
                                </selectize-component>
                            </div>
                            <span v-if="errors.supplier" id="produk_error" class="label label-danger">
                                {{ errors.supplier[0] }}
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
                                        <th >Produk</th>
                                        <th style="text-align:right;">Jumlah Retur</th>
                                        <th style="text-align:center;">Satuan</th>
                                        <th style="text-align:right;">Harga</th>
                                        <th style="text-align:right;">Potongan</th>
                                        <th style="text-align:right;">Pajak</th>
                                        <th style="text-align:right;">Subtotal</th>
                                        <th style="text-align:right;">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody v-if="tbsReturPembelians.length > 0 && loading == false"  class="data-ada">
                                    <tr v-for="tbs_retur_pembelian, index in tbsReturPembelians" >
                                        <td>
                                            {{ tbs_retur_pembelian.data_tbs.kode_barang }} - {{ tbs_retur_pembelian.data_tbs.nama_barang | capitalize }}
                                        </td>
                                        <td align="right">
                                            <a href="#create-retur-pembelian" v-bind:id="'edit-' + tbs_retur_pembelian.data_tbs.id_tbs_retur_pembelian" v-on:click="editJumlah(tbs_retur_pembelian.data_tbs.id_tbs_retur_pembelian, index,tbs_retur_pembelian.data_tbs.nama_barang,tbs_retur_pembelian.data_tbs.subtotal)">
                                                {{ tbs_retur_pembelian.data_tbs.jumlah_retur | pemisahTitik }}
                                            </a>
                                        </td>

                                        <td align="center">
                                            <a href="#create-retur-pembelian" v-bind:id="'edit-' + tbs_retur_pembelian.data_tbs.id_tbs_retur_pembelian" v-bind:class="'hurufBesar satuan-' + tbs_retur_pembelian.data_tbs.id_produk" v-bind:data-satuan="''+tbs_retur_pembelian.data_tbs.satuan_id" v-on:click="editSatuan(tbs_retur_pembelian.data_tbs.id_tbs_retur_pembelian, index,tbs_retur_pembelian.data_tbs.nama_barang,tbs_retur_pembelian.data_tbs.subtotal, tbs_retur_pembelian.data_tbs.id_produk)">
                                                {{ tbs_retur_pembelian.data_tbs.nama_satuan }}
                                            </a>
                                        </td>

                                        <td align="right">{{ tbs_retur_pembelian.data_tbs.harga_produk | pemisahTitik }}</td>

                                        <td align="right">
                                            <a href="#create-retur-pembelian" v-bind:id="'edit-' + tbs_retur_pembelian.data_tbs.id_tbs_retur_pembelian" v-on:click="editPotongan(tbs_retur_pembelian.data_tbs.id_tbs_retur_pembelian, index,tbs_retur_pembelian.data_tbs.nama_barang,tbs_retur_pembelian.data_tbs.subtotal)">
                                                {{ tbs_retur_pembelian.data_tbs.potongan | pemisahTitik }} | {{ Math.round(tbs_retur_pembelian.potongan_persen,2) }} %
                                            </a>
                                        </td>

                                        <td align="right">
                                            <a href="#create-retur-pembelian" v-bind:id="'edit-' + tbs_retur_pembelian.data_tbs.id_tbs_retur_pembelian" v-on:click="editTax(tbs_retur_pembelian.data_tbs.id_tbs_retur_pembelian, index,tbs_retur_pembelian.data_tbs.nama_barang,tbs_retur_pembelian.data_tbs.jumlah_retur,tbs_retur_pembelian.data_tbs.harga_produk,tbs_retur_pembelian.data_tbs.potongan,tbs_retur_pembelian.ppn_produk,tbs_retur_pembelian.data_tbs.subtotal)" >
                                                {{ tbs_retur_pembelian.data_tbs.tax | pemisahTitik }} | {{ Math.round(tbs_retur_pembelian.tax_persen,2) }} %
                                            </a>
                                        </td>

                                        <td align="right">{{ tbs_retur_pembelian.data_tbs.subtotal | pemisahTitik }}</td>
                                        <td style="text-align:right;">
                                            <a href="#create-retur-pembelian" class="btn btn-xs btn-danger" v-bind:id="'delete-' + tbs_retur_pembelian.data_tbs.id_tbs_retur_pembelian" v-on:click="deleteEntry(tbs_retur_pembelian.data_tbs.id_tbs_retur_pembelian, index,tbs_retur_pembelian.data_tbs.nama_barang,tbs_retur_pembelian.data_tbs.subtotal)">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>          
                                <tbody class="data-tidak-ada"  v-else-if="tbsReturPembelians.length == 0 && loading == false">
                                    <tr ><td colspan="8"  class="text-center">Tidak Ada Data</td></tr>
                                </tbody>
                            </table>

                            <vue-simple-spinner v-if="loading"></vue-simple-spinner>

                            <div align="right">
                                <pagination :data="tbsReturPembelianDatas" v-on:pagination-change-page="getTbs" :limit="4">
                                </pagination>
                            </div>
                        </div>                     
                    </div>

                    <div class="col-md-3">
                        <div class="card card-stats">
                            <div class="card-header" data-background-color="blue">
                                <i class="material-icons">shopping_cart</i>
                            </div>
                            <div class="card-content">
                                <p class="category" style="font-size: 18px; font-weight: 400"> Subtotal </p>
                                <h3 class="card-title"><b><font style="font-size:32px;">
                                    {{ returPembelian.subtotal | pemisahTitik }}</font></b>
                                </h3>

                                <p class="category" style="font-size: 18px; padding-bottom: 5px;">
                                    <div class="checkbox">
                                        <label style="font-size:18px; color: #999999">
                                            <input type="checkbox" name="potong_hutang" id="display_potong_hutang" data-toogle="0" @change="displayPotongHutang"> Potong Hutang
                                        </label>
                                    </div> 
                                </p>

                                <span id="spanFakturHutang" style="display: none">
                                    <selectize-component v-model="returPembelian.faktur_hutang" multiple :settings="placeholder_faktur_hutang" id="faktur_hutang" ref='faktur_hutang'>
                                        <option v-for="fakturHutang, index in fakturHutangs" v-bind:value="fakturHutang.no_faktur">
                                            {{fakturHutang.no_faktur+' | '+fakturHutang.hutang}}
                                        </option>
                                    </selectize-component>
                                </span>
                            </div>
                            <div class="card-footer">
                                <div class="row"> 
                                    <div class="col-md-6 col-xs-6"> 
                                        <button type="button" class="btn btn-success" id="bayar" v-on:click="bayarRetur()" v-shortkey.push="['f2']" @shortkey="bayarRetur()"><font style="font-size:13px;">Bayar(F2)</font></button>
                                    </div>
                                    <div class="col-md-6 col-xs-6">
                                        <button type="submit" class="btn btn-danger" id="btnBatal" v-on:click="batalRetur()" v-shortkey.push="['f3']" @shortkey="batalRetur()">
                                            <font style="font-size:13px;">Batal(F3) </font>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <span style="display: none;">

                    <input class="form-control" type="hidden"  v-model="returPembelian.supplier"  name="supplier" id="supplier" v-shortkey="['f1']" @shortkey="openSelectizeSupplier()">
                    <input class="form-control" type="hidden"  v-model="returPembelian.kas"  name="kas" id="kas" v-shortkey="['f6']" @shortkey="openSelectizeKas()">


                </span>
            </div>
        </div>

        <!-- MODAL EDIT SATUAN-->
        <div class="modal" id="modalEditSatuan" role="dialog" tabindex="-1"  aria-labelledby="myModalLabel" aria-hidden="true" >
            <div class="modal-dialog modal-medium">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close"  v-on:click="closeModalEditSatuan()" v-shortkey.push="['f9']" @shortkey="closeModalEditSatuan()"> &times;
                        </button> 
                    </div>

                    <form class="form-horizontal" v-on:submit.prevent="prosesEditSatuan(inputTbsRetur.id_produk, inputTbsRetur.id_tbs, inputTbsRetur.subtotal, inputTbsRetur.nama_produk)"> 
                        <div class="modal-body">
                            <h3 class="text-center"><b>{{inputTbsRetur.nama_produk}}</b></h3>

                            <div class="form-group">
                                <div class="col-md-12 col-xs-12 hurufBesar">
                                    <selectize-component v-model="inputTbsRetur.satuan_produk" :settings="placeholder_satuan" id="satuan" name="satuan" ref='satuan'> 
                                        <option v-for="satuans, index in satuan" v-bind:value="satuans.satuan" class="pull-left">{{ satuans.nama_satuan }}</option>
                                    </selectize-component>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-simple" v-on:click="closeModalEditSatuan()" v-shortkey.push="['f9']" @shortkey="closeModalEditSatuan()">Close(F9)</button>
                            <button type="submit" class="btn btn-info">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END MODAL EDIT SATUAN-->

    </div>
</div>
</template>

<script>
    import { mapState } from 'vuex';
    export default {
        data: function () {
            return {
                errors: [],
                fakturHutangs: [],
                satuan: [],
                tbsReturPembelians: [],
                tbsReturPembelianDatas : {},
                pembelians: [],
                pembelianDatas : {},
                url : window.location.origin+(window.location.pathname).replace("dashboard", "retur-pembelian"),
                url_satuan : window.location.origin+(window.location.pathname).replace("dashboard", "pembelian/satuan-konversi"),
                urlKas : window.location.origin+(window.location.pathname).replace("dashboard", "kas"),
                pencarian: '',
                pencarianPembelian: '',
                id_retur: '',
                loading: true,
                seen: false,
                disable: false,
                separator: {
                    decimal: ',',
                    thousands: '.',
                    prefix: '',
                    suffix: '',
                    precision: 0,
                    masked: false
                },
                placeholder_supplier:{
                    placeholder: 'Cari Supplier (F1) ...',
                    sortField: 'text',
                    openOnFocus : true,
                },
                placeholder_faktur_hutang:{
                    placeholder: 'Cari Faktur Hutang ...',
                    sortField: 'text',
                    openOnFocus : true,
                },
                placeholder_kas:{
                    placeholder: '--PILIH KAS--',
                    sortField: 'text',
                    openOnFocus : true,
                },
                placeholder_satuan: {
                    placeholder: '--PILIH SATUAN--',
                    sortField: 'text',
                    openOnFocus : true,
                },
                inputTbsRetur: {
                    supplier: '',
                    nama_produk : '',
                    id_produk : '',
                    jumlah_retur : '',
                    harga_produk : '',
                    harga_beli : '',
                    satuan_produk: '',
                    stok_produk: 0,
                    potongan_produk: 0,
                    tax_produk: 0,
                    id_tbs: ''
                },
                returPembelian: {
                    kas: '',
                    supplier: '',
                    subtotal : 0,
                    potongan_faktur : 0,
                    potongan_persen : 0,
                    total_akhir : 0,
                    potong_hutang : 0,
                    faktur_hutang : '',
                    pembayaran : 0,
                    keterangan: '',                    
                },
                tambahKas: {
                    kode_kas : '',
                    nama_kas : '',
                    status_kas : 0,
                    default_kas : 0
                },
            }
        },
        computed : mapState ({    
            supliers(){
                return this.$store.state.supplier
            },
            kas(){
                return this.$store.state.kas
            },
            default_kas: state => state.default_kas
        }),
        mounted() {
            var app = this;
            app.id_retur = app.$route.params.id;
            app.$store.dispatch('LOAD_SUPPLIER_LIST');
            app.$store.dispatch('LOAD_KAS_LIST')
            app.getTbs();
        },
        filters: {
            pemisahTitik: function (value) {
                var angka = [value];
                var numberFormat = new Intl.NumberFormat('es-ES');
                var formatted = angka.map(numberFormat.format);
                return formatted.join('; ');
            },
            capitalize: function (value) {
                return value.replace(/(^|\s)\S/g, l => l.toUpperCase())
            },
            hurufBesar: function (value) {
                return value.toUpperCase()
            },
        },
        watch: {
            pencarian: function (newQuestion) {
                this.getPencarianTbs()
                this.loading = true
            },
            pencarianPembelian: function (newQuestion) {
                this.getPencarianPembelian()
                this.loading = true
            },
            'inputTbsRetur.supplier': function () {
                if (this.inputTbsRetur.supplier != '') {
                    this.getDataPembelian()
                }
            },
            'inputTbsRetur.satuan_produk':function(){
                this.hitungHargaKonversi(this.inputTbsRetur.harga_beli)
            },
            'returPembelian.potongan_faktur':function(){
                this.potonganFaktur();
            },
        },
        methods: {
            openSelectizeSupplier(){      
                this.$store.dispatch('LOAD_SUPPLIER_LIST'); 
                this.$refs.supplier.$el.selectize.focus();
            },
            openSelectizeKas(){      
                this.$refs.kas.$el.selectize.focus();
            },
            getFakturHutang() {
                var app = this;

                axios.get(app.url+'/data-faktur-hutang')
                .then( (resp) => {

                    app.fakturHutangs = resp.data
                })
                .catch( (err) => {
                    console.log(err);
                    alert("Tidak Dapat Memuat Faktur Hutang");
                })                
            },
            getDataPembelian(page) {
                var app = this;
                var supplier = app.inputTbsRetur.supplier;
                if (typeof page === 'undefined') {
                    page = 1;
                }

                axios.get(app.url+'/data-pembelian?supplier='+supplier+'&page='+page)
                .then( (resp) => {

                    app.pembelians = resp.data.data
                    app.pembelianDatas = resp.data
                    app.loading = false
                    app.seen = true
                    $("#modal_pembelian").show();
                })
                .catch( (err) => {
                    console.log(err);
                    app.loading = false;
                    app.seen = true;
                    alert("Tidak Dapat Memuat Data Pembelian");
                })
            },
            getPencarianPembelian(page) {
                var app = this;
                var supplier = app.inputTbsRetur.supplier;
                if (typeof page === 'undefined') {
                    page = 1;
                }

                axios.get(app.url+'/pencarian-data-pembelian?search='+app.pencarianPembelian+'&supplier='+supplier+'&page='+page)
                .then( (resp) => {

                    app.pembelians = resp.data.data
                    app.pembelianDatas = resp.data
                    app.loading = false
                    app.seen = true
                })
                .catch( (err) => {
                    console.log(err);
                    app.loading = false;
                    app.seen = true;
                    alert("Tidak Dapat Memuat Data Pembelian");
                })
            },
            insertTbs(id_produk, nama_barang, stok_produk, satuan_id, harga_produk, subtotal) {
                var app = this;

                if (stok_produk > 0) {
                    app.getInsertTbs(id_produk,nama_barang,stok_produk,satuan_id,harga_produk,subtotal);                    
                    app.getSatuan(id_produk);
                }else{
                    app.alertGagal("Stok "+titleCase(nama_barang)+" Tidak Mencukupi.")
                }
            },
            getInsertTbs(id_produk,nama_barang,stok_produk,satuan_id,harga_produk,subtotal) {
                var app = this
                app.inputTbsRetur.nama_produk = titleCase(nama_barang)
                app.inputTbsRetur.id_produk = id_produk
                app.inputTbsRetur.harga_produk = harga_produk
                app.inputTbsRetur.harga_beli = harga_produk
                app.inputTbsRetur.satuan_produk = satuan_id

                app.closeModal();
                $("#modalInsertTbs").show();
                app.$refs.jumlah_retur.focus();
            },
            submitInsertTbs(jumlah_retur, nama_produk){
                var app = this

                if (jumlah_retur == "" || jumlah_retur == 0) {

                    app.$swal("Jumlah Retur Tidak Boleh Nol atau kosong!")
                    .then((value) => {
                        app.$refs.jumlah_retur.focus() 
                    })

                }else{
                    app.prosesInsertTbs(jumlah_retur, nama_produk)
                }

            },
            prosesInsertTbs(jumlah_retur, nama_produk) {
                var app = this;
                var newTbs = app.inputTbsRetur;

                app.loading = true;
                axios.post(app.url+'/proses-tambah-tbs-retur-pembelian', newTbs)
                .then(function (resp) {
                    $("#modalInsertTbs").hide();
                    app.alert("Menambahkan Produk "+titleCase(nama_produk));
                    app.loading = false;
                    app.getTbs();

                    if (resp.data.status == 1) {
                        var subtotal = (parseInt(app.returPembelian.subtotal) - parseInt(resp.data.subtotal_lama) + parseInt(resp.data.subtotal))
                    }else{      
                        var subtotal = parseInt(app.returPembelian.subtotal) + parseInt(resp.data.subtotal)
                    }

                    app.returPembelian.subtotal = subtotal                       
                    app.returPembelian.total_akhir  = subtotal
                    app.inputTbsRetur.id_produk = ''
                    app.inputTbsRetur.nama_produk = ''
                    app.inputTbsRetur.harga_produk = ''
                    app.inputTbsRetur.jumlah_retur = ''
                    app.inputTbsRetur.supplier = ''

                })
                .catch(function (resp) {

                    app.loading = false;
                    alert("Gagal Menambahkan Retur Pembelian");
                });
            },
            getTbs(page) {
                var app = this; 
                if (typeof page === 'undefined') {
                    page = 1;
                }

                axios.get(app.url+'/view-edit-tbs/'+app.id_retur+'?page='+page)
                .then( (resp) => {

                    app.tbsReturPembelians = resp.data.data
                    app.tbsReturPembelianDatas = resp.data
                    app.loading = false
                    app.seen = true
                    app.returPembelian.kas = app.default_kas
                    app.openSelectizeSupplier();
                    app.getFakturHutang();

                    if (app.returPembelian.subtotal == 0) {          
                        app.getSubtotal(); 
                    }
                })
                .catch( (err) => {
                    console.log(err);
                    app.loading = false;
                    app.seen = true;
                    alert("Tidak Dapat Memuat Retur Pembelian");
                })
            },
            getSubtotal(){
                var app =  this;
                var jenis_tbs = 1;
                axios.get(app.url+'/subtotal-tbs')
                .then(function (resp) {
                    app.returPembelian.subtotal += resp.data.subtotal;
                    app.returPembelian.total_akhir += resp.data.subtotal;
                })
                .catch(function (resp) {
                    ;
                });
            }, 
            getPencarianTbs(page) {
                var app = this; 
                if (typeof page === 'undefined') {
                    page = 1;
                }

                axios.get(app.url+'/pencarian-edit-tbs/'+app.id_retur+'?search='+app.pencarian+'&page='+page)
                .then( (resp) => {

                    app.tbsReturPembelians = resp.data.data
                    app.tbsReturPembelianDatas = resp.data
                    app.loading = false
                    app.seen = true
                    app.returPembelian.kas = app.default_kas
                })
                .catch( (err) => {
                    console.log(err);
                    app.loading = false;
                    app.seen = true;
                    alert("Tidak Dapat Memuat Retur Pembelian");
                })
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
                                app.inputTbsRetur.satuan_produk = resp.data[i].satuan;
                            }
                        });

                    }else{

                        $.each(resp.data, function (i, item) {
                            if (resp.data[i].id === parseInt(satuan_tbs)) {
                                app.inputTbsRetur.satuan_produk = resp.data[i].satuan;
                            }
                        });

                    }
                })
                .catch(function (resp) {
                    ;
                    alert("Tidak Dapat Memuat Satuan Produk");
                });
            },
            deleteEntry(id, index,nama_produk,subtotal_lama) {
                var app = this;
                app.$swal({
                    text: `Anda Yakin Ingin Menghapus Produk ${titleCase(nama_produk)} ?`,
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
                axios.delete(app.url+'/hapus-tbs/'+id)
                .then(function (resp) {
                    app.getTbs();

                    var subtotal = parseFloat(app.returPembelian.subtotal) - parseFloat(resp.data.subtotal)
                    app.returPembelian.subtotal = subtotal                       
                    app.returPembelian.total_akhir  = subtotal
                    app.alert("Menghapus Produk "+titleCase(nama_produk));
                    app.loading = false;
                })
                .catch(function (resp) {

                    app.loading = false;
                    alert("Tidak Dapat Menghapus Produk "+titleCase(nama_produk));
                });
            },
            editJumlah(id, index,nama_produk,subtotal_lama) {
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
                        axios.get(app.url+'/proses-edit-jumlah-retur?jumlah_retur='+jumlah_retur+'&id_tbs='+id)
                        .then(function (resp) {
                            app.alert("Mengubah Jumlah Retur "+titleCase(nama_produk));
                            app.loading = false;
                            app.getTbs();      
                            var subtotal = (parseInt(app.returPembelian.subtotal) - parseInt(subtotal_lama))  + parseInt(resp.data.subtotal)
                            app.returPembelian.subtotal = subtotal                       
                            app.returPembelian.total_akhir  = subtotal
                        })
                        .catch(function (resp) {
                            app.loading = false;
                            alert("Jumlah Retur Tidak Bisa Diedit");
                        });
                    } 
                    else { 
                        swal('Oops...', 'Jumlah Tidak Boleh 0 !', 'error'); 
                        return false; 
                    } 
                }); 
            },
            editSatuan(id, index,nama_produk,subtotal_lama, id_produk) {
                var app = this;
                app.inputTbsRetur.nama_produk = titleCase(nama_produk);
                app.inputTbsRetur.id_tbs = id;
                app.inputTbsRetur.subtotal = subtotal_lama;
                app.inputTbsRetur.id_produk = id_produk;
                app.getSatuan(id_produk);
                $("#modalEditSatuan").show();
            },
            prosesEditSatuan(id_produk, id_tbs, subtotal_lama, nama_produk){
                var app = this;
                var newSatuan = app.inputTbsRetur;
                var satuan_produk = app.inputTbsRetur.satuan_produk.split("|");
                var satuan_tbs = $(".satuan-"+id_produk).attr("data-satuan");

                if (satuan_tbs == satuan_produk[0]) {
                    $("#modalEditSatuan").hide();
                }else{
                    axios.post(app.url+'/edit-satuan-tbs', newSatuan)
                    .then(function (resp) {

                        var subtotal = (parseInt(app.returPembelian.subtotal) - parseInt(subtotal_lama) + parseInt(resp.data.subtotal))

                        app.alert("Mengubah Satuan "+titleCase(nama_produk));
                        app.getTbs();

                        app.returPembelian.subtotal = subtotal.toFixed(2)
                        app.returPembelian.total_akhir = subtotal.toFixed(2)
                        app.openSelectizeSupplier() 
                        $("#modalEditSatuan").hide();
                    })
                    .catch(function (resp) {
                      ;                  
                      app.loading = false;
                      alert("Tidak Dapat Mengubah Satuan");
                  });
                }
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

                app.inputTbsRetur.id_tbs = id;
                app.inputTbsRetur.potongan_produk = potongan;
                var newinputTbsRetur = app.inputTbsRetur;

                axios.post(app.url+'/proses-potongan-tbs', newinputTbsRetur)
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
                        var subtotal = (parseFloat(app.returPembelian.subtotal) - parseFloat(subtotal_lama)) + parseFloat(resp.data.subtotal)

                        app.returPembelian.subtotal = subtotal.toFixed(2)
                        app.returPembelian.total_akhir = subtotal.toFixed(2)           
                        app.inputTbsRetur.potongan_produk = ''
                        app.inputTbsRetur.id_tbs = ''
                        app.getTbs();
                    }
                })
                .catch(function (resp) { 
                    console.log(resp);    
                    alert("Tidak dapat Mengubah Potongan Produk");
                });
            },
            editTax(id, index,nama_produk,jumlah,harga,potongan,ppn,subtotal_lama){
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
            },
            submitEditTax(pajak,id,nama_produk,ppn_edit,subtotal_lama){
                var app = this;
                console.log(pajak)
                app.loading = true;
                axios.get(app.url+'/proses-tax-tbs?tax_edit_produk='+pajak+'&id_tax='+id+'&ppn_produk='+ppn_edit)
                .then(function (resp) {
                    app.alert("Mengubah Pajak Produk "+titleCase(nama_produk));
                    app.loading = false;
                    app.getTbs();  

                    var subtotal = (parseInt(app.returPembelian.subtotal) - parseInt(subtotal_lama)) + parseInt(resp.data.subtotal);
                    app.returPembelian.subtotal = subtotal                       
                    app.returPembelian.total_akhir  = subtotal 
                })
                .catch(function (resp) {
                    app.loading = false;
                    alert("Pajak Produk tidak bisa diedit");
                });
            },
            batalRetur(){
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
                    axios.post(app.url+'/proses-batal-retur')
                    .then(function (resp) {
                        app.getTbs();
                        app.alert("Membatalkan Transaksi Retur Pembelian");
                        app.inputTbsRetur.supplier = ''
                        app.returPembelian.subtotal = 0
                        app.returPembelian.total_akhir = 0
                        app.returPembelian.potong_hutang = 0
                        app.returPembelian.potongan_faktur = 0
                        app.returPembelian.potongan_persen = 0
                        app.returPembelian.pembayaran = 0
                        app.returPembelian.kas = ''
                        app.returPembelian.keterangan = ''
                        app.returPembelian.supplier = ''
                    })
                    .catch(function (resp) {
                        console.log(resp);
                        app.loading = false;
                        alert("Tidak Dapat Membatalkan Transaksi Retur Pembelian");
                    });
                });
            },
            potonganPersen(){
                var app = this;
                var potonganPersen = app.returPembelian.potongan_persen

                if (potonganPersen > 100) {
                    app.alertGagal("Potongan Tidak Bisa Lebih Dari 100%")
                    var selisih = app.returPembelian.total_akhir - app.returPembelian.potong_hutang;
                    if (selisih >= 0) {
                        app.returPembelian.pembayaran = app.returPembelian.subtotal - app.returPembelian.potong_hutang
                    }

                    app.returPembelian.total_akhir = app.returPembelian.subtotal
                    app.returPembelian.potongan_faktur = 0
                    app.returPembelian.potongan_persen = 0
                }else{
                    if (potonganPersen == '') {
                        potonganPersen = 0
                    }

                    var potongan_nominal = parseFloat(app.returPembelian.subtotal) * (parseFloat(potonganPersen) / 100) 
                    var total_akhir = parseFloat(app.returPembelian.subtotal,10) - parseFloat(potongan_nominal,10)
                    var selisih = total_akhir - app.returPembelian.potong_hutang;
                    if (selisih >= 0){
                        app.returPembelian.pembayaran = total_akhir - app.returPembelian.potong_hutang
                    }

                    app.returPembelian.potongan_faktur = potongan_nominal
                    app.returPembelian.total_akhir = total_akhir
                }
            },
            potonganFaktur(){
                var app = this;
                var potonganFaktur = app.returPembelian.potongan_faktur

                if (potonganFaktur == '') {
                    potonganFaktur = 0
                }
                var potongan_persen = (parseFloat(potonganFaktur) / parseFloat(app.returPembelian.subtotal)) * 100
                var total_akhir = parseFloat(app.returPembelian.subtotal) - parseFloat(potonganFaktur)

                if (potongan_persen > 100) {
                    app.alertGagal("Potongan Tidak Bisa Lebih Dari 100%")
                    var selisih = total_akhir - app.returPembelian.potong_hutang;
                    if (selisih >= 0) {
                        app.returPembelian.pembayaran = app.returPembelian.subtotal - app.returPembelian.potong_hutang
                    }

                    app.returPembelian.total_akhir = app.returPembelian.subtotal
                    app.returPembelian.potongan_faktur = 0
                    app.returPembelian.potongan_persen = 0

                }else{
                    var selisih = total_akhir - app.returPembelian.potong_hutang;
                    if (selisih >= 0) {
                        app.returPembelian.pembayaran = total_akhir - app.returPembelian.potong_hutang
                    }
                    app.returPembelian.potongan_persen = potongan_persen.toFixed(2)
                    app.returPembelian.total_akhir = total_akhir
                }

            },
            saveFormKas() {
                var app = this;
                var newKas = app.tambahKas;
                axios.post(app.urlKas, newKas)
                .then(function (resp) {
                    app.message = 'Menambah Kas '+ app.tambahKas.nama_kas;
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
                var newRetur = app.returPembelian;
                app.loading = true;
                console.log(app.returPembelian.kas)
                if (app.returPembelian.kas == '') {
                    app.loading = false;    
                    app.$swal("Silakan Pilih Kas Terlebih Dahulu").then((value) => {
                        app.openSelectizeKas();
                    });
                }else{
                    app.closeModal();
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
                            app.getTbs();
                            app.alert("Menyelesaikan Transaksi Retur Pembelian");
                            app.returPembelian.supplier = ''
                            app.returPembelian.subtotal = 0
                            app.returPembelian.potongan_persen = 0
                            app.returPembelian.potongan_faktur = 0
                            app.returPembelian.total_akhir = 0
                            app.returPembelian.pembayaran = 0
                            app.inputTbsRetur.supplier = ''   
                            window.open('retur-pembelian/cetak-retur-pembelian/'+resp.data.respons_retur,'_blank');
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
            displayPotongHutang() {
                var data_toogle = $("#display_potong_hutang").attr("data-toogle");

                if (data_toogle == 0) {
                    $('#spanFakturHutang').show();
                    $("#display_potong_hutang").attr("data-toogle", 1)
                    this.disable = true;
                }
                else{
                    $('#spanFakturHutang').hide();
                    $("#display_potong_hutang").attr("data-toogle", 0)
                    this.disable = false;
                    this.returPembelian.faktur_hutang = '';
                }
            },
            hitungPotongHutang() {
                var app = this;
                var faktur_hutang = app.returPembelian.faktur_hutang

                axios.post(app.url+'/nilai-potong-hutang', {faktur_hutang})
                .then( (resp) => {
                    app.returPembelian.potong_hutang = resp.data;
                    var selisih = app.returPembelian.total_akhir - app.returPembelian.potong_hutang;
                    if (selisih < 0) {
                        app.returPembelian.pembayaran = 0;
                    }else{
                        app.returPembelian.pembayaran = selisih;
                    }

                    $("#modal_selesai").show(); 
                    this.$refs.pembayaran.$el.focus()           
                })
                .catch( (err) => {
                    console.log(err);
                    alert("Terjadi Masalah Hitung Nilai Potong Hutang");
                })                   
            },
            tambahModalKas(){
                $("#modal_tambah_kas").show();
                $("#modal_selesai").hide();
                this.$refs.kode_kas.focus(); 
            },
            hitungHargaKonversi(harga_beli){
                var satuan = this.inputTbsRetur.satuan_produk.split("|");

                this.inputTbsRetur.harga_produk = parseFloat(harga_beli) * ( parseFloat(satuan[3]) * parseFloat(satuan[4]) );
            },
            bayarRetur(){
                this.hitungPotongHutang()
            },
            closeModal(){
                $("#modal_selesai").hide(); 
                $("#modal_pembelian").hide(); 
            },
            closeModalInsertTbs(){
                $("#modalInsertTbs").hide();
                $("#modal_pembelian").show(); 
            },
            closeModalEditSatuan(){
                $("#modalEditSatuan").hide();
            },
            closeModalKas(){
                $("#modal_tambah_kas").hide(); 
                $("#modal_selesai").show(); 
            },
            alert(pesan) {
                this.$swal({
                    text: pesan,
                    icon: "success",
                    buttons: false,
                    timer: 1000
                })
            },
            alertGagal(pesan) {
                this.$swal({
                    text: pesan,
                    icon: "warning",
                    buttons: false,
                    timer: 2000
                })
            }
        }
    }
</script>