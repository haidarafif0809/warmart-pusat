<style scoped>
  .btn-icon{
    border-radius: 1px solid;
    padding: 10px 10px;
  }
</style>

<template>
    <div class="row" >
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><a href=" ">Home</a></li>
                <li><router-link :to="{name: 'indexKasKeluar'}">Kas Keluar</router-link></li>
                <li class="active">Edit Kas Keluar</li>
            </ul>

            <!--MODAL KAS BARU -->
            <div class="modal" id="modal_tambah_kas" role="dialog" data-backdrop="">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close"  v-on:click="tutupModal()" v-shortkey.push="['esc']" @shortkey="tutupModal()"> <i class="material-icons">close</i></button>
                          <h4 class="modal-title">
                            <div class="alert-icon">
                              <b>Silahkan Isi Kas!</b>
                            </div>
                          </h4>
                        </div>

                        <div class="modal-body">
                            <form v-on:submit.prevent="saveFormKas()" class="form-horizontal">
                                <div class="form-group">
                                    <label for="kode_kas" class="col-md-3 control-label">Kode Kas</label>
                                    <div class="col-md-9">
                                      <input class="form-control" autocomplete="off" placeholder="Kode Kas" v-model="kasBaru.kode_kas" type="text" name="kode_kas" id="kode_kas"  autofocus="">
                                      <span v-if="errors.kode_kas" id="kode_kas_error" class="label label-danger">{{ errors.kode_kas[0] }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nama_kas" class="col-md-3 control-label">Nama Kas</label>
                                    <div class="col-md-9">
                                      <input class="form-control" autocomplete="off" placeholder="Nama Kas" v-model="kasBaru.nama_kas" type="text" name="nama_kas" id="nama_kas"  >
                                      <span v-if="errors.nama_kas" id="nama_kas_error" class="label label-danger">{{ errors.nama_kas[0] }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                  <label for="nama_kas" class="col-md-3 control-label">Tampil Transaksi</label>
                                       <div class="togglebutton col-md-9">
                                            <label>
                                                <b>No</b>  <input type="checkbox" v-model="kasBaru.status_kas" value="1" name="status_kas" id="status_kas"><b>Yes</b>
                                            </label>
                                        </div>
                                </div>
                                <div class="form-group">
                                    <label for="nama_kas" class="col-md-3 control-label">Default Kas</label>
                                        <div class="togglebutton col-md-9">
                                            <label>
                                                <b>No</b>  <input type="checkbox" v-on:change="defaultKas()" v-model="kasBaru.default_kas" value="1" name="default_kas" id="default_kas"><b>Yes</b>
                                            </label>
                                        </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-9 col-md-offset-3">
                                        <p style="color: red; font-style: italic;">*Note : Hanya 1 Kas yang dijadikan default.</p>
                                        <button class="btn btn-primary" id="btnSimpanKas" type="submit"><i class="material-icons">send</i> Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer"></div> 
                    </div>       
                </div> 
            </div>


            <div class="card">
               <div class="card-header card-header-icon" data-background-color="purple">
                   <i class="material-icons">money_off</i>
               </div>
               <div class="card-content">
                 <h4 class="card-title"> Edit Kas Keluar </h4>
                 <div>
                    <form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
                            <div class="form-group">
                                <label for="kas" class="col-md-2 control-label">Kas</label>
                                <div class="col-md-4 col-xs-10">
                                    <selectize-component v-model="kasKeluar.kas" :settings="placeholderKas" id="kas"  name="kas"> 
                                        <option v-for="data_kas, index in kas" v-bind:value="data_kas.id">{{ data_kas.nama_kas }}</option>
                                    </selectize-component>
                                </div>
                                
                                <div class="col-md-1 col-xs-1" style="padding-left:0px">
                                  <div class="row" style="margin-top:-10px">
                                    <button class="btn btn-primary btn-icon waves-effect waves-light" @click="tambahKasBaru()" type="button"> <i class="material-icons" style="font-size:14px">add</i> </button>
                                  </div>
                                </div>  
                            </div>

                            <div class="form-group">
                                <label for="kategori" class="col-md-2 control-label">Kategori</label>
                                <div class="col-md-4 col-xs-10">
                                     <selectize-component v-model="kasKeluar.kategori" :settings="placeholderKategori" id="kategori_transaksi" name="kategori_transaksi">
                                        <option v-for="data_kategori, index in kategori" v-bind:value="data_kategori.id">{{ data_kategori.nama_kategori_transaksi }}</option>
                                    </selectize-component>
                                </div>
                                
                                <div class="col-md-1 col-xs-1" style="padding-left:0px">
                                  <div class="row" style="margin-top:-10px">
                                    <button class="btn btn-primary btn-icon waves-effect waves-light" @click="kategoriBaru()" type="button"> <i class="material-icons" style="font-size:14px">add</i> </button>
                                  </div>
                                </div>  
                            </div>

                            <div class="form-group">
                                <label for="jumlah" class="col-md-2 control-label">Jumlah </label>
                                <div class="col-md-4">
                                    <money class="form-control" ref="jumlah" required autocomplete="off" v-model="kasKeluar.jumlah" v-bind="separator" id="jumlah" name="jumlah"></money>
                                    <span v-if="errors.jumlah" id="jumlah_error" class="label label-danger">{{ errors.jumlah[0] }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="keterangan" class="col-md-2 control-label">Keterangan</label>
                                <div class="col-md-4">
                                    <input class="form-control" autocomplete="off" placeholder="Keterangan" type="text" v-model="kasKeluar.keterangan" id="jumlah" name="keterangan"  autofocus="">
                                    <span v-if="errors.keterangan" id="keterangan_error" class="label label-danger">{{ errors.keterangan[0] }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4 col-md-offset-2">
                                    <button class="btn btn-primary" id="btnSimpanKasKeluar" type="submit"><i class="material-icons">send</i> Submit</button>
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

        app.kasKeluarId = id;
        app.dataKas();
        app.dataKategori();
        app.getData(id);
    },
    data: function () {
        return {
            kasKeluarId: null,
            kasKeluar: {
                kas: '',
                kategori: '',
                jumlah: '',
                keterangan: '',
            },
            kategoriTransaksi: {
                nama_kategori_transaksi: '',
            },
            kasBaru: {
              kode_kas : '',
              nama_kas : '',
              status_kas : 0,
              default_kas : 0
            },
            message : '',
            placeholderKas: {
                placeholder: '--PILIH KAS--'
            },
            placeholderKategori: {
                placeholder: '--PILIH KATEGORI--'
            },
            url : window.location.origin+(window.location.pathname).replace("dashboard", "kas-keluar"),
            urlKategoriTransaksi : window.location.origin+(window.location.pathname).replace("dashboard", "kategori-transaksi"),
            urlKas : window.location.origin+(window.location.pathname).replace("dashboard", "kas"),
            errors: [],
            kas : [],
            kategori :[],
            separator: {
              decimal: ',',
              thousands: '.',
              prefix: '',
              suffix: '',
              precision: 2,
              masked: false /* doesn't work with directive */
          }
        }
    },
    methods: {
        getData(id){
            var app = this;
            axios.get(app.url+'/' + id)
            .then(function (resp) {
                app.kasKeluar = resp.data;
            })
            .catch(function () {
                alert("Tidak bisa memuat warung")
        });

        },
        saveForm() {
            var app = this;
            var newkasKeluar = app.kasKeluar;

            axios.patch(app.url+'/' + app.kasKeluarId, newkasKeluar)
            .then(function (resp) {
                console.log(resp)
                if (resp.data < 0) {
                    app.message = 'Kas Tidak Mencukupi. Sisa Kas = '+new Intl.NumberFormat().format(resp.data);
                    app.alertGagal(app.message);
                    app.$router.replace('/edit-kas-keluar/'+kasKeluarId);
                    app.$refs.jumlah.$el.focus()
                }
                else{
                    app.message = 'Berhasil Mengubah Kas Keluar';
                    app.alert(app.message);
                    app.$router.replace('/kas-keluar');
                }
            })
            .catch(function (resp) {
                app.errors = resp.response.data.errors;
                alert("Tidak Bisa Memuat Kas Keluar");
            });
         },
        dataKas() {
            var app = this;
            axios.get(app.url+'/pilih-kas').then(function (resp) {
                app.kas = resp.data;
            })
            .catch(function (resp) {
                alert("Tidak Bisa Memuat Kas ");
            });
        },
        dataKategori() {
            var app = this;
            axios.get(app.url+'/pilih-kategori').then(function (resp) {
                app.kategori = resp.data;
            })
            .catch(function (resp) {
                alert("Tidak Bisa Memuat Kategori");
            });
        },
        alert(pesan) {
          this.$swal({
              title: "Berhasil !",
              text: pesan,
              icon: "success",
          });
        },
        alertGagal(pesan) {
          this.$swal({
              title: "Peringatan!",
              text: pesan,
              icon: "warning",
          });
        },
        alertBerhasil(pesan) {
          this.$swal({
              title: "Sukses!",
              text: pesan,
              icon: "success",
              timer: 1500,
          });
            },
          kategoriBaru(){
              var app = this;
              app.$swal({
                    title: "Kategori Transaksi",
                    content: {
                        element: "input",
                        attributes: {
                            placeholder: "Nama Kategori Transaksi",
                            type: "text",
                        },
                    },
                    closeOnEsc: true,
                    buttons: {
                        confirm: "OK"                   
                    }
              }).then((value) => {
                  this.tambahKategoriTransaksiBaru(value);
            });
          },
          tambahKategoriTransaksiBaru(value){
            if (value == "") {
              this.$swal({
                  text: "Nama Kategori Transaksi Tidak Boleh Kosong!",
              });
            }else{
              var app = this;
              app.kategoriTransaksi.nama_kategori_transaksi = value;
              var newKategoriTransaksi = app.kategoriTransaksi;

              axios.post(app.urlKategoriTransaksi, newKategoriTransaksi).then(function (resp) {

                  app.dataKategori();
                  app.getData(app.kasKeluarId);
                  app.message = 'Berhasil Menambah Kategori Transaksi '+ app.kategoriTransaksi.nama_kategori_transaksi;
                  app.alertBerhasil(app.message);
                  app.$router.replace('/edit-kas-keluar/'+app.kasKeluarId);
                  timer: 2000
                  console.log(resp);
              })
              .catch(function (resp) {
                  console.log(resp);
                  app.kategoriTransaksi = ''
                  app.$router.replace('/edit-kas-keluar/'+app.kasKeluarId);
              });
            }
          },
        tambahKasBaru(){
            $("#modal_tambah_kas").show();
            this.$refs.kode_kas.$el.focus(); 
        },
        saveFormKas() {
          var app = this;
          var newKas = app.kasBaru;
          axios.post(app.urlKas, newKas)
          .then(function (resp) {
            app.message = 'Menambah Kas '+ app.kasBaru.nama_kas;
            app.alertBerhasil(app.message);
            app.kasBaru.kode_kas = ''
            app.kasBaru.nama_kas = ''
            app.kasBaru.status_kas = 0
            app.kasBaru.default_kas = 0
            app.errors = '';
            app.dataKas();
            $("#modal_tambah_kas").hide();
          })
          .catch(function (resp) {
            app.success = false;
            app.errors = resp.response.data.errors;
          });
        },
        defaultKas() {
            var app = this;
            var toogle = app.kasBaru.default_kas;

            if (toogle == true) {
                app = this;
                app.$swal({
                  title: "Konfirmasi",
                  text: "Apakah Anda Yakin Ingin Mengubah Kas Utama ?",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                })
                .then((confirm) => {
                    if (confirm) {
                        toogle.prop('checked', true);
                    } else {
                        toogle.prop('checked', false);
                    }
              });
            }  
        },
        tutupModal(){
            $("#modal_tambah_kas").hide();  
        },
    }
}
</script>
