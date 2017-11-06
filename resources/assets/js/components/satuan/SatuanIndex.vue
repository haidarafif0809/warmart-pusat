<template>


  <div class="row" >

    <div class="col-md-12">
     <ul class="breadcrumb">
      <li><a href="#">Home</a></li>
      <li class="active">Satuan</li>
    </ul>


    <div class="card">
      <div class="card-header card-header-icon" data-background-color="purple">
       <i class="material-icons">dns</i>
     </div>
     <div class="card-content">
       <h4 class="card-title"> Satuan </h4>

       <div class="toolbar">

        <p> <router-link :to="{name: 'createSatuan'}" class="btn btn-primary">Tambah Satuan</router-link></p>

      </div>
      <div class=" table-responsive ">
       <div  align="right">
         pencarian
         <input type="text" name="pencarian" v-model="pencarian" placeholder="Kolom Pencarian" >
       </div>

       <table class="table table-striped table-hover ">
        <thead class="text-primary">
          <tr>

            <th>Nama Satuan</th>
            <th>Aksi</th>

          </tr>
        </thead>
        <tbody v-if="satuans.length" class="data-ada">
          <tr v-for="satuan, index in satuans" >

            <td>{{ satuan.nama_satuan }}</td>
            <td> 
             <router-link :to="{name: 'editSatuan', params: {id: satuan.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + satuan.id" >
              Edit 
            </router-link> <a href="#"
            class="btn btn-xs btn-danger" v-bind:id="'delete-' + satuan.id"
            v-on:click="deleteEntry(satuan.id, index,satuan.nama_satuan)">
            Delete
          </a></td>


        </tr>
      </tbody>
      <tbody class="data-tidak-ada" v-else>
        <tr ><td colspan="4"  class="text-center">Tidak Ada Data</td></tr>
      </tbody>
    </table>

    <vue-simple-spinner v-if="loading"></vue-simple-spinner>

    <div align="right"><pagination :data="satuansData" v-on:pagination-change-page="getResults"></pagination></div>

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
      satuans: [],
      satuansData: {},
      url : window.location.origin+(window.location.pathname).replace("dashboard", "satuan"),
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
            app.satuans = resp.data.data;
            app.satuansData = resp.data;
            app.loading = false;
          })
          .catch(function (resp) {
            console.log(resp);
            app.loading = false;
            alert("Could not load satuans");
          });
        },
        getHasilPencarian(page){
          var app = this;
          if (typeof page === 'undefined') {
            page = 1;
          }
          axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
          .then(function (resp) {
            app.satuans = resp.data.data;
            app.satuansData = resp.data;
          })
          .catch(function (resp) {
            console.log(resp);
            alert("Could not load satuans");
          });
        },
        alert(pesan) {
          this.$swal({
            title: "Berhasil Menghapus satuan!",
            text: pesan,
            icon: "success",
          });
        },
        deleteEntry(id, index,nama_satuan) {
          if (confirm("Yakin Ingin Menghapus satuan "+nama_satuan+" ?")) {
            var app = this;
            axios.delete(app.url+'/' + id)
            .then(function (resp) {
              app.getResults();
              app.alert("Berhasil Menghapus satuan "+nama_satuan)
            })
            .catch(function (resp) {
              alert("Could not delete company");
            });
          }
        }
        axios.get(app.url+'/view?page='+page)
                .then(function (resp) {
                    app.satuans = resp.data.data;
                    app.satuansData = resp.data;
                    app.loading = false;
                })
                .catch(function (resp) {
                    console.log(resp);
                    app.loading = false;
                    alert("Could not load satuans");
                });
       },
      getHasilPencarian(page){
        var app = this;
        if (typeof page === 'undefined') {
          page = 1;
        }
        axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
                .then(function (resp) {
                    app.satuans = resp.data.data;
                    app.satuansData = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Could not load satuans");
                });
      },
		  alert(pesan) {
              this.$swal({
                  title: "Berhasil Menghapus satuan!",
                  text: pesan,
                  icon: "success",
                });
            },
            deleteEntry(id, index,nama_satuan){
		     if (confirm("Yakin Ingin Menghapus Satuan "+nama_satuan+" ?")) {
                    var app = this;
                    axios.delete(app.url+'/' + id)
                        .then(function (resp) {
                          app.getResults();
							app.alert("Berhasil Menghapus Satuan "+nama_satuan)
						})
                        .catch(function (resp) {
                            alert("Could not delete company");
                        });
                }
            }
         }
	  }
</script>

