<template>
    <div class="row" >
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><a href=" ">Home</a></li>
                <li><router-link :to="{name: 'indexWarung'}">Warung</router-link></li>
                <li class="active">Edit Warung</li>
            </ul>
            <div class="card">
               <div class="card-header card-header-icon" data-background-color="purple">
                   <i class="material-icons">dns</i>
               </div>
               <div class="card-content">
                 <h4 class="card-title"> Warung </h4>
                 <div>
                    <form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
                        <div class="form-group">
                            <label for="name" class="col-md-2 control-label">Nama Warung</label>
                            <div class="col-md-4">
                                <input class="form-control" required autocomplete="off" placeholder="Nama Warung" type="text" v-model="warung.name" name="name"  autofocus="">
                                <span v-if="errors.name" id="name_error" class="label label-danger">{{ errors.name[0] }}</span>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="no_telpon" class="col-md-2 control-label">No. Telpon</label>
                            <div class="col-md-4">
                                <input class="form-control" required autocomplete="off" placeholder="No. Telpon" type="text" v-model="warung.no_telpon" name="no_telpon"  autofocus="">
                                <span v-if="errors.no_telpon" id="no_telpon_error" class="label label-danger">{{ errors.no_telpon[0] }}</span>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-md-2 control-label">Email</label>
                            <div class="col-md-4">
                                <input class="form-control" required autocomplete="off" placeholder="Email" type="email" v-model="warung.email" name="email"  autofocus="">
                                <span v-if="errors.email" id="email_error" class="label label-danger">{{ errors.email[0] }}</span>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nama_bank" class="col-md-2 control-label">Nama Bank</label>
                            <div class="col-md-4">
                                <input class="form-control" required autocomplete="off" placeholder="Nama Bank" type="text" v-model="warung.nama_bank" name="nama_bank"  autofocus="">
                                <span v-if="errors.nama_bank" id="nama_bank_error" class="label label-danger">{{ errors.nama_bank[0] }}</span>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="atas_nama" class="col-md-2 control-label">Atas Nama</label>
                            <div class="col-md-4">
                                <input class="form-control" required autocomplete="off" placeholder="Atas Nama" type="text" v-model="warung.atas_nama" name="atas_nama"  autofocus="">
                                <span v-if="errors.atas_nama" id="atas_nama_error" class="label label-danger">{{ errors.atas_nama[0] }}</span>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="no_rek" class="col-md-2 control-label">No Rek</label>
                            <div class="col-md-4">
                                <input class="form-control" required autocomplete="off" placeholder="No Rek" type="text" v-model="warung.no_rek" name="no_rek"  autofocus="">
                                <span v-if="errors.no_rek" id="no_rek_error" class="label label-danger">{{ errors.no_rek[0] }}</span>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="alamat" class="col-md-2 control-label">Alamat</label>
                            <div class="col-md-4">
                                <input class="form-control" required autocomplete="off" placeholder="Alamat" type="text" v-model="warung.alamat" name="alamat"  autofocus="">
                                <span v-if="errors.alamat" id="alamat_error" class="label label-danger">{{ errors.alamat[0] }}</span>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kelurahan" class="col-md-2 control-label ">Kelurahan</label>
                            <div class="col-md-4">
                                <selectize-component v-model="warung.kelurahan" :settings="settings"> 
                                    <option v-for="kelurahans, index in kelurahan" v-bind:value="kelurahans.id" >{{ kelurahans.nama }}</option>
                                </selectize-component>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4 col-md-offset-2">
                                <button class="btn btn-primary" id="btnSimpanWarung" type="submit"><i class="material-icons">send</i> Submit</button>
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
        app.warungId = id;
        app.selected();

        axios.get(app.url+'/' + id)
        .then(function (resp) {
            app.warung = resp.data;
        })
        .catch(function () {
            alert("Tidak bisa memuat warung")
        });
    },
    data: function () {
        return {
            warungId: null,
            warung: {
                name  : '',
                no_telpon : '',
                email : '',
                nama_bank : '',
                atas_nama : '',
                no_rek : '',
                alamat : '',
                kelurahan : '',
            },
            message : '',
            settings: {
                value: app.warung
            }, 
            url : window.location.origin+(window.location.pathname).replace("dashboard", "warung"),
            errors: []
        }
    },
    methods: {
        saveForm() {
            var app = this;
            var newWarung = app.warung;
            axios.patch(app.url+'/' + app.warungId, newWarung)
            .then(function (resp) {
                app.message = 'Sukses : Berhasil Mengubah Warung';
                app.alert(app.message);
                app.$router.replace('/warung/');
            })
            .catch(function (resp) {
                console.log(resp);
                app.errors = resp.response.data.errors;
                alert("Periksa kembali data yang anda masukan");
            });
        },
        selected() {
          var app = this;
          axios.get(app.url+'/pilih-kelurahan')
          .then(function (resp) {
            app.kelurahan = resp.data;
        })
          .catch(function (resp) {
            alert("Tidak bisa memuat kelurahan ");
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
