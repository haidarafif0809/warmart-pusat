<template>
  <div class="row">
    <div class="col-md-12">
      <ul class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li class="active">Warung</li>
      </ul>


    <div class="card">
      <div class="card-header card-header-icon" data-background-color="purple">
       <i class="material-icons">store</i>
     </div>
     <div class="card-content">
       <h4 class="card-title"> Warung </h4>

       <div class="toolbar">

        <p> <router-link :to="{name: 'createWarung'}" class="btn btn-primary">Tambah Warung</router-link></p>

      </div>
      <div class=" table-responsive ">
       <div  align="right">
         pencarian
         <input type="text" name="pencarian" v-model="pencarian" autocomplete="off" placeholder="Kolom Pencarian" >
       </div>

       <table class="table table-striped table-hover ">
        <thead class="text-primary">
          <tr>
            <th>Nama</th>
            <th>No Telp</th>
            <th>Email</th>
            <th>Nama Bank</th>
            <th>Nama Rekening</th>
            <th>No Rekening</th>
            <th>Alamat</th>
            <th>Wilayah</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody v-if="warungs.length > 0 && loading == false" class="data-ada">
          <tr v-for="warung, index in warungs" >
            <td>{{ warung.name }}</td>
            <td>{{ warung.no_telpon }}</td>
            <td>{{ warung.email }}</td>
            <td>{{ warung.nama_bank }}</td>
            <td>{{ warung.atas_nama }}</td>
            <td>{{ warung.no_rek }}</td>
            <td>{{ warung.alamat }}</td>
            <td v-if="warung.nama != null ">{{ warung.nama }}</td>
            <td v-else>-</td>
            <td> 
             <router-link :to="{name: 'editWarung', params: {id:warung.warung_id}}" class="btn btn-xs btn-default" v-bind:id="'edit-'+warung.warung_id" >
              Edit 
            </router-link> <a href="#"
            class="btn btn-xs btn-danger" v-bind:id="'delete-' + warung.warung_id"
            v-on:click="deleteEntry(warung.warung_id, index,warung.name)">
            Delete
          </a>
        </td>
      </tr>
      </tbody>
      <tbody class="data-tidak-ada" v-else-if="warungs.length == 0 && loading == false">
        <tr ><td colspan="9"  class="text-center">Tidak Ada Data</td></tr>
      </tbody>
    </table>


    <vue-simple-spinner v-if="loading"></vue-simple-spinner>

    <div align="right"><pagination :data="warungsData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>



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
      warungsData: {
      },
      url : window.location.origin+(window.location.pathname).replace("dashboard", "warung"),
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
            app.warungs = resp.data.data;
            app.warungsData = resp.data;
            app.loading = false;
          })
          .catch(function (resp) {
            console.log(resp);
            app.loading = false;
            alert("Could not load warungs");
          });
        },

        getHasilPencarian(page){
          var app = this;
          if (typeof page === 'undefined') {
            page = 1;
          }
          axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
          .then(function (resp) {
            app.warungs = resp.data.data;
            app.warungsData = resp.data;
          })
          .catch(function (resp) {
            console.log(resp);
            alert("Could not load warungs");
          });
        },
        alert(pesan) {
          this.$swal({
            title: "Berhasil ",
            text: pesan,
            icon: "success",
          });
        },
        deleteEntry(id, index,name) {
             swal({
            title: "Konfirmasi Hapus",
            text : "Anda Yakin Ingin Menghapus Warung "+name+" ?",
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
                swal("Warung Berhasil Dihapus!  ", {
                  icon: "success",
                });
              })
              .catch(function (resp) {
                swal("Gagal Menghapus Warung!  ", {
                  icon: "warning",
                });
              });

           }
          });
        }
      }
    }
    </script>
