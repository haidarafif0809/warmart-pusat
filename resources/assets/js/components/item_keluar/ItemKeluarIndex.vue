<template>
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li style="color: purple">Persediaan</li>
				<li class="active">Item Keluar</li>
			</ul>

			
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">vertical_align_top</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Item Keluar </h4>
					
					<div class="toolbar">
						<p> <router-link :to="{name: 'createItemKeluar'}" class="btn btn-primary">Tambah Item Keluar</router-link></p>
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
									<th>Total</th>
									<th>Keterangan</th>
									<th>Waktu</th>
									<th>Waktu Edit</th>
									<th>Detail</th>
									<th>Edit</th>
									<th>Hapus</th>

								</tr>
							</thead>
							<tbody v-if="item_keluar.length"  class="data-ada">
								<tr v-for="item_keluar, index in item_keluar" >

									<td>{{ item_keluar.no_faktur }}</td>
									<td>{{ item_keluar.total }}</td>
									<td>{{ item_keluar.keterangan }}</td>
									<td>{{ item_keluar.waktu }}</td>
									<td>{{ item_keluar.waktu_edit }}</td>
									<td>
										<router-link :to="{name: 'detailItemKeluar', params: {id: item_keluar.id}}" class="btn btn-xs btn-info" v-bind:id="'detail-' + item_keluar.id" >
										Detail </router-link> </td>
									</td>
									<td><router-link :to="{name: 'editItemKeluarProses', params: {id: item_keluar.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + item_keluar.id" >
									Edit </router-link> </td>

									<td> 
										<a href="#item-keluar" class="btn btn-xs btn-danger" v-bind:id="'delete-' + item_keluar.id" v-on:click="deleteEntry(item_keluar.id, index,item_keluar.no_faktur)">Delete</a>
									</td>
								</tr>
							</tbody>					
							<tbody class="data-tidak-ada" v-else>
								<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>	


						<vue-simple-spinner v-if="loading"></vue-simple-spinner>

						<div align="right"><pagination :data="itemKeluarData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

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
			item_keluar: [],
			itemKeluarData: {},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "item-keluar"),
			pencarian: '',
			loading: true,
			seen : false
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
    			app.item_keluar = resp.data.data;
    			app.itemKeluarData = resp.data;
    			app.loading = false;
    			app.seen = true;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			app.loading = false;
    			app.seen = true;
    			alert("Tidak Dapat Memuat Item Keluar");
    		});
    	},
    	getHasilPencarian(page){
    		var app = this;
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
    		.then(function (resp) {
    			app.item_keluar = resp.data.data;
    			app.itemKeluarData = resp.data;
    			app.loading = false;
    			app.seen = true;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Item Keluar");
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

    		var app = this;
    		app.$swal({
    			text: "Anda Yakin Ingin Menghapus Transaksi "+no_faktur+ " ?",
    			buttons: true,
    			dangerMode: true,
    		})
    		.then((willDelete) => {
    			if (willDelete) {

    				this.prosesDelete(id,no_faktur);

    			} else {

    				app.$swal.close();

    			}
    		});

    		
    	},
    	prosesDelete(id,no_faktur){
    		var app = this;
    		app.loading = true;
    		axios.delete(app.url+'/' + id)
    		.then(function (resp) {

    			if (resp.data == 0) {

    				app.alertGagal(no_faktur+" Gagal di Hapus, Silakan di Coba Lagi");
    				app.loading = false;

    			}else{

    				app.getResults();
    				app.alert("Menghapus Item Keluar "+no_faktur);
    				app.loading = false;  
    			}

    		})
    		.catch(function (resp) {
    			alert("Tidak dapat Menghapus Item Keluar");
    		});
    	},
    	alertGagal(pesan) {
    		this.$swal({
    			text: pesan,
    			icon: "warning",
    		});
    	}
    }
}
</script>