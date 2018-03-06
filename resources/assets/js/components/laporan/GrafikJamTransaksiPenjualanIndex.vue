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
        <li class="active">Grafik Jam Transaksi Penjualan</li>
      </ul>
      <div class="card">
        <div class="card-header card-header-icon" data-background-color="purple">
          <i class="material-icons">insert_chart</i>
        </div>

        <div class="card-content">
          <h4 class="card-title"> Grafik Jam Transaksi Penjualan </h4>
                <div class="row">
                  <div class="form-group col-sm-2 col-xs-2">
                    <datepicker :input-class="'form-control'" placeholder="dari_tanggal" v-model="filter.dari_tanggal" name="dari_tanggal" v-bind:id="'dari_tanggal'"></datepicker>
                  </div>
                  <div class="form-group col-sm-2 col-xs-2">
                    <datepicker :input-class="'form-control'" placeholder="sampai_tanggal" v-model="filter.sampai_tanggal" name="sampai_tanggal" v-bind:id="'sampai_tanggal'"></datepicker>
                  </div>
                  <div class="form-group col-md-2 col-xs-2">
                  <selectize-component v-model="filter.jenis_penjualan" :settings="placeholder_penjualan" id="jenis_penjualan" ref="jenis_penjualan"> 
                    <option v-bind:value="0" > Penjualan POS </option>
                    <option v-bind:value="1" > Penjualan Online </option>
                  </selectize-component>
                  <input class="form-control" type="hidden"  v-model="filter.jenis_penjualan"  name="jenis_penjualan" id="jenis_penjualan"  v-shortkey="['f1']">
                </div>
                  <div class="form-group col-sm-4 col-xs-4">
                    <button class="btn btn-primary" id="btnSubmit" type="submit" style="margin: 0px 0px;" @click="submitJamTransaksiPenjualan()"><i class="material-icons">search</i> Cari</button>
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
        jamTransaksiPenjualan: [],
        jamTransaksiPenjualanData: {},
        filter: {
          dari_tanggal: '',
          sampai_tanggal: new Date(),
          jenis_penjualan: '0',
        },
        url : window.location.origin+(window.location.pathname).replace("dashboard", "grafik-jam-transaksi-penjualan"),
        pencarian: '',
        placeholder_penjualan: {
          placeholder: '--JENIS PENJUALAN--'
        },
        loading: false,

      }
     },
     watch: {
        // whenever question changes, this function will run
        pencarian: function (newQuestion) {
          this.getHasilPencarian();
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
        return moment(String(value)).format('DD/MM/YYYY hh:mm')
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
      submitJamTransaksiPenjualan(){
        var app = this;
        var filter = app.filter;

        var dari_tanggal = app.dariTanggal(filter);
        var sampai_tanggal = app.sampaiTanggal(filter);

        if (filter.jenis_penjualan == "") {
          app.alertGagal('Silakan Pilih Jenis Penjualan Terlebih Dahulu');          
        }else{
          if (filter.jenis_penjualan == 0) {
            app.$router.replace('/grafik-jam-transaksi-penjualan/view/'+dari_tanggal+'/'+sampai_tanggal);
          }else{
            app.$router.replace('/grafik-jam-transaksi-penjualan-online/view/'+dari_tanggal+'/'+sampai_tanggal);
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