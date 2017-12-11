<style scoped>
.pencarian {
  color: red; 
  float: right;
  padding-bottom: 10px;
}
.card-detail{
	margin-top: 5px;
	margin-bottom: 1px;
}
.breadcrumb{
	margin-top: 1px;
	margin-bottom: 1px;
}
</style>

<template>	

	<div class="row">

		<div class="col-md-12">
			<ul class="breadcrumb">
			    <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
			    <li><router-link :to="{name: 'indexPesananWarung'}">Pesanan</router-link></li>
			    <li class="active">Detail Pesanan</li>
		 	</ul>

		 	<!-- DATA PESANAN -->
		 	<div class="card" style="margin-top: 5px; margin-bottom: 1px;">
			  <div class="card-content">

			    <div class="row">

			      <div class="col-md-1">Order #{{pesananData.id}}
			        <!-- MODAL PEMESAN -->
			        <div class="modal " id="data_pemesan" role="dialog" data-backdrop="">
			          <div class="modal-dialog">
			            <!-- Modal content-->
			            <div class="modal-content">
			              <div class="modal-header">
			                <button type="button" class="close" data-dismiss="modal">&times;</button>
			                <h4 class="modal-title"><b>Data Pemesan</b></h4>
			              </div>

			              <div class="modal-body">
			                <div class="responsive">
			                   <table>
								  <tbody>
								      <tr><td width="25%"> Nama</td> <td> : </td> <td>  {{pesananData.nama_pemesan}} </td></tr>
								      <tr><td width="25%"> Alamat</td> <td> : </td> <td>  {{pesananData.alamat_pemesan}} </td></tr>
								      <tr><td width="25%"> No Telp</td> <td> : </td> <td>  {{pesananData.no_telp_pemesan}} </td></tr>
								  </tbody>
								</table>
			                </div>
			              </div>
			            </div>
			          </div>
			        </div> <!--END MODAL PEMESAN-->

			      </div> <!--END ORDER PESANAN ID-->

			      <div class="col-md-3">Waktu Pesan : {{pesananData.created_at}}</div>					
				  <div class="col-md-2">Total :Rp. {{ new Intl.NumberFormat().format(pesananData.subtotal) }}</div>					
				  <div class="col-md-2">Status : 
				  		<b style="color:red" v-if="pesananData.konfirmasi_pesanan == 0" >Belum Di Konfirmasi</b>
				  		<b style="color:orange" v-else-if="pesananData.konfirmasi_pesanan == 1" >Sudah Di Konfirmasi</b>
				  		<b style="color:#01573e" v-else-if="pesananData.konfirmasi_pesanan == 2" >Selesai</b>
				  		<b style="color:red" v-else > Batal</b>
				  </div>

				  <div class="col-md-4">
				  	<p v-if="pesananData.konfirmasi_pesanan == 1">Selesai ? :</p>
				  	<p v-else>Lanjut ? :</p>
				  	
				  	<p v-if="pesananData.konfirmasi_pesanan == 0">
				  		<button id="konfirmasi-pesanan-warung" :id-pesanan="pesananData.id" class="btn btn-sm btn-info" @click="konfirmasiPesanan(pesananData.id)"><font style="font-size: 12px;">Lanjut</font></button>
				  		<button id="batalkan-pesanan-warung" :id-pesanan="pesananData.id" class="btn btn-sm btn-danger" @click="batalPesanan(pesananData.id)"><font style="font-size: 12px;">Batal</font></button>
					  	<!--PEMESAN-->
					  	<button type="button" class="btn btn-sm btn-primary" id="btnDetail" data-toggle="modal" data-target="#data_pemesan"><font style="font-size: 12px;">Pemesan</font></button>
				  	</p>
				  	
				  	<p v-else-if="pesananData.konfirmasi_pesanan == 1">
				  		<button class="btn btn-info btn-sm" :data-id="pesananData.id" id="selesaikan_pesanan">  <font style="font-size: 12px;">Selesai</font></button>
				  		<button id="batalkan-konfirmasi-pesanan-warung" :id-pesanan="pesananData.id" class="btn btn-sm btn-danger"><font style="font-size: 12px;">Batal</font></button>
					  	<!--PEMESAN-->
					  	<button type="button" class="btn btn-sm btn-primary" id="btnDetail" data-toggle="modal" data-target="#data_pemesan"><font style="font-size: 12px;">Pemesan</font></button>
				  	</p>
				  	
				  	<p v-else-if="pesananData.konfirmasi_pesanan == 2">
				  		<button id="batalkan-pesanan-warung" :id-pesanan="pesananData.id" class="btn btn-sm btn-danger"><font style="font-size: 12px;">Batal</font></button>	
					  	<!--PEMESAN-->
					  	<button type="button" class="btn btn-sm btn-primary" id="btnDetail" data-toggle="modal" data-target="#data_pemesan"><font style="font-size: 12px;">Pemesan</font></button>		  
				  	</p>
				  	
				  	<p v-else-if="pesananData.konfirmasi_pesanan == 3">
				  		<button id="konfirmasi-pesanan-warung" :id-pesanan="pesananData.id" class="btn btn-sm btn-info"><font style="font-size: 12px;">Lanjut</font></button>	
					  	<!--PEMESAN-->
					  	<button type="button" class="btn btn-sm btn-primary" id="btnDetail" data-toggle="modal" data-target="#data_pemesan"><font style="font-size: 12px;">Pemesan</font></button>
				  	</p>

			      </div>

			    </div> <!--END ROW-->
			  </div> <!--END CARD CONTENT-->
			</div> <!--END CARD-->

			<!--DATA DETAIL PESANAN -->			
			<div class="card card-detail">
			  <div class="card-content">

			    <div class="responsive">
			    	<table class="table table-hover table-responsive">
				    	<thead>
					        <tr>
						        <th><b>Produk</b></th>
						        <th><b>Harga</b> </th>
						        <th><center><b>Jumlah</b></center></th>
						        <th><b>Status</b> </th>
					        </tr>
				      	</thead>
				      	<tbody  v-if="detailPesanan.length > 0 && loading == false"  class="data-ada">

				      	 	<tr v-for="detailPesanans, index in detailPesanan">
				      			<td>{{ detailPesanans.produk.nama_barang }}</td>			          			
				      			<td>Rp. {{ new Intl.NumberFormat().format(detailPesanans.harga_produk) }}</td>			          			
				      			<td>
				      				<center>
					      				<div class="btn-group" v-if="detailPesanans.pesanan_pelanggan.konfirmasi_pesanan == 0">
					      					<a v-if="detailPesanans.jumlah_produk == 0" disabled="true" class="btn btn-round btn-xs"> <i class="material-icons">remove</i></a>
					      					<a v-else href="#" class="btn btn-round btn-xs" @click="kurangProduk(detailPesanans.id)"> <i class="material-icons">remove</i></a>

					      					<a id="edit-jumlah-pesanan" :data-nama="detailPesanans.produk.nama_barang" :data-id="detailPesanans.id"  class="btn btn-round btn-xs" rel="tooltip" data-placement="top" title="Edit Jumlah" @click="editProduk(detailPesanans.id, index, detailPesanans.produk.nama_barang)"> <font style="font-size: 11.5px;">{{ detailPesanans.jumlah_produk }}</font> </a>

					      					<a href="#" class="btn btn-round btn-xs" @click="tambahProduk(detailPesanans.id)"> <i class="material-icons">add</i></a>
					      				</div>
					      				<div v-else>
					      					{{detailPesanans.jumlah_produk}}
					      				</div>
				      				</center>
				      			</td>			          			
				      			<td>
				      				<b style="color:red" v-if="detailPesanans.pesanan_pelanggan.konfirmasi_pesanan == 0" >Belum Di Konfirmasi</b>
									<b style="color:orange" v-else-if="detailPesanans.pesanan_pelanggan.konfirmasi_pesanan == 1" >Sudah Di Konfirmasi</b>
									<b style="color:#01573e" v-else-if="detailPesanans.pesanan_pelanggan.konfirmasi_pesanan == 2" >Selesai</b>
									<b style="color:red" v-else > Batal</b>
				      			</td>			          			
			          		</tr>

				      </tbody>	

				      <tbody class="data-tidak-ada" v-else-if="detailPesanan.length == 0 && loading == false">
                            <tr ><td colspan="4"  class="text-center">Tidak Ada Pesanan</td></tr>
                       </tbody>
				    </table>
			    	
			    <!-- 	<vue-simple-spinner v-if="loading"></vue-simple-spinner>
			    	<div align="right"><pagination :data="detailPesananData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div> -->
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
			detailPesanan: [],
			detailPesananData: {},
			pesananData: {},
			detailPesananId: null,
            url: window.location.origin + (window.location.pathname).replace("dashboard", "pesanan-warung"),
            urlTambahProduk: window.location.origin + (window.location.pathname).replace("dashboard", "tambah-produk-pesanan-warung"),
            urlKurangProduk: window.location.origin + (window.location.pathname).replace("dashboard", "kurang-produk-pesanan-warung"),
            urlKonfirmasiPesanan: window.location.origin + (window.location.pathname).replace("dashboard", "konfirmasi-pesanan-warung"),
            urlOrigin: window.location.origin + (window.location.pathname).replace("dashboard", ""),
			loading: true
		}
	},
	mounted() {
		var app = this;
        let id = app.$route.params.id;
        app.detailPesananId = id;
		app.getResults();
	},
    methods: {
    	getResults(page) {
    		var app = this;	
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		axios.get(app.url+'/detail/'+app.detailPesananId+'?page='+page)
    		.then(function (resp) {
    			app.detailPesanan = resp.data.data.detail_pesanan.data;
    			app.detailPesananData = resp.data.data.detail_pesanan;
    			app.pesananData = resp.data.data.pesanan;
    			app.loading = false;
    		})
    		.catch(function (resp) {
    			app.loading = false;
    			alert("Tidak Dapat Memuat Pesanan");
    		});
    	},
    	tambahProduk(id){
    		var app = this;
    		axios.get(app.urlTambahProduk+'/'+ id)
            .then(function (resp) {
              app.getResults();
              app.$router.replace('/detail-pesanan-warung/'+app.detailPesananId);
            });
    	},
    	kurangProduk(id){
    		var app = this;
    		axios.get(app.urlKurangProduk+'/'+ id)
            .then(function (resp) {
              app.getResults();
              app.$router.replace('/detail-pesanan-warung/'+app.detailPesananId);
            });
    	},
    	editProduk(id, index, nama_produk){
    		var nama_produk = nama_produk;
			var id = id;
			console.log(id);
			console.log(nama_produk);
			    swal({
			      title: nama_produk,
			      input: 'number',
			      inputPlaceholder : 'Jumlah Produk',
			      html:'Masukkan Jumlah Produk Yang Di Inginkan',
			      showCloseButton: true,
			      showCancelButton: true,
			      confirmButtonColor: '#3085d6',
			      cancelButtonColor: '#d33',
			      confirmButtonText: 'Simpan',
			      cancelButtonText: 'Batal',
			      confirmButtonClass: 'btn btn-success',
			      cancelButtonClass: 'btn btn-danger',
			      buttonsStyling: false,
			      inputAttributes: {
			        'name': 'jumlah_produk',
			        'data-id': id,
			        'data-nama': nama_produk,
			      }
			    }).then((jumlah_produk, id) => {
		          if (!jumlah_produk) throw null;
		          this.submitProduk(jumlah_produk);
		        });
    	},
    	submitProduk(jumlah_produk){

    		if (jumlah_produk == 0 || jumlah_produk == "") {
    			this.$swal({
		        	text: "Jumlah Produk Tidak Boleh Nol!",
		        });
    		}else{
    			var app = this;
	        	app.loading = true;

	        	axios.post(app.urlOrigin+'edit-jumlah-produk-warung, ')
	        	.then(function (resp) {
		            app.getResults();
		            app.loading = false;
	        	})
	        	.catch(function (resp) {
	            	alert("Tidak Dapat Mengubah Jumlah Produk");
	        	});
	        }
      	},
      	konfirmasiPesanan(id){
      		var app = this;
      		var id_pesanan = id;
      		console.log(id_pesanan)
      		swal({
		      text: "Anda Yakin Ingin Melanjutkan Pesanan Ini??",
		      type: 'question',
		      showCancelButton: true,
		      confirmButtonColor: '#3085d6',
		      cancelButtonColor: '#d33',
		      confirmButtonText: 'Ya!',
		      cancelButtonText: 'Tidak',
		      confirmButtonClass: 'btn btn-success',
		      cancelButtonClass: 'btn btn-danger',
		      buttonsStyling: false
		    }).then(function (id_pesanan) {
		    	app.submitKonfirmasiPesanan(id_pesanan)
		    })
      	},
      	submitKonfirmasiPesanan(id_pesanan){      		
      		var app = this;
    		axios.get(app.urlKonfirmasiPesanan+'/'+ id_pesanan)
            .then(function (resp) {
              app.getResults();
              app.$router.replace('/detail-pesanan-warung/'+id_pesanan);
            });
      	}
    }
}
</script>