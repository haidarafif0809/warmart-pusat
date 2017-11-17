<template>
	
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li class="active">Detail Komunitas</li>
			</ul>

			
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">people</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Detail Komunitas {{komunitas.name}}</h4>

					<div class="toolbar">
						<p> <router-link :to="{name: 'indexKomunitas'}" class="btn btn-primary"><i class="material-icons">reply</i> Kembali</router-link></p>
					</div>

					<div class=" table-responsive ">

						<table class="table table-striped table-hover ">
							<thead class="text-primary">
								<tr>
									<th>Nama Penggiat</th>
									<th>Alamat Penggiat</th>
									<th>Nama Bank</th>
									<th>No. Rekening</th>
									<th>A.N Rekening</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>{{ komunitas.komunitas_penggiat.nama_penggiat }}</td>
									<td>{{ komunitas.komunitas_penggiat.alamat_penggiat }}</td>
									<td>{{ komunitas.bank_komunitas.nama_bank }}</td>
									<td>{{ komunitas.bank_komunitas.no_rek }}</td>
									<td>{{ komunitas.bank_komunitas.atas_nama }}</td>
								</tr>
							</tbody>	
						</table>	

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>
					</div>

				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {

	mounted() {
		let app = this;
		let id = app.$route.params.id;
		this.getResults(id);
	},	
	data: function () {
		return {
			komunitas: {},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "komunitas"),
			loading: true
		}
	},
	methods: { 
		getResults(id) {
			var app = this;	
			axios.get(app.url+'/detail-komunitas/'+id)
			.then(function (resp) {
				app.komunitas = resp.data;
				app.loading = false;
			})
			.catch(function (resp) {
				console.log(resp);
				app.loading = false;
				alert("Could not load komunitas");
			});
		}
	}
}
</script>