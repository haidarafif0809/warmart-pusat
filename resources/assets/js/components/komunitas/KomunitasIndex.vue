<template>
	
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li class="active">Komunitas</li>
			</ul>

			
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">people</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Komunitas </h4>

					<div class="toolbar">
						<p> <router-link :to="{name: 'createKomunitas'}" class="btn btn-primary">Tambah Komunitas</router-link></p>
					</div>

					<div class=" table-responsive ">
						<div  align="right">
							pencarian
							<input type="text" name="pencarian" v-model="pencarian" placeholder="Kolom Pencarian" >
						</div>

						<table class="table table-striped table-hover ">
							<thead class="text-primary">
								<tr>

									<th>Nama Komunitas</th>
									<th>No. Telp</th>
									<th>Alamat Komunitas</th>
									<th>Warung</th>
									<th>Email</th>
									<th>Wilayah</th>
									<th>Link Afiliasi</th>
									<th>Aksi</th>

								</tr>
							</thead>
							<tbody v-if="komunitas.length"  class="data-ada">
								<tr v-for="komunitas, index in komunitas" >

									<td>{{ komunitas.nama_komunitas }}</td>
									<td>{{ komunitas.no_telp }}</td>
									<td>{{ komunitas.alamat_komunitas }}</td>
									<td>{{ komunitas.warung }}</td>
									<td>{{ komunitas.email }}</td>
									<td>{{ komunitas.wilayah }}</td>
									<td>{{ komunitas.link_afiliasi }}</td>
									<td><router-link :to="{name: 'detailKomunitas', params: {id: komunitas.id}}" class="btn btn-xs btn-warning" v-bind:id="'detail-' + komunitas.id" >
									Detail </router-link> </td>
									<td v-if="komunitas.konfirmasi_admin == 0"> <a href="#" class="btn btn-xs btn-primary" v-bind:id="'konfirmasi-' + komunitas.id" v-on:click="ConfirmEntry(komunitas.id, index,komunitas.nama_komunitas)">Iya</a> </td>

									<td v-else><a href="#" class="btn btn-xs btn-danger" v-bind:id="'konfirmasi-' + komunitas.id" v-on:click="NoConfirmEntry(komunitas.id, index,komunitas.nama_komunitas)">Tidak</a></td>

									<td><router-link :to="{name: 'editKomunitas', params: {id: komunitas.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + komunitas.id" >
									Edit </router-link> 
									<a href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + komunitas.id" v-on:click="deleteEntry(komunitas.id, index,komunitas.nama_komunitas)">Delete</a>
								</td>
							</tr>
						</tbody>					
						<tbody class="data-tidak-ada" v-else>
							<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
						</tbody>
					</table>	

					<vue-simple-spinner v-if="loading"></vue-simple-spinner>

					<div align="right"><pagination :data="komunitasData" v-on:pagination-change-page="getResults"></pagination></div>

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
			komunitas: [],
			komunitasData: {},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "komunitas"),
			pencarian: '',
			loading: true
		}
	},
	mounted() {
		var app = this;
		app.getResults();
	},
	watch: {
        // whenever question changes, this function will run
        pencarian: function (newQuestion) {
        	this.getHasilPencarian()  
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
    			app.komunitas = resp.data.data;
    			app.komunitasData = resp.data;
    			app.loading = false;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			app.loading = false;
    			alert("Could not load komunitas");
    		});
    	},
    	getHasilPencarian(page){
    		var app = this;
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
    		.then(function (resp) {
    			app.komunitas = resp.data.data;
    			app.komunitasData = resp.data;
    			app.loading = false;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Could not load komunitas");
    		});
    	},
    	alert(pesan) {
    		this.$swal({
    			title: "Berhasil ",
    			text: pesan,
    			icon: "success",
    		});
    	},
    	ConfirmEntry(id, index,nama_komunitas) {
    		if (confirm("Yakin Ingin Mengkonfirmasi Komunitas "+nama_komunitas+" ?")) {
    			var app = this;
    			axios.get(app.url+'/konfirmasi?confirm='+id)
    			.then(function (resp) {
    				app.getResults();
    				app.alert("Mengkonfirmasi Komunitas "+nama_komunitas)
    			})
    			.catch(function (resp) {
    				alert("Could not delete company");
    			});
    		}
    	},
    	NoConfirmEntry(id, index,nama_komunitas) {
    		if (confirm("Yakin Tidak Ingin Mengkonfirmasi Komunitas "+nama_komunitas+" ?")) {
    			var app = this;
    			axios.get(app.url+'/no-konfirmasi?confirm='+id)
    			.then(function (resp) {
    				app.getResults();
    				app.alert("Komunitas "+nama_komunitas+" Tidak Dikonfirmasi")
    			})
    			.catch(function (resp) {
    				alert("Could not delete company");
    			});
    		}
    	},
    	deleteEntry(id, index,nama_komunitas) {
    		if (confirm("Yakin Ingin Menghapus Komunitas "+nama_komunitas+" ?")) {
    			var app = this;
    			axios.delete(app.url+'/' + id)
    			.then(function (resp) {
    				app.getResults();
    				app.alert("Menghapus Komunitas "+nama_komunitas)
    			})
    			.catch(function (resp) {
    				alert("Could not delete company");
    			});
    		}
    	}
    }
}
</script>