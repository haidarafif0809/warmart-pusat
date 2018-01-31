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
                <li><router-link :to="{name: 'indexProduk'}">Setting Footer</router-link></li>
                <li class="active">Edit Setting Footer</li>
            </ul>
            <div class="card">

                <div class="card-header card-header-icon" data-background-color="purple">
                    <i class="material-icons">dns</i>
                </div>

                <div class="card-content">
                    <h4 class="card-title">Edit Setting Footer </h4>
                    <form v-on:submit.prevent="saveForm()" class="form-horizontal">
                        <div class="row" style="margin-left: 2%;">
                            <div class="form-group">
                                <label for="header_warung" class="col-md-2 control-label label-font-style">Header Warung</label>
                                <div class="col-md-8">
                                    <div class="col-md-10">
                                        <input type="text" v-model="setting_footer.header_warung" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.header_warung" name="header_warung">
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
                                                <input type="number" v-model="setting_footer.contact_us.no_telp" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.contact_us.no_telp" name="no_telp">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat" class="col-md-2 control-label label-font-style">Alamat</label>
                                            <div class="col-md-6">
                                                <input type="text" v-model="setting_footer.contact_us.alamat" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.contact_us.alamat" name="alamat">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="email" class="col-md-2 control-label label-font-style">Email</label>
                                            <div class="col-md-6">
                                                <input type="email" v-model="setting_footer.contact_us.email" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.contact_us.email" name="email">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="whatsapp" class="col-md-2 control-label label-font-style">Whatsapp</label>
                                            <div class="col-md-6">
                                                <input type="text" v-model="setting_footer.contact_us.whatsapp" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.contact_us.whatsapp" name="whatsapp">
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
                                                <input type="text" v-model="setting_footer.sosmed.facebook" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.sosmed.facebook" name="facebook">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="twitter" class="col-md-2 control-label label-font-style">Twitter</label>
                                            <div class="col-md-6">
                                                <input type="text" v-model="setting_footer.sosmed.twitter" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.sosmed.twitter" name="twitter">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="instagram" class="col-md-2 control-label label-font-style">Instagram</label>
                                            <div class="col-md-6">
                                                <input type="text" v-model="setting_footer.sosmed.instagram" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.sosmed.instagram" name="instagram">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="google_plus" class="col-md-2 control-label label-font-style">Google plus</label>
                                            <div class="col-md-6">
                                                <input type="text" v-model="setting_footer.sosmed.google_plus" class="form-control input-font-style" autocomplete="off" :placeholder="placeholders.sosmed.google_plus" name="google_plus">
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

    },
    data: function () {
        return {
            warung_id: '',
            errors: [],
            url : window.location.origin+(window.location.pathname).replace("dashboard", "setting-footer"),
            setting_footer: {
                header_warung: '',
                support_link: '',
                about_link: '',
                about_us: '',
                contact_us: {
                    no_telp: '',
                    alamat: '',
                    email: '',
                    whatsapp: ''
                },
                sosmed: {
                    facebook: '',
                    twitter: '',
                    instagram: '',
                    google_plus: ''
                },
            },
            placeholders: {
                header_warung: 'Nama Warung Anda',
                support_link: 'https://andaglos.id/support/',
                about_link: 'https://andaglos.id/topos/',
                about_us: 'Tentang kami...',
                contact_us: {
                    no_telp: '07218050299',
                    alamat: 'Jl. Pramuka, Sentra Bisnis Terminal Kemiling Blok R3 No.7 Bandar Lampung',
                    email: 'solusibisnis@andaglos.id',
                    whatsapp: '0811728549'
                },
                sosmed: {
                    facebook: 'https://facebook.com/andaglos',
                    twitter: 'https://www.twitter.com/andaglos',
                    instagram: 'https://www.instagram.com/andaglos',
                    google_plus: 'https://plus.google.com/u/0/102529791461131425545'
                },
            }
        }
    },
    methods: {
        saveFrom() {

        }
    }
}
</script>