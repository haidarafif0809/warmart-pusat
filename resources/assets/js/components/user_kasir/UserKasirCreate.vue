<template>
	
	<div class="row" >
    <div class="col-md-12">
      <ul class="breadcrumb">
        <li><a href=" ">Home</a></li>
        <li><router-link :to="{name: 'indexUserKasir'}">User Kasir</router-link></li>
        <li class="active">Tambah User Kasir</li>
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
                <input class="form-control" autocomplete="off" placeholder="Nama" type="text" v-model="userKasir.name" name="name"  autofocus="">
                <span v-if="errors.name" id="name_error" class="label label-danger">{{ errors.name[0] }}</span>
              </div>
            </div>

            <div class="form-group">
              <label for="no_telp" class="col-md-2 control-label">No. Telpon</label>
              <div class="col-md-4">
                <input class="form-control" autocomplete="off" placeholder="No. Telpon" type="number" v-model="userKasir.no_telp" name="no_telp"  autofocus="">
                <span v-if="errors.no_telp" id="no_telp_error" class="label label-danger">{{ errors.no_telp[0] }}</span>
              </div>
            </div>

            <div class="form-group">
              <label for="alamat" class="col-md-2 control-label">Alamat</label>
              <div class="col-md-4">
                <input class="form-control" autocomplete="off" placeholder="Alamat" type="text" v-model="userKasir.alamat" name="alamat"  autofocus="">
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
                <button class="btn btn-primary" id="btnSimpanuserKasir" type="submit"><i class="material-icons">send</i> Submit</button>
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
    mounted() {
      let app = this;

    },
    data: function () {
      return {
        userKasir: {
          name: '',
          no_telp: '',
          alamat: '',
          password: '',
        },
        message : '',
        url : window.location.origin+(window.location.pathname).replace("dashboard", "user-kasir"),
        errors: []
      }
    },
    methods: {
      saveForm() {
        var app = this;
        var newUserKasir = app.userKasir;
        axios.post(app.url, newUserKasir)
        .then(function (resp) {
          app.message = 'Sukses : Berhasil Menambah User Kasir '+ app.userKasir.name;
          app.alert(app.message);
          app.userKasir.name = ''
          app.userKasir.no_telp = ''
          app.userKasir.alamat = ''
          app.errors = '';
          app.$router.replace('/user-kasir');
        })
        .catch(function (resp) {
          app.success = false;
          console.log(resp.response.data.errors)
          app.errors = resp.response.data.errors;
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