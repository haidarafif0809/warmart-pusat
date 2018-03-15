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
       <li class="active">Otoritas</li>
     </ul>

     <div class="card">
      <div class="card-header card-header-icon" data-background-color="purple">
        <i class="material-icons">accessibility</i>
      </div>

      <div class="card-content">
        <h4 class="card-title"> Otoritas </h4>

        <div class="row">
          <div class="col-md-6">
           <div class="toolbar">
            <p> <a href="#otoritas" class="btn btn-primary" v-on:click="tambahOtoritas()"><i class="material-icons">add</i> Tambah Otoritas</a></p>
          </div>
        </div>
        <div class="col-md-6">
          <div  class="pencarian">
           <input type="text" class="form-control pencarian" autocomplete="off" name="pencarian" v-model="pencarian" placeholder="Pencarian" >
         </div>
       </div>
     </div>


     <div class=" table-responsive">
       <table class="table table-striped table-hover">
        <thead class="text-primary">
          <tr>
            <th>Otoritas</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody v-if="otoritas.length > 0 && loading== false" class="data-ada">
          <tr v-for="otoritass, index in otoritas">
            <td>{{ otoritass.display_name }}</td>
            <td>
              <router-link :to="{name: 'settingOtoritas', params: {id: otoritass.id}}" class="btn btn-xs btn-warning">
                Setting Otoritas 
              </router-link>
              |
              <a href="#otoritas" class="btn btn-xs btn-default" v-on:click="editOtoritas(otoritass.id, index,otoritass.display_name)">
                Edit
              </a>
              |
              <a href="#otoritas" class="btn btn-xs btn-danger" v-on:click="deleteEntry(otoritass.id, index,otoritass.name)"> 
                Hapus
              </a>
            </td>
          </tr>
        </tbody>
        <!--JIKA DATA CUSTOMER KOSONG-->
        <tbody class="data-tidak-ada" v-else-if="otoritas.length == 0 && loading== false">
          <tr>
            <td colspan="4"  class="text-center">Tidak Ada Data</td>
          </tr>
        </tbody>
      </table>

      <!--LOADING-->
      <vue-simple-spinner v-if="loading"></vue-simple-spinner>
      <!--PAGINATION TABLE-->
      <div align="right"><pagination :data="otoritasData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

    </div><!-- /END RESPONSIVE-->

  </div>
</div>

</div><!--/END-COL-SM-10-->
</div><!--/END ROW-->

</template>

<script>
export default {
  data: function () {
    return {
      otoritas: [],
      otoritasData: {},
      newOtoritas : {
       otoritas : ''
     },  
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
  getResults(page) {
    var app = this;
    if (typeof page === 'undefined') {
      page = 1;
    }
    axios.get(app.url+'/view?page='+page)
    .then(function (resp) {
      app.otoritas = resp.data.data;
      app.otoritasData = resp.data;
      app.loading = false;
    })
    .catch(function (resp) {
      console.log(resp);
      app.loading = false;
      alert("Tidak Bisa Memuat Otoritas");
    });
  },
  getHasilPencarian(page){
    var app = this;
    if (typeof page === 'undefined') {
      page = 1;
    }
    app.loading = true
    axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
    .then(function (resp) {
      app.otoritas = resp.data.data;
      app.otoritasData = resp.data;
      app.loading = false
    })
    .catch(function (resp) {
      app.loading = false
      console.log(resp);
      alert("Tidak Bisa Memuat Otoritas");
    });
  }, 
  tambahOtoritas(){
    var app = this;     
    app.$swal({
      title: 'Otoritas',
      content: {
        element: "input",
        attributes: {
          placeholder: "Otoritas",
          type: "text",
        },
      },
      buttons: {
        cancel: true,
        confirm: "OK"                   
      },

    }).then((value) => {
      if (!value) throw null;
      this.prosesTambahOtoritas(value);
    });
  },
  prosesTambahOtoritas(value){
    var app = this
    app.loading = true
    app.newOtoritas.otoritas = value
    axios.post(app.url,app.newOtoritas)
    .then(function (resp) {
      if (resp.data.status == 0) {
        app.alert("Menambahkan Otoritas "+value);
        app.getResults(); 
      }else{
        app.$swal({
          text: "Otoritas "+ value +" Sudah Ada!",
        });      
      }
      app.loading = false
    })
    .catch(function (resp) {
      app.loading = false
      console.log(resp);
      alert("Tidak Bisa Memuat Otoritas");
    });
  },
  editOtoritas(id,index,nama){
    var app = this;     
    app.$swal({
      title: 'Edit Otoritas '+ '"' +nama+'"',
      content: {
        element: "input",
        attributes: {
          placeholder: "Otoritas",
          type: "text",
        },
      },
      buttons: {
        cancel: true,
        confirm: "OK"                   
      },

    }).then((value) => {
      if (!value) throw null;
      this.prosesEditOtoritas(value,id);
    });
  },
  prosesEditOtoritas(value,id){
    var app = this
    app.loading = true
    app.newOtoritas.otoritas = value
    axios.patch(app.url+'/'+id,app.newOtoritas)
    .then(function (resp) {
      if (resp.data.status == 0) {
        app.alert("Merubah Otoritas Menjadi "+value);
        app.getResults(); 
      }else{
        app.$swal({
          text: "Otoritas "+ value +" Sudah Ada!",
        });      
      }
      app.loading = false
    })
    .catch(function (resp) {
      app.loading = false
      console.log(resp);
      alert("Tidak Bisa Memuat Otoritas");
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
  deleteEntry(id, index,nama) {

    var app = this;
    app.$swal({
      text: "Anda Yakin Ingin Menghapus Otoritas "+nama+ " ?",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {

        this.prosesDelete(id,nama);

      } else {

        app.$swal.close();

      }
    });

  },
  prosesDelete(id,nama){
   var app = this; 
   axios.delete(app.url+'/' + id) 
   .then(function (resp) { 


    if (resp.data.status == 0) {

      function cekIndexOtoritas(otoritas) { 
        return otoritas.id === id
      }
      var index = app.otoritas.findIndex(cekIndexOtoritas)
      app.otoritas.splice(index,1)
      app.alert("Menghapus Otoritas "+nama)
      console.log(index)
    }else{
      app.$swal({
        text: "Otoritas "+ nama +" Tidak Dapat Dihapus, Karena Ada User Yang Berkaitan!",
      });     
    }


  }) 
   .catch(function (resp) { 
    alert("Tidak dapat Menghapus Otoritas")
  });  
 }
}
}
</script>
