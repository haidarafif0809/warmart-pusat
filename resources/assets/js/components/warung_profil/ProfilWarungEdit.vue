<template>
    <div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb" v-if="profilWarung.setting_aplikasi.tipe_aplikasi == 0">
                <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                <li><router-link :to="{name: 'indexProfilWarung'}">Profil Warung</router-link></li>
                <li class="active" >Edit Profil Warung</li>
            </ul>
            <ul class="breadcrumb" v-else>
                <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                <li><router-link :to="{name: 'indexProfilWarung'}">Profil Toko</router-link></li>
                <li class="active" >Edit Profil Tokoo</li>
            </ul>
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="purple">
                    <i class="material-icons">store</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title" v-if="profilWarung.setting_aplikasi.tipe_aplikasi == 0"> Edit Profil Warung </h4>
                    <h4 class="card-title" v-else> Edit Profil Toko </h4>
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
                                <div class="col-md-4" v-if="loading == false">
                                    <selectize-component v-model="profilWarung.kabupaten" :settings="placeholder_kabupaten" id="pilih_kabupaten"> 
                                        <option v-for="kabupatens, index in kabupaten" v-bind:value="kabupatens.id" >{{ kabupatens.name }}</option>
                                    </selectize-component>
                                    <span v-if="errors.kabupaten" id="kabupaten_error" class="label label-danger">{{ errors.kabupaten[0] }}</span>
                                </div>
                                <vue-simple-spinner v-if="loading"></vue-simple-spinner>
                            </div>
                            <div class="form-group">
                                <label for="kecamatan" class="col-md-2 control-label">Kecamatan</label>
                                <div class="col-md-4" v-if="loadingKecamatan == false">
                                    <selectize-component v-model="profilWarung.kecamatan" :settings="placeholder_kecamatan" id="pilih_kecamatan"> 
                                        <option v-for="kecamatans, index in kecamatan" v-bind:value="kecamatans.id" >{{ kecamatans.name }}</option>
                                    </selectize-component>
                                    <span v-if="errors.kecamatan" id="kecamatan_error" class="label label-danger">{{ errors.kecamatan[0] }}</span>
                                </div>
                                <vue-simple-spinner v-if="loadingKecamatan"></vue-simple-spinner>
                            </div>
                            <div class="form-group">
                                <label for="kelurahan" class="col-md-2 control-label">Kelurahan</label>
                                <div class="col-md-4" v-if="loadingKelurahan == false">
                                    <selectize-component v-model="profilWarung.kelurahan" :settings="placeholder_kelurahan" id="pilih_kelurahan"> 
                                        <option v-for="kelurahans, index in kelurahan" v-bind:value="kelurahans.id" >{{ kelurahans.name }}</option>
                                    </selectize-component>
                                    <span v-if="errors.kelurahan" id="kelurahan_error" class="label label-danger">{{ errors.kelurahan[0] }}</span>
                                </div> 
                                <vue-simple-spinner v-if="loadingKelurahan"></vue-simple-spinner>
                            </div>
                            <div class="form-group">
                                <label for="footer_struk" class="col-md-2 control-label">Footer Struk</label>
                                <div class="col-md-4">
                                    <textarea v-model="profilWarung.footer_struk" class="form-control" placeholder="Footer Struk.." id="footer_struk" name="footer_struk" ref="footer_struk"></textarea>
                                </div> 
                            </div>
                            <div class="form-group">
                                <div class="col-md-4 col-md-offset-2">
                                    <button class="btn btn-primary" id="btnSimpan" type="submit">
                                        <i class="material-icons">send</i> Submit
                                    </button>
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
        app.dataProvinsi();

        axios.get(app.url + '/' + id)
        .then(function (resp) {
            app.profilWarung = resp.data;
            app.loading = false;
            app.loadingKecamatan = false;
            app.loadingKelurahan = false;
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
                alamat : '',
                provinsi : '',
                kabupaten : '',
                kecamatan : '',
                kelurahan : '',
                footer_struk : '',
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
            url : window.location.origin + (window.location.pathname).replace("dashboard", "profil-warung"),
            errors: [],
            loading: true,
            loadingKecamatan: true,
            loadingKelurahan: true
        }
    },
    methods: {
        saveForm() {
            var app = this;
            var newWarung = app.profilWarung;
            axios.patch(app.url + '/' + app.profilWarungId, newWarung)
            .then((resp) => {
                app.message = 'Sukses : Berhasil Mengubah Warung';
                swal({
                    title: 'Berhasil!',
                    type: 'success',
                    showConfirmButton: false,
                    text: 'Sukses Mengubah Warung.',
                })
                .then(() => {
                    app.$router.replace('/profil-warung');

                    // kirim bankTransfer sebagai parameter untuk diproses di halaman index setting pengiriman
                    app.$route.params.tab = 'tokoTab';
                })
                .catch((resp) => {
                    console.log('catch ubah warung: ', resp);
                });

                setTimeout(() => {
                    swal.clickConfirm();
                }, 1800);
            })
            .catch((resp) => {
                console.log(resp);
                app.errors = resp.response.data.errors;
                alert("Periksa kembali data yang anda masukan");
            });
        },
        dataProvinsi() {
            var app = this;
            axios.get(app.url + '/pilih-provinsi')
            .then((resp) => {
                app.provinsi = resp.data;
            })
            .catch((resp) => {
                alert("Tidak bisa memuat provinsi ");
            });
        },
        dataKabupaten(provinsi_id) {
            var app = this;
            var type = "kabupaten";
            app.loading = true;

            axios.get(app.url + '/pilih-wilayah/' + provinsi_id + '/' + type)
            .then((resp) => {
                app.kabupaten = resp.data;            
                app.loading = false;

            })
            .catch((resp) => {
                alert("Tidak bisa memuat kabupaten ");
            });
        },
        dataKecamatan(kabupaten_id) {
            var app = this;
            var type = "kecamatan";
            app.loadingKecamatan = true;

            axios.get(app.url + '/pilih-wilayah/' + kabupaten_id + '/' + type)
            .then((resp) => {
                app.kecamatan = resp.data;
                app.loadingKecamatan = false;            
            })
            .catch((resp) => {
                alert("Tidak bisa memuat kecamatan ");
            });
        },
        dataKelurahan(kecamatan_id) {
            var app = this;
            var type = "kelurahan";
            app.loadingKelurahan = true;

            axios.get(app.url + '/pilih-wilayah/' + kecamatan_id + '/' + type)
            .then((resp) => {
                app.kelurahan = resp.data;
                app.loadingKelurahan = false;

            })
            .catch((resp) => {
                alert("Tidak bisa memuat kelurahan ");
            });
        },
    },
    watch:{
        'profilWarung.provinsi': function (newVal, oldVal){
            if (newVal != "") {
                this.dataKabupaten(newVal);
            }
        },
        'profilWarung.kabupaten': function (newVal, oldVal){
            if (newVal != "") {
                this.dataKecamatan(newVal);
            }
        },
        'profilWarung.kecamatan': function (newVal, oldVal){
            if (newVal != "") {
                this.dataKelurahan(newVal);
            }
        }
    }
}
</script>
