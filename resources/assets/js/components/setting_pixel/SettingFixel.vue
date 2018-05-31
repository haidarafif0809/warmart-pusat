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
                    <li class="active">Setting Pixel & Analytics</li>
                </ul>
                <div class="card">

                    <div class="card-header card-header-icon" data-background-color="purple">
                        <i class="material-icons">settings_applications</i>
                    </div>

                    <div class="card-content">
                        <h4 class="card-title">Setting Pixel & Analytics</h4>

                        <div class="table-responsive">
                            <table class="table table-striped">

                                <tbody>
                                    <tr>
                                        <td width="10%"><img src="image/google_analytics.png" style="width: 30%"> </td>
                                        <td width="10%">
                                            <a href="#setting-fixel" v-on:click="settingFixel('Google')">{{fixel.google}}</a>
                                        </td> 
                                    </tr>
                                    <tr>
                                        <td width="10%"><img src="image/facebook_pixel.png" style="width: 30%"> </td>
                                        <td width="10%">
                                            <a href="#setting-fixel" v-on:click="settingFixel('Facebook')">{{fixel.facebook}}</a>
                                        </td> 
                                    </tr>
                                </tbody>                                
                            </table>
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
            app.getDataSettingFixel();
        },
        data: function () {
            return {
                fixel : {
                  google: "",
                  facebook: ""
              },
              url : window.location.origin+(window.location.pathname).replace("dashboard", "setting-fixel"),
          }
      },
      methods: {
        settingFixel(fixel){
            let app = this;
            let pixel = fixel == 'Google' ? 'Google Analytics' : 'Facebook Pixel'
            console.log(pixel)
            app.$swal({
                title: pixel,
                content: {
                  element: "input",
                  attributes: {
                    placeholder: pixel,
                    type: "text",
                },
            },
            buttons: {
              cancel: true,
              confirm: "OK"                   
          }}).then((value) => {
            if (!value) throw null;
            app.simpanSetting(fixel,value);
        });
      },
      simpanSetting(fixel,idPixel) {
        var app = this; 
        if (fixel == "Google") {
            app.fixel.google = idPixel;
        }else{
            app.fixel.facebook = idPixel;
        }
        var newFixel = app.fixel;
        axios.post(app.url+'/simpan-fixel', newFixel) 
        .then(function (resp) {
            app.message = 'Berhasil Mengubah Setting Setting Pixel & Analytics';
            app.alert(app.message);       
        })
        .catch(function (resp) {
            console.log(resp);
            app.fixel.google = "#";
            app.fixel.facebook = "#";
            alert("Tidak Dapat Menyimpan Setting Setting Pixel & Analytics");
        });
    },
    getDataSettingFixel() {
        let app = this;
        axios.get(app.url+'/view')
        .then(function (resp) {
            if (resp.data.google == 0) {
                app.fixel.google = "#";
            }else{
                app.fixel.google = resp.data.google;
            }
            if (resp.data.facebook == 0) {
                app.fixel.facebook = "#";
            }else{
                app.fixel.facebook = resp.data.facebook;
            }
        })
        .catch(function (resp) {
            console.log(resp);
            alert('Tidak dapat memuat data setting jasa fixel');
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
