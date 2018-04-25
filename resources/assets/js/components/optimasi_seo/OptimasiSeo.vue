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
                <li class="active">Optimas SEO</li>
            </ul>
            <div class="card">

                <div class="card-header card-header-icon" data-background-color="purple">
                    <i class="material-icons">settings_applications</i>
                </div>

                <div class="card-content">
                    <h4 class="card-title">Optimas SEO</h4>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <th>Name</th>
                                <th>Content</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="10%">Keywords</td>
                                    <td width="10%">
                                        <a href="#optimasi-seo" v-on:click="settingSeo('Keyword')">{{optimasiSeo.keyword}}</a>
                                    </td> 
                                </tr>
                                <tr>
                                    <td width="10%">Description</td>
                                    <td width="10%">
                                        <a href="#optimasi-seo" v-on:click="settingSeo('Deskripsi Toko')">{{optimasiSeo.deskripsi}}</a>
                                    </td> 
                                </tr>
                            </tbody>                                
                        </table>
                    </div>
                    <p style="color: red; font-style: italic;">*Note : Klik Kolom Content Untuk Mengubah.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            var app = this;
            app.getDataSeo();
        },
        data: function () {
            return {
                optimasiSeo : {
                    keyword: "",
                    deskripsi: ""
                },
                url : window.location.origin+(window.location.pathname).replace("dashboard", "optimasi-seo"),
            }
        },
        methods: {
            settingSeo(name){
                var app = this;
                app.$swal({
                    title: name,
                    content: {
                        element: "input",
                        attributes: {
                            placeholder: name,
                            type: "text",
                        },
                    },
                    buttons: {
                        cancel: true,
                        confirm: "OK"                   
                    }}).then((value) => {
                        if (!value) throw null;
                        app.simpanSetting(name,value);
                    });
                },
                simpanSetting(name,value) {
                    var app = this;
                    if (name == "Keyword") {
                        app.optimasiSeo.keyword = value;
                    }else{
                        app.optimasiSeo.deskripsi = value;
                    }

                    var newSeo = app.optimasiSeo;
                    axios.post(app.url+'/simpan-seo', newSeo) 
                    .then(function (resp) {
                        app.message = 'Berhasil Mengubah Optimas SEO';
                        app.alert(app.message);       
                    })
                    .catch(function (resp) {
                        console.log(resp);
                        alert("Tidak Dapat Menyimpan Optimas SEO");
                    });
                },
                getDataSeo() {
                    let app = this;
                    axios.get(app.url+'/view')
                    .then(function (resp) {
                        console.log(resp.data)
                        app.optimasiSeo = resp.data;
                    })
                    .catch(function (resp) {
                        console.log(resp);
                        alert('Tidak Dapat Memuat Optimas SEO');
                    })
                },
                alert(pesan) {
                    this.$swal({
                        text: pesan,
                        icon: "success",
                        buttons: false,
                        timer: 1000,
                    });
                }
            }
        }
    </script>
