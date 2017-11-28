<template>
    <div class="row" >
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                <li><router-link :to="{name: 'indexProfilWarung'}">Profil Warung</router-link></li>
                <li class="active">Edit Profil Warung</li>
            </ul>
            <div class="card">
               <div class="card-header card-header-icon" data-background-color="purple">
                   <i class="material-icons">store</i>
               </div>
               <div class="card-content">
                 <h4 class="card-title"> Edit Profil Warung </h4>
                 <div>
                    <form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
                        <div class="form-group">
                            <label for="name" class="col-md-2 control-label">Nama Warung</label>
                            <div class="col-md-4">
                                <input class="form-control" required autocomplete="off" placeholder="Nama Warung" type="text" v-model="profilWarung.name" name="name"  autofocus="">
                                <span v-if="errors.name" id="name_error" class="label label-danger">{{ errors.name[0] }}</span>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="no_telpon" class="col-md-2 control-label">No. Telpon</label>
                            <div class="col-md-4">
                                <input class="form-control" required autocomplete="off" placeholder="No. Telpon" type="text" v-model="profilWarung.no_telpon" name="no_telpon"  autofocus="">
                                <span v-if="errors.no_telpon" id="no_telpon_error" class="label label-danger">{{ errors.no_telpon[0] }}</span>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-md-2 control-label">Email</label>
                            <div class="col-md-4">
                                <input class="form-control" autocomplete="off" placeholder="Email" type="email" v-model="profilWarung.email" name="email"  autofocus="">
                                <span v-if="errors.email" id="email_error" class="label label-danger">{{ errors.email[0] }}</span>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nama_bank" class="col-md-2 control-label">Nama Bank</label>
                            <div class="col-md-4">
                                <input class="form-control" required autocomplete="off" placeholder="Nama Bank" type="text" v-model="profilWarung.nama_bank" name="nama_bank"  autofocus="">
                                <span v-if="errors.nama_bank" id="nama_bank_error" class="label label-danger">{{ errors.nama_bank[0] }}</span>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="atas_nama" class="col-md-2 control-label">Atas Nama</label>
                            <div class="col-md-4">
                                <input class="form-control" required autocomplete="off" placeholder="Atas Nama" type="text" v-model="profilWarung.atas_nama" name="atas_nama"  autofocus="">
                                <span v-if="errors.atas_nama" id="atas_nama_error" class="label label-danger">{{ errors.atas_nama[0] }}</span>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="no_rek" class="col-md-2 control-label">No Rek</label>
                            <div class="col-md-4">
                                <input class="form-control" required autocomplete="off" placeholder="No Rek" type="text" v-model="profilWarung.no_rek" name="no_rek"  autofocus="">
                                <span v-if="errors.no_rek" id="no_rek_error" class="label label-danger">{{ errors.no_rek[0] }}</span>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="alamat" class="col-md-2 control-label">Alamat</label>
                            <div class="col-md-4">
                                <input class="form-control" required autocomplete="off" placeholder="Alamat" type="text" v-model="profilWarung.alamat" name="alamat"  autofocus="">
                                <span v-if="errors.alamat" id="alamat_error" class="label label-danger">{{ errors.alamat[0] }}</span>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="provinsi" class="col-md-2 control-label">Provinsi</label>
                            <div class="col-md-4">
                                <selectize-component v-model="profilWarung.provinsi" :settings="placeholder_provinsi" id="pilih_provinsi"> 
                                    <option v-for="provinsis, index in provinsi" v-bind:value="provinsis.id" >{{ provinsis.name }}</option>
                                </selectize-component>
                                <span v-if="errors.provinsi" id="provinsi_error" class="label label-danger">{{ errors.provinsi[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kabupaten" class="col-md-2 control-label">Kabupaten</label>
                            <div class="col-md-4">
                                <selectize-component v-model="profilWarung.kabupaten" :settings="placeholder_kabupaten" id="pilih_kabupaten"> 
                                    <option v-for="kabupatens, index in kabupaten" v-bind:value="kabupatens.id" >{{ kabupatens.name }}</option>
                                </selectize-component>
                                <span v-if="errors.kabupaten" id="kabupaten_error" class="label label-danger">{{ errors.kabupaten[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kecamatan" class="col-md-2 control-label">Kecamatan</label>
                            <div class="col-md-4">
                                <selectize-component v-model="profilWarung.kecamatan" :settings="placeholder_kecamatan" id="pilih_kecamatan"> 
                                    <option v-for="kecamatans, index in kecamatan" v-bind:value="kecamatans.id" >{{ kecamatans.name }}</option>
                                </selectize-component>
                                <span v-if="errors.kecamatan" id="kecamatan_error" class="label label-danger">{{ errors.kecamatan[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kelurahan" class="col-md-2 control-label">Kelurahan</label>
                            <div class="col-md-4">
                                <selectize-component v-model="profilWarung.kelurahan" :settings="placeholder_kelurahan" id="pilih_kelurahan"> 
                                    <option v-for="kelurahans, index in kelurahan" v-bind:value="kelurahans.id" >{{ kelurahans.name }}</option>
                                </selectize-component>
                                <span v-if="errors.kelurahan" id="kelurahan_error" class="label label-danger">{{ errors.kelurahan[0] }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4 col-md-offset-2">
                                <button class="btn btn-primary" id="btnSimpan" type="submit"><i class="material-icons">send</i> Submit</button>
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

        app.profilWarungId = id;
        app.selected_provinsi();

        axios.get(app.url+'/' + id)
        .then(function (resp) {
            app.profilWarung = resp.data;
        })
        .catch(function () {
            alert("Tidak bisa memuat warung")
        });
    },
    data: function () {
        return {
            provinsi: [],
            kabupaten: [],
            kecamatan: [],
            kelurahan: [],
            profilWarungId: null,
            profilWarung: {
                name  : '',
                no_telpon : '',
                email : '',
                nama_bank : '',
                atas_nama : '',
                no_rek : '',
                alamat : '',
                provinsi : '',
                kabupaten : '',
                kecamatan : '',
                kelurahan : '',
            },
            message : '',
            placeholder_provinsi: {
                placeholder: '--PILIH PROVINSI--'
            },
            placeholder_kabupaten: {
                placeholder: '--PILIH KABUPATEN--'
            },
            placeholder_kecamatan: {
                placeholder: '--PILIH KECAMATAN--'
            }, 
            placeholder_kelurahan: {
                placeholder: '--PILIH KELURAHAN--'
            }, 
            url : window.location.origin+(window.location.pathname).replace("dashboard", "profil-warung"),
            errors: []
        }
    },
    methods: {
        saveForm() {
            var app = this;
            var newWarung = app.profilWarung;
            axios.patch(app.url+'/' + app.profilWarungId, newWarung)
            .then(function (resp) {
                app.message = 'Sukses : Berhasil Mengubah Warung';
                app.alert(app.message);
                app.$router.replace('/profil-warung/');
            })
            .catch(function (resp) {
                console.log(resp);
                app.errors = resp.response.data.errors;
                alert("Periksa kembali data yang anda masukan");
            });
        },
        selected_provinsi() {
          var app = this;
          axios.get(app.url+'/pilih-provinsi')
          .then(function (resp) {
            app.provinsi = resp.data;

        })
          .catch(function (resp) {
            alert("Tidak bisa memuat provinsi ");
        });
      },
        selected_kabupaten(provinsi_id) {
          var app = this;
          var type = "kabupaten";
          axios.get(app.url+'/pilih-wilayah/'+provinsi_id+'/'+type)
          .then(function (resp) {
            app.kabupaten = resp.data;
        })
          .catch(function (resp) {
            alert("Tidak bisa memuat kabupaten ");
        });
      },
        selected_kecamatan(kabupaten_id) {
          var app = this;
          var type = "kecamatan";
          axios.get(app.url+'/pilih-wilayah/'+kabupaten_id+'/'+type)
          .then(function (resp) {
            app.kecamatan = resp.data;
        })
          .catch(function (resp) {
            alert("Tidak bisa memuat kecamatan ");
        });
      },
        selected_kelurahan(kecamatan_id) {
          var app = this;
          var type = "kelurahan";
          axios.get(app.url+'/pilih-wilayah/'+kecamatan_id+'/'+type)
          .then(function (resp) {
            app.kelurahan = resp.data;
            console.log(resp.data)
        })
          .catch(function (resp) {
            alert("Tidak bisa memuat kelurahan ");
        });
      }
      ,alert(pesan) {
          this.$swal({
              title: "Berhasil !",
              text: pesan,
              icon: "success",
          });
      }
  },
  watch:{
     'profilWarung.provinsi': function (newVal, oldVal){
         if (newVal != "") {
            this.selected_kabupaten(newVal);
         }
     },
     'profilWarung.kabupaten': function (newVal, oldVal){
         if (newVal != "") {
            this.selected_kecamatan(newVal);
         }
     },
     'profilWarung.kecamatan': function (newVal, oldVal){
         if (newVal != "") {
            this.selected_kelurahan(newVal);
         }
     }
 }
}
</script>
