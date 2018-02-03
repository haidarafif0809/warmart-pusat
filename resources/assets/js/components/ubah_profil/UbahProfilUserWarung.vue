<template>
	
	<div class="row">
		<div class="col-md-12">
      <div class="alert alert-warning" v-if="profil_user_warung.foto_ktp == null && profil_user_warung.konfirmasi_admin == 0 && profil_user_warung.setting_aplikasi.tipe_aplikasi == 0">
       <div class="alert-icon">
        <i class="material-icons">info_outline</i>
      </div>
      <b>Info : Silakan lengkapi profil untuk mengakses fitur warung.</b>
    </div>
    <div class="alert alert-info" v-else-if="profil_user_warung.foto_ktp != null && profil_user_warung.konfirmasi_admin == 0">
     <div class="alert-icon">
      <i class="material-icons">info_outline</i>
    </div>
    <b>Info : Pendaftaran anda sebagai warung sedang menunggu verifikasi dari admin.</b>
  </div>

  <ul class="breadcrumb">
    <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
    <li class="active">Ubah Profil</li>
  </ul>

  <div class="card">
    <div class="card-header card-header-icon" data-background-color="purple">
     <i class="material-icons">account_circle</i>
   </div>
   <div class="card-content">
     <h4 class="card-title"> Ubah Profil </h4>
     <div class="toolbar">

      <form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
       <div class="form-group">
        <label for="nama" class="col-md-2 control-label">Nama</label>
        <div class="col-md-4">
         <input class="form-control" autocomplete="off" placeholder="Nama" v-model="profil_user_warung.nama" type="text" name="nama" id="nama"  autofocus="">
         <span v-if="errors.nama" id="nama_error" class="label label-danger">{{ errors.nama[0] }}</span>
       </div>
     </div>
     <div class="form-group">
      <label for="no_telp" class="col-md-2 control-label">No. Telp</label>
      <div class="col-md-4">
       <input class="form-control" autocomplete="off" placeholder="No. Telp" v-model="profil_user_warung.no_telp" type="number" name="no_telp" id="no_telp"  autofocus="">
       <span v-if="errors.no_telp" id="no_telp_error" class="label label-danger">{{ errors.no_telp[0] }}</span>
     </div>
   </div>
   <div class="form-group">
    <label for="email" class="col-md-2 control-label">Email</label>
    <div class="col-md-4">
     <input class="form-control" autocomplete="off" placeholder="Email" v-model="profil_user_warung.email" type="email" name="email" id="email"  autofocus="">
     <span v-if="errors.email" id="email_error" class="label label-danger">{{ errors.email[0] }}</span>
   </div>
 </div>
 <div class="form-group">
  <label for="alamat" class="col-md-2 control-label">Alamat</label>
  <div class="col-md-4">
   <input class="form-control" autocomplete="off" placeholder="Alamat" v-model="profil_user_warung.alamat" type="text" name="alamat" id="alamat"  autofocus="">
   <span v-if="errors.alamat" id="alamat_error" class="label label-danger">{{ errors.alamat[0] }}</span>
 </div>
</div>		
<div class="form-group">
  <label for="foto_ktp" class="col-md-2 control-label" v-if="profil_user_warung.setting_aplikasi.tipe_aplikasi == 0">Foto Ktp </label>
  <label for="foto_ktp" class="col-md-2 control-label" v-else>Logo Toko Anda</label>
  <div class="col-md-4">
    <div class="fileinput fileinput-new text-center" data-provides="fileinput">
      <div class="fileinput-new thumbnail">
        <img v-if="profil_user_warung.foto_ktp != null" :src="url_picture+'/'+profil_user_warung.foto_ktp" /> 
        <img :src="url_origin+'/assets/img/image_placeholder.jpg'" alt="Foto Ktp Akan Tampil Disini" v-else>
      </div>
      <div class="fileinput-preview fileinput-exists thumbnail"></div>
      <div>
        <span class="btn btn-rose btn-round btn-file">
          <span class="fileinput-new" v-if="profil_user_warung.setting_aplikasi.tipe_aplikasi == 0">Ambil Foto Ktp</span>
          <span class="fileinput-new" v-else>Ambil Logo Toko Anda</span>
          <span class="fileinput-exists">Ubah</span>
          <input class="form-control" type="file" name="foto_ktp" id="foto_ktp">
        </span>
        <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Batal</a>
      </div>
      <span v-if="errors.foto_ktp" id="foto_ktp_error" class="label label-danger">{{ errors.foto_ktp[0] }}</span>
      <a v-if="profil_user_warung.setting_aplikasi.tipe_aplikasi == 0" style="color: red;">Size Foto (Ukuran Max : 3MB) </a>
      <a v-else style="color: red;">Size Foto (Ukuran Max : 3MB) </a>
    </div>
  </div>
</div> 

<input class="form-control" autocomplete="off" v-model="profil_user_warung.id" type="hidden" name="id" id="id"  autofocus="">
<div class="form-group">
  <div class="col-md-4 col-md-offset-2">
   <button class="btn btn-primary" id="btnUbahProfil" type="submit"><i class="material-icons">send</i> Submit</button>
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
    app.profil_user_warungId = id;

    axios.get(app.url)
    .then(function (resp) {
      app.profil_user_warung = resp.data;
    })
    .catch(function () {
      alert("Tidak Dapat Memuat Profil User Warung")
    });
  },
  data: function () {
    return {
      profil_user_warung: {
        id : '',
        nama : '',
        no_telp : '',
        email : '',
        alamat : '',
        foto_ktp :'',
        konfirmasi_admin :''
      },
      message : '',
      url : window.location.origin+(window.location.pathname).replace("dashboard", "ubah-profil-user-warung"),
      url_picture : window.location.origin+(window.location.pathname).replace("dashboard", "foto_ktp_user"),
      url_origin : window.location.origin+(window.location.pathname).replace("dashboard", ""),
      errors: [],
      profil_user_warungId : null,
    }
  },
  methods: {        
    saveForm() {
      var app = this;
      var newProfilUserWarung = app.inputData();

      app.loading();
      axios.post(app.url+'/'+app.profil_user_warung.id,newProfilUserWarung)
      .then(function (resp) {
        app.message = 'Berhasil Mengubah Profil  '+app.profil_user_warung.nama;
        app.alert(app.message);
        window.location.replace(window.location.origin+(window.location.pathname))
        app.kosongkanData();
        app.$swal.close();
      })
      .catch(function (resp) {
        app.errors = resp.response.data.errors;
        app.$swal.close();
      });
    },
    alert(pesan) {
      this.$swal({
        title: "Berhasil !",
        text: pesan,
        icon: "success",
      });
    },
    loading(){
      this.$swal({
        title: "Sedang Memproses Data ...",
        text: "Harap Tunggu!",
        icon: "info",
        buttons:  false,
        closeOnClickOutside: false,
        closeOnEsc: false

      });
    },
    inputData(){
      var app = this;

      let newProfilUserWarung = new FormData();
      if (document.getElementById('foto_ktp').files[0] != undefined) {
        newProfilUserWarung.append('foto_ktp', document.getElementById('foto_ktp').files[0]);
      }
      newProfilUserWarung.append('id', app.profil_user_warung.id);       
      newProfilUserWarung.append('name', app.profil_user_warung.nama);
      newProfilUserWarung.append('no_telp', app.profil_user_warung.no_telp);
      newProfilUserWarung.append('email', app.profil_user_warung.email);
      newProfilUserWarung.append('alamat', app.profil_user_warung.alamat);

      return newProfilUserWarung;
    },
    kosongkanData(){
      var app = this;
      app.profil_user_warung.foto_ktp = '';
      app.profil_user_warung.nama = '';
      app.profil_user_warung.no_telp = '';
      aap.profil_user_warung.email = '';
      app.profil_user_warung.alamat = '';
      app.errors = '';
    }
  }
}
</script>