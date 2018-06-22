<template>
	
	<div class="row" >
    <div class="col-md-12">
      <ul class="breadcrumb">
        <li><a href=" ">Home</a></li>
        <li><router-link :to="{name: 'indexUserKasir'}">User Kasir</router-link></li>
        <li class="active">Edit User Kasir</li>
      </ul>

      <div class="card">
       <div class="card-header card-header-icon" data-background-color="purple">
         <i class="material-icons">account_circle</i>
       </div>

       <div class="card-content">
         <h4 class="card-title"> User Kasir </h4>
         <div>
          <form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
            <div class="form-group">
              <label for="name" class="col-md-2 control-label">Nama</label>
              <div class="col-md-4">
                <input class="form-control" required autocomplete="off" placeholder="Nama" type="text" v-model="userKasir.name" name="name"  autofocus="">
                <span v-if="errors.name" id="name_error" class="label label-danger">{{ errors.name[0] }}</span>
              </div>
            </div>

            <div class="form-group">
              <label for="no_telp" class="col-md-2 control-label">No. Telpon</label>
              <div class="col-md-4">
                <input class="form-control" required autocomplete="off" placeholder="No. Telpon" type="number" v-model="userKasir.no_telp" name="no_telp"  autofocus="">
                <span v-if="errors.no_telp" id="no_telp_error" class="label label-danger">{{ errors.no_telp[0] }}</span>
              </div>
            </div>

            <div class="form-group">
              <label for="alamat" class="col-md-2 control-label">Alamat</label>
              <div class="col-md-4">
                <input class="form-control" required autocomplete="off" placeholder="Alamat" type="text" v-model="userKasir.alamat" name="alamat"  autofocus="">
                <span v-if="errors.alamat" id="alamat_error" class="label label-danger">{{ errors.alamat[0] }}</span>
              </div>
            </div>

            <div class="form-group">
              <label for="password" class="col-md-2 control-label">Password </label>
              <div class="col-md-4">
                <input class="form-control" autocomplete placeholder="Password " v-model="userKasir.password" type="password" name="password" id="password">
                <span v-if="errors.password" id="password_error" class="label label-danger">{{ errors.password[0] }}</span>
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-4 col-md-offset-2">
                <button class="btn btn-primary" id="btnSimpanUserKasir" type="submit"><i class="material-icons">send</i> Submit</button>
              </div>
            </div>
          </form>
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
        warung: [],
        kelurahan: [],
        userKasirId: null,
        userKasir: {
          name: '',
          no_telp: '',
          email: '',
          alamat: '',
          kelurahan: '',
          id_warung: '',
          password: '',
        },
        message : '',
        url : window.location.origin+(window.location.pathname).replace("dashboard", "user-kasir"),
        errors: []
      }
    },
    mounted() {
      let app = this;
      let id = app.$route.params.id;

      app.userKasirId = id;
      app.getDataKasir(app, id);
    },
    methods: {
      getDataKasir(app, id){
        axios.get(app.url+'/' + id)
        .then(function (resp) {
          app.userKasir = resp.data;
        })
        .catch(function () {
          console.log(resp)
          alert("Tidak Bisa Memuat User Kasir!")
        });
      },
      saveForm() {
        var app = this;
        var newUserKasir = app.userKasir;
        axios.patch(app.url+'/' + app.userKasirId, newUserKasir)
        .then(function (resp) {
          app.message = 'Berhasil Mengubah User Kasir '+ app.userKasir.name;
          app.alert(app.message);
          app.$router.replace('/user-kasir');
        })
        .catch(function (resp) {
          app.errors = resp.response.data.errors;
          alert("Periksa Kembali Data Yang Anda Masukan");
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
</script>