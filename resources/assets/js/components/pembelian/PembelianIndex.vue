<style scoped>
.pencarian {
	color: red; 
	float: right;
}
</style>
<template>
	<div class="row">
		<div class="col-md-12">

		<!-- MODAL PILIH PRODUK --> 	
			<div class="modal" id="data_detail" role="dialog" data-backdrop="">
		  <div class="modal-dialog modal-lg"> 
		    <!-- Modal content --> 
		    <div class="modal-content"> 
		      <div class="modal-header"> 
		        <button type="button" class="close" data-dismiss="modal">&times;</button> 
		        <h4 class="modal-title">Detail Pembelian</h4> 
		      </div> 
		      <div class="modal-body">  
		        <div class="responsive"> 
		         <table class="table table-bordered" >  
		          <thead> 
			            <tr> 
			              <th>No. Transaksi</th> 
			              <th>Produk</th> 
			              <th>Jumlah</th> 
			              <th>Harga</th> 
			              <th>Potongan</th> 
			              <th>Pajak</th> 
			              <th>Subtotal</th> 
			            </tr> 
		          </thead>
		          <tbody>
			          	<tr v-for="detailPembelian, index in detailPembelians" >
			          		<td>{{detailPembelian.no_faktur}}</td>
			          		<td>{{detailPembelian.nama_produk}}</td>
			          		<td>{{detailPembelian.jumlah_produk}}</td>
			          		<td>{{detailPembelian.harga_produk}}</td>
			          		<td>{{detailPembelian.potongan_produk}}</td>
			          		<td>{{detailPembelian.pajak_produk}}</td>
			          		<td>{{detailPembelian.subtotal}}</td>
			          	</tr>
		          </tbody> 
		        </table> 
		      </div>  
		      <div class="modal-footer">  
		      <button type="button" class="btn btn-default" data-dismiss="modal" id="btnCloseModal"><i class="material-icons">close</i> Close</button>  
		  </div>
		    </div>   
		  </div> 
		</div> 
		</div> 
		<!-- MODAL PILIH PRODUK --> 

			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li style="color: purple">Persediaan</li>
				<li class="active">Pembelian</li>
			</ul>

			
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">add_shopping_cart</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Pembelian </h4>
					
					<div class="toolbar">
						<p> <router-link :to="{name: 'createPembelian'}" class="btn btn-primary">Tambah Pembelian</router-link></p>
					</div>


					<div class=" table-responsive ">
						<div class="pencarian">
							<input type="text" class="form-control pencarian" name="pencarian" v-model="pencarian" placeholder="Kolom Pencarian" >
						</div>

						<table class="table table-striped table-hover">
							<thead class="text-primary">
								<tr>
									<th>No. Transaksi</th>
									<th>Waktu</th>
									<th>Suplier</th>
									<th>Status</th>
									<th>Total</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody v-if="pembelian.length > 0 && loading == false"  class="data-ada">
								<tr v-for="pembelians, index in pembelian" >

									<td>{{ pembelians.no_faktur }}</td>
									<td>{{ pembelians.waktu }}</td>
									<td>{{ pembelians.suplier }}</td>
									<td>{{ pembelians.status_pembelian }}</td>
									<td>Rp. {{ pembelians.total }}</td>
									<td><router-link :to="{name: 'editPembelianProses', params: {id: pembelians.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + pembelians.id" >
									Edit </router-link> </td>
									<td>
										<button type="button" class="btn btn-xs btn-success btn-detail" id="btnDetail" data-toggle="modal" data-target="#data_detail" 
										v-on:click="detailModalPembelian(pembelians.id,index,pembelians.no_faktur)" >Detail</button>
									</td>
									<td> 
										<a href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + pembelians.id" v-on:click="deleteEntry(pembelians.id, index,pembelians.no_faktur)">Delete</a>
									</td>
								</tr>
							</tbody>					
							<tbody class="data-tidak-ada" v-else-if="pembelian.length == 0 && loading == false">
								<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>	

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>

						<div align="right"><pagination :data="pembelianData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

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
			pembelian: [],
			pembelianData: {},
			detailPembelians: [],
			url : window.location.origin+(window.location.pathname).replace("dashboard", "pembelian"),
			pencarian: '',
			loading: true,
		}
	},
	mounted() {
		var app = this;
		app.getResults();
	},
	watch: {
        // whenever question changes, this function will run
        pencarian: function (newQuestion) {
        	this.getHasilPencarian();
        	this.loading = true;  
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
    			app.pembelian = resp.data.data;
    			app.pembelianData = resp.data;
    			app.loading = false;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			app.loading = false;
    			alert("Tidak Dapat Memuat Pembelian");
    		});
    	},
    	getHasilPencarian(page){
    		var app = this;
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
    		.then(function (resp) {
    			app.pembelian = resp.data.data;
    			app.pembelianData = resp.data;
    			app.loading = false;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Pembelian");
    		});
    	},
    	alert(pesan) {
    		this.$swal({
    			title: "Berhasil ",
    			text: pesan,
    			icon: "success",
    		});
    	},
    	deleteEntry(id, index,no_faktur) {
            this.$swal({
                title: "Konfirmasi Hapus",
                text : "Anda Yakin Ingin Menghapus "+no_faktur+" ?",
                icon : "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                  var app = this;
                  app.loading = true;
                  axios.delete(app.url+'/' + id)
                  .then(function (resp) {
			                if (resp.data == 0) {
			                    app.$swal('Oops...','Pembelian Tidak Dapat Dihapus, Karena Sudah Terpakai','error');
			                    app.loading = false;

			                }else{
			                    app.getResults();
			                    app.alert("Menghapus Pembelian "+no_faktur);
			                    app.loading = false;  
			                }
                  })
                  .catch(function (resp) {
                      alert("Tidak dapat Menghapus Item Masuk");
                  });
               }else {
    				app.$swal.close();
    			}
            });
    	},
    	detailModalPembelian(id,index,no_faktur){
            var app = this;
            axios.get(app.url+'/detail-view?id='+id+'&no_faktur='+no_faktur)
            .then(function (resp) {
                app.detailPembelians = resp.data.data;
            })
            .catch(function (resp) {
                app.loading = false;
                alert("Tidak Dapat Memuat Detail Pesanan");
            });
        }
    }
}
</script>

