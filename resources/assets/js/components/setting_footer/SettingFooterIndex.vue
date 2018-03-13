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
                <li class="active">Edit Setting Footer</li>
            </ul>
            <div class="card">

                <div class="card-header card-header-icon" data-background-color="purple">
                    <i class="material-icons">settings_applications</i>
                </div>

                <div class="card-content">
                    <h4 class="card-title">Edit Setting Footer </h4>
                    <form v-on:submit.prevent="saveForm()" class="form-horizontal">
                        <div class="row" style="margin-left: 2%;">
                            <div class="form-group">
                                <label for="judul_warung" class="col-md-2 control-label label-font-style">Header Warung</label>
                                <div class="col-md-8">
                                    <div class="col-md-10">
                                        <input type="text" v-model="setting_footer.judul_warung" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.judul_warung" name="judul_warung">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="support_link" class="col-md-2 control-label label-font-style">Support link</label>
                                <div class="col-md-8">
                                    <div class="col-md-10">
                                        <input type="text" v-model="setting_footer.support_link" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.support_link" name="support_link">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="about_link" class="col-md-2 control-label label-font-style">About link</label>
                                <div class="col-md-8">
                                    <div class="col-md-10">
                                        <input type="text" v-model="setting_footer.about_link" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.about_link" name="about_link">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="about_us" class="col-md-2 control-label label-font-style">About Us</label>
                                <div class="col-md-8">
                                    <div class="col-md-10">
                                        <textarea class="form-control input-font-style" v-model="setting_footer.about_us" name="about_us"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="contact_us" class="col-md-2 control-label label-font-style" style="font-weight: bold;">CONTACT US</label>
                                <div class="col-md-10">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="no_telp" class="col-md-2 control-label label-font-style">Nomor Telepon</label>
                                            <div class="col-md-6">
                                                <input type="text" v-model="setting_footer.no_telp" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.contact_us.no_telp" name="no_telp">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat" class="col-md-2 control-label label-font-style">Alamat</label>
                                            <div class="col-md-6">
                                                <input type="text" v-model="setting_footer.alamat" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.contact_us.alamat" name="alamat">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="email" class="col-md-2 control-label label-font-style">Email</label>
                                            <div class="col-md-6">
                                                <input type="email" v-model="setting_footer.email" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.contact_us.email" name="email">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="whatsapp" class="col-md-2 control-label label-font-style">Whatsapp</label>
                                            <div class="col-md-6">
                                                <input type="text" v-model="setting_footer.whatsapp" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.contact_us.whatsapp" name="whatsapp">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="sosmed" class="col-md-2 control-label label-font-style" style="font-weight: bold;">SOCIAL MEDIA</label>
                                <div class="col-md-10">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="facebook" class="col-md-2 control-label label-font-style">Facebook</label>
                                            <div class="col-md-6">
                                                <input type="text" v-model="setting_footer.facebook" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.sosmed.facebook" name="facebook">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="twitter" class="col-md-2 control-label label-font-style">Twitter</label>
                                            <div class="col-md-6">
                                                <input type="text" v-model="setting_footer.twitter" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.sosmed.twitter" name="twitter">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="instagram" class="col-md-2 control-label label-font-style">Instagram</label>
                                            <div class="col-md-6">
                                                <input type="text" v-model="setting_footer.instagram" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.sosmed.instagram" name="instagram">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="google_plus" class="col-md-2 control-label label-font-style">Google plus</label>
                                            <div class="col-md-6">
                                                <input type="text" v-model="setting_footer.google_plus" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.sosmed.google_plus" name="google_plus">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="play_store" class="col-md-2 control-label label-font-style">Google Play Store</label>
                                            <div class="col-md-6">
                                                <input type="text" v-model="setting_footer.play_store" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.sosmed.play_store" name="play_store">
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
            this.getDataSettingFooter();
            this.getDefaultData();
        },
        data: function () {
            return {
                id_warung: '',
                errors: [],
                url : window.location.origin+(window.location.pathname).replace("dashboard", "setting-footer"),
                setting_footer: {},
                placeholders: {
                    contact_us: {},
                    sosmed: {}
                },
            }
        },
        methods: {
            saveForm() {
                let app = this;

                axios.patch(app.url + '/' + app.setting_footer.id, app.setting_footer)
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
                    alert('Tidak dapat menyimpan perubahan.');
                })
            },
            getDefaultData() {
                let app = this;

                axios.get(app.url + '/default-data-setting-footer')
                .then(function (resp) {
                    let data = resp.data;
                    console.log(data.no_telp);

                    app.placeholders.judul_warung           = data.judul_warung;
                    app.placeholders.support_link           = data.support_link;
                    app.placeholders.about_link             = data.about_link;
                    app.placeholders.about_us               = data.about_us;
                    app.placeholders.contact_us.no_telp     = data.no_telp;
                    app.placeholders.contact_us.alamat      = data.alamat;
                    app.placeholders.contact_us.email       = data.email;
                    app.placeholders.contact_us.whatsapp    = data.whatsapp;
                    app.placeholders.sosmed.facebook        = data.facebook;
                    app.placeholders.sosmed.twitter         = data.twitter;
                    app.placeholders.sosmed.instagram       = data.instagram;
                    app.placeholders.sosmed.google_plus     = data.google_plus;
                    app.placeholders.sosmed.play_store     = data.play_store;
                })
                .catch(function (resp) {
                    console.log(resp);
                })
            },
            getDataSettingFooter() {
                let app = this;

                axios.get(app.url)
                .then(function (resp) {
                    console.log(resp);
                    app.setting_footer = resp.data;
                    app.setting_footer.support_link = 'https:' + resp.data.support_link;
                    app.setting_footer.about_link   = 'https:' + resp.data.about_link;
                    app.setting_footer.facebook     = 'https:' + resp.data.facebook;
                    app.setting_footer.twitter      = 'https:' + resp.data.twitter;
                    app.setting_footer.instagram    = 'https:' + resp.data.instagram;
                    app.setting_footer.google_plus  = 'https:' + resp.data.google_plus;
                    app.setting_footer.play_store  = 'https:' + resp.data.play_store;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert('Tidak dapat memuat data setting footer');
                })
            },
        }
    }
</script>