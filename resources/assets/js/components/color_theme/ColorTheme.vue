<style scoped>
    .pencarian {
        color: red; 
        float: right;
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
                                    <th>Background</th>
                                    <th>Tombol Header</th>
                                    <th style="text-align: center">Status</th>
                                    <th style="text-align: center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody v-if="settingTemas.length" class="data-ada">
                                <tr v-for="settingTema, index in settingTemas" >

                                    <td>{{ settingTema.tema.nama_tema }}</td>
                                    <td :style="'background-color:'+settingTema.tema.kode_tema"></td>
                                    <td :style="'background-color:'+settingTema.tema.header_tema"></td>
                                    <td v-if="settingTema.tema.default_tema == 1" align="center">
                                        <i style="color:green" class="material-icons">check_circle</i>
                                    </td>
                                    <td v-else align="center">
                                        <i style="color:red" class="material-icons">cancel</i>
                                    </td>

                                </tr>
                            </tbody>
                            <tbody class="data-tidak-ada" v-else>
                                <tr ><td colspan="4"  class="text-center">Tidak Ada Data</td></tr>
                            </tbody>
                        </table>

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
                    console.log(resp);
                    app.loading = false;
                    alert("Tidak Bisa Memuat Tema");
                });
            },
        }
    }
</script>
