<style scoped>
  .pencarian {
    color: red; 
    float: right;
  }
</style>
<template>
  <div class="row">
    <div class="col-md-12">
     <ul class="breadcrumb">
      <li><a href="#">Home</a></li>
      <li class="active">Kas</li>
    </ul>
    <div class="card">
      <div class="card-header card-header-icon" data-background-color="purple">
       <i class="material-icons">payment</i>
     </div>
     <div class="card-content">
       <h4 class="card-title"> Kas</h4>
       <div class="toolbar" v-if="otoritas.tambah_kas == 1">
         <p> 
           <router-link :to="{name: 'createKas'}" class="btn btn-primary">Tambah Kas</router-link>

           <router-link :to="{name: 'createBankWarung'}" class="btn btn-primary">Tambah Bank</router-link>
         </p>
       </div>
       <br>
       <div class="table-responsive">
         <div  class="pencarian">
           <input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control" autocomplete="">
         </div>
         <table class="table table-striped table-hover ">
          <thead class="text-primary">
            <tr>

              <th>Kode Kas</th>
              <th>Nama Kas</th>
              <th>No Rekening</th>
              <th>Atas Nama</th>
              <th>Tampil Transaksi</th>
              <th>Default Kas</th>
              <th>Total Kas</th>
              <th  v-if="otoritas.edit_kas == 1">Edit</th>
              <th  v-if="otoritas.hapus_kas == 1">Hapus</th>
            </tr>
          </thead>
          <tbody v-if="kass.length > 0 && loading == false" class="data-ada">
            <tr v-for="kas, index in kass" >

              <td>{{ kas.kas.kode_kas }}</td>
              <td>{{ kas.kas.nama_kas }}</td>

              <td v-if="kas.kas.no_rek != null">{{ kas.kas.no_rek }}</td>
              <td v-else> - </td>

              <td v-if="kas.kas.atas_nama != null">{{ kas.kas.atas_nama }}</td>
              <td v-else> - </td>

              <td v-if="kas.kas.status_kas == 1">
                Aktif
              </td>
              <td v-else>
                Tidak Aktif
              </td>
              <td v-if="kas.kas.default_kas == 1">
                <i style="color:green" class="material-icons">check_circle</i>
              </td>
              <td v-else>
                <i style="color:red" class="material-icons">cancel</i>
              </td>
              <td>{{ kas.total_kas }}</td>
              <td> 
               <router-link :to="{name: 'editKas', params: {id: kas.kas.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + kas.kas.id" v-if="otoritas.edit_kas == 1">
                Edit 
              </router-link> 
            </td>
            <td>

              <a v-if="kas.status_transaksi == 0 && otoritas.hapus_kas == 1" href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + kas.kas.id"  v-on:click="deleteEntry(kas.kas.id, index,kas.kas.nama_kas)" >
                Delete </a>
                <a v-if="kas.status_transaksi == 1  && otoritas.hapus_kas == 1" href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + kas.kas.id"
                v-on:click="gagalHapus(kas.kas.id, index,kas.kas.nama_kas)">  Delete </a>

              </td>

            </tr>
          </tbody>
          <tbody class="data-tidak-ada" v-else-if="kass.length == 0 && loading == false">
            <tr ><td colspan="8"  class="text-center">Tidak Ada Data</td></tr>
          </tbody>
        </table>

        <vue-simple-spinner v-if="loading"></vue-simple-spinner>

        <div align="right"><pagination :data="kassData" v-on:pagination-change-page="getResults"></pagination></div>

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
        kass: [],
        kassData: {},
        otoritas: {},
        url : window.location.origin+(window.location.pathname).replace("dashboard", "kas"),
        pencarian: '',
        contoh : '',
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
            app.kass = resp.data.data;
            app.kassData = resp.data;
            app.otoritas = resp.data.otoritas_kas.original;
            app.loading = false;
            console.log(app.otoritas)
          })
          .catch(function (resp) {
            console.log(resp);
            app.loading = false;
            alert("Data Kas tidak dapat dicari");
          });
        },
        getHasilPencarian(page){
          var app = this;
          if (typeof page === 'undefined') {
            page = 1;
          }
          axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
          .then(function (resp) {
            app.kass = resp.data.data;
            app.kassData = resp.data;
            app.otoritas = resp.data.otoritas_kas.original;
          })
          .catch(function (resp) {
            console.log(resp);
            alert("Data Kas tidak dapat dicari");
          });
        },
        alert(pesan) {
          this.$swal({
            title: "Berhasil Menghapus Kas Masuk!",
            text: pesan,
            icon: "success",
          });
        },
        deleteEntry(id, index,nama_kas) {
          var app = this;
          app.$swal({
            title: "Konfirmasi Hapus",
            text : "Anda Yakin Ingin Menghapus Kas "+nama_kas+" ?",
            icon : "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              var app = this;
              axios.delete(app.url+'/' + id)
              .then(function (resp) {
                app.$router.replace('/kas/');
                app.getResults();
                app.$swal("Kas Berhasil Dihapus!  ", {
                  icon: "success",
                });
              })
              .catch(function (resp) {
                app.$swal("Gagal Menghapus Kas Masuk!  ", {
                  icon: "warning",
                });
              });
            }
          });
        },
        gagalHapus(id, index,nama_kas) {
          this.$swal({
            title: "Gagal ",
            text: "Kas '"+nama_kas+"' Sudah Terpakai",
            icon: "warning",
          });
        }

      }
    }
  </script>