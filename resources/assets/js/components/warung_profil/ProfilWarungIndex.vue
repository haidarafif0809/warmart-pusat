<style scoped>
.table>tbody>tr>th {
    padding-right: 50px;
    padding-bottom: 8px;
}
</style>
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
                <li class="active" v-if="profil_user_warung.setting_aplikasi.tipe_aplikasi == 0">Profil Warung</li>
                <li class="active" v-else>Profil Toko</li>
            </ul>

            <div class="card">
                <div class="card-header card-header-icon" data-background-color="purple">
                    <i class="material-icons">store</i>
                </div>

                <div class="card-content">

                    <ul class="nav nav-pills nav-pills-rose" role="tablist" style="margin-top:5px;">
                        <li class="active">
                            <a href="#user" id="userTab" role="tab" data-toggle="tab" style="margin-right:10px; ">
                                <span v-if="profil_user_warung.setting_aplikasi.tipe_aplikasi == 0">
                                    Profil User Warung
                                </span>
                                <span v-else>
                                    Profil User Toko
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#toko" id="tokoTab" role="tab" data-toggle="tab" style="margin-left:10px;">
                                <span v-if="profil_user_warung.setting_aplikasi.tipe_aplikasi == 0">
                                    Profil Warung
                                </span>
                                <span v-else>
                                    Profil Toko
                                </span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content tab-space" style="margin-top:5px;margin-bottom:5px;">
                        <div class="tab-pane active" id="user"  style="margin-top:5px;margin-bottom:5px;">
                            <div class="card-content">
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
                        <div class="tab-pane" id="toko"  style="margin-top:5px;margin-bottom:5px;">

                            <h4 class="card-title" v-if="warungData.setting_aplikasi == 0">Profil Warung</h4>
                            <table class="table table-striped table-hover ">
                                <tbody v-if="warungs.length > 0 && loading == false" class="data-ada">
                                    <tr v-for="warung, index in warungs" >
                                        <th class="text-primary">Nama</th>
                                            <td>{{ warung.warung.name }}</td>
                                    </tr>
                                    <tr v-for="warung, index in warungs" >              
                                        <th class="text-primary">No. Telpon</th>
                                        <td>{{ warung.warung.no_telpon }}</td>
                                    </tr>
                                    <tr v-for="warung, index in warungs" >              
                                        <th class="text-primary">Email</th>
                                        <td>{{ warung.warung.email }}</td>
                                    </tr>
                                    <tr v-for="warung, index in warungs" >              
                                        <th class="text-primary">Alamat</th>
                                        <td>{{ warung.warung.alamat }}</td>
                                    </tr> 
                                    <tr v-for="warung, index in warungs" >              
                                        <th class="text-primary">Provinsi</th>
                                        <td>{{ warung.provinsi.name }}</td>
                                    </tr> 
                                    <tr v-for="warung, index in warungs" >              
                                        <th class="text-primary">Kabupaten</th>
                                        <td>{{ warung.kabupaten.name }}</td>
                                    </tr> 
                                    <tr v-for="warung, index in warungs" >              
                                        <th class="text-primary">Kecamatan</th>
                                        <td>{{ warung.kecamatan.name }}</td>
                                    </tr> 
                                    <tr v-for="warung, index in warungs" >              
                                        <th class="text-primary">Kelurahan</th>
                                        <td>{{ warung.kelurahan.name }}</td>
                                    </tr>
                                    <tr v-for="warung, index in warungs" >              
                                        <th class="text-primary">Footer Struk</th>
                                        <td>{{ warung.warung.footer_struk }}</td>
                                    </tr>
                                </tbody>
                                <tbody class="data-tidak-ada" v-else-if="warungs.length == 0 && loading == false">
                                    <tr>
                                        <td colspan="9"  class="text-center">Tidak Ada Data</td>
                                    </tr>
                                </tbody>
                            </table>

                            <vue-simple-spinner v-if="loading"></vue-simple-spinner>

                            <div align="right">
                                <p v-for="warung, index in warungs">
                                    <router-link :to="{name: 'editProfilWarung', params: {id:warung.warung.id}}" class="btn btn-xs btn-rose btn-round" v-bind:id="'edit-'+warung.warung.id" >
                                        Edit 
                                    </router-link>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- penutup  tab-content tab-space-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: function () {
        return {
            warungs: [],
            warungData : {},
            profil_user_warung: {
                id : '',
                nama : '',
                no_telp : '',
                email : '',
                alamat : '',
                foto_ktp :'',
                konfirmasi_admin :'',
            },
            message : '',
            url : window.location.origin + (window.location.pathname).replace("dashboard", "profil-warung"),
            url_user : window.location.origin + (window.location.pathname).replace("dashboard", "ubah-profil-user-warung"),
            url_picture : window.location.origin + (window.location.pathname).replace("dashboard", "foto_ktp_user"),
            url_origin : window.location.origin + (window.location.pathname).replace("dashboard", ""),
            pencarian: '',
            errors: [],
            profil_user_warungId : null,
            loading: true
        }
    },
    mounted() {
        var app = this;
        app.getResults();
        let id = app.$route.params.id;
        app.profil_user_warungId = id;

        if (app.$route.params.tab != undefined) {
            let tab = '#' + app.$route.params.tab;
            setTimeout(() => {
                $(tab).click();
            }, 1000);
        }

        axios.get(app.url_user)
        .then(function (resp) {
            app.profil_user_warung = resp.data;
        })
        .catch(function () {
            swal({
                title: 'Gagal!',
                type: 'warning',
                showConfirmButton: false,
                text: 'Gagal Mengedit Profil User Toko.',
                timer: 1800
            });
        });
    },
    watch: {
        // whenever question changes, this function will run
        pencarian: function (newQuestion) {
            this.getHasilPencarian()  
        }
    },

    methods: {
        saveForm() {
            var app = this;
            var newProfilUserWarung = app.inputData();

            swal({
                title: "Sedang Memproses Data ...",
                text: "Harap Tunggu!",
                type: "info",
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            })
            .then((resp) => {
                swal({
                    title: 'Berhasil!',
                    type: 'success',
                    showConfirmButton: false,
                    text: 'Berhasil Mengedit Bank Transfer.',
                })
                .then(() => {
                    app.$router.replace('/');
                })
                .catch((resp) => {
                    console.log('catch redirect ke dashboard: ', resp);
                });

                setTimeout(() => {
                    swal.clickConfirm();
                }, 1800);
            })
            .catch((resp) => {
                console.log('catch swal "Sedang Memproses data":', resp);
            });

            axios.post(app.url_user + '/' + app.profil_user_warung.id, newProfilUserWarung)
            .then((resp) => {
                app.kosongkanData();
                swal.clickConfirm();
            })
            .catch((resp) => {
                console.log('catch edit user toko: ', resp);
                app.errors = resp.response.data.errors;
                swal.close();
                setTimeout(() => {
                    swal({
                        title: 'Gagal!',
                        type: 'warning',
                        showConfirmButton: false,
                        text: 'Gagal Mengubah Data.',
                        timer: 1800
                    });
                }, 300);
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
            app.profil_user_warung.email = '';
            app.profil_user_warung.alamat = '';
            app.errors = '';
        },
        getResults(page) {
            var app = this;
            if (typeof page === 'undefined') {
                page = 1;
            }
            axios.get(app.url + '/view?page=' + page)
            .then((resp) => {
                console.log(resp);
                app.warungs = resp.data.data;
                app.warungData = resp.data;
                app.loading = false;
            })
            .catch((resp) => {
                console.log(resp);
                app.loading = false;
                swal({
                    title: 'Gagal!',
                    type: 'warning',
                    showConfirmButton: false,
                    text: 'Tidak dapat memuat warung.',
                    timer: 1800
                });
            });
        }
    }
}
</script>
