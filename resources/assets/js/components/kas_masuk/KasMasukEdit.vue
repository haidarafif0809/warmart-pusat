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
                <li><router-link :to="{name: 'indexKasMasuk'}">Kas Masuk</router-link></li>
                <li class="active">Edit Kas Masuk</li>
            </ul>
            <div class="card">
               <div class="card-header card-header-icon" data-background-color="purple">
                   <i class="material-icons">dns</i>
               </div>
               <div class="card-content">
                 <h4 class="card-title"> Edit Kas Masuk </h4>
                 <div>
                    <form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
                            <div class="form-group">
                                <label for="kas" class="col-md-2 control-label">Pilih Kas</label>
                                <div class="col-md-4 col-xs-10">
                                    <selectize-component v-model="kasmasuk.kas" :settings="setKas" id="kas"  name="kas"> 
                                        <option v-for="data_kas, index in kas" v-bind:value="data_kas.id">{{ data_kas.nama_kas }}</option>
                                    </selectize-component>
                                </div>
                                <div class="col-md-1 col-xs-1" style="padding-left:0px;">
                                  <div class="row" style="margin-top:-10px">
                                    <button class="btn btn-primary btn-icon waves-effect waves-light" @click="tambahKasBaru()" type="button"> <i class="material-icons" style="font-size:14px">add</i> </button>
                                  </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="kategori" class="col-md-2 control-label">Kategori</label>
                                <div class="col-md-4 col-xs-10">
                                     <selectize-component v-model="kasmasuk.kategori" :settings="setKategori" id="kategori_transaksi" name="kategori_transaksi">
                                        <option v-for="data_kategori, index in kategori" v-bind:value="data_kategori.id">{{ data_kategori.nama_kategori_transaksi }}</option>
                                    </selectize-component>
                                </div>
                                <div class="col-md-1 col-xs-1" style="padding-left:0px;">
                                  <div class="row" style="margin-top:-10px">
                                    <button class="btn btn-primary btn-icon waves-effect waves-light" @click="kategoriBaru()" type="button"> <i class="material-icons" style="font-size:14px">add</i> </button>
                                  </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="jumlah" class="col-md-2 control-label">Jumlah </label>
                                <div class="col-md-4">
                                    <money class="form-control" required autocomplete="off" v-model="kasmasuk.jumlah" v-bind="separator" id="jumlah" name="jumlah"></money>
                                    <span v-if="errors.jumlah" id="jumlah_error" class="label label-danger">{{ errors.jumlah[0] }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="keterangan" class="col-md-2 control-label">Keterangan</label>
                                <div class="col-md-4">
                                    <input class="form-control" autocomplete="off" placeholder="Keterangan" type="text" v-model="kasmasuk.keterangan" id="jumlah" name="keterangan"  autofocus="">
                                    <span v-if="errors.keterangan" id="keterangan_error" class="label label-danger">{{ errors.keterangan[0] }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4 col-md-offset-2">
                                    <button class="btn btn-primary" id="btnSimpanKasMasuk" type="submit"><i class="material-icons">send</i> Submit</button>
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

        app.kasmasukId = id;
        app.dataKas();
        app.dataKategori();
        app.getData(id);
    },
    data: function () {
        return {
            kasmasukId: null,
            kasmasuk: {
                kas: '',
                kategori: '',
                jumlah: '',
                keterangan: '',
            },
            message : '',
            setKas: {
                placeholder: '--PILIH KAS--'
            },
           setKategori: {
                placeholder: '--PILIH KATEGORI--'
            },
            url : window.location.origin+(window.location.pathname).replace("dashboard", "kas-masuk"),
            url_update : window.location.origin+(window.location.pathname).replace("dashboard", "kas_masuk"),
            urlKategoriTransaksi : window.location.origin+(window.location.pathname).replace("dashboard", "kategori-transaksi"),
            urlKas : window.location.origin+(window.location.pathname).replace("dashboard", "kas"),
            errors: [],
            separator: {
              decimal: ',',
              thousands: '.',
              prefix: '',
              suffix: '',
              precision: 2,
              masked: false /* doesn't work with directive */
            },
            kategoriTransaksi: {
                nama_kategori_transaksi: '',
            },
            kasBaru: {
              kode_kas : 'K001',
              nama_kas : '',
              status_kas : 1,
              default_kas : 0
            },
        }
    },
    methods: {
        getData(id){
            var app = this;
            axios.get(app.url_update+'/' + id)
            .then(function (resp) {
                app.kasmasuk = resp.data;
            })
            .catch(function () {
                alert("Tidak bisa memuat warung")
            });

        },
        saveForm() {
            var app = this;
            var newKasMasuk = app.kasmasuk;
            axios.get(app.url+'/cek-kas-terpakai/'+app.kasmasukId)
                .then(function (resp) {
                    var jumlah_kas = parseInt(resp.data) + parseInt(app.kasmasuk.jumlah);
                     if (jumlah_kas < 0) {   
                        swal({
                            title: "Peringatan",
                            text:"Jumlah Kas Yang Anda Masukan Lebih Kecil Dari Total Kas Saat Ini",
                            });
                    }
                    else{
                         axios.patch(app.url_update+'/' + app.kasmasukId, newKasMasuk)
                        .then(function (resp) {
                        app.alert();
                        app.$router.replace('/kas-masuk/');
                        })
                        .catch(function (resp) {
                        console.log(resp);
                        app.errors = resp.response.data.errors;
                        alert("Could not create your Kas Masuk");
                        });
                    }
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
              app.getData(app.kasmasukId);
              app.message = 'Berhasil Menambah Kategori Transaksi '+ app.kategoriTransaksi.nama_kategori_transaksi;
              app.alertBerhasil(app.message);
              app.$router.replace('/edit-kas-masuk/'+app.kasmasukId);
              timer: 2000
              console.log(resp);
          })
          .catch(function (resp) {
              console.log(resp);
              app.kategoriTransaksi = ''
              app.$router.replace('/edit-kas-masuk/'+app.kasmasukId);
          });
        }
      },
      tambahKasBaru(){
          var app = this;
          swal({
            title: "Kas",
            html:
              '<input class="form-control" autocomplete="off" placeholder="Kode Kas" type="text" name="kode_kas" id="kode_kas" autofocus="" required="">' +
              '<input class="form-control" autocomplete="off" placeholder="Nama Kas" type="text" name="nama_kas" id="nama_kas" required="">',
            allowEnterKey : false,
            showCloseButton: true, 
            showCancelButton: true,                        
            focusConfirm: false, 
            confirmButtonText:'OK', 
            confirmButtonAriaLabel: 'Thumbs up, great!', 
            cancelButtonText:'Batal', 
            closeOnConfirm: false, 
            cancelButtonAriaLabel: 'Thumbs down', 
            preConfirm: function () {
              return new Promise(function (resolve) { 
                resolve([ 
                  $('#kode_kas').val(), 
                  $('#nama_kas').val(),
                ]) 
              }) 
            }
        }).then((result) => {
              app.prosesTambahKasBaru(result);
        });
      },
      prosesTambahKasBaru(result){
        var app = this;
        app.kasBaru.kode_kas = result[0];
        app.kasBaru.nama_kas = result[1];
        var newKas = app.kasBaru;

        axios.post(app.urlKas, newKas)
        .then(function (resp) {

              app.dataKas();
              app.getData(app.kasmasukId);
              app.message = 'Berhasil Menambah Kas '+ app.kasBaru.nama_kas;
              app.alertBerhasil(app.message);
              app.$router.replace('/edit-kas-masuk/'+app.kasmasukId);
              timer: 2000
        })
        .catch(function (resp) {
          console.log(resp);
          app.success = false;
          app.errors = resp.response.data.errors;
        });
      }
    }
}
</script>
