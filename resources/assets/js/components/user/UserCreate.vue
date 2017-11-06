
<template>
<div class="row" >
<div class="col-md-12">
<ul class="breadcrumb">
    <li><a href=" ">Home</a></li>
    <li><router-link :to="{name: 'indexUser'}">User</router-link></li>
    <li class="active">Tambah User</li>
</ul>
  <div class="card">
       <div class="card-header card-header-icon" data-background-color="purple">
           <i class="material-icons">dns</i>
       </div>
          <div class="card-content">
             <h4 class="card-title"> User </h4>
    <div>
                <form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
                    <div class="form-group">
                        <label for="name" class="col-md-2 control-label">Nama</label>
                        <div class="col-md-4">
                            <input class="form-control" required autocomplete="off" placeholder="Nama" type="text" v-model="user.name" name="name"  autofocus="">
                               <span v-if="errors.name" id="name_error" class="label label-danger">{{ errors.name[0] }}</span>
                            
                        </div>
                    </div>
                   <div class="form-group">
                        <label for="name" class="col-md-2 control-label">No Telp</label>
                        <div class="col-md-4">
                            <input class="form-control" required autocomplete="off" placeholder="No Telp" type="number" v-model="user.no_telp" name="no_telp"  autofocus="">
                               <span v-if="errors.no_telp" id="nama_satuan_error" class="label label-danger">{{ errors.no_telp[0] }}</span>
                            
                        </div>
                    </div>
                   <div class="form-group">
                        <label for="name" class="col-md-2 control-label">Email</label>
                        <div class="col-md-4">
                            <input class="form-control" required autocomplete="off" placeholder="Email" type="email" v-model="user.email" name="email"  autofocus="">
                               <span v-if="errors.email" id="email_error" class="label label-danger">{{ errors.email[0] }}</span>
                            
                        </div>
                    </div>
                   <div class="form-group">
                        <label for="name" class="col-md-2 control-label">Alamat</label>
                        <div class="col-md-4">
                            <input class="form-control" required autocomplete="off" placeholder="Alamat" type="text" v-model="user.alamat" name="alamat"  autofocus="">
                               <span v-if="errors.alamat" id="email_error" class="label label-danger">{{ errors.alamat[0] }}</span>
                            
                        </div>
                    </div>

                    <div class="form-group">
                            <div class="col-md-4 col-md-offset-2">
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
        data: function () {
            return {
                errors: [],
                url : window.location.origin+window.location.pathname,
                satuan: {
                    nama_satuan: '',
                },
                message : ''
            }
            
        },
        methods: {
            saveForm() {
                var app = this;
                var newsatuan = app.satuan;
                axios.post(app.url, newsatuan)
                    .then(function (resp) {
                    app.message = 'Sukses : Berhasil Menambah Satuan '+ app.satuan.nama_satuan;
                    app.alert(app.message);
                    app.satuan.nama_satuan = ''
                    app.errors = '';
                    app.$router.replace('/');

                    })
                    .catch(function (resp) {
                    app.success = false;
                    app.errors = resp.response.data.errors;
                });
            },
            alert(pesan) {
              this.$swal({
                  title: "Berhasil!",
                  text: pesan,
                  icon: "success",
                });
            }

        }
    }
</script>
