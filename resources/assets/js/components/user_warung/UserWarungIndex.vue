<template>

  <div class="row" >
    <div class="col-md-12">

      <ul class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li class="active">User Warung</li>
      </ul>

      <div class="card">
        <div class="card-header card-header-icon" data-background-color="purple">
          <i class="material-icons">account_circle</i>
          <i class="material-icons">store</i>
        </div>

         <div class="card-content">
          <h4 class="card-title"> User Warung </h4>

          <div class=" table-responsive ">
            <div  align="right">
               Pencarian
               <input type="text" name="pencarian" v-model="pencarian" placeholder="Kolom Pencarian" >
            </div>

            <table class="table table-striped table-hover">
              <thead class="text-primary">
                <tr>
                  <th>Email</th>
                  <th>No. Telpon</th>
                  <th>Nama</th>
                  <th>Alamat</th>
                  <th>Wilayah</th>
                  <th>Warung</th>
                  <th>Foto Ktp</th>
                  <th>Konfirmasi</th>
                  <th></th>
                </tr>
              </thead>

              <tbody v-if="userWarungs.length > 0 && loading== false" class="data-ada">
                <tr v-for="userWarung, index in userWarungs">
                  <td>{{ userWarung.user_warung.email }}</td>
                  <td>{{ userWarung.user_warung.no_telp }}</td>
                  <td>{{ userWarung.user_warung.name }}</td>
                  <td>{{ userWarung.user_warung.alamat }}</td>
                  <td>{{ userWarung.wilayah }}</td>
                  <td>{{ userWarung.warung }}</td>
                  <td><a v-if="userWarung.user_warung.foto_ktp != undefined" v-bind:href="url_foto_ktp+ '/'+userWarung.user_warung.foto_ktp" target="blank">Lihat Ktp</a>
                      <p v-else >Belum Ada Ktp</p>
                  </td>
                  <td v-if="userWarung.user_warung.konfirmasi_admin == 1">
                    <a href="#"
                    class="btn btn-xs btn-warning" v-bind:id="'confirm-no-' + userWarung.user_warung.id"
                    v-on:click="nokonfirmasiEntry(userWarung.user_warung.id, index,userWarung.user_warung.name)">
                    Tidak
                  </a>
                  </td>
                  <td v-else>
                    <a href="#"
                    class="btn btn-xs btn-primary" v-bind:id="'confirm-ya-' + userWarung.user_warung.id"
                    v-on:click="konfirmasiEntry(userWarung.user_warung.id, index,userWarung.user_warung.name)">
                    Iya
                  </a>
                  </td>
                  <td> 
                    <router-link :to="{name: 'editUserWarung', params: {id: userWarung.user_warung.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + userWarung.user_warung.id" >
                      Edit 
                    </router-link>
                    <a href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + userWarung.user_warung.id" v-on:click="deleteEntry(userWarung.user_warung.id, index,userWarung.user_warung.name)"> 
                      Delete
                    </a>
                  </td>
                </tr>
              </tbody>
              <!--JIKA DATA UserWarung KOSONG-->
              <tbody class="data-tidak-ada" v-else-if="userWarungs.length == 0 && loading== false">
                <tr>
                  <td colspan="8"  class="text-center">Tidak Ada Data</td>
                </tr>
              </tbody>
            </table>

            <!--LOADING-->
            <vue-simple-spinner v-if="loading"></vue-simple-spinner>
            <!--PAGINATION TABLE-->
            <div align="right"><pagination :data="userWarungsData" :limit="4" v-on:pagination-change-page="getResults"></pagination></div>

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
      userWarungs: [],
      userWarungsData: {},
      url : window.location.origin+(window.location.pathname).replace("dashboard", "user-warung"),
      url_foto_ktp : window.location.origin+(window.location.pathname).replace("dashboard", "foto_ktp_user"),
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
            app.userWarungs = resp.data.data;
            app.userWarungsData = resp.data;
            app.loading = false;
          })
          .catch(function (resp) {
            console.log(resp);
            app.loading = false;
            alert("Tidak Bisa Memuat User Warung");
          });
        },
        getHasilPencarian(page){
          var app = this;
          if (typeof page === 'undefined') {
            page = 1;
          }
          axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
          .then(function (resp) {
            app.userWarungs = resp.data.data;
            app.userWarungsData = resp.data;
          })
          .catch(function (resp) {
            console.log(resp);
            alert("Tidak Bisa Memuat User Warung");
          });
        },
        deleteEntry(id, index,name) {
          swal({ 
            title: "Konfirmasi Hapus", 
            text : "Anda Yakin Ingin Menghapus User Warung "+name+" ?", 
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
                      text: "User Warung Berhasil Dihapus!",
                      icon: "success",
                }); 
                }
                else{
                  swal({ 
                      title: "Gagal !",
                      text: "User Warung Tidak Bisa Dihapus!",
                      icon: "warning",
                  }); 
                }
              }) 
              .catch(function (resp) { 
                swal("Gagal! User Warung Tidak Bisa Dihapus!  ", { 
                  icon: "warning", 
                }); 
              });  
            }
          });
        },
        konfirmasiEntry(id, index,name) {
           swal({
            title: "Konfirmasi User",
            text : "Apakah Anda Yakin Ingin Mengkonfirmasi User "+name+" ?",
            icon : "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              var app = this;
              axios.get(app.url+'/konfirmasi?confirm=' + id)
              .then(function (resp) {
                app.$router.replace('/user-warung/');
                app.getResults();
                swal("Berhasil Mengkonfirmasi Warung!  ", {
                  icon: "success",
                });
              })
              .catch(function (resp) {
                swal("Gagal Mengkonfirmasi User Warung !  ", {
                  icon: "warning",
                });
              });

           }
          });
        },
        nokonfirmasiEntry(id, index,name) {
        swal({
            title: "Konfirmasi User",
            text : "Apakah Anda Yakin Batal Mengkonfirmasi User "+name+" ?",
            icon : "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              var app = this;
              axios.get(app.url+'/no-konfirmasi?confirm=' + id)
              .then(function (resp) {
                app.$router.replace('/user-warung/');
                app.getResults();
                swal("Berhasil Membatalkan Konfirmasi Warung!  ", {
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
