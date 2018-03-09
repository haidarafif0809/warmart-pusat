<style scoped>
.pencarian {
  color: red; 
  float: right;
  padding-bottom: 10px;
}
</style>
<template>
  <div class="row">
    <div class="col-md-12">
      <ul class="breadcrumb">
        <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
        <li class="active">Laporan Penjualan Terbaik Per Item</li>
      </ul>
      <div class="card">
        <div class="card-header card-header-icon" data-background-color="purple">
          <i class="material-icons">insert_chart</i>
        </div>



        <div class="card-content">
          <h4 class="card-title"> Laporan Penjualan Terbaik  Per Item</h4>

              <ul class="nav nav-pills nav-pills-rose" role="tablist" style="margin-top:5px;">
                  <!--
                    color-classes: "nav-pills-primary", "nav-pills-info", "nav-pills-success", "nav-pills-warning","nav-pills-danger"
                  -->
                  <li class="active">
                    <a href="#laporan_grafik" role="tab" data-toggle="tab" style="margin-left:10px;">
                      Laporan Grafik
                    </a>
                  </li>
                  <li>
                    <a href="#laporan_data" role="tab" data-toggle="tab" style="margin-right:10px; ">
                      Laporan Data
                    </a>
                  </li>
                </ul>



               <div class="tab-content tab-space" style="margin-top:5px;margin-bottom:5px;">
                  <div class="tab-pane active" id="laporan_grafik"  style="margin-top:5px;margin-bottom:5px;">

                      <div class="row">
                        <div class="form-group col-sm-2">
                          <label>Dari Tanggal</label>
                          <datepicker :input-class="'form-control'" placeholder="Dari Tanggal" v-model="filter.dari_tanggal" name="dari_tanggal" v-bind:id="'dari_tanggal'"></datepicker>
                        </div>
                        <div class="form-group col-sm-2">
                          <label>Sampai Tanggal</label>
                          <datepicker :input-class="'form-control'" placeholder="Sampai Tanggal" v-model="filter.sampai_tanggal" name="sampai_tanggal" v-bind:id="'sampai_tanggal'"></datepicker>
                        </div>
                        <div class="form-group col-md-2">
                          <label>Tampil Terbaik (Item)</label>
                          <input class="form-control" type="number" v-model="filter.tampil_terbaik" v-bind:id="'tampil_terbaik'" ref="tampil_terbaik">
                        </div>
                        <div class="form-group col-md-2">
                          <label>Jenis Penjualan</label>
                          <selectize-component v-model="filter.jenis_penjualan" :settings="placeholder_penjualan" id="jenis_penjualan" ref="jenis_penjualan"> 
                            <option v-bind:value="0" > Penjualan POS </option>
                            <option v-bind:value="1" > Penjualan Online </option>
                          </selectize-component>
                          <input class="form-control" type="hidden"  v-model="filter.jenis_penjualan"  name="jenis_penjualan" id="jenis_penjualan"  v-shortkey="['f1']">
                        </div>
                        <div class="form-group col-sm-3">
                          <br>
                          <button class="btn btn-primary" id="btnSubmit" type="submit" style="margin: 0px 0px;" v-on:click="submitPenjualanTerbaik()"><i class="material-icons">search</i> Cari</button>
                        </div>
                      </div>
                  </div>
                  <div class="tab-pane" id="laporan_data"  style="margin-top:5px;margin-bottom:5px;">
                      <div class="row">
                        <div class="form-group col-sm-2">
                          <label>Dari Tanggal</label>
                          <datepicker :input-class="'form-control'" placeholder="Dari Tanggal" v-model="filter.dari_tanggal" name="dari_tanggal" v-bind:id="'dari_tanggal'"></datepicker>
                        </div>
                        <div class="form-group col-sm-2">
                          <label>Sampai Tanggal</label>
                          <datepicker :input-class="'form-control'" placeholder="Sampai Tanggal" v-model="filter.sampai_tanggal" name="sampai_tanggal" v-bind:id="'sampai_tanggal'"></datepicker>
                        </div>
                        <div class="form-group col-md-2">
                          <label>Tampil Terbaik (Item)</label>
                          <input class="form-control" type="number" v-model="filter.tampil_terbaik" v-bind:id="'tampil_terbaik'" ref="tampil_terbaik">
                        </div>
                        <div class="form-group col-sm-3">
                          <br>
                          <button class="btn btn-primary" id="btnSubmit" type="submit" style="margin: 0px 0px;" v-on:click="submitPenjualanTerbaikData()"><i class="material-icons">search</i> Cari</button>
                        </div>
                      </div>

                      <div class="card-content">
                       <h4 class="card-title"><b> Laporan Penjualan POS </b></h4>
                       <hr>
                            <div class=" table-responsive">
                            <div class="pencarian">
                              <input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control">
                            </div>
                            <table class="table table-striped table-hover">
                              <thead class="text-primary">
                                <tr>
                                  <th>Kode Produk</th>
                                  <th>Nama Produk</th>
                                  <th style="text-align:right">Jumlah Terjual</th>
                                </tr>
                              </thead>
                            <tbody v-if="laporanPenjualanTerbaik.length > 0 && loading == false"  class="data-ada">
                               <tr v-for="laporanPenjualanTerbaiks, index in laporanPenjualanTerbaik" >
                                <td >{{ laporanPenjualanTerbaiks.laporan_penjualan_terbaik.kode_barang  }} </td>
                                <td >{{ laporanPenjualanTerbaiks.nama_barang  }}</td>
                                <td align="right">{{ laporanPenjualanTerbaiks.laporan_penjualan_terbaik.jumlah_produk | pemisahTitik }} </td>
                              </tr>
                            </tbody>          
                            <tbody class="data-tidak-ada" v-else-if="laporanPenjualanTerbaik.length == 0 && loading == false">
                              <tr ><td colspan="3"  class="text-center">Tidak Ada Data</td></tr>
                            </tbody>
                          </table>
                          <vue-simple-spinner v-if="loading"></vue-simple-spinner>
                          <div align="right"><pagination :data="laporanPenjualanTerbaikData" v-on:pagination-change-page="prosesTerbaikData" :limit="4"></pagination></div>
                        </div><!--RESPONSIVE-->
                      </div>
                      <div class="card-content">
                       <h4 class="card-title"><b> Laporan Penjualan Online </b></h4>
                       <hr>
                            <div class="table-responsive">
                            <div class="pencarian">
                              <input type="text" name="pencarian" v-model="pencarianOnline" placeholder="Pencarian" class="form-control">
                            </div>
                            <table class="table table-striped table-hover">
                              <thead class="text-primary">
                                <tr>
                                  <th>Kode Produk</th>
                                  <th>Nama Produk</th>
                                  <th style="text-align:right">Jumlah Terjual</th>
                                </tr>
                              </thead>
                               <tbody v-if="laporanPenjualanTerbaikOnline.length > 0 && loading == false"  class="data-ada">
                               <tr v-for="laporanPenjualanTerbaikOnlines, index in laporanPenjualanTerbaikOnline" >
                                <td >{{ laporanPenjualanTerbaikOnlines.laporan_penjualan_terbaik.kode_barang  }} </td>
                                <td >{{ laporanPenjualanTerbaikOnlines.nama_barang  }}</td>
                                <td align="right">{{ laporanPenjualanTerbaikOnlines.laporan_penjualan_terbaik.jumlah_produk | pemisahTitik }} </td>
                              </tr>
                            </tbody>          
                            <tbody class="data-tidak-ada" v-else-if="laporanPenjualanTerbaikOnline.length == 0 && loading == false">
                              <tr ><td colspan="3"  class="text-center">Tidak Ada Data</td></tr>
                            </tbody>
                          </table>
                               <vue-simple-spinner v-if="loading"></vue-simple-spinner>
                          <div align="right"><pagination :data="laporanPenjualanTerbaikOnlineData" v-on:pagination-change-page="prosesTerbaikDataOnline" :limit="4"></pagination></div>
                        </div><!--RESPONSIVE-->
                      </div>

                    </div>
              </div>

        </div>
      </div>
    </div>
  </div>
</template>


<script>
  export default {
    data: function () {
      return {
        laporanPenjualanTerbaik: [],
        laporanPenjualanTerbaikData: {},
        laporanPenjualanTerbaikOnline: [],
        laporanPenjualanTerbaikOnlineData: {},
        filter: {
          dari_tanggal: '',
          sampai_tanggal: new Date(),
          tampil_terbaik: 10,
          jenis_penjualan: '',
        },
        url : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-penjualan-terbaik"),
        pencarian: '',
        pencarianOnline: '',
        placeholder_penjualan: {
          placeholder: '--JENIS PENJUALAN--'
        },
        loading: false,

      }
    },
    mounted() {
      var app = this;
      var awal_tanggal = new Date();
      awal_tanggal.setDate(1);
      app.filter.dari_tanggal = awal_tanggal;
    },
    filters: {
      pemisahTitik: function (value) {
        var angka = [value];
        var numberFormat = new Intl.NumberFormat('es-ES');
        var formatted = angka.map(numberFormat.format);
        return formatted.join('; ');
      }
    },
    watch: {
      // whenever question changes, this function will run
      pencarian: function (newQuestion) {
        this.getHasilPencarian();
      },
      pencarianOnline: function (newQuestion) {
        this.getHasilPencarianOnline();
      }
      },
    methods: {
      dariTanggal(filter){
        var dari_tanggal = "" + filter.dari_tanggal.getFullYear() +'-'+ ((filter.dari_tanggal.getMonth() + 1) > 9 ? '' : '0') + (filter.dari_tanggal.getMonth() + 1) +'-'+ (filter.dari_tanggal.getDate() > 9 ? '' : '0') + filter.dari_tanggal.getDate();

        return dari_tanggal;
      },
      sampaiTanggal(filter){
        var sampai_tanggal = "" + filter.sampai_tanggal.getFullYear() +'-'+ ((filter.sampai_tanggal.getMonth() + 1) > 9 ? '' : '0') + (filter.sampai_tanggal.getMonth() + 1) +'-'+ (filter.sampai_tanggal.getDate() > 9 ? '' : '0') + filter.sampai_tanggal.getDate();

        return sampai_tanggal;
      },
      submitPenjualanTerbaik(){
        var app = this;
        var filter = app.filter;

        var dari_tanggal = app.dariTanggal(filter);
        var sampai_tanggal = app.sampaiTanggal(filter);

                if (filter.jenis_penjualan == "") {
                  app.alertGagal('Silakan Pilih Jenis Penjualan Terlebih Dahulu');          
                }else{
                      if (filter.jenis_penjualan == 0) {
                        app.$router.replace('/laporan-penjualan-terbaik/view/'+dari_tanggal+'/'+sampai_tanggal+'/'+app.filter.tampil_terbaik);
                      }else{
                        app.$router.replace('/laporan-penjualan-terbaik-online/view/'+dari_tanggal+'/'+sampai_tanggal+'/'+app.filter.tampil_terbaik);
                      }
                }
                // window.location.replace(window.location.origin+(window.location.pathname)+'#/laporan-bucket-size/view/'+dari_tanggal+'/'+sampai_tanggal+'/'+app.filter.kelipatan) 
          
         },
      submitPenjualanTerbaikData(){
                var app = this;
                app.prosesTerbaikData();
                app.prosesTerbaikDataOnline();
         },
         prosesTerbaikData(page){
           var app = this; 
              var newFilter = app.filter;
              if (typeof page === 'undefined') {
                page = 1;
              }
              app.loading = true,
              axios.post(app.url+'/view-pos-data?page='+page, newFilter)
              .then(function (resp) {
                app.laporanPenjualanTerbaik = resp.data.data;
                app.laporanPenjualanTerbaikData = resp.data;
                app.loading = false
                console.log(resp.data.data);
              })
              .catch(function (resp) {
                // console.log(resp);
                alert("Tidak Dapat Memuat Laporan Terbaik");
              });
         },
         prosesTerbaikDataOnline(page){
           var app = this; 
              var newFilter = app.filter;
              if (typeof page === 'undefined') {
                page = 1;
              }
              app.loading = true,
              axios.post(app.url+'/view-online-data?page='+page, newFilter)
              .then(function (resp) {
                app.laporanPenjualanTerbaikOnline = resp.data.data;
                app.laporanPenjualanTerbaikOnlineData = resp.data;
                app.loading = false
                console.log(resp.data.data);
              })
              .catch(function (resp) {
                // console.log(resp);
                alert("Tidak Dapat Memuat Laporan Terbaik");
              });
         },
          getHasilPencarian(page){
            var app = this;
            var newFilter = app.filter;
            if (typeof page === 'undefined') {
              page = 1;
            }
            app.loading = true,
            axios.post(app.url+'/pencarian-pos-data?search='+app.pencarian+'&page='+page, newFilter)
            .then(function (resp) {
              app.laporanPenjualanTerbaik = resp.data.data;
              app.laporanPenjualanTerbaikData = resp.data;
            })
            .catch(function (resp) {
              // console.log(resp);
              alert("Tidak Dapat Memuat Laporan Penjualan Terbaik");
            });
          },
            getHasilPencarianOnline(page){
            var app = this;
            var newFilter = app.filter;
            if (typeof page === 'undefined') {
              page = 1;
            }
            app.loading = true,
            axios.post(app.url+'/pencarian-online-data?search='+app.pencarianOnline+'&page='+page, newFilter)
            .then(function (resp) {
              app.laporanPenjualanTerbaikOnline = resp.data.data;
              app.laporanPenjualanTerbaikOnlineData = resp.data;
            })
            .catch(function (resp) {
              // console.log(resp);
              alert("Tidak Dapat Memuat Laporan Penjualan Terbaik");
            });
          },
        alertGagal(pesan) {
          this.$swal({
            title: "Peringatan!",
            text: pesan,
            icon: "warning",
          });
        },

    }
  }
</script>