<style scoped>
    .pencarian {
        color: red; 
        float: right;
    }
    .roundColor {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 1px solid grey;
    }
    .cursor {
        cursor: pointer;
    }
</style>
<template>


    <div class="row" >

        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li class="active">Setting Tema</li>
            </ul>


            <div class="card">
                <div class="card-header card-header-icon" data-background-color="purple">
                    <i class="material-icons">color_lens</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title"> Setting Tema </h4>

                    <div class="toolbar">
                        <p> <router-link :to="{name: 'createColorTheme'}" class="btn btn-primary">Tambah Tema</router-link></p>
                    </div>

                    <div class=" table-responsive ">
                        <div class="pencarian">
                            <input type="text" class="form-control pencarian" autocomplete="off" name="pencarian" v-model="pencarian" placeholder="Pencarian" >
                        </div>
                        <table class="table table-striped table-hover ">
                            <thead class="text-primary">
                                <tr>
                                    <th>Tema</th>
                                    <th style="text-align: center">Background</th>
                                    <th style="text-align: center">Tombol Header</th>
                                    <th style="text-align: center">Status</th>
                                    <th>Hapus</th>
                                </tr>
                            </thead>
                            <tbody v-if="settingTemas.length" class="data-ada">
                                <tr v-for="settingTema, index in settingTemas" >

                                    <td>{{ settingTema.tema.nama_tema }}</td>
                                    <td align="center">
                                        <div class="roundColor" :style="'background-color:'+settingTema.tema.kode_tema"></div>
                                    </td>
                                    <td align="center">
                                        <div class="roundColor" :style="'background-color:'+settingTema.tema.header_tema"></div>
                                    </td>
                                    <td v-if="settingTema.tema.default_tema == 1" align="center">
                                        <i style="color:green" class="material-icons cursor" @click="nonActiveTheme(settingTema.tema.id, index, settingTema.tema.nama_tema, settingTema.tema.default_tema)">check_circle</i>
                                    </td>
                                    <td v-else align="center">
                                        <i style="color:red" class="material-icons cursor" @click="activeTheme(settingTema.tema.id, index, settingTema.tema.nama_tema, settingTema.tema.default_tema)">cancel</i>
                                    </td>
                                    <td>
                                        <a class="btn btn-xs btn-danger" v-bind:id="'delete-' + settingTema.tema.id"  v-on:click="deleteEntry(settingTema.tema.id, index,settingTema.tema.nama_tema)" v-if="settingTema.tema.default_tema == 0">
                                            Delete 
                                        </a>
                                        <a href="#tema" class="btn btn-xs btn-danger" v-bind:id="'delete-' + settingTema.tema.id" v-on:click="temaTerpakai(settingTema.tema.id, index,settingTema.tema.nama_tema)" v-else> Delete 
                                        </a>
                                    </td>

                                </tr>
                            </tbody>
                            <tbody class="data-tidak-ada" v-else>
                                <tr ><td colspan="6"  class="text-center">Tidak Ada Data</td></tr>
                            </tbody>
                        </table>
                        <p style="color: red; font-style: italic;">*Note : Klik Pada Kolom Status Untuk Memilih Tema Yang Ingin Diterapkan atau Dilepas.</p>

                        <vue-simple-spinner v-if="loading"></vue-simple-spinner>

                        <div align="right"><pagination :data="settingTemasData" v-on:pagination-change-page="getThemeColor" :limit="4"></pagination></div>

                    </div>


                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                settingTemas: [],
                settingTemasData: {},
                url : window.location.origin+(window.location.pathname).replace("dashboard", "tema"),
                pencarian: '',
                loading: true
            }
        },
        mounted() {
            var app = this;
            app.getThemeColor();
        },
        watch: {
            pencarian: function (newQuestion) {
                this.getHasilPencarian()  
            }
        },

        methods: {
            getThemeColor(page) {
                var app = this;
                if (typeof page === 'undefined') {
                    page = 1;
                }
                axios.get(app.url+'/view?page='+page)
                .then(function (resp) {
                    app.settingTemas = resp.data.data;
                    app.settingTemasData = resp.data;
                    app.loading = false;
                })
                .catch(function (resp) {
                    app.loading = false;
                    alert("Tidak Bisa Memuat Tema");
                });
            },
            getHasilPencarian(page){
                var app = this;
                if (typeof page === 'undefined') {
                    page = 1;
                }
                axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
                .then(function (resp) {
                    app.settingTemas = resp.data.data;
                    app.settingTemasData = resp.data;
                    app.loading = false;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Tidak Bisa Memuat Tema");
                });
            },
            deleteEntry(id, index,nama_tema) {
                var app = this;
                app.$swal({
                    title: "Konfirmasi Hapus",
                    text : "Anda Yakin Ingin Menghapus Tema "+nama_tema+" ?",
                    icon : "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {                    
                        var app = this;
                        axios.delete(app.url+'/' + id)
                        .then(function (resp) {
                            app.getThemeColor();
                            app.alert(`Tema ${nama_tema} Berhasil Dihapus`);
                        })
                        .catch(function (resp) {
                            console.log(resp)
                            app.alertGagal(`Tema ${nama_tema} Tidak Bisa Dihapus`);
                        });                
                    }
                });
            },
            temaTerpakai(id, index,nama_tema) {
                var app = this;                  
                app.alertGagal(`Tema ${nama_tema} Tidak Bisa Dihapus, Karena Sudah Diterapkan`);
            },
            activeTheme(id, index,nama_tema, default_tema) {
                var app = this;

                axios.get(app.url+'/ubah-tema/'+id+'/'+default_tema)
                .then(function (resp) {
                    app.getThemeColor();
                    app.alert(`Tema ${nama_tema} Berhasil Dipasang`);
                })
                .catch(function (resp) {
                    console.log(resp)
                    app.alertGagal(`Tema ${nama_tema} Gagal Dipasang`);
                });                
            },
            nonActiveTheme(id, index,nama_tema, default_tema) {
                var app = this;

                axios.get(app.url+'/ubah-tema/'+id+'/'+default_tema)
                .then(function (resp) {
                    app.getThemeColor();
                    app.alert(`Tema ${nama_tema} Berhasil Dilepas`);
                })
                .catch(function (resp) {
                    console.log(resp)
                    app.alertGagal(`Tema ${nama_tema} Gagal Dilepas`);
                });
            },
            alert(pesan) {
                this.$swal({
                    title: "Berhasil!",
                    text: pesan,
                    icon: "success",
                    timer: 1500
                });
            },
            alertGagal(pesan) {
                this.$swal({
                    title: "Gagal!",
                    text: pesan,
                    icon: "warning",
                    timer: 1500
                });
            }
        }
    }
</script>
