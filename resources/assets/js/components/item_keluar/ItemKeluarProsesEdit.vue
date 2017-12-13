<template>

  <div class="row">
    <div class="col-md-12">
      <ul class="breadcrumb">

        <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
        <li style="color: purple">Persediaan</li>
        <li><router-link :to="{name: 'indexItemKeluar'}">Item Keluar</router-link></li>
        <li class="active">Edit Item Keluar</li>

      </ul>

      <div class="card">
        <div class="card-header card-header-icon" data-background-color="purple">
          <i class="material-icons">vertical_align_top</i>
        </div>
        <div class="card-content">
          <h4 class="card-title">Edit Item Keluar</h4>
          <div class="row">

            <div class="col-md-8">
              <form class="form-horizontal"> 

                <div class="form-group">
                  <div class="col-md-4"><br>
                    <selectize-component :settings="placeholder_produk" id="produk" ref='produk'> 
                      <option></option>
                    </selectize-component>
                  </div> 
                </div>
              </form>
            </div>
            <!-- / col md 7 -->
            <div class="col-md-1"></div>
            <div class="col-md-3">
              <!-- TOMBOL BATAL -->

              <!--- TOMBOL SELESAI -->
              <button type="button" class="btn btn-primary" id="btnSelesai"><i class="material-icons">send</i> Selesai</button>

              <button type="submit" class="btn btn-danger" id="btnBatal"><i class="material-icons">cancel</i> Batal </button>

            </div>

            <!--TOMBOL SELESAI & BATAL -->
            <div class="col-md-4">
              <div class="form-group col-md-3">


              </div>
              <div class="form-group col-md-2">                                      

              </div>                    
            </div>

          </div>


          <vue-simple-spinner v-if="loading"></vue-simple-spinner>



          <p style="color: red; font-style: italic;">*Note : Klik Kolom Jumlah Untuk Mengubah Jumlah Produk.</p> 



        </div><!-- / PANEL BODY -->

      </div>
    </div>
  </div>

</template>


<script>
export default {
  data: function () {
    return {
      url : window.location.origin+(window.location.pathname).replace("dashboard", "item-keluar"), 
      placeholder_produk: {
        placeholder: '--PILIH PRODUK--'
      },
      loading: true
    }
  },
  mounted() {
    var app = this;
    app.getFakturItemKeluar();

  },
  methods: { 
    getFakturItemKeluar(){
      var app = this;
      var id = app.$route.params.id;
      axios.get(app.url+'/proses-form-edit/'+id).then(function (resp) {

        app.$router.replace('/edit-item-keluar/'+id);

      })
      .catch(function (resp) {
        alert("Tidak Bisa Memuat Item Keluar");

      });

    }

  }

}
</script>