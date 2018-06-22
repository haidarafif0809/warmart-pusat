<style scoped>
.pencarian {
  color: red; 
  float: right;
}
</style>
<template>

  <div class="row" >
    <div class="col-md-12">

      <ul class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li class="active">User Topos</li>
      </ul>

      <div class="card">
        <div class="card-header card-header-icon" data-background-color="purple">
          <i class="material-icons">account_circle</i>
          <i class="material-icons">store</i>
        </div>

        <div class="card-content">
          <h4 class="card-title"> User Topos </h4>

          <div class=" table-responsive ">

            <div class="pencarian">
              <input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
            </div>

            <table class="table table-striped table-hover">
              <thead class="text-primary">
                <tr>
                  <th>Nama Toko</th>
                  <th>Nama Pemilik</th>
                  <th>Email</th>
                  <th>No. Telpon</th>
                  <th>Alamat</th>
                  <th>Foto Bukti Pembayaran</th>
                  <th>Detail</th>
                  <th>Konfirmasi</th>
                </tr>
              </thead>

              <tbody v-if="pendaftaranTopos.length > 0 && loading== false" class="data-ada">
                <tr v-for="pendaftaranTopos, index in pendaftaranTopos">
                  <td>{{ pendaftaranTopos.pendaftar_topos.name }}</td>
                  <td>{{ pendaftaranTopos.pendaftar_topos.user_warung.name }}</td>
                  <td>{{ pendaftaranTopos.pendaftar_topos.email }}</td>
                  <td>{{ pendaftaranTopos.pendaftar_topos.no_telp }}</td>
                  <td>{{ pendaftaranTopos.pendaftar_topos.alamat }}</td>
                  <td><a v-if="pendaftaranTopos.pendaftar_topos.foto != undefined" v-bind:href="url_foto_bukti_pembayaran+ '/'+pendaftaranTopos.pendaftar_topos.foto" target="blank">Lihat Foto</a>
                    <p v-else >Belum Membayar</p>
                  </td>
                  <td><router-link :to="{name: 'detailPendaftaranTopos', params: {id: pendaftaranTopos.pendaftar_topos.id }}" class="btn btn-xs btn-info" v-bind:id="'detail-' + pendaftaranTopos.pendaftar_topos.id">
                  Detail </router-link> </td>
                  <td>
                    <a v-if="pendaftaranTopos.pendaftar_topos.foto != undefined && pendaftaranTopos.pendaftar_topos.status_pembayaran == 1" href="#user-topos" class="btn btn-xs btn-primary" v-bind:id="'confirm-ya-' + pendaftaranTopos.pendaftar_topos.id" v-on:click="konfirmasiEntry(pendaftaranTopos.pendaftar_topos.id, index,pendaftaranTopos.pendaftar_topos.name)">
                    Iya  </a>

                    <a v-else-if="pendaftaranTopos.pendaftar_topos.foto != undefined && pendaftaranTopos.pendaftar_topos.status_pembayaran == 2" href="#user-topos" class="btn btn-xs btn-warning">
                    Sudah Terkonfirmasi </a>

                    <a v-else-if="pendaftaranTopos.pendaftar_topos.foto == null && pendaftaranTopos.pendaftar_topos.status_pembayaran == null" href="#user-topos" class="btn btn-xs btn-warning" >
                    Belum Membayar </a>
                  </td>
                </tr>
              </tbody>
              <!--JIKA DATA pendaftaranTopos KOSONG-->
              <tbody class="data-tidak-ada" v-else-if="pendaftaranTopos.length == 0 && loading== false">
                <tr>
                  <td colspan="8"  class="text-center">Tidak Ada Data</td>
                </tr>
              </tbody>
            </table>

            <!--LOADING-->
            <vue-simple-spinner v-if="loading"></vue-simple-spinner>
            <!--PAGINATION TABLE-->
            <div align="right"><pagination :data="pendaftaranToposData" :limit="4" v-on:pagination-change-page="getResults"></pagination></div>

          </div><!-- /END RESPONSIVE-->

        </div>
      </div>

    </div><!--/END-COL-SM-10-->
  </div><!--/END ROW-->

</template>

<script>
export default {
  data: function () {
    return {
      pendaftaranTopos: [],
      pendaftaranToposData: {},
      url : window.location.origin+(window.location.pathname).replace("dashboard", "daftar-topos"),
      url_foto_bukti_pembayaran : window.location.origin+(window.location.pathname).replace("dashboard", "foto_bukti_pembayaran"),
      pencarian: '',
      loading: true
    }
  },
  mounted() {
    var app = this;
    app.getResults();
  },
  watch: {
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
        app.pendaftaranTopos = resp.data.data;
        app.pendaftaranToposData = resp.data;
        app.loading = false;
      })
      .catch(function (resp) {
        console.log(resp);
        app.loading = false;
        alert("Tidak Bisa Memuat user Topos");
      });
    },
    getHasilPencarian(page){
      var app = this;
      if (typeof page === 'undefined') {
        page = 1;
      }
      axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
      .then(function (resp) {
        app.pendaftaranTopos = resp.data.data;
        app.pendaftaranToposData = resp.data;
      })
      .catch(function (resp) {
        console.log(resp);
        alert("Tidak Bisa Memuat User Topos");
      });
    },
    deleteEntry(id, index,name) {
      swal({ 
        title: "Konfirmasi Hapus", 
        text : "Anda Yakin Ingin Menghapus User Topos "+name+" ?", 
        icon : "warning", 
        buttons: true, 
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) { 
          var app = this; 
          axios.delete(app.url+'/' + id) 
          .then(function (resp) { 
            app.$router.replace('/user-warung/'); 
            app.getResults();
            console.log(resp)
            if (resp.status == 200) {
              swal({ 
                title: "Berhasil !",
                text: "User Topos Berhasil Dihapus!",
                icon: "success",
              }); 
            }
            else{
              swal({ 
                title: "Gagal !",
                text: "User Topos Tidak Bisa Dihapus!",
                icon: "warning",
              }); 
            }
          }) 
          .catch(function (resp) { 
            swal("Gagal! User Topos Tidak Bisa Dihapus!  ", { 
              icon: "warning", 
            }); 
          });  
        }
      });
    },
    konfirmasiEntry(id, index,name) {
     this.$swal({
      text : "Anda Yakin Ingin Mengkonfirmasi User Topos "+name+" ?",
      buttons: true,
      dangerMode: true,
    })
     .then((willDelete) => {
      if (willDelete) {

        var app = this;
        axios.get(app.url+'/konfirmasi/' + id)
        .then(function (resp) {

          app.getResults();
          app.alert("Mengkonfirmasi User Topos "+name)

        })
        .catch(function (resp) {
          console.log(resp);
          alert("Tidak dapat Mengkonfirmasi User Topos "+name);
        });

      }
    });
   },
   alert(pesan) {
    this.$swal({
      title: "Berhasil ",
      text: pesan,
      icon: "success",
    });
  }
}
}
</script>
