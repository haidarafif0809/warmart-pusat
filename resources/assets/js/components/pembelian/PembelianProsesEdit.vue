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


        <div class="card" style="margin-bottom: 1px; margin-top: 1px;" ><!-- CARD --> 
          <div class="card-content"> 
            <h4 class="card-title" style="margin-bottom: 1px; margin-top: 1px;"> Edit Pembelian </h4> 
            <div class="row" style="margin-bottom: 1px; margin-top: 1px;"> 

              <div class="col-md-3">
                <div class="card card-produk" style="margin-bottom: 1px; margin-top: 1px;">
                  <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                   
                    <selectize-component  id="produk" ref='produk' > 
                      <option ></option>
                      </selectize-component>
                      </div><!--/COL MD  3 --> 

                <span style="display: none;">
                </span>
            </div>
          </div>
          </div>

    <div class="row">
        <div class="col-md-8">
          <div class=" table-responsive ">
               <div class="pencarian">
                <input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
            </div>
            <vue-simple-spinner v-if="loading"></vue-simple-spinner>

          </div>

          <p style="color: red; font-style: italic;">*Note : Klik Kolom Jumlah, Harga, Potongan & Tax Untuk Mengubah Nilai.</p> 
      </div><!-- COL SM 8 --> 

      <div class="col-md-4"><!-- COL SM 4 --> 
        <div class="card card-stats"><!-- CARD --> 
          <div class="card-content"> 
            <div class="row"> 
              <div class="col-md-6 col-xs-12"> 
                  <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                  <label class="label-control">Suplier</label><br>
                  <selectize-component  id="suplier" name="suplier" ref='suplier'> 
                      <option ></option>
                  </selectize-component>
                </div>
              </div> 

                <div class="col-md-6 col-xs-12"> 
                <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                 <label class="label-control">Kas</label><br>

                  <selectize-component id="cara_bayar" name="cara_bayar" ref='cara_bayar'> 
                  <option ></option>
                  </selectize-component>

                </div>
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