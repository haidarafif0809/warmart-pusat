<style scoped>
  .hurufBesar{
    text-transform: uppercase;
  }
</style>
<template>
  <div class="row" >
    <div class="col-md-12">
      <ul class="breadcrumb">
        <li><a href=" ">Home</a></li>
        <li><router-link :to="{name: 'indexKas'}">Kas</router-link></li>
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
              <label for="nama_bank" class="col-md-2 control-label">Pilih Bank</label>
              <div class="col-md-4 hurufBesar">
                <selectize-component v-model="bankWarung.nama_bank" :settings="placeholder_bank" id="nama_bank" ref='nama_bank'>
                  <option v-for="banks, index in bank" v-bind:value="banks.id">
                    {{ banks.nama_bank }}
                  </option>
                </selectize-component>
                <br v-if="errors.nama_bank">
                <span v-if="errors.nama_bank" id="nama_bank_error" class="label label-danger">
                  {{ errors.nama_bank[0] }}
                </span>
              </div>
            </div>

            <div class="form-group">
              <label for="nama_tampil" class="col-md-2 control-label">Nama Bank</label>
              <div class="col-md-4 hurufBesar">
                <input class="form-control" required autocomplete="off" placeholder="Nama Bank" type="text" v-model="bankWarung.nama_tampil" name="nama_tampil" >
                <span v-if="errors.nama_tampil" class="label label-danger">{{ errors.nama_tampil[0] }}</span>
              </div>
            </div>
            <div class="form-group">
              <label for="no_telp" class="col-md-2 control-label">A.N Bank</label>
              <div class="col-md-4">
                <input class="form-control" required autocomplete="off" placeholder="A.N Bank" type="text" v-model="bankWarung.atas_nama" name="atas_nama" >
                <span v-if="errors.atas_nama" class="label label-danger">{{ errors.atas_nama[0] }}</span>
              </div>
            </div>   
            <div class="form-group">
              <label for="no_rek" class="col-md-2 control-label">No Rek</label>
              <div class="col-md-4">
                <input class="form-control" required autocomplete="off" placeholder="No Rekening" type="text" v-model="bankWarung.no_rek" name="no_rek" >
                <span v-if="errors.no_rek" class="label label-danger">{{ errors.no_rek[0] }}</span>
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
  import { mapState } from 'vuex';
  export default {
    data: function () {
      return {
        errors: [],
        url : window.location.origin + (window.location.pathname).replace("dashboard", "bank-warung"),
        bankWarung: {
          nama_bank: '',
          nama_tampil: '',
          atas_nama: '',
          no_rek: '',
        },
        message : '',
        placeholder_bank: {
          placeholder: '--PILIH BANK--',
        },
      }
    },
    mounted() {   
      var app = this;
      app.$store.dispatch('LOAD_BANK_LIST');
      app.openSelectizeBank();
    },
    computed : mapState ({
      bank(){
        return this.$store.state.bank;
      },
    }),
    methods: {
      openSelectizeBank(){
        this.$refs.nama_bank.$el.selectize.focus();
      },
      saveForm() {
        var app = this;
        var newbank = app.bankWarung;
        axios.post(app.url, newbank)
        .then(function (resp) {
          app.message = 'Sukses : Berhasil Menambah Bank '+ app.bankWarung.nama_bank;
          app.alert(app.message);

          app.bankWarung.atas_nama = ''
          app.bankWarung.nama_tampil = ''
          app.bankWarung.no_rek = ''
          app.errors = '';
          app.$router.replace('/bank-warung/');

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
