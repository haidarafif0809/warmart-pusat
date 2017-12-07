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
        <li class="active">Kas Keluar</li>
      </ul>
      
      <div class="card">
        <div class="card-header card-header-icon" data-background-color="purple">
          <i class="material-icons">money_off</i>
        </div>
        <div class="card-content">
          <h4 class="card-title"> Kas Keluar</h4>

          <div class="toolbar">
            <router-link :to="{name: 'createKasKeluar'}" class="btn btn-primary" style="padding-bottom:10px"><i class="material-icons">add</i>  Kas Keluar</router-link>
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
                  <th>Kas</th>
                  <th>Kategori</th>
                  <th>Jumlah</th>
                  <th>Keterangan</th>
                  <th>Waktu</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody v-if="kasKeluar.length"  class="data-ada">
                <tr v-for="dataKas, index in kasKeluar" >
                  <td>{{ dataKas.kas_keluar.no_faktur }}</td>
                  <td>{{ dataKas.kas_keluar.nama_kas }}</td>
                  <td>{{ dataKas.kas_keluar.nama_kategori_transaksi }}</td>
                  <td>{{ dataKas.jumlah }}</td>
                  <td>{{ dataKas.kas_keluar.keterangan }}</td>
                  <td>{{ dataKas.kas_keluar.created_at }}</td>
                  <td>
                    <router-link :to="{name: 'editKasKeluar', params: {id: dataKas.kas_keluar.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + dataKas.kas_keluar.id" >
                      Edit
                    </router-link>
                      <a href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + dataKas.kas_keluar.id" v-on:click="deleteEntry(dataKas.kas_keluar.id, index,dataKas.nama_kas)">
                        Delete
                      </a>
                    </td>
                  </tr>
              </tbody>
              <tbody class="data-tidak-ada" v-else>
                <tr><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
              </tbody>
            </table>

            <vue-simple-spinner v-if="loading"></vue-simple-spinner>
            <div align="right"><pagination :data="kasKeluarData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

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
      kasKeluar: [],
      kasKeluarData: {},
      url : window.location.origin+(window.location.pathname).replace("dashboard", "kas-keluar"),
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
          app.kasKeluar = resp.data.data;
          app.kasKeluarData = resp.data;
          app.loading = false;

        })
        .catch(function (resp) {
          console.log(resp);
          app.loading = false;
          alert("Tidak Dapat Memuat Kas Keluar");
        });
      },
      getHasilPencarian(page){
        var app = this;
        if (typeof page === 'undefined') {
          page = 1;
        }
        axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
        .then(function (resp) {
          app.kasKeluar = resp.data.data;
          app.kasKeluarData = resp.data;
          app.loading = false;
        })
        .catch(function (resp) {
          console.log(resp);
          alert("Tidak Dapat Memuat Kas Keluar");
        });
      },
      alert(pesan) {
        this.$swal({
          title: "Berhasil ",
          text: pesan,
          icon: "success",
        });
      },
        deleteEntry(id, index,name) {
            swal({
                title: "Konfirmasi Hapus",
                text : "Anda Yakin Ingin Menghapus "+name+" ?",
                icon : "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                  var app = this;
                  axios.delete(app.url+'/' + id)
                  .then(function (resp) {
                    app.getResults();
                    swal("Kas Keluar Berhasil Dihapus!  ", {
                      icon: "success",
                    });
                  })
                  .catch(function (resp) {
                    app.$router.replace('/kas-keluar/');
                    swal("Gagal Menghapus Kas Keluar!", {
                      icon: "warning",
                    });
                  });
               }
               this.$router.replace('/kas-keluar/');
            });
        },
         gagalHapus(id, index,nama_kategori_transaksi) {
            this.$swal({
                title: "Gagal ",
                text: "Kas Keluar '"+nama_kategori_transaksi+"' Sudah Terpakai",
                icon: "warning",
            });
        }
    }
}
</script>