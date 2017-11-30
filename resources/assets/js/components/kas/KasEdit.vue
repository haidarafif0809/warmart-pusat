<template>
    <div class="row" >
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><a href=" ">Home</a></li>
                <li><router-link :to="{name: 'indexKas'}">Kas</router-link></li>
                <li class="active">Edit Kas</li>
            </ul>
            <div class="card">
             <div class="card-header card-header-icon" data-background-color="purple">
                 <i class="material-icons">dns</i>
             </div>
             <div class="card-content">
               <h4 class="card-title"> Kas </h4>
               <div>

                <form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
                    <div class="form-group">
                            <label for="kode_kas" class="col-md-2 control-label">Kode Kas</label>
                            <div class="col-md-4">
                                <input class="form-control" autocomplete="off" placeholder="Kode Kas" v-model="kas.kode_kas" type="text" name="kode_kas" id="kode_kas"  autofocus="">
                                <span v-if="errors.kode_kas" id="kode_kas_error" class="label label-danger">{{ errors.kode_kas[0] }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nama_kas" class="col-md-2 control-label">Nama Kas</label>
                            <div class="col-md-4">
                                <input class="form-control" autocomplete="off" placeholder="Nama Kas" v-model="kas.nama_kas" type="text" name="nama_kas" id="nama_kas"  autofocus="">
                                <span v-if="errors.nama_kas" id="nama_kas_error" class="label label-danger">{{ errors.nama_kas[0] }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nama_kas" class="col-md-2 control-label">Tampil Transaksi</label>
                                <div class="togglebutton col-md-4">
                                <label>
                                    <input type="checkbox" v-model="kas.status_kas" value="1" name="status_kas" id="status_kas"> 
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nama_kas" class="col-md-2 control-label">Default Kas</label>
                                <div class="togglebutton col-md-4">
                                <label>
                                    <input type="checkbox" v-on:change="defaultKas()" v-model="kas.default_kas" value="1" name="default_kas" id="default_kas"> 
                                </label>
                            </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-4 col-md-offset-2">
                             <p style="color: red; font-style: italic;">*Note : Hanya 1 Kas yang dijadikan default.</p>
                            <button class="btn btn-primary" id="btnSimpanSatuan" type="submit"><i class="material-icons">send</i> Submit</button>
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
        app.kasId = id;

        axios.get(app.url+'/' + id)
        .then(function (resp) {
            app.kas = resp.data;
        })
        .catch(function () {
            alert("Could not load your Kas")
        });
    },
    data: function () {
        return {
            kasId: null,
            kas: {
                kode_kas : '',
                nama_kas : '',
                status_kas : 0,
                default_kas : 0
            },
            url : window.location.origin + (window.location.pathname).replace("dashboard", "kas"),
            errors: []
        }
    },
    methods: {
        saveForm() {
            var app = this;
            var newKas = app.kas;
             if (app.kas.default_kas == false || app.kas.default_kas == 0 ){
                axios.get(app.url+'/cek-default-kas?id='+app.kasId)
                .then(function (resp) {
                    if (resp.data == 1) {
                        swal({
                            title: "Peringatan",
                            text:"Harus Ada 1 Kas Yang Menjadi Default Kas",
                            });
                    }
                    else{
                        axios.patch(app.url+'/' + app.kasId, newKas)
                        .then(function (resp) {
                        app.alert();
                        app.$router.replace('/kas/');
                        })
                        .catch(function (resp) {
                        console.log(resp);
                        app.errors = resp.response.data.errors;
                        alert("Could not create your kas");
                        });
                    }
              });
            }
            else{
                 axios.patch(app.url+'/' + app.kasId, newKas)
                .then(function (resp) {
                app.alert();
                app.$router.replace('/kas/');
                })
                .catch(function (resp) {
                console.log(resp);
                app.errors = resp.response.data.errors;
                alert("Could not create your kas");
                });
            }
        },
        alert() {
          this.$swal({
              title: "Berhasil Mengubah Kas!",
              icon: "success",
          });
      },
         defaultKas() {
         var app = this;
          var toogle = app.kas.default_kas;

         if (toogle == true) {
            swal({
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
      }
  }
}
</script>