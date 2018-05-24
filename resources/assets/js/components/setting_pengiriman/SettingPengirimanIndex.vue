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
.selectizeLoading > .selectize-input, .selectizeLoading > .selectize-input > input
{
    cursor: wait !important;
    font-style: italic;
    background:
    url('http://www.hsi.com.hk/HSI-Net/pages/images/en/share/ajax-loader.gif')
    no-repeat
    center center;
}
</style>
<template>
    <div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                <li class="active">Setting Pengiriman</li>
            </ul>
            <div class="card">

                <div class="card-header card-header-icon" data-background-color="purple">
                    <i class="material-icons">settings_applications</i>
                </div>

                <div class="card-content">
                    <h4 class="card-title">Setting Pengiriman</h4>
                    <ul class="nav nav-pills nav-pills-rose" role="tablist">
                        <li class="active">
                            <a href="#jasa_pengiriman" id="jasaPengiriman" role="tab" data-toggle="tab" style="margin-right:10px; ">
                                Jasa Pengiriman
                            </a>
                        </li>
                        <li>
                            <a href="#bank_transfer" id="bankTransfer" role="tab" data-toggle="tab" style="margin-left:10px;">
                                Bank Transfer
                            </a>
                        </li>
                        <li>
                            <a href="#default_alamat_pelanggan" id="defaultAlamatPelanggan" role="tab" data-toggle="tab" style="margin-left:10px;" v-on:click="loadDefaultAlamatPelanggan">
                               Default Alamat Pelanggan
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="jasa_pengiriman">

                            <div class="table-responsive">
                                <button class="btn btn-primary" id="btnSubmit" @click="simpanSetting()" style=""> <i class="material-icons">save</i> Simpan
                                </button>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Jasa Pengiriman</th>
                                            <th class="text-center"></th>
                                            <th class="text-center">Pilih</th>
                                            <th class="text-center">Default</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="settingPengirimans, index in settingPengiriman">
                                            <td width="10%">{{settingPengirimans.setting.jasa_pengiriman.toUpperCase()}}</td>
                                            <td align="center">
                                                <div class="img-container">
                                                    <img :src="url_picture+'/'+settingPengirimans.setting.logo_jasa" style="width: 100%" v-if="settingPengirimans.agent == 0"/> 

                                                    <img :src="url_picture+'/'+settingPengirimans.setting.logo_jasa" style="width: 80px" v-else/> 
                                                </div>
                                            </td>
                                            <td align="center">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" v-model="settingPengirimans.setting.tampil_jasa_pengiriman" true-value="1" false-value="0">
                                                    </label>
                                                </div>
                                            </td>
                                            <td align="center">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="optionsRadios" v-model="settingPengirimans.setting.default_jasa_pengiriman" 
                                                        @click="defaultPengiriman(index)" v-bind:value="1">
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>                                
                                    </tbody>                                
                                </table>
                            </div>

                        </div>

                        <div class="tab-pane" id="bank_transfer">

                            <div class="table-responsive">
                                <router-link class="btn btn-primary" :to="{ name: 'settingPengirimanTambahBank' }"> <i class="material-icons">add</i> Bank Transfer </router-link>
                                <button class="btn btn-primary" id="btnSubmitBank" @click="simpanSettingBank()" style=""> <i class="material-icons">save</i> Simpan
                                </button>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama Bank</th>
                                            <th class="text-center"></th>
                                            <th class="text-center">Tampilkan Bank</th>
                                            <th class="text-center">Default</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="settingBanks, index in settingBank">
                                            <td width="10%">{{settingBanks.setting.nama_bank.toUpperCase()}}</td>
                                            <td align="center">
                                                <div class="img-container">
                                                    <img :src="url_picture+'/'+settingBanks.setting.logo_bank" style="width: 100%" v-if="settingBanks.agent == 0"/> 

                                                    <img :src="url_picture+'/'+settingBanks.setting.logo_bank" style="width: 80px" v-else/> 
                                                </div>
                                            </td>
                                            <td align="center">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" v-model="settingBanks.setting.tampil_bank" true-value="1" false-value="0">
                                                    </label>
                                                </div>
                                            </td>
                                            <td align="center">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="optionsRadiosBank" v-model="settingBanks.setting.default_bank" 
                                                        @click="defaultBank(index)" v-bind:value="1">
                                                    </label>
                                                </div>
                                            </td>
                                            <td align="center">
                                                <router-link class="btn btn-warning btn-xs" :to="{name: 'settingPengirimanEditBank', params: {id: settingBanks.setting.id}}"> Edit </router-link>
                                                <button @click="hapusBankTransfer(settingBanks.setting.id, settingBanks.setting.logo_bank)" class="btn btn-danger btn-xs">Hapus</button>
                                            </td>
                                        </tr>                                
                                    </tbody>                                
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="default_alamat_pelanggan">                                
                            <form v-on:submit.prevent="saveSettingDefaultAlamatPelanggan()" class="form-horizontal">                            

                                <div class="form-group">
                                    <label for="provinsi" class="col-md-2 control-label">Provinsi</label>
                                    <div class="col-md-4">
                                        <selectize-component v-model="settingDefaultAlamatPelanggan.provinsi" :settings="placeholder_provinsi" id="pilih_provinsi" ref='provinsi'> 
                                            <option v-for="provinsis, index in provinsi" v-bind:value="provinsis.id" >{{ provinsis.name }}</option>
                                        </selectize-component>
                                        <br v-if="errors.provinsi">
                                        <span v-if="errors.provinsi" id="provinsi_error" class="label label-danger">{{ errors.provinsi[0] }}</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="kabupaten" class="col-md-2 control-label">Kabupaten</label>
                                    <div class="col-md-4">
                                        <selectize-component v-model="settingDefaultAlamatPelanggan.kabupaten" :settings="placeholder_kabupaten" id="pilih_kabupaten" ref='kabupaten'> 
                                            <option v-for="kabupatens, index in kabupaten" v-bind:value="kabupatens.id" >{{ kabupatens.name }}</option>
                                        </selectize-component>
                                        <br v-if="errors.kabupaten">
                                        <span v-if="errors.kabupaten" id="kabupaten_error" class="label label-danger">{{ errors.kabupaten[0] }}</span>                      
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="status_aktif" class="col-md-2 control-label">Status Aktif</label>
                                    <div class="col-md-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="status_aktif" true-value="1" false-value="0" v-model="settingDefaultAlamatPelanggan.status_aktif">
                                                <b v-if="settingDefaultAlamatPelanggan.status_aktif == 1">Ya</b>
                                                <b v-if="settingDefaultAlamatPelanggan.status_aktif == 0">Tidak</b>
                                            </label>
                                        </div>       
                                        <br v-if="errors.status_aktif">      
                                        <span v-if="errors.status_aktif" id="status_aktif_error" class="label label-danger">{{ errors.status_aktif[0] }}</span>                      
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-4 col-md-offset-2">
                                        <button class="btn btn-primary" id="btnSimpan" type="submit">
                                            <i class="material-icons">save</i> Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
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
        app.getDataSettingPengiriman();
        app.getDataSettingBank();

        console.log(app.$route.params);

        if (app.$route.params.tab != undefined) {
            let tab = '#' + app.$route.params.tab;
            $(tab).click();
        }
    },
    data: function () {
        return {
            errors : [],
            provinsi : [],
            kabupaten : [],
            settingPengiriman : [],
            settingBank : [],
            settingDefaultAlamatPelanggan: {
                provinsi: '',
                kabupaten: '',
                status_aktif: 0
            },
            placeholder_provinsi: {
                placeholder: 'Pilih Provinsi',
                sortField: 'text',                
                
            },
            placeholder_kabupaten: {
                placeholder: 'Pilih Kabupaten',
                sortField: 'text'
                
            },
            url : window.location.origin + (window.location.pathname).replace("dashboard", "setting-pengiriman"),
            url_picture : window.location.origin + (window.location.pathname).replace("dashboard", "jasa_pengiriman"),
            url_wilayah : window.location.origin + (window.location.pathname).replace("dashboard", "profil-warung"),
        }
    },
    watch : {
        'settingDefaultAlamatPelanggan.provinsi':function(){
            if (this.settingDefaultAlamatPelanggan.provinsi != '') {            
                this.loadKabupaten()
            }
        }
    },
    methods: {
        simpanSetting() {
            var app = this; 
            var newSetting = app.settingPengiriman;

            axios.post(app.url + '/simpan-setting-pengiriman', { data: newSetting }) 
            .then((resp) => {
                app.message = 'Berhasil Mengubah Setting Pengiriman';
                app.alert(app.message);                    
                app.getDataSettingPengiriman();
            })
            .catch((resp) => {
                console.log(resp);
                alert("Tidak Dapat Menyimpan Setting Pengiriman");
            });
        },
        getDataSettingPengiriman() {
            let app = this;

            axios.get(app.url + '/view')
            .then((resp) => {
                app.settingPengiriman = resp.data;
            })
            .catch((resp) => {
                console.log(resp);
                alert('Tidak dapat memuat data setting jasa pengiriman');
            })
        },
        defaultPengiriman(index) {
            var app = this;
            $.each(app.settingPengiriman, (i, item) => {
                if (index == i) {
                    app.settingPengiriman[i].setting.default_jasa_pengiriman = 1;
                } else {
                    app.settingPengiriman[i].setting.default_jasa_pengiriman = 0;                        
                }
            });
        },
        simpanSettingBank() {
            var app = this; 
            var newSetting = app.settingBank;

            axios.post(app.url + '/simpan-setting-bank', { data: newSetting })
            .then((resp) => {
                app.message = 'Berhasil Mengubah Setting Transfer Bank';
                app.alert(app.message);                    
                app.getDataSettingBank();
            })
            .catch((resp) => {
                console.log(resp);
                alert("Tidak Dapat Menyimpan Setting Transfer Bank");
            });
        },
        getDataSettingBank() {
            let app = this;

            axios.get(app.url +'/view-bank')
            .then((resp) => {
                app.settingBank = resp.data;
            })
            .catch((resp) => {
                console.log(resp);
                alert('Tidak dapat memuat data setting bank transfer');
            })
        },
        defaultBank(index) {
            var app = this;
            $.each(app.settingBank, (i, item) => {
                if (index == i) {
                    app.settingBank[i].setting.default_bank = 1;
                } else {
                    app.settingBank[i].setting.default_bank = 0;                        
                }
            });
        },
        saveSettingDefaultAlamatPelanggan() {
            var app = this; 
            var newSettingDefaultAlamatPelanggan = app.settingDefaultAlamatPelanggan;

            axios.post(app.url + '/simpan-setting-default-alamat-pengiriman', newSettingDefaultAlamatPelanggan) 
            .then((resp) => {
                app.message = 'Berhasil Menyimpan Setting Default Alamat Pelanggan';
                app.alert(app.message);          
            })
            .catch((resp) => {
                console.log(resp);
                app.errors = resp.response.data.errors;
                alert("Tidak Dapat Menyimpan Setting Default Alamat Pelanggan");
            });
        },
        loadDefaultAlamatPelanggan(){
            var app = this; 

            axios.get(app.url + '/view-default-alamat-pengiriman') 
            .then((resp) => {
                app.provinsi = resp.data.provinsi;
                app.settingDefaultAlamatPelanggan = resp.data.defaultAlamatPelanggan;
            })
            .catch((resp) => {
                console.log(resp);
                alert("Tidak Dapat Memuat Data");
            });
        },
        loadKabupaten(){
            var app = this;
            var provinsi_id = app.settingDefaultAlamatPelanggan.provinsi;
            var type = "kabupaten";

            app.$refs.kabupaten.$el.selectize.settings.placeholder = "Tunggu Sebentar ...";
            app.$refs.kabupaten.$el.selectize.updatePlaceholder();
            axios.get(app.url_wilayah + '/pilih-wilayah/' + provinsi_id + '/' + type)
            .then((resp) => {

                app.$refs.kabupaten.$el.selectize.clearOptions();            
                app.$refs.kabupaten.$el.selectize.settings.placeholder = "Pilih Kabupaten";
                app.$refs.kabupaten.$el.selectize.updatePlaceholder();
                app.kabupaten = resp.data;    
                app.$refs.kabupaten.$el.selectize.focus();

            })
            .catch((resp) => {
              alert("Tidak bisa memuat kabupaten ");
          });

        },
        hapusBankTransfer(id_bank, logo_bank) {
            let app = this;

            swal({
                title: 'Hapus?',
                type: 'warning',
                showCancelButton: true,
                reverseButtons: true,
                text: 'Apakah Anda yakin ingin menghapus Bank Transfer ini?'
            })
            .then((hapus) => {
                if (hapus) {
                    axios.get(app.url + '/hapus-bank-transfer/' + id_bank + '/' + logo_bank)
                    .then((resp) => {
                        app.getDataSettingBank();
                    })
                    .catch((resp) => {
                        console.log('catch: ', resp);
                    });
                }
            })
            .catch((resp) => {
                console.log(resp);
            })

        },
        alert(pesan) {
            this.$swal({
                title: "Berhasil ",
                text: pesan,
                icon: "success",
                buttons: false,
                timer: 1000,
            });
        }
    }
}
</script>
