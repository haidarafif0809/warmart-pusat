<template>


  <div class="row" >

    <div class="col-md-12">
     <ul class="breadcrumb">
      <li><a href="#">Home</a></li>
      <li class="active">User</li>
    </ul>


    <div class="card">
      <div class="card-header card-header-icon" data-background-color="purple">
       <i class="material-icons">dns</i>
     </div>
     <div class="card-content">
       <h4 class="card-title"> User </h4>

       <div class="toolbar">

        <p> <router-link :to="{name: 'createUser'}" class="btn btn-primary">Tambah User</router-link></p>

      </div>
      <div class=" table-responsive ">
       <div  align="right">
         pencarian
         <input type="text" name="pencarian" v-model="pencarian" autocomplete="" placeholder="Kolom Pencarian" >
       </div>

       <table class="table table-striped table-hover ">
        <thead class="text-primary">
          <tr>
            <th>Nama</th>
            <th>No Telp</th>
            <th>Email</th>
            <th>Alamat</th>
            <th>Otoritas</th>
            <th>Reset</th>
            <th>Konfirmasi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody v-if="users.length > 0 && loading == false" class="data-ada">
          <tr v-for="user, index in users" >
            <td>{{ user.user.name }}</td>
            <td>{{ user.user.no_telp }}</td>
            <td>{{ user.user.email }}</td>
            <td>{{ user.user.alamat }}</td>
            <td>{{ user.role_user }}</td>
            <td><a href="#"
            class="btn btn-xs btn-info" v-bind:id="'reset-' + user.user.id"
            v-on:click="resetEntry(user.user.id, index,user.user.name)">
            Reset
          </a></td>
          <td v-if="user.user.status_konfirmasi == 1">
            <a href="#"
            class="btn btn-xs btn-warning" v-bind:id="'confirm-no-' + user.user.id"
            v-on:click="nokonfirmasiEntry(user.user.id, index,user.user.name)">
            Tidak
          </a>
          </td>
          <td v-else>
            <a href="#"
            class="btn btn-xs btn-primary" v-bind:id="'confirm-ya-' + user.user.id"
            v-on:click="konfirmasiEntry(user.user.id, index,user.user.name)">
            Iya
          </a>
          </td>
            <td> 
             <router-link :to="{name: 'editUser', params: {id: user.user.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + user.user.id" >
              Edit 
            </router-link> <a href="#"
            class="btn btn-xs btn-danger" v-bind:id="'delete-' + user.user.id"
            v-on:click="deleteEntry(user.user.id, index,user.user.name)">
            Delete
          </a>
        </td>
      </tr>
      </tbody>
      <tbody class="data-tidak-ada" v-else-if="users.length == 0 && loading == false">
        <tr ><td colspan="4"  class="text-center">Tidak Ada Data</td></tr>
      </tbody>
    </table>


    <vue-simple-spinner v-if="loading"></vue-simple-spinner>

    <div align="right"><pagination :data="usersData" v-on:pagination-change-page="getResults"></pagination></div>



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
      users: [],
      usersData: {
      },
      url : window.location.origin+(window.location.pathname).replace("dashboard", "user"),
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
            app.users = resp.data.data;
            app.usersData = resp.data;
            app.loading = false;
          })
          .catch(function (resp) {
            console.log(resp);
            app.loading = false;
            alert("Could not load users");
          });
        },

        getHasilPencarian(page){
          var app = this;
          if (typeof page === 'undefined') {
            page = 1;
          }
          axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
          .then(function (resp) {
            app.users = resp.data.data;
            app.usersData = resp.data;
          })
          .catch(function (resp) {
            console.log(resp);
            alert("Could not load users");
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
            text : "Anda Yakin Ingin Menghapus User "+name+" ?",
            icon : "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              var app = this;
              axios.delete(app.url+'/' + id)
              .then(function (resp) {
                app.$router.replace('/user/');
                app.getResults();
                swal("User Berhasil Dihapus!  ", {
                  icon: "success",
                });
              })
              .catch(function (resp) {
                swal("Gagal Menghapus User!  ", {
                  icon: "warning",
                });
              });

           }
          });
        },
        resetEntry(id, index,name) {
          swal({
            title: "Konfirmasi Reset",
            text : "Anda Yakin Ingin Me Reset Password User "+name+" ?",
            icon : "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              var app = this;
              axios.get(app.url+'/reset?idreset=' + id)
              .then(function (resp) {
                app.$router.replace('/user/');
                app.getResults();
                swal("User Berhasil Direset!  ", {
                  icon: "success",
                });
              })
              .catch(function (resp) {
                swal("Gagal Mereset User!  ", {
                  icon: "warning",
                });
              });

           }
          });
        },
       konfirmasiEntry(id, index,name) {
           swal({
            title: "Konfirmasi User",
            text : "Apakah Anda Yakin Ingin Meng Konfirmasi User "+name+" ?",
            icon : "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              var app = this;
              axios.get(app.url+'/konfirmasi?confirm=' + id)
              .then(function (resp) {
                app.$router.replace('/user/');
                app.getResults();
                swal("User Berhasil Dikonfirmasi!  ", {
                  icon: "success",
                });
              })
              .catch(function (resp) {
                swal("Gagal konfirmasi User!  ", {
                  icon: "warning",
                });
              });

           }
          });
        },
       nokonfirmasiEntry(id, index,name) {
        swal({
            title: "Konfirmasi User",
            text : "Apakah Anda Yakin Batal Meng Konfirmasi User "+name+" ?",
            icon : "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              var app = this;
              axios.get(app.url+'/no-konfirmasi?confirm=' + id)
              .then(function (resp) {
                app.$router.replace('/user/');
                app.getResults();
                swal("User Berhasil Batal Konfirmasi!  ", {
                  icon: "success",
                });
              })
              .catch(function (resp) {
                swal("Gagal batal konfirmasi User!  ", {
                  icon: "warning",
                });
              });

           }
          });
        }
      }
    }
    </script>
