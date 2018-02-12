<style scoped>
.label-font-style {
    font-size: 14px !important;
    color: #3C4858 !important;
    text-align: left;
}
.input-font-style {
    color: #3C4858;
    font-size: 12px;
}
.input-font-style::placeholder {
    color: #DFDFDF;
    font-size: 12px;
}
</style>
<template>
    <div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                <li class="active">Edit Setting Verifikasi</li>
            </ul>
            <div class="card">

                <div class="card-header card-header-icon" data-background-color="purple">
                    <i class="material-icons">dns</i>
                </div>

                <div class="card-content">
                    <h4 class="card-title">Edit Setting Verifikasi </h4>
                    <form v-on:submit.prevent="saveForm()" class="form-horizontal">
                        <div class="row" style="margin-left: 2%;">
                            <div class="form-group">
                                <label for="judul_warung" class="col-md-2 control-label label-font-style">Verifikasi</label>
                                <div class="col-md-8">
                                    <div class="col-md-10">
                                        <!-- <input type="text" v-model="setting_footer.judul_warung" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.judul_warung" name="judul_warung"> -->
                                        <div class="col-md-5">
                                            <div class="checkbox">
                                                <label>
                                                    <input v-model="setting_verifikasi.email" name="email" type="checkbox">
                                                    Email
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="checkbox">
                                                <label>
                                                    <input v-model="setting_verifikasi.no_telp" name="no_telp" type="checkbox">
                                                    Nomor telepon
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-1">
                                    <button class="btn btn-primary" id="btnSimpanSettingFooter" type="submit">
                                        <i class="material-icons">send</i> Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    mounted() {
        this.getDataSettingVerifikasi();
    },
    data: function () {
        return {
            errors: [],
            url : window.location.origin+(window.location.pathname).replace("dashboard", "setting-verifikasi"),
            setting_verifikasi: {},
        }
    },
    methods: {
        saveForm() {
            let app       = this;
            let email     = app.setting_verifikasi.email;
            let no_telp   = app.setting_verifikasi.no_telp;

            if (email == false) email = 0;
            if (no_telp == false) no_telp = 0;
            if (email == false && no_telp == false) {
                email   = 0;
                no_telp = 0;
            }

            if (email == true) email = 1;
            if (no_telp == true) no_telp = 1;
            if (email == true && no_telp == true) {
                email   = 1;
                no_telp = 1;
            }

            console.log(email);
            console.log(no_telp);
            axios.put(app.url + '/' + app.setting_verifikasi.id, app.setting_verifikasi)
            .then(function (resp) {
                console.log(resp);
                
                swal({
                    title: 'Berhasil!',
                    type: 'success',
                    text: 'Berhasil mengubah setting.',
                    timer: 1800,
                    showConfirmButton: false
                });
            })
            .catch(function (resp) {
                console.log(resp);
                alert('tidak dapat menyimpan perubahan.');
            });
        },
        getDataSettingVerifikasi() {
            let app = this;

            axios.get(app.url)
            .then(function (resp) {
                console.log(resp);
                app.setting_verifikasi = resp.data;
            })
            .catch(function (resp) {
                console.log(resp);
                alert('tidak dapat memuat data setting verifikasi');
            })
        }
    }
}
</script>