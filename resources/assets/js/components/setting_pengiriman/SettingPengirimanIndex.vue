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
                                <a href="#jasa_pengiriman" role="tab" data-toggle="tab" style="margin-right:10px; ">
                                    Jasa Pengiriman
                                </a>
                            </li>
                            <li>
                                <a href="#bank_transfer" role="tab" data-toggle="tab" style="margin-left:10px;">
                                    Bank Transfer
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

                                                        <img :src="url_picture+'/'+settingPengirimans.setting.logo_jasa" style="width: 15%" v-else/> 
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
                                    <button class="btn btn-primary" id="btnSubmit" @click="simpanSetting()" style=""> <i class="material-icons">save</i> Simpan
                                    </button>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nama Bank</th>
                                                <th class="text-center"></th>
                                                <th class="text-center">Pilih</th>
                                                <th class="text-center">Default</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="settingBanks, index in settingBank">
                                                <td width="10%">{{settingBanks.setting.nama_bank.toUpperCase()}}</td>
                                                <td align="center">
                                                    <div class="img-container">
                                                        <img :src="url_picture+'/'+settingBanks.setting.logo_bank" style="width: 100%" v-if="settingBanks.agent == 0"/> 

                                                        <img :src="url_picture+'/'+settingBanks.setting.logo_bank" style="width: 15%" v-else/> 
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
                                                            <input type="radio" name="optionsRadios" v-model="settingBanks.setting.default_bank" 
                                                            @click="defaultPengiriman(index)" v-bind:value="1">
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>                                
                                        </tbody>                                
                                    </table>
                                </div>

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
            },
            data: function () {
                return {
                    settingPengiriman: {
                        jasa_pengiriman: '',
                        logo_jasa: '',
                        tampil_jasa_pengiriman: 0,
                        default_jasa_pengiriman: 0,
                    },
                    settingBank: {
                        nama_bank: '',
                        logo_bank: '',
                        tampil_bank: 0,
                        default_bank: 0,
                    },
                    url : window.location.origin+(window.location.pathname).replace("dashboard", "setting-pengiriman"),
                    url_picture : window.location.origin+(window.location.pathname).replace("dashboard", "jasa_pengiriman"),
                }
            },
            filters: {
                hurufKapital(e) {
                    e.target.value = e.target.value.toUpperCase()
                }
            },
            methods: {
                simpanSetting() {
                    var app = this; 
                    var newSetting = app.settingPengiriman;

                    axios.post(app.url+'/simpan-setting-pengiriman', {data:newSetting})
                    .then(function (resp) {
                        app.message = 'Berhasil Mengubah Setting Pengiriman';
                        app.alert(app.message);                    
                        app.getDataSettingPengiriman();
                    })
                    .catch(function (resp) {
                        console.log(resp);
                        alert("Tidak Dapat Menyimpan Setting Pengiriman");
                    });
                },
                getDataSettingPengiriman() {
                    let app = this;

                    axios.get(app.url+'/view')
                    .then(function (resp) {
                        console.log(resp.data);
                        app.settingPengiriman = resp.data;
                    })
                    .catch(function (resp) {
                        console.log(resp);
                        alert('Tidak dapat memuat data setting jasa pengiriman');
                    })
                },
                defaultPengiriman(index){
                    var app = this;
                    $.each(app.settingPengiriman, function (i, item){
                        if (index == i) {
                            app.settingPengiriman[i].setting.default_jasa_pengiriman = 1;
                        }else{
                            app.settingPengiriman[i].setting.default_jasa_pengiriman = 0;                        
                        }
                    });
                },
                getDataSettingBank() {
                    let app = this;

                    axios.get(app.url+'/view-bank')
                    .then(function (resp) {
                        console.log(resp.data);
                        app.settingBank = resp.data;
                    })
                    .catch(function (resp) {
                        console.log(resp);
                        alert('Tidak dapat memuat data setting bank transfer');
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