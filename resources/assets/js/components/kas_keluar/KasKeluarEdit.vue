<template>
    <div class="row" >
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><a href=" ">Home</a></li>
                <li><router-link :to="{name: 'indexKasKeluar'}">Kas Keluar</router-link></li>
                <li class="active">Edit Kas Keluar</li>
            </ul>
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
                                <div class="col-md-4">
                                    <selectize-component v-model="kasKeluar.kas" :settings="placeholderKas" id="kas"  name="kas"> 
                                        <option v-for="data_kas, index in kas" v-bind:value="data_kas.id">{{ data_kas.nama_kas }}</option>
                                    </selectize-component>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="kategori" class="col-md-2 control-label">Kategori</label>
                                <div class="col-md-4">
                                     <selectize-component v-model="kasKeluar.kategori" :settings="placeholderKategori" id="kategori_transaksi" name="kategori_transaksi">
                                        <option v-for="data_kategori, index in kategori" v-bind:value="data_kategori.id">{{ data_kategori.nama_kategori_transaksi }}</option>
                                    </selectize-component>
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

        axios.get(app.url+'/' + id)
        .then(function (resp) {
            app.kasKeluar = resp.data;
        })
        .catch(function () {
            alert("Tidak bisa memuat warung")
        });
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
            message : '',
            placeholderKas: {
                placeholder: '--PILIH KAS--'
            },
            placeholderKategori: {
                placeholder: '--PILIH KATEGORI--'
            },
            url : window.location.origin+(window.location.pathname).replace("dashboard", "kas-keluar"),
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
        }
    }
}
</script>
