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
       <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
       <li style="color: purple">Master Data</li>
       <li class="active">User</li>
     </ul>

     <div class="card">
      <div class="card-header card-header-icon" data-background-color="purple">
        <i class="material-icons">person</i>
      </div>

      <div class="card-content">
        <h4 class="card-title"> User </h4>

        <div class="row">
          <div class="col-md-6">
           <div class="toolbar">
            <p> <router-link :to="{name: 'createDaftarUserWarung'}" class="btn btn-primary"><i class="material-icons">add</i> Tambah User</router-link></p>
          </div>
        </div>
        <div class="col-md-6">
          <div  class="pencarian">
           <input type="text" class="form-control pencarian" autocomplete="off" name="pencarian" v-model="pencarian" placeholder="Pencarian" >
         </div>
       </div>
     </div>


     <div class=" table-responsive">
       <table class="table table-striped table-hover">
        <thead class="text-primary">
          <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>No. Telpon</th>
            <th>Alamat</th>
            <th>Otoritas</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody v-if="user.length > 0 && loading== false" class="data-ada">
          <tr v-for="users, index in user">
            <td>{{ users.user.name }}</td>
            <td>{{ users.user.email }}</td>
            <td>{{ users.user.no_telp }}</td>
            <td>{{ users.user.alamat }}</td>
            <td>{{ users.role_user }}</td>
          </td>
          <td> 
           <router-link :to="{name: 'editDaftarUser', params: {id: users.user.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + users.user.id" >
            Edit 
          </router-link>
          <a href="#daftar-user" class="btn btn-xs btn-danger" v-bind:id="'delete-' + users.user.id" v-on:click="deleteEntry(users.user.id, index,users.user.name)">
            Delete
          </a>
        </td>

      </tr>
    </tbody>
    <!--JIKA DATA CUSTOMER KOSONG-->
    <tbody class="data-tidak-ada" v-else-if="user.length == 0 && loading== false">
      <tr>
        <td colspan="4"  class="text-center">Tidak Ada Data</td>
      </tr>
    </tbody>
  </table>

  <!--LOADING-->
  <vue-simple-spinner v-if="loading"></vue-simple-spinner>
  <!--PAGINATION TABLE-->
  <div align="right"><pagination :data="userData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

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
      user: [],
      userData: {},
      url : window.location.origin+(window.location.pathname).replace("dashboard", "daftar-user-warung"),
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
  filters: {
    tanggal: function (value) {
      return moment(String(value)).format('DD/MM/YYYY hh:mm')
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
        app.user = resp.data.data;
        app.userData = resp.data;
        app.loading = false;
      })
      .catch(function (resp) {
        console.log(resp);
        app.loading = false;
        alert("Tidak Bisa Memuat User");
      });
    },
    getHasilPencarian(page){
      var app = this;
      if (typeof page === 'undefined') {
        page = 1;
      }
      app.loading = true
      axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
      .then(function (resp) {
        app.user = resp.data.data;
        app.userData = resp.data;
        app.loading = false
      })
      .catch(function (resp) {
        app.loading = false
        console.log(resp);
        alert("Tidak Bisa Memuat User");
      });
    }, 
    alert(pesan) { 
      this.$swal({ 
        title: "Berhasil!", 
        text: pesan, 
        icon: "success", 
        buttons: false,
        timer: 1000,
      }); 
    }, 
    deleteEntry(id, index,nama) {

      var app = this;
      app.$swal({
        text: "Anda Yakin Ingin Menghapus User "+nama+ " ?",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {

          this.prosesDelete(id,nama);

        } else {

          app.$swal.close();

        }
      });

    },
    prosesDelete(id,nama){
     var app = this; 
     axios.delete(app.url+'/' + id) 
     .then(function (resp) { 

      function cekIndexUser(user) { 
        return user.user.id === id
      }
      var index = app.user.findIndex(cekIndexUser)
      app.user.splice(index,1)
      app.alert("Menghapus User "+nama)

    }) 
     .catch(function (resp) { 
      alert("Tidak dapat Menghapus User")
    });  
   }
 }
}
</script>
