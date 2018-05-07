<template>
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                    <li><router-link :to="{name: 'indexSettingPromo'}">Setting Promo</router-link></li>
                    <li class="active">Edit Setting Promo</li>
                </ul>
                <div class="card">
                    <div class="card-header card-header-icon" data-background-color="purple">
                        <i class="material-icons">settings_applications</i>
                    </div>

                    <div class="card-content">
                        <h4 class="card-title">Setting Promo</h4>
                        <form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        
                                        <label for="kode_barcode" class="col-md-2 control-label">Produk</label>
                                        <div class="col-md-5">
                                        <selectize-component v-model="setting.produk":settings="placeholder_produk" id="pilih_produk"> 
                                        <option v-for="produks, index in produk" v-bind:value="produks.id+'|'+produks.harga_jual" >{{ produks.nama_produk }}</option>
                                        </selectize-component>
                                    </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="harga_produk" class="col-md-2 control-label">Harga Coret</label>
                                        <div class="col-md-5">
                                            <input class="form-control" autocomplete="off" readonly="" placeholder="Harga Coret" v-model="setting.harga_produk" type="text" name="harga_produk" id="harga_produk" ref="harga_produk">
                                            <span v-if="errors.harga_produk" v-shortkey="['f1']" @shortkey="openSelectizeProduk()" id="harga_produk_error" class="label label-danger">{{ errors.harga_produk[0] }}</span>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="harga_promo" class="col-md-2 control-label">Harga Promo</label>
                                        <div class="col-md-5">
                                            <input class="form-control" autocomplete="off" placeholder="Harga Promo" v-model="setting.harga_promo" type="text" name="harga_promo" id="harga_promo" ref="harga_promo" autofocus="">
                                            <span v-if="errors.harga_promo" id="harga_promo_error" class="label label-danger">{{ errors.harga_promo[0] }}</span>
                                        </div>
                                    </div>

                                    
                                <div class="form-group">
                                    <label for="foto" class="col-md-2 control-label">Baner Promo</label>
                                        <div class="col-md-10">
                                        <div class="fileinput fileinput-new text-center" data-provides="fileinput" id="div-foto">
                                            <div class="fileinput-new thumbnail">
                                                <img v-if="setting.baner_promo != ''" :src="url_picture+'/'+setting.baner_promo" /> 
                                                <img :src="url_origin+'/assets/img/image_placeholder.jpg'" alt="Baner Akan Tampil Disini" v-else>
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                            <div>
                                                <span class="btn btn-rose btn-round btn-file">
                                                    <span class="fileinput-new">Ambil Baner</span>
                                                    <span class="fileinput-exists">Ubah</span>
                                                    <input class="form-control" type="file" name="baner_promo" id="baner_promo">
                                                </span>
                                                <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Batal</a>
                                            </div>
                                            <span v-if="errors.baner_promo" id="baner_promo_error" class="label label-danger">{{ errors.baner_promo[0] }}</span>
                                            <a style="color: red;">Size Baner (Ukuran Max : 3MB , Ukuran Dimensi : 2100 × 370 )</a>
                                        </div>
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label for="foto" class="col-md-2 control-label">Periode Promo</label>
                                        <div class="col-md-10">
                                                <div class="form-group col-md-5">
                                                    <datepicker :input-class="'form-control'" placeholder="Dari Tanggal" v-model="setting.dari_tanggal" name="dari_tanggal" v-bind:id="'dari_tanggal'"></datepicker> 
                                                    <span v-if="errors.dari_tanggal" id="dari_tanggal_error" class="label label-danger">{{ errors.dari_tanggal[0] }}</span>                   
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <datepicker :input-class="'form-control'" placeholder="Sampai Tanggal" v-model="setting.sampai_tanggal" name="sampai_tanggal" v-bind:id="'sampai_tanggal'"></datepicker>
                                                    <span v-if="errors.sampai_tanggal" id="sampai_tanggal_error" class="label label-danger">{{ errors.sampai_tanggal[0] }}</span>
                                                </div>
                                        </div>
                                </div> 

                                <div class="form-group">
                                    <div class="row">
                                    <label for="foto" class="col-md-2 col-xs-2 control-label">Hari</label>
                                        <div class="col-md-10 col-xs-10" v-if="loadingHari == false">
                                        <div class="checkbox col-md-3"  v-if="seen">
                                          <label>
                                            <input type="checkbox" name="setting_hari" v-model="pilih_semua_hari" v-bind:value="1" v-on:change="pilihSemuaHari"> <b>Pilih Semua</b>
                                            </label>
                                        </div>
                                        <div v-for="filter_haris, index in filter_hari" class="col-md-3 col-xs-3"  >
                                        <div class="checkbox" >
                                          <label>
                                            <input type="checkbox" name="setting_hari" v-bind:value="filter_haris.id" v-model="filter_setting.hari"> {{filter_haris.display_name}} 
                                          </label>
                                        </div> 
                                        </div>
                                    </div>
                                    <vue-simple-spinner v-if="loadingHari"></vue-simple-spinner>
                                </div>
                            </div>

                               <!-- <div class="form-group">
                                    <div class="row">
                                        <label for="foto" class="col-md-2 col-xs-2 control-label">Jam</label>
                                        <div class="col-md-10 col-xs-10" v-if="loadingJam == false">
                                        <div class="checkbox col-md-3"  v-if="seen">
                                          <label>
                                            <input type="checkbox" name="setting_jam" v-model="pilih_semua_jam" v-bind:value="1" v-on:change="pilihSemuaJam"> <b>Pilih Semua</b>
                                            </label>
                                        </div>  
                                        <div v-for="filter_jams, index in filter_jam" class="col-md-3 col-xs-3"  >
                                        <div class="checkbox" >
                                          <label>
                                            <input type="checkbox" name="setting_jam" v-bind:value="filter_jams.id" v-model="filter_setting.jam"> {{filter_jams.display_name}} 
                                          </label>
                                        </div> 
                                        </div>
                                    </div>
                                        <vue-simple-spinner v-if="loadingJam"></vue-simple-spinner>    
                                    </div>
                                </div> -->

                                <div class="form-group">
                                        <label for="harga_promo" class="col-md-2 control-label">Judul Promo</label>
                                        <div class="col-md-5">
                                            <input class="form-control" autocomplete="off" placeholder="Judul Promo" v-model="setting.jenis_promo" type="text" name="jenis_promo" id="jenis_promo" ref="jenis_promo" autofocus="">
                                            <span v-if="errors.jenis_promo" id="jenis_promo_error" class="label label-danger">{{ errors.jenis_promo[0] }}</span>
                                        </div>
                                    </div>

                                <div class="form-group">
                                        <label for="status_aktif" class="col-md-2 control-label">Status Aktif</label>
                                        <div class="checkbox col-md-10">
                                              <label>
                                                <input type="checkbox" name="status_aktif" true-value="1" false-value="0" v-model="setting.status_aktif">
                                                <b v-if="setting.status_aktif == 1">Ya</b>
                                                <b v-if="setting.status_aktif == 0">Tidak</b>
                                            </label>
                                        </div> 
                                </div> 

                                 <input type="hidden" placeholder="Dari Tanggal" v-model="setting.dari_tanggal_lama" name="dari_tanggal_lama">
                                  <input type="hidden" placeholder="Sampai Tanggal" v-model="setting.sampai_tanggal_lama" name="sampai_tanggal_lama">


                            </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-10 col-md-offset-1">
                            <button class="btn btn-primary" id="btnSimpanProduk" type="submit"><i class="material-icons">send</i> Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</template>


<script>
import { mapState } from 'vuex';
    export default {
        mounted() {
            let app = this;
            let id = app.$route.params.id;
            axios.get(app.url+'/' + id)
               .then(function (resp) {
                    app.setting = resp.data;
                    app.setting.produk = resp.data.produk+"|"+resp.data.harga_produk;
                    app.setting.harga_promo = resp.data.harga_promo
                    console.log(app.setting);
                })
                .catch(function () {
                    alert("Could not load your bank")
                });
            app.settingPromoId = id;
            app.$store.dispatch('LOAD_PRODUK_LAPORAN_LIST');
            app.getResults();

        },  
        data: function () {
            return {

                settingPromoId: null,
                errors: [],
                filter_hari: [],
                url : window.location.origin+(window.location.pathname).replace("dashboard", "setting-promo"),
                url_picture : window.location.origin+(window.location.pathname).replace("dashboard", "baner_setting_promo"),
                url_origin : window.location.origin+(window.location.pathname).replace("dashboard", ""),
                setting: {
                    baner_promo : '',
                    harga_promo : '',
                    produk : '',
                    harga_produk : '',
                    dari_tanggal:'',
                    sampai_tanggal:'',
                    dari_tanggal_lama:'',
                    sampai_tanggal_lama:'',
                    jenis_promo: '',
                    status_aktif:'',
                },
                filter_setting:{
                    hari:[],
                },
                seen: false,
                pilih_semua_hari : false,
                message : '',
                loadingHari: true,
                placeholder_produk: {
                placeholder: '--PILIH PRODUK--',
                sortField: 'text',
                maxOptions : 8,
                scrollDuration : 10,
                loadThrottle : 150,
                openOnFocus : false
              },
                separator: {
                    decimal: ',',
                    thousands: '.',
                    prefix: '',
                    suffix: '',
                    precision: 0,
                    masked: false /* doesn't work with directive */
                } 
            }
        },
        watch: {            
            'setting.produk': function () {
                this.pilihProduk()
            }
        },
        filters: {
          pemisahTitik: function (value) {
            var angka = [value];
            var numberFormat = new Intl.NumberFormat('es-ES');
            var formatted = angka.map(numberFormat.format);
            return formatted.join('; ');
          }
        },
        computed: {
        produk(){
                return this.$store.getters.produk_setting
            }
        },
        methods: {  
            getResults() {
                var app = this;

                axios.get(app.url+'/data-filter-edit/'+app.settingPromoId)
                .then(function (resp) {

                  app.filter_hari = resp.data.filter_hari
                  app.filter_setting.hari = resp.data.data_filter_hari
                  app.loadingHari = false;
                  app.loadingJam = false;
                  app.seen = true;
                })
                .catch(function (resp) {
                  alert("Tidak Bisa Memuat Filter Hari Tanggal");
                });
              },
            openSelectizeProduk(){      
                  this.$refs.produk.$el.selectize.focus();
            },
            pilihProduk(){
                if (this.setting.produk != '') {
                  var app = this;
                  var produk = app.setting.produk.split("|");
                  var harga_produk = produk[1];
                  app.setting.harga_produk = harga_produk;                
                }
            },
            pilihSemuaHari(){
            var app = this
            var pilih_semua_hari = app.pilih_semua_hari
            if (pilih_semua_hari == true) {

                // Filter Hari
                $.each(app.filter_hari, function (i, item) { 
                  app.filter_setting.hari.push(app.filter_hari[i].id)     
                });

              }else{
                // REMOVE VALUE ARRAY 
                app.filter_setting.hari.splice(0)
              }
              console.log(app.setting)
        },
         /*pilihSemuaJam(){
            var app = this
            var pilih_semua_jam = app.pilih_semua_jam
            if (pilih_semua_jam == true) {

                // Filter jam
                $.each(app.filter_jam, function (i, item) { 
                  app.filter_setting.jam.push(app.filter_jam[i].id)     
                });

              }else{
                // REMOVE VALUE ARRAY 
                app.filter_setting.jam.splice(0)
              }
              console.log(app.setting)
        },*/
            saveForm() {
                var app = this;
                var newSettingPromo = app.inputData();
                app.loading();
                var produk = app.setting.produk.split("|");
                var id_produk = produk[0];
                if (id_produk == "") {
                        app.message = 'Produk Tidak boleh kosong';
                        app.alertWarning(app.message);
               }else{
                    axios.post(app.url+'/' + app.settingPromoId, newSettingPromo)
                    .then(function (resp) {
                        app.updateWaktuSettingPromo();
                        app.message = 'Berhasil Mengubah Setting Promo';
                        app.alert(app.message);
                        app.kosongkanData();
                        app.$router.replace('/setting-promo');
                        app.$swal.close();
                    })
                    .catch(function (resp) {
                        alert("Periksa Kembali Inputan Anda!")
                        app.success = false;
                        app.errors = resp.response.data.errors;
                        app.$swal.close();
                    });
                }
            },
            updateWaktuSettingPromo(){
                var app = this;
                var newWaktuSettingPromo = app.filter_setting;
                axios.put(app.url+"/tambah-waktu-edit/"+app.settingPromoId,newWaktuSettingPromo)
                .then(function (resp) {
                })
                .catch(function (resp) {
                app.loading = false
                alert("Tidak Bisa Menyimpan Promo");
               });
            },
            alert(pesan) {
                this.$swal({
                    title: "Berhasil !",
                    text: pesan,
                    icon: "success",
                    buttons: false,
                    timer: 1000,
                });
            },
            loading(){
                this.$swal({
                    title: "Sedang Memproses Data ...",
                    text: "Harap Tunggu!",
                    icon: "info",
                    buttons:  false,
                    closeOnClickOutside: false,
                    closeOnEsc: false

                });
            },
            inputData(){
                var app = this;

                let newSettingPromo = new FormData();
                if (document.getElementById('baner_promo').files[0] != undefined) {
                    newSettingPromo.append('baner_promo', document.getElementById('baner_promo').files[0]);
                }

                //perubahan tanggal "Y-m-d"
                if (app.setting.dari_tanggal == app.setting.dari_tanggal_lama && app.setting.sampai_tanggal == app.setting.sampai_tanggal_lama){
                    var dari_tanggal = app.setting.dari_tanggal;
                    var sampai_tanggal = app.setting.sampai_tanggal;
                    console.log(1);
                }else if(app.setting.dari_tanggal == app.setting.dari_tanggal_lama && app.setting.sampai_tanggal != app.setting.sampai_tanggal_lama){
                    var dari_tanggal = app.setting.dari_tanggal;
                     
                     var date_sampai_tanggal = app.setting.sampai_tanggal;
                     var sampai_tanggal = "" + date_sampai_tanggal.getFullYear() +'-'+ ((date_sampai_tanggal.getMonth() + 1) > 9 ? '' : '0') + (date_sampai_tanggal.getMonth() + 1) +'-'+ (date_sampai_tanggal.getDate() > 9 ? '' : '0') + date_sampai_tanggal.getDate();
                     console.log(2);

                }else if(app.setting.dari_tanggal != app.setting.dari_tanggal_lama && app.setting.sampai_tanggal == app.setting.sampai_tanggal_lama){
                    var date_dari_tanggal = app.setting.dari_tanggal;
                    var dari_tanggal = "" + date_dari_tanggal.getFullYear() +'-'+ ((date_dari_tanggal.getMonth() + 1) > 9 ? '' : '0') + (date_dari_tanggal.getMonth() + 1) +'-'+ (date_dari_tanggal.getDate() > 9 ? '' : '0') + date_dari_tanggal.getDate();

                    var sampai_tanggal = app.setting.sampai_tanggal;
                    console.log(3);
                }else{
                    var date_dari_tanggal = app.setting.dari_tanggal;
                    var date_sampai_tanggal = app.setting.sampai_tanggal;

                    var dari_tanggal = "" + date_dari_tanggal.getFullYear() +'-'+ ((date_dari_tanggal.getMonth() + 1) > 9 ? '' : '0') + (date_dari_tanggal.getMonth() + 1) +'-'+ (date_dari_tanggal.getDate() > 9 ? '' : '0') + date_dari_tanggal.getDate();
                    var sampai_tanggal = "" + date_sampai_tanggal.getFullYear() +'-'+ ((date_sampai_tanggal.getMonth() + 1) > 9 ? '' : '0') + (date_sampai_tanggal.getMonth() + 1) +'-'+ (date_sampai_tanggal.getDate() > 9 ? '' : '0') + date_sampai_tanggal.getDate();
                    console.log(4);
                }
                //perubahan tanggal "Y-m-d"



                newSettingPromo.append('produk', app.setting.produk);
                newSettingPromo.append('harga_promo', app.setting.harga_promo);
                newSettingPromo.append('dari_tanggal', dari_tanggal);
                newSettingPromo.append('sampai_tanggal', sampai_tanggal);
                newSettingPromo.append('jenis_promo', app.setting.jenis_promo);
                newSettingPromo.append('status_aktif', app.setting.status_aktif);

                return newSettingPromo;
            },
            kosongkanData(){
                var app = this;

                app.setting.baner_promo = '';
                app.setting.produk = '';
                app.setting.harga_promo = '';
                app.setting.dari_tanggal = '';
                app.setting.sampai_tanggal = '';
                app.setting.jenis_promo = '';
                app.setting.status_aktif = false;


                app.errors = '';
            },

        }
    }
</script>