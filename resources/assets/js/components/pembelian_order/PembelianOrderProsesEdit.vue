<template>
  <div class="row"> 
    <div class="col-md-12"> 
      <ul class="breadcrumb"> 
        <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li> 
        <li><router-link :to="{name: 'indexPembelianOrder'}">Order Pembelian</router-link></li> 
        <li class="active">Proses Edit OrderPembelian</li> 
      </ul> 
    </div> 
  </div> 
</template>

<script>
  export default {
    data: function () {
      return {
        url : window.location.origin+(window.location.pathname).replace("dashboard", "pembelian-order"),
      }
    },
    mounted() {
      var app = this;
      app.getDataOrderPembelian();
    },
    methods: { 
      getDataOrderPembelian(){
        var app = this;
        var id = app.$route.params.id;
        axios.get(app.url+'/proses-edit-pembelian-order/'+id).then(function (resp) {

          app.$router.replace('/edit-order-pembelian/'+id);

        })
        .catch(function (resp) {
          alert("Tidak Bisa Memuat Order Pembelian");
        });

      }

    }

  }
</script>