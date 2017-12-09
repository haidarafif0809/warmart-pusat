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
                                <label for="kas" class="col-md-2 control-label">Kas</label>
                                <div class="col-md-4">
                                    <selectize-component v-model="kasmasuk.kas" :settings="setKas" id="kas"  name="kas"> 
                                        <option v-for="data_kas, index in kas" v-bind:value="data_kas.id">{{ data_kas.nama_kas }}</option>
                                    </selectize-component>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="kategori" class="col-md-2 control-label">Kategori</label>
                                <div class="col-md-4">
                                     <selectize-component v-model="kasmasuk.kategori" :settings="setKategori" id="kategori_transaksi" name="kategori_transaksi">
                                        <option v-for="data_kategori, index in kategori" v-bind:value="data_kategori.id">{{ data_kategori.nama_kategori_transaksi }}</option>
                                    </selectize-component>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="jumlah" class="col-md-2 control-label">Jumlah </label>
                                <div class="col-md-4">
                                    <input class="form-control" required autocomplete="off" placeholder="Jumlah" type="text" v-model="kasmasuk.jumlah" id="jumlah" name="jumlah"  autofocus="">
                                    <span v-if="errors.jumlah" id="jumlah_error" class="label label-danger">{{ errors.jumlah[0] }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="keterangan" class="col-md-2 control-label">Keterangan</label>
                                <div class="col-md-4">
                                    <input class="form-control" required autocomplete="off" placeholder="Keterangan" type="text" v-model="kasmasuk.keterangan" id="jumlah" name="keterangan"  autofocus="">
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

        axios.get(app.url_update+'/' + id)
        .then(function (resp) {
            app.kasmasuk = resp.data;
        })
        .catch(function () {
            alert("Tidak bisa memuat warung")
        });
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
            errors: []
        }
    },
    methods: {
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
      }
  }
}
</script>
