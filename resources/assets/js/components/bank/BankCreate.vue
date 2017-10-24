
<template>
<div class="row" >
<div class="col-md-12">
<ul class="breadcrumb">
    <li><a href=" ">Home</a></li>
    <li><router-link :to="{name: 'indexBank'}">Bank</router-link></li>
    <li class="active">Tambah Bank</li>
</ul>
  <div class="card">
       <div class="card-header card-header-icon" data-background-color="purple">
           <i class="material-icons">payment</i>
       </div>
          <div class="card-content">
             <h4 class="card-title"> Bank </h4>
    <div>
                <form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
                    <div class="form-group">
                        <label for="name" class="col-md-2 control-label">Nama Bank</label>
                        <div class="col-md-4">
                            <input class="form-control" required autocomplete="off" placeholder="Nama Bank" type="text" v-model="bank.nama_bank" name="nama_bank"  autofocus="">
                               <span v-if="errors.nama_bank" class="label label-danger">{{ errors.nama_bank[0] }}</span>
                            
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="no_telp" class="col-md-2 control-label">A.N Bank</label>
                        <div class="col-md-4">
                            <input class="form-control" required autocomplete="off" placeholder="A.N Bank" type="text" v-model="bank.atas_nama" name="atas_nama" >
                              <span v-if="errors.atas_nama" class="label label-danger">{{ errors.atas_nama[0] }}</span>
                        </div>
                    </div>   
                  <div class="form-group">
                        <label for="no_rek" class="col-md-2 control-label">No Rek</label>
                        <div class="col-md-4">
                            <input class="form-control" required autocomplete="off" placeholder="No Rekening" type="text" v-model="bank.no_rek" name="no_rek" >
                              <span v-if="errors.no_rek" class="label label-danger">{{ errors.no_rek[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                         <div  class="col-md-2 "></div>
                         <div class="togglebutton col-md-4">
                            <label>
                                <input type="checkbox" v-model="bank.tampil_customer" value="1"> Tampil Di Customer
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                            <div class="col-md-4 col-md-offset-2">
                                <button class="btn btn-primary" id="btnSimpanBank" type="submit"><i class="material-icons">send</i> Submit</button>
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
                bank: {
                    nama_bank: '',
                    atas_nama: '',
                    no_rek: '',
                    tampil_customer: 0
                },
                message : ''
            }
        },
        methods: {
            saveForm() {
                var app = this;
                var newbank = app.bank;
                axios.post(app.url, newbank)
                    .then(function (resp) {
                    app.message = 'Sukses : Berhasil Menambah Bank '+ app.bank.nama_bank;
                    app.alert(app.message);
                    app.bank.nama_bank = ''
                    app.bank.atas_nama = ''
                    app.bank.no_rek = ''
                    app.bank.tampil_customer = 0
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
