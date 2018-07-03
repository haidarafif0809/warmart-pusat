<template>
  <div class="row"> 
    <div class="col-md-12"> 
      <ul class="breadcrumb"> 
        <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li> 
        <li><router-link :to="{name: 'indexPenerimaanProduk'}">Penerimaan Produk</router-link></li> 
        <li class="active">Proses Edit Penerimaan Produk</li> 
      </ul> 
    </div> 
  </div> 
</template>

<script>
  export default {
    data: function () {
      return {
        url : window.location.origin+(window.location.pathname).replace("dashboard", "penerimaan-produk"),
      }
    },
    mounted() {
      var app = this;
      app.getDataPenerimaanProduk();
    },
    methods: { 
      getDataPenerimaanProduk(){
        var app = this;
        var id = app.$route.params.id;
        axios.get(app.url+'/proses-edit-penerimaan-produk/'+id).then(function (resp) {

          app.$router.replace('/edit-penerimaan-produk/'+id);

        })
        .catch(function (resp) {
          alert("Tidak Bisa Memuat Penerimaan Produk");
        });

      }

    }

  }
</script>