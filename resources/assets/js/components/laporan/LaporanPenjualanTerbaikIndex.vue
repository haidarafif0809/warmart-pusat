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
      </div>
    </div>
  </div>
</template>


<script>
  export default {
    data: function () {
      return {
        penjualanTerbaik: [],
        penjualanTerbaikData: {},
        filter: {
          dari_tanggal: '',
          sampai_tanggal: new Date(),
          tampil_terbaik: 10,
          jenis_penjualan: '',
        },
        url : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-penjualan-terbaik"),
        pencarian: '',
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