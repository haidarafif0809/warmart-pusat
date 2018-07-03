<template>
  <div class="row"> 
    <div class="col-md-12"> 
      <ul class="breadcrumb"> 
        <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li> 
        <li><router-link :to="{name: 'indexReturPembelian'}">Retur Pembelian</router-link></li> 
        <li class="active">Proses Edit Retur Pembelian</li> 
      </ul> 
    </div> 

    <vue-simple-spinner v-if="loading"></vue-simple-spinner>
  </div> 
</template>

<script>
  export default {
    data: function () {
      return {
        url : window.location.origin+(window.location.pathname).replace("dashboard", "retur-pembelian"),
        loading: true
      }
    },
    mounted() {
      var app = this;
      app.getDataReturPembelian();
    },
    methods: { 
      getDataReturPembelian(){
        var app = this;
        var id = app.$route.params.id;
        axios.get(app.url+'/proses-edit-retur-pembelian/'+id).then(function (resp) {

          app.$router.replace('/edit-retur-pembelian/'+id);
          app.loading = false

        })
        .catch(function (resp) {
          alert("Tidak Bisa Memuat Retur Pembelian");
        });

      }

    }

  }
</script>