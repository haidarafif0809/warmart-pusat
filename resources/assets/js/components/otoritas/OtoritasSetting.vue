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
       <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
       <li style="color: purple">Master Data</li>
       <li style="color: purple"><router-link :to="{name: 'indexOtoritas'}">Otoritas</router-link></li>
       <li class="active">Setting Otoritas {{nama_otoritas}}</li>
     </ul>

     <div class="card">
      <div class="card-header card-header-icon" data-background-color="purple">
        <i class="material-icons">settings</i>
      </div>

      <div class="card-content">
        <h4 class="card-title">Setting Otoritas <b>{{nama_otoritas}}</b>
        </h4>

        <div class="row">
          <!-- OTORITAS USER -->
          <div class="col-sm-2">
            <b>User</b>
            <div class="checkbox" v-for="permissions_users, index in permission_user">
              <label>
                <input type="checkbox" name="setting_user" v-bind:value="permissions_users.id" v-model="setting_otoritas.user"> {{permissions_users.display_name}}
              </label>
            </div>
          </div>
          <!--END OTORITAS USER -->

          <!-- otoritas -->
          <div class="col-sm-2"> 
            <b>Master Data</b>
            <div class="checkbox" v-for="permissions_master_data, index in permission_master_data">
              <label>
                <input type="checkbox" name="setting_otoritas" v-bind:value="permissions_master_data.id" v-model="setting_otoritas.master_data"> {{permissions_master_data.display_name}}
              </label>
            </div>
            <b>Otoritas</b>
            <div class="checkbox" v-for="permissions_otoritas, index in permission_otoritas">
              <label>
                <input type="checkbox" name="setting_otoritas" v-bind:value="permissions_otoritas.id" v-model="setting_otoritas.otoritas"> {{permissions_otoritas.display_name}}
              </label>
            </div>
          </div>          
          <!--end otoritas -->

          <!-- Bank  -->
          <div class="col-sm-2"> 
            <b>Bank</b>
            <div class="checkbox" v-for="permission_bank, index in permission_bank">
              <label>
                <input type="checkbox" name="setting_bank" v-bind:value="permission_bank.id" v-model="setting_otoritas.bank"> {{permission_bank.display_name}}
              </label>
            </div>
          </div>
          <!-- Bank  -->

          <!-- Customer  -->
          <div class="col-sm-2"> 
            <b>Customer</b>
            <div class="checkbox" v-for="permission_customer, index in permission_customer">
              <label>
                <input type="checkbox" name="setting_customer" v-bind:value="permission_customer.id" v-model="setting_otoritas.customer"> {{permission_customer.display_name}}
              </label>
            </div>
          </div>
          <!-- Customer  -->

        </div>
        <center>     <vue-simple-spinner v-if="loading"></vue-simple-spinner></center>
        <button class="btn btn-primary" id="btnSimpanOtoritas" type="submit" v-on:click="submitOtoritas()"><i class="material-icons">send</i> Simpan</button>

      </div>
    </div>

  </div><!--/END-COL-SM-10-->
</div><!--/END ROW-->

</template>

<script>
export default {
  data: function () {
    return {
     permission_user: [],
     permission_otoritas: [],
     permission_master_data: [],
     permission_bank: [],
     permission_customer: [],
     permission_master_data: [],
     setting_otoritas : {
      user : [],
      otoritas : [],
      bank : [],
      customer : [],
    },
    nama_otoritas : '',
    url : window.location.origin+(window.location.pathname).replace("dashboard", "otoritas"),
    pencarian: '',
    loading: true
  }
},
mounted() {
  var app = this;
  app.getResults();
},
watch: {
  pencarian: function (newQuestion) {
    this.getHasilPencarian()  
  }
},
methods: {
  getResults() {
    var app = this;
    var id = app.$route.params.id;
    axios.get(app.url+'/'+id+'/edit')
    .then(function (resp) {

      app.permission_user = resp.data.permission_user
      app.permission_otoritas = resp.data.permission_otoritas
      app.permission_master_data = resp.data.permission_master_data
      app.permission_bank = resp.data.permission_bank
      app.permission_customer = resp.data.permission_customer
      app.permission_master_data = resp.data.permission_master_data
      app.nama_otoritas = resp.data.otoritas.display_name
      app.setting_otoritas.user = resp.data.data_permission_user
      app.setting_otoritas.otoritas = resp.data.data_permission_otoritas
      app.setting_otoritas.bank = resp.data.data_permission_bank
      app.setting_otoritas.customer = resp.data.data_permission_customer
      app.setting_otoritas.master_data = resp.data.data_permission_master_data
      
      app.loading = false;
    })
    .catch(function (resp) {
      console.log(resp);
      app.loading = false;
      alert("Tidak Bisa Memuat Otoritas");
    });
  },
  submitOtoritas(){
    var app = this
    app.$swal({
      text: "Anda Yakin Ingin Menyimpan Otoritas Ini ?",
      buttons: {
        cancel: true,
        confirm: "OK"                   
      },

    }).then((value) => {

      if (!value) throw null;

      this.prosesSimpanOtoritas();

    });
  },
  prosesSimpanOtoritas(){
    var app = this
    app.loading = true
    var id = app.$route.params.id
    axios.put(app.url+"/permission/"+id,app.setting_otoritas)
    .then(function (resp) {
      app.alert("Menyimpan Otoritas "+app.nama_otoritas)
      app.loading = false
      app.$router.replace('/otoritas')
    })
    .catch(function (resp) {
      app.loading = false
      alert("Tidak Bisa Menyimpan Otoritas");
    });
  },
  alert(pesan) { 
    this.$swal({ 
      title: "Berhasil!", 
      text: pesan, 
      icon: "success", 
      buttons: false,
      timer: 1000,
    }); 
  }, 
}
}
</script>
