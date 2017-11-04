
<template>
<div class="row" >
<div class="col-md-12">
<ul class="breadcrumb">
    <li><a href=" ">Home</a></li>
    <li><router-link :to="{name: 'indexSatuan'}">Satuan</router-link></li>
    <li class="active">Tambah Satuan</li>
</ul>
  <div class="card">
       <div class="card-header card-header-icon" data-background-color="purple">
           <i class="material-icons">dns</i>
       </div>
          <div class="card-content">
             <h4 class="card-title"> Satuan </h4>
    <div>
                <form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
                    <div class="form-group">
                        <label for="name" class="col-md-2 control-label">Nama Satuan</label>
                        <div class="col-md-4">
                            <input class="form-control" required autocomplete="off" placeholder="Nama Satuan" type="text" v-model="satuan.nama_satuan" name="nama_satuan"  autofocus="">
                               <span v-if="errors.nama_satuan" id="nama_satuan_error" class="label label-danger">{{ errors.nama_satuan[0] }}</span>
                            
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
