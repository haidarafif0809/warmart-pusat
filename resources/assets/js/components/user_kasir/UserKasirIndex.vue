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
        <li class="active">User Kasir</li>
      </ul>

      <div class="card">
        <div class="card-header card-header-icon" data-background-color="purple">
          <i class="material-icons">account_circle</i>
        </div>

        <div class="card-content">
          <h4 class="card-title"> User Kasir </h4>

          <div class="toolbar">
            <p> <router-link :to="{name: 'createUserKasir'}" class="btn btn-primary">Tambah Kasir</router-link></p>
          </div>

          <div class=" table-responsive ">
           <div class="pencarian">
             <input type="text" class="form-control pencarian" autocomplete="off" name="pencarian" v-model="pencarian" placeholder="Pencarian" >
           </div>

           <table class="table table-striped table-hover">
            <thead class="text-primary">
              <tr>
                <th>No. Telpon</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th></th>
              </tr>
            </thead>

            <tbody v-if="userKasirs.length > 0 && loading== false" class="data-ada">
              <tr v-for="userKasir, index in userKasirs">
                <td>{{ userKasir.data_kasir.no_telp }}</td>
                <td>{{ userKasir.data_kasir.name }}</td>
                <td>{{ userKasir.data_kasir.alamat }}</td>
                <td> 
                  <router-link :to="{name: 'editUserKasir', params: {id: userKasir.data_kasir.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + userKasir.data_kasir.id" >
                    Edit 
                  </router-link>
                  <a href="#user-kasir" class="btn btn-xs btn-danger" v-bind:id="'delete-' + userKasir.data_kasir.id" v-on:click="deleteEntry(userKasir.data_kasir.id, index,userKasir.data_kasir.name)"> 
                    Delete
                  </a>
                </td>
              </tr>
            </tbody>
            <!--JIKA DATA UserKasir KOSONG-->
            <tbody class="data-tidak-ada" v-else-if="userKasirs.length == 0 && loading== false">
              <tr>
                <td colspan="4"  class="text-center">Tidak Ada Data</td>
              </tr>
            </tbody>
          </table>

          <!--LOADING-->
          <vue-simple-spinner v-if="loading"></vue-simple-spinner>
          <!--PAGINATION TABLE-->
          <div align="right"><pagination :data="userKasirsData" :limit="4" v-on:pagination-change-page="getResults"></pagination></div>

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
        userKasirs: [],
        userKasirsData: {},
        url : window.location.origin+(window.location.pathname).replace("dashboard", "user-kasir"),
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
          app.userKasirs = resp.data.data;
          app.userKasirsData = resp.data;
          app.loading = false;
        })
        .catch(function (resp) {
          console.log(resp);
          app.loading = false;
          alert("Tidak Bisa Memuat User Kasir");
        });
      },
      getHasilPencarian(page){
        var app = this;
        if (typeof page === 'undefined') {
          page = 1;
        }
        axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
        .then(function (resp) {
          app.userKasirs = resp.data.data;
          app.userKasirsData = resp.data;
        })
        .catch(function (resp) {
          console.log(resp);
          alert("Tidak Bisa Memuat User Kasir");
        });
      },
      deleteEntry(id, index,name) {
        swal({ 
          title: "Konfirmasi Hapus", 
          text : "Anda Yakin Ingin Menghapus User Kasir "+name+" ?", 
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
              app.alert('User Kasir Berhasil Dihapus!');
            }) 
            .catch(function (resp) { 
              swal("Gagal! User Kasir Tidak Bisa Dihapus!  ", { 
                icon: "warning", 
              }); 
            });  
          }
        });
      },
      alert(pesan) {
        this.$swal({
          title: "Berhasil ",
          text: pesan,
          icon: "success",
          buttons: false,
          timer: 1000
        });
      }
    }
  }
</script>
