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
.table>thead>tr>th {
    border-bottom-width: 1px;
    font-size: 1em;
    font-weight: 300;
}
.tab-pane .table tbody>tr>td:first-child {
    width: 10%; 
}
.nav-pills:not(.nav-pills-icons)>li>a {
    border-radius: 3px;
    padding-left: 15px
}
</style>
<template>
    <div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                <li class="active">Setting Pixel & Analytics</li>
            </ul>

            <div class="card">
                <div class="card-header card-header-icon" data-background-color="purple">
                    <i class="material-icons">settings_applications</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title">Setting Pixel & Analytics</h4>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td width="10%"><img src="image/google_analytics.png" style="width: 30%"> </td>
                                    <td width="10%">
                                        <a href="#setting-pixel" v-on:click="settingPixel('Google', pixel.google)">{{pixel.google}}</a>
                                    </td> 
                                </tr>
                                <tr>
                                    <td width="10%"><img src="image/facebook_pixel.png" style="width: 30%"> </td>
                                    <td width="10%">
                                        <a href="#setting-pixel" v-on:click="settingPixel('Facebook', pixel.facebook)">{{pixel.facebook}}</a>
                                    </td> 
                                </tr>
                            </tbody>                                
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    mounted() {
        var app = this;
        app.getDataSettingPixel();
    },
    data: function () {
        return {
            pixel : {
                google: '',
                facebook: ''
            },
            url : window.location.origin + (window.location.pathname).replace("dashboard", "setting-pixel")
        }
    },
    methods: {
        getDataSettingPixel() {
            let app = this;
            
            axios.get(app.url + '/view')
            .then((resp) => {
                if (resp.data.google == 0)
                    app.pixel.google = "";
                else
                    app.pixel.google = resp.data.google;

                if (resp.data.facebook == 0)
                    app.pixel.facebook = "";
                else
                    app.pixel.facebook = resp.data.facebook;
            })
            .catch((resp) => {
                console.log('catch getDataSettingPixel:', resp);
                swal("Terjadi kesalahan", "Tidak dapat memuat data setting pixel", "warning");
            });
        },
        settingPixel(arg, value) {
            let app = this;

            swal({
                title: 'Pixel ' + arg,
                html:
                '<input id="key" placeholder="Pixel ' + arg + '" class="swal2-input">',
                focusConfirm: false,
                showLoaderOnConfirm: false,
                preConfirm: () => {
                    return new Promise((resolve, reject) => {
                        let key = $('#key');

                        setTimeout(() => {
                            if (key.val() == '') {
                                swal.showValidationError('Masukkan ID ' + arg + ' Pixel Anda!');
                                key.focus();
                                reject();
                            }
                            resolve(key);
                        }, 5);
                    });
                }
            })
            .then((data) => {
                app.pixel.pixel = arg;
                app.pixel.id_pixel = data[0].value;
                let newPixel = app.pixel;

                axios.post(app.url + '/simpan-pixel', newPixel) 
                .then((resp) => {
                    app.getDataSettingPixel();
                    swal({
                        title: 'Berhasil!',
                        type: 'success',
                        text: 'Berhasil Mengubah Setting Pixel',
                        showConfirmButton: false,
                        timer: 1800
                    })
                })
                .catch((resp) => {
                    console.log('catch proses ubah pixel: ', resp);
                    swal("Terjadi kesalahan", "", "warning");
                }); 
            })
            .catch((resp) => {
                console.log('catch swal input pixel: ', resp);
            });

            key.focus();
            $('#key').val(value);
            var button = $(".swal2-confirm");
            $('.swal2-container').keydown((event) => {

                /*
                | ----------------------------------------------------------------------------
                | Untuk mendeteksi apakah tombol enter ditekan atau tidak, jika ditekan
                | maka trigger event klik untuk tombol konfirmasi pada swal yang muncul
                | karena untuk swal tambah nomor resi kita tidak bisa menekan tombol enter
                | untuk submit form secara bawaan, jadi harus dibuat manual :3
                | ----------------------------------------------------------------------------
                */
                if (event.which == 13) {
                    button.click();
                }
            });
        }
    }
}
</script>
