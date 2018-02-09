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
                <input class="form-control" required autocomplete="off" placeholder="Nama" type="text" v-model="userWarung.name" name="name"  autofocus="">
                <span v-if="errors.name" id="name_error" class="label label-danger">{{ errors.name[0] }}</span>
              </div>
            </div>

            <div class="form-group">
              <label for="no_telp" class="col-md-2 control-label">No. Telpon</label>
              <div class="col-md-4">
                <input class="form-control" required autocomplete="off" placeholder="No. Telpon" type="number" v-model="userWarung.no_telp" name="no_telp"  autofocus="">
                <span v-if="errors.no_telp" id="no_telp_error" class="label label-danger">{{ errors.no_telp[0] }}</span>
              </div>
            </div>

            <div class="form-group">
              <label for="email" class="col-md-2 control-label">Email</label>
              <div class="col-md-4">
                <input class="form-control" required autocomplete="off" placeholder="Email" type="email" v-model="userWarung.email" name="email"  autofocus="">
                <span v-if="errors.email" id="email_error" class="label label-danger">{{ errors.email[0] }}</span>
              </div>
            </div>

            <div class="form-group">
              <label for="alamat" class="col-md-2 control-label">Alamat</label>
              <div class="col-md-4">
                <input class="form-control" required autocomplete="off" placeholder="Alamat" type="text" v-model="userWarung.alamat" name="alamat"  autofocus="">
                <span v-if="errors.alamat" id="alamat_error" class="label label-danger">{{ errors.alamat[0] }}</span>
              </div>
            </div>

            <div class="form-group">
              <label for="kelurahan" class="col-md-2 control-label ">Kelurahan</label>
              <div class="col-md-4">
                <selectize-component v-model="userWarung.wilayah" :settings="placeholderKelurahan" id="kelurahan"> 
                  <option v-for="data_kelurahan, index in kelurahan" v-bind:value="data_kelurahan.id" >{{ data_kelurahan.nama }}</option>
                </selectize-component>
              </div>
            </div>

            <div class="form-group">
              <label for="warung" class="col-md-2 control-label ">Warung</label>
              <div class="col-md-4">
                <selectize-component v-model="userWarung.id_warung" :settings="placeholderWarung" id="warung"> 
                  <option v-for="data_warung, index in warung" v-bind:value="data_warung.id" >{{ data_warung.name }}</option>
                </selectize-component>
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-4 col-md-offset-2">
                <button class="btn btn-primary" id="btnSimpanuserWarung" type="submit"><i class="material-icons">send</i> Submit</button>
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
      let id = app.$route.params.id;
      app.userWarungId = id;
      app.pilih_kelurahan();
      app.pilih_warung();
      axios.get(app.url+'/' + id)
      .then(function (resp) {
        app.userWarung = resp.data;
      })
      .catch(function () {
        alert("Tidak Bisa Memuat User Warung!")
      });
    },
    data: function () {
      return {
        warung: [],
        kelurahan: [],
        userWarungId: null,
        userWarung: {
          name: '',
          no_telp: '',
          email: '',
          alamat: '',
          kelurahan: '',
          id_warung: '',
        },
        message : '',
        placeholderKelurahan: {
          placeholder: '--PILIH KELURAHAN--'
        }, 
        placeholderWarung: {
          placeholder: '--PILIH WARUNG--'
        }, 
        url : window.location.origin+(window.location.pathname).replace("dashboard", "user-warung"),
        errors: []
      }
    },
    methods: {
      saveForm() {
        var app = this;
        var newUserWarung = app.userWarung;
        axios.patch(app.url+'/' + app.userWarungId, newUserWarung)
        .then(function (resp) {
          app.message = 'Berhasil Mengubah User Warung '+ app.userWarung.name;
          app.alert(app.message);
          app.$router.replace('/user-warung/');
        })
        .catch(function (resp) {
          app.errors = resp.response.data.errors;
          alert("Periksa Kembali Data Yang Anda Masukan");
        });
      },
      pilih_kelurahan() {
        var app = this;
        axios.get(app.url+'/pilih-kelurahan')
        .then(function (resp) {
          app.kelurahan = resp.data;
        })
        .catch(function (resp) {
          alert("Tidak Bisa Memuat Komunitas!");
        });
      },
      pilih_warung() {
        var app = this;
        axios.get(app.url+'/pilih-warung')
        .then(function (resp) {
          app.warung = resp.data;
        })
        .catch(function (resp) {
          alert("Tidak Bisa Memuat Komunitas!");
        });
      },
      alert(pesan) {
        this.$swal({
          title: "Berhasil !",
          text: pesan,
          icon: "success",
        });
      }
    }
  }
</script>
</script>