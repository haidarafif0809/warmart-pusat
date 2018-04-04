<style scoped>
.pencarian {
  color: red; 
  float: right;
}
.hurufBesar{
  text-transform: uppercase;
}
</style>
<template>


  <div class="row" >

    <div class="col-md-12">
     <ul class="breadcrumb">
      <li><a href="#">Home</a></li>
      <li class="active">Bank</li>
    </ul>


    <div class="card">
      <div class="card-header card-header-icon" data-background-color="purple">
       <i class="material-icons">payment</i>
     </div>
     <div class="card-content">
       <h4 class="card-title"> Bank</h4>

       <div class="toolbar">

        <p> <router-link :to="{name: 'createBankWarung'}" class="btn btn-primary" v-if="otoritas.tambah_bank == 1">Tambah Bank</router-link></p>

      </div>


      <div class=" table-responsive ">
        <div class="pencarian">
         <input type="text" class="form-control pencarian" autocomplete="off" name="pencarian" v-model="pencarian" placeholder="Pencarian" >
       </div>
       <table class="table table-striped table-hover ">
        <thead class="text-primary">
          <tr>

            <th>Nama Bank</th>
            <th>A.N Bank</th>
            <th>No Rekening</th>
            <th v-if="otoritas.edit_bank == 1 || otoritas.hapus_bank == 1">Aksi</th>

          </tr>
        </thead>
        <tbody v-if="bankWarungs.length" class="data-ada">
          <tr v-for="bankWarung, index in bankWarungs" >

            <td class="hurufBesar">{{ bankWarung.nama_bank }}</td>
            <td>{{ bankWarung.atas_nama }}</td>
            <td>{{ bankWarung.no_rek }}</td>
            <td> 
             <router-link :to="{name: 'editBankWarung', params: {id: bankWarung.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + bankWarung.id" v-if="otoritas.edit_bank == 1">
              Edit 
            </router-link> 
            <a href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + bankWarung.id" v-on:click="deleteEntry(bankWarung.id, index,bankWarung.nama_bank)" v-if="otoritas.hapus_bank == 1">
              Delete
            </a>
          </td>


        </tr>
      </tbody>
      <tbody class="data-tidak-ada" v-else>
        <tr ><td colspan="4"  class="text-center">Tidak Ada Data</td></tr>
      </tbody>
    </table>

    <vue-simple-spinner v-if="loading"></vue-simple-spinner>

    <div align="right"><pagination :data="bankWarungsData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>
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
      bankWarungs: [],
      bankWarungsData: {},
      otoritas: {},
      url : window.location.origin+(window.location.pathname).replace("dashboard", "bank-warung"),
      pencarian: '',
      contoh : '',
      loading: true
    }
  },
  mounted() {
    var app = this;
    app.getResults();
  },
  watch: {
        // whenever question changes, this function will run
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
            app.bankWarungs = resp.data.bank.data;
            app.bankWarungsData = resp.data.bank;
            app.otoritas = resp.data.otoritas.original;
            app.loading = false;
          })
          .catch(function (resp) {
            console.log(resp);
            app.loading = false;
            alert("Could not load banks");
          });
        },
        getHasilPencarian(page){
          var app = this;
          if (typeof page === 'undefined') {
            page = 1;
          }
          axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
          .then(function (resp) {
            app.bankWarungs = resp.data.bank.data;
            app.bankWarungsData = resp.data.bank;
            app.otoritas = resp.data.otoritas.original;
          })
          .catch(function (resp) {
            console.log(resp);
            alert("Could not load banks");
          });
        },
        deleteEntry(id, index,nama_bank) {
          this.$swal({
            title: "Konfirmasi Hapus",
            text : "Anda Yakin Ingin Menghapus "+nama_bank+" ?",
            icon : "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
             var app = this;
             axios.delete(app.url+'/' + id)
             .then(function (resp) {
              app.getResults();
              app.alert("Berhasil Menghapus Bank");            
            })
             .catch(function (resp) {
               swal("Gagal Menghapus Bank!", {
                icon: "warning",
              });
             });
           }
           this.$router.replace('/bank-warung/');
         });
        },
        alert(pesan) {
          this.$swal({
            title: "Berhasil Menghapus Bank!",
            text: pesan,
            icon: "success",
          });
        },

      }
    }
    </script>
