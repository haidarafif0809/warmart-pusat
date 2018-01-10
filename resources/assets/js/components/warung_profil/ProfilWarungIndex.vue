<style scoped>
.table>tbody>tr>th {
  padding-right: 50px;
  padding-bottom: 8px;
}
</style>

<template>
  <div class="row">
    <div class="col-md-12">
      <ul class="breadcrumb">
        <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
        <li class="active" v-if="warungData.setting_aplikasi == 0">Profil Warung</li>
        <li class="active" v-else>Profil Toko</li>
      </ul>

      <div class="card">
        <div class="card-header card-header-icon" data-background-color="purple">
         <i class="material-icons">store</i>
      </div>

      <div class="card-content">
        <h4 class="card-title" v-if="warungData.setting_aplikasi == 0">Profil Warung </h4>
        <h4 class="card-title" v-else >Profil Toko </h4>

          <table class="table table-striped table-hover ">
            <tbody v-if="warungs.length > 0 && loading == false" class="data-ada">
              <tr v-for="warung, index in warungs" >
                <th class="text-primary">Nama</th>
                <td>{{ warung.warung.name }}</td>
              </tr>
              <tr v-for="warung, index in warungs" >              
                <th class="text-primary">No. Telpon</th>
                <td>{{ warung.warung.no_telpon }}</td>
              </tr>
              <tr v-for="warung, index in warungs" >              
                <th class="text-primary">Email</th>
                <td>{{ warung.warung.email }}</td>
              </tr>
              <tr v-for="warung, index in warungs" >              
                <th class="text-primary">Nama Bank</th>
                <td>{{ warung.nama_bank }}</td>
              </tr>
              <tr v-for="warung, index in warungs" >              
                <th class="text-primary">Nama Rekening</th>
                <td>{{ warung.atas_nama }}</td>
              </tr>
              <tr v-for="warung, index in warungs" >              
                <th class="text-primary">No. Rekening</th>
                <td>{{ warung.no_rek }}</td>
              </tr>
              <tr v-for="warung, index in warungs" >              
                <th class="text-primary">Alamat</th>
                <td>{{ warung.warung.alamat }}</td>
              </tr> 
              <tr v-for="warung, index in warungs" >              
                <th class="text-primary">Provinsi</th>
                <td>{{ warung.provinsi.name }}</td>
              </tr> 
              <tr v-for="warung, index in warungs" >              
                <th class="text-primary">Kabupaten</th>
                <td>{{ warung.kabupaten.name }}</td>
              </tr> 
              <tr v-for="warung, index in warungs" >              
                <th class="text-primary">Kecamatan</th>
                <td>{{ warung.kecamatan.name }}</td>
              </tr> 
              <tr v-for="warung, index in warungs" >              
                <th class="text-primary">Kelurahan</th>
                <td>{{ warung.kelurahan.name }}</td>
              </tr>    
            </tbody>
            
            <tbody class="data-tidak-ada" v-else-if="warungs.length == 0 && loading == false">
              <tr><td colspan="9"  class="text-center">Tidak Ada Data</td></tr>
            </tbody>
          </table>

          <vue-simple-spinner v-if="loading"></vue-simple-spinner>

        <div align="right">
          <p v-for="warung, index in warungs">
          <router-link :to="{name: 'editProfilWarung', params: {id:warung.warung.id}}" class="btn btn-xs btn-rose btn-round" v-bind:id="'edit-'+warung.warung.id" >
            Edit 
          </router-link>
          </p>
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
      warungs: [],
      warungData : {},
      url : window.location.origin+(window.location.pathname).replace("dashboard", "profil-warung"),
      pencarian: '',
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
            app.warungs = resp.data.data;
            app.warungData = resp.data;
            app.loading = false;
          })
          .catch(function (resp) {
            console.log(resp);
            app.loading = false;
            alert("Could not load warungs");
          });
        },
        alert(pesan) {
          this.$swal({
            title: "Berhasil ",
            text: pesan,
            icon: "success",
          });
        }
      }
    }
    </script>
