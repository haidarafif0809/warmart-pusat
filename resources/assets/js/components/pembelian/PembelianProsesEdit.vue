<style scoped>
.pencarian {
  color: red; 
  float: right;
}
</style>
<template>
<div class="row"> 
  <div class="col-md-12"> 
    <ul class="breadcrumb"> 
      <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li> 
      <li><router-link :to="{name: 'indexPembelian'}">Pembelian</router-link></li> 
      <li class="active">Edit Pembelian</li> 
    </ul> 


    <div class="row"><!-- ROW --> 
      <div class="col-md-8"><!-- COL SM 8 --> 

        <div class="card"><!-- CARD --> 

          <div class="card-header card-header-icon" data-background-color="purple"> 
            <i class="material-icons">add_shopping_cart</i> 
          </div> 

          <div class="card-content"> 
            <h4 class="card-title">Edit Pembelian </h4> 
            <div class="row"> 

              <!--COL MD 8--> 
              <div class="col-md-8"> 
                <form class="form-horizontal" id="form-produk"> 
                  <div class="form-group">
                    <div class="col-md-4"><br> 
                      <selectize-component :settings="placeholder_produk"  id="produk" ref='produk' name="jumlah_produk"> 
                      <option ></option>
                      </selectize-component>


                  </div>
                </div><!--/COL MD 8--> 
              </form>
            </div> 
            <div class="pencarian">
              <input type="text" name="pencarian"  placeholder="Pencarian" class="form-control pencarian" autocomplete="">
            </div>
          </div>

            <vue-simple-spinner v-if="loading"></vue-simple-spinner>
          <p style="color: red; font-style: italic;">*Note : Klik Kolom Jumlah, Harga, Potongan & Tax Untuk Mengubah Nilai.</p> 


          </div><!-- / PANEL BODY --> 

        </div><!-- CARD --> 

      </div><!-- COL SM 8 --> 

      <div class="col-md-4"><!-- COL SM 4 --> 
        <div class="card"><!-- CARD --> 
          <div class="card-content"> 
            <div class="row"> 
              <div class="col-md-6"> 
                  <h4>Supplier</h4> 
                  <selectize-component :settings="placeholder_produk"  id="suplier" name="suplier" ref='suplier'> 
                      <option ></option>
                  </selectize-component>
              </div> 

                <div class="col-md-6"> 
                  <h4>Cara Bayar</h4> 
                  <selectize-component :settings="placeholder_produk"  id="cara_bayar" name="cara_bayar" ref='cara_bayar'> 
                  <option></option>
                  </selectize-component>
                </div> 
              </div> 
            

            <!--- TOMBOL SELESAI --> 
            <button type="button" class="btn btn-primary" id="btnSelesai"  data-toggle="modal"><i class="material-icons">send</i> Selesai </button> 

            <button type="submit" class="btn btn-danger" id="btnBatal"  ><i class="material-icons">cancel</i> Batal </button> 
          </div> 
        </div>             
      </div><!-- COL SM 4 --> 

    </div><!-- ROW --> 
  </div> 
</div> 
</template>

<script>
export default {
  data: function () {
    return {
      url : window.location.origin+(window.location.pathname).replace("dashboard", "pembelian"), 
      placeholder_produk: {
        placeholder: '--PILIH PRODUK--'
      },
      placeholder_suplier: {
        placeholder: '--PILIH SUPPLIER--'
      },
      placeholder_cara_bayar: {
        placeholder: '--PILIH CARA BAYAR--'
      },
      loading: true
    }
  },
  mounted() {
    var app = this;
    app.getFakturPembelian();

  },
  methods: { 
   getFakturPembelian(){
    var app = this;
    var id = app.$route.params.id;
    axios.get(app.url+'/proses-form-edit/'+id).then(function (resp) {
      app.$router.replace('/edit-pembelian/'+id);

    })
    .catch(function (resp) {
     alert("Tidak Bisa Memuat Pembelian");

   });

  }

}

}
</script>