<template>
	
	<div class="row">
		<div class="col-md-12">
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
						<div  align="right">
							pencarian
							<input type="text" name="pencarian" v-model="pencarian" placeholder="Kolom Pencarian" >
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
								<tr v-for="pembelian, index in pembelians" >

									<td>{{ pembelians.no_faktur }}</td>
									<td>{{ pembelians.waktu }}</td>
									<td>{{ pembelians.suplier }}</td>
									<td>{{ pembelians.status_pembelian }}</td>
									<td>{{ pembelians.total }}</td>
									<td><router-link :to="{name: 'editPembelian', params: {id: pembelians.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + pembelians.id" >
									Edit </router-link> </td>

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
    			alert(app.pembelian);
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
    		if (confirm("Yakin Ingin Menghapus Pembelian "+no_faktur+" ?")) {
    			var app = this;
    			axios.delete(app.url+'/' + id)
    			.then(function (resp) {
    				app.getResults();
    				app.alert("Menghapus Pembelian "+no_faktur)
    			})
    			.catch(function (resp) {
    				alert("Tidak dapat Menghapus Pembelian");
    			});
    		}
    	}
    }
}
</script>

