<template>



	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">

				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li style="color: purple">Persediaan</li>
				<li><router-link :to="{name: 'indexItemKeluar'}">Item Keluar</router-link></li>
				<li class="active">Detail Item Keluar</li>

			</ul>

			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">vertical_align_top</i>
				</div>
				<div class="card-content">
					<h4 class="card-title">Detail Item Keluar {{ no_faktur }}</h4>

					<!--TABEL TBS ITEM 	MASUK -->


          <div class="toolbar">
            <p> <router-link :to="{name: 'indexItemKeluar'}" class="btn btn-primary">Kembali</router-link></p>
          </div>

          <div class=" table-responsive ">
            <div  align="right">
             pencarian
             <input type="text" name="pencarian" v-model="pencarian" placeholder="Kolom Pencarian" >
           </div>

           <table class="table table-striped table-hover" v-if="seen">
             <thead class="text-primary">
              <tr>

               <th>No. Transaksi</th>
               <th>Produk</th>
               <th>Jumlah</th>

             </tr>
           </thead>
           <tbody v-if="detail_item_keluar.length"  class="data-ada">
            <tr v-for="detail_item_keluar, index in detail_item_keluar" >

              <td>{{ detail_item_keluar.no_faktur }} </td>
              <td>{{ detail_item_keluar.kode_produk }} - {{ detail_item_keluar.nama_produk }}</td>
              <td>{{ detail_item_keluar.jumlah_produk | pemisahTitik }} </td>
            </tr>
          </tbody>					
          <tbody class="data-tidak-ada" v-else>
            <tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
          </tbody>
        </table>	

        <vue-simple-spinner v-if="loading"></vue-simple-spinner>

        <div align="right"><pagination :data="detailItemKeluarData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

      </div>



    </div><!-- / PANEL BODY -->

  </div>
</div>
</div>

</template>


<script>
export default {
	data: function () {
		return {
			errors: [],
			produk: [],
			detail_item_keluar: [],
			detailItemKeluarData : {},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "item-keluar"),
			no_faktur : '',
      placeholder_produk: {
        placeholder: '--PILIH PRODUK--'
      },
      pencarian: '',
      loading: true,
      seen : false
    }
  },
      mounted() {
        var app = this;
        app.getFakturItemKeluar();
        app.getResults();

      },
      watch: {
        // whenever question changes, this function will run
        pencarian: function (newQuestion) {
        	this.getHasilPencarian();
        	this.loading = true;  
        },

      },
    filters: {
        pemisahTitik: function (value) {      
            var angka = [value];
            var numberFormat = new Intl.NumberFormat('es-ES');
            var formatted = angka.map(numberFormat.format);
            return formatted.join('; ');
        }
      },
      methods: {
       getResults(page) {
        var app = this;	
        var id = app.$route.params.id;
        if (typeof page === 'undefined') {
         page = 1;
       }
       axios.get(app.url+'/detail-item-keluar/'+id+'?page='+page)
       .then(function (resp) {

         app.detail_item_keluar = resp.data.data;
         app.detailItemKeluarData = resp.data;
         app.loading = false;
         app.seen = true;

       })
       .catch(function (resp) {

         console.log(resp);
         app.loading = false;
         app.seen = true;
         alert("Tidak Dapat Memuat Detail Item Keluar");

       });
     },
     getHasilPencarian(page){
      var app = this;
      var id = app.$route.params.id;
      if (typeof page === 'undefined') {
       page = 1;
     }
     axios.get(app.url+'/pencarian-detail-item-keluar/'+id+'?search='+app.pencarian+'&page='+page)
     .then(function (resp) {

      app.detail_item_keluar = resp.data.data;
      app.detailItemKeluarData = resp.data;
      app.loading = false;
      app.seen = true;
    })
     .catch(function (resp) {

       console.log(resp);
       alert("Tidak Dapat Memuat Detail Item Keluar");

     });

   }, 
   getFakturItemKeluar(){
    var app = this;
    var id = app.$route.params.id;
    axios.get(app.url+'/ambil-faktur-item-keluar/'+id).then(function (resp) {

      app.no_faktur = resp.data.no_faktur; 

    })
    .catch(function (resp) {

     app.loading = false;
     app.seen = true;
     alert("Tidak Bisa Memuat Detail Item Keluar");

   });

  }

}

}
</script>