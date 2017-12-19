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
        <li class="active">Kas Mutasi</li>
      </ul>
      
      <div class="card">
        <div class="card-header card-header-icon" data-background-color="purple">
          <i class="material-icons">compare_arrows</i>
        </div>
        <div class="card-content">
          <h4 class="card-title"> Kas Mutasi</h4>

          <div class="toolbar">
            <router-link :to="{name: 'createKasMutasi'}" class="btn btn-primary" style="padding-bottom:10px"><i class="material-icons">add</i>  Kas Mutasi</router-link>
            <div class="pencarian">
              <input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
            </div>
          </div>

          <br>
          <div class=" table-responsive ">
            <table class="table table-striped table-hover ">
              <thead class="text-primary">
                <tr>
                  <th>No Transaksi</th>
                  <th>Dari Kas</th>
                  <th>Ke Kas</th>
                  <th>Jumlah</th>
                  <th>Keterangan</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody v-if="kasMutasi.length > 0 && loading == false"  class="data-ada">
                <tr v-for="dataKas, index in kasMutasi" >
                  <td>{{ dataKas.kas_mutasi.no_faktur }}</td>
                  <td>{{ dataKas.kas_mutasi.nama_dari_kas }}</td>
                  <td>{{ dataKas.kas_mutasi.nama_ke_kas }}</td>
                  <td>{{ dataKas.jumlah }}</td>
                  <td>{{ dataKas.kas_mutasi.keterangan }}</td>
                  <td>
                    <router-link :to="{name: 'editKasMutasi', params: {id: dataKas.kas_mutasi.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + dataKas.kas_mutasi.id" >
                      Edit
                    </router-link>
                      <a href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + dataKas.kas_mutasi.id" v-on:click="deleteEntry(dataKas.kas_mutasi.id, index,dataKas.kas_mutasi.no_faktur)">
                        Delete
                      </a>
                    </td>
                  </tr>
              </tbody>
              <tbody class="data-tidak-ada" v-else-if="kasMutasi.length == 0 && loading == false">
                <tr><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
              </tbody>
            </table>

            <vue-simple-spinner v-if="loading"></vue-simple-spinner>
            <div align="right"><pagination :data="kasMutasiData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

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
      kasMutasi: [],
      kasMutasiData: {},
      url : window.location.origin+(window.location.pathname).replace("dashboard", "kas-mutasi"),
      pencarian: '',
      loading: true
    }
  },
  mounted() {
    var app = this;
    app.getResults();
  },
  watch: {
        // whenever question changes, this function will run
        pencarian: function (newQuestion) {
          this.getHasilPencarian()  
        }
    },

    methods: {
      getResults(page) {
        var app = this; 
        if (typeof page === 'undefined') {
          page = 1;
        }
        axios.get(app.url+'/view?page='+page)
        .then(function (resp) {
          app.kasMutasi = resp.data.data;
          app.kasMutasiData = resp.data;
          app.loading = false;
          console.log(resp.data)
        })
        .catch(function (resp) {
          console.log(resp);
          app.loading = false;
          alert("Tidak Dapat Memuat Kas Mutasi");
        });
      },
      getHasilPencarian(page){
        var app = this;
        if (typeof page === 'undefined') {
          page = 1;
        }
        axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
        .then(function (resp) {
          app.kasMutasi = resp.data.data;
          app.kasMutasiData = resp.data;
          app.loading = false;
        })
        .catch(function (resp) {
          console.log(resp);
          alert("Tidak Dapat Memuat Kas Mutasi");
        });
      },
      alert(pesan) {
        this.$swal({
          title: "Berhasil ",
          text: pesan,
          icon: "success",
        });
      },
      deleteEntry(id, index,faktur) {
        var app = this;
        app.$swal({
          text: "Anda Yakin Ingin Menghapus Transaksi "+faktur+ " ?",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            this.hapusTransaksi(id,faktur);
          } else {
            app.$swal.close();
            app.$router.replace('/kas-mutasi/');
          }
        });
      },
      hapusTransaksi(id,faktur){
        var app = this;
        app.loading = true;
        axios.delete(app.url+'/' + id)
        .then(function (resp) {
          if (resp.data == 0) {
            app.alertGagal("Mohon Maaf, Kas Mutasi " +faktur+ " Tidak bisa Di Hapus, Jika Dihapus Kas Akan Minus");
            app.loading = false;
          }else{
            app.getResults();
            app.alert("Menghapus Kas Mutasi "+faktur);
            app.loading = false;  
          }
          app.$router.replace('/kas-mutasi/');
        })
        .catch(function (resp) {
          alert("Tidak dapat Menghapus Item Masuk");
        });
      },
      alertGagal(pesan) {
        this.$swal({
            text: pesan,
            icon: "warning",
        });
      }
    }
}
</script>