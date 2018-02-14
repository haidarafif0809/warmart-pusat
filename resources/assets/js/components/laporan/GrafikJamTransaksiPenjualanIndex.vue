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
                  <div class="form-group col-sm-4">
                    <datepicker :input-class="'form-control'" placeholder="tanggal" v-model="filter.tanggal" name="tanggal" v-bind:id="'tanggal'"></datepicker>
                  </div>
                  <div class="form-group col-sm-4">
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
          tanggal: new Date(),
        },
        url : window.location.origin+(window.location.pathname).replace("dashboard", "grafik-jam-transaksi-penjualan"),
        pencarian: '',
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
    },
      methods: {
      submitJamTransaksiPenjualan(){
        var app = this;
        var filter = app.filter;

        var date_tanggal = filter.tanggal;

        var tanggal = "" + date_tanggal.getFullYear() +'-'+ ((date_tanggal.getMonth() + 1) > 9 ? '' : '0') + (date_tanggal.getMonth() + 1) +'-'+ (date_tanggal.getDate() > 9 ? '' : '0') + date_tanggal.getDate();

        // window.location.replace(window.location.origin+(window.location.pathname)+'#/laporan-bucket-size/view/'+dari_tanggal+'/'+sampai_tanggal+'/'+app.filter.kelipatan)
        app.$router.replace('/grafik-jam-transaksi-penjualan/view/'+tanggal);
      }
}
}
</script>